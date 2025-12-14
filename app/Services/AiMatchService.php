<?php

namespace App\Services;

use App\Models\Profile;

class AiMatchService
{
    public function rankMatches(array $user, array $candidates): array
    {
        // If DB has no candidates, generate some
        if (count($candidates) === 0) {
            $candidates = $this->seedCandidates();
        }

        $scored = [];
        foreach ($candidates as $c) {
            $candidate = $c instanceof Profile ? $c->toArray() : $c;

            $scoreData = $this->score($user, $candidate);

            $scored[] = [
                'profile_id' => $candidate['id'] ?? null,         // Profile table id (DB)
                'user_id'    => $candidate['user_id'] ?? null,    // Users table id (DB) - best for chat/match
                // optionally:
                'email'   => $candidate['email'] ?? null,
                
                'name' => $candidate['name'] ?? 'Unknown Entity',
                'species' => $candidate['species'] ?? '???',
                'intent' => $candidate['intent'] ?? '???',
                'bioType' => $candidate['bioType'] ?? '???',
                'risk' => (int)($candidate['risk'] ?? 50),

                'score' => $scoreData['score'],
                'tier' => $scoreData['tier'],
                'summary' => $scoreData['summary'],
                'signals' => $scoreData['signals'], // breakdown for UI/debug
            ];
        }

        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);
        return array_slice($scored, 0, 11);
    }

    public function score(array $u, array $c): array
    {
        $score = 0;
        $signals = [];

        // 1) Environment compatibility (atmosphere + gravity)
        $env = 0;
        if (($u['atmosphere'] ?? '') === ($c['atmosphere'] ?? '')) $env += 18;
        if (($u['gravity'] ?? '') === ($c['gravity'] ?? '')) $env += 12;
        $signals['env'] = $env;
        $score += $env;

        // 2) Temperature overlap (min/max band)
        $uMin = (int)$u['tempMin']; $uMax = (int)$u['tempMax'];
        $cMin = (int)($c['tempMin'] ?? $uMin); $cMax = (int)($c['tempMax'] ?? $uMax);

        $overlap = max(0, min($uMax, $cMax) - max($uMin, $cMin));
        $uSpan = max(1, $uMax - $uMin);
        $tempRatio = $overlap / $uSpan; // 0..1+
        $temp = (int)round(min(1, $tempRatio) * 20); // up to 20
        $signals['temp'] = $temp;
        $score += $temp;

        // 3) Communication compatibility
        $comms = 0;
        if (($u['comms'] ?? '') === ($c['comms'] ?? '')) $comms = 15;
        else {
            // partial compatibility rules (fun but deterministic)
            $pairs = [
                'Radio' => ['Text'],
                'Text' => ['Radio'],
                'Light' => ['Telepathy'],
                'Telepathy' => ['Light', 'Pheromones'],
                'Pheromones' => ['Telepathy'],
            ];
            $uC = $u['comms'] ?? '';
            $cC = $c['comms'] ?? '';
            if (isset($pairs[$uC]) && in_array($cC, $pairs[$uC], true)) $comms = 8;
        }
        $signals['comms'] = $comms;
        $score += $comms;

        // 4) Intent alignment
        $intent = 0;
        if (($u['intent'] ?? '') === ($c['intent'] ?? '')) $intent = 20;
        else {
            $bad = [
                'Romance' => ['Conquest'],
                'Alliance' => ['Conquest'],
                'Trade' => ['Conquest'],
            ];
            $uI = $u['intent'] ?? '';
            $cI = $c['intent'] ?? '';
            if (isset($bad[$uI]) && in_array($cI, $bad[$uI], true)) $intent = -10;
            else $intent = 6;
        }
        $signals['intent'] = $intent;
        $score += $intent;

        // 5) BioType synergy
        $bio = 0;
        if (($u['bioType'] ?? '') === ($c['bioType'] ?? '')) $bio = 10;
        else {
            $uB = $u['bioType'] ?? '';
            $cB = $c['bioType'] ?? '';
            if (($uB === 'Organic' && $cB === 'Mechanical') || ($uB === 'Mechanical' && $cB === 'Organic')) {
                $bio = 7; // “opposites attract”
            } else {
                $bio = 3;
            }
        }
        $signals['bioType'] = $bio;
        $score += $bio;

        // 6) Risk delta (closer risk = better)
        $uR = (int)$u['risk'];
        $cR = (int)($c['risk'] ?? 50);
        $delta = abs($uR - $cR); // 0..100
        $risk = (int)round(15 * (1 - ($delta / 100))); // up to 15
        $signals['risk'] = $risk;
        $score += $risk;

        // Clamp
        $score = max(0, min(100, (int)round($score)));

        $tier = $this->tierFromScore($score, $u, $c);
        $summary = $this->makeNarrative($tier, $signals, $u, $c, $score);

        return compact('score', 'tier', 'summary', 'signals');
    }

    private function tierFromScore(int $score, array $u, array $c): string
    {
        // extra “catastrophic” detection
        $uI = $u['intent'] ?? '';
        $cI = $c['intent'] ?? '';
        if ($uI !== '' && $cI === 'Conquest' && $uI !== 'Conquest') return 'DANGEROUS';

        if ($score >= 80) return 'PERFECT';
        if ($score >= 60) return 'COMPATIBLE';
        if ($score >= 40) return 'WEIRD';
        return 'DISASTER';
    }

    private function makeNarrative(string $tier, array $signals, array $u, array $c, int $score): string
    {
        $top = $signals;
        arsort($top);
        $topKeys = array_slice(array_keys($top), 0, 2);

        $focus = [];
        foreach ($topKeys as $k) {
            $focus[] = match ($k) {
                'env' => 'habitat alignment',
                'temp' => 'thermal overlap',
                'comms' => 'communication resonance',
                'intent' => 'mission alignment',
                'bioType' => 'bio-mech symmetry',
                'risk' => 'risk harmony',
                default => $k,
            };
        }

        $name = $c['name'] ?? 'Unknown Entity';

        return match ($tier) {
            'PERFECT' => "SYNC LOCKED ({$score}%). {$name} shows high {$focus[0]} and {$focus[1]}. Initiate contact protocol: Friendly.",
            'COMPATIBLE' => "STABLE MATCH ({$score}%). Strong {$focus[0]}. Minor turbulence detected in {$focus[1]}. Proceed with mild caution.",
            'WEIRD' => "ANOMALOUS LINK ({$score}%). {$focus[0]} is promising, but {$focus[1]} is unpredictable. Expect strange diplomacy.",
            'DANGEROUS' => "RED ALERT ({$score}%). One party signals conquest. Recommend decoy transmission before direct contact.",
            default => "CATASTROPHIC ({$score}%). Signals conflict across key parameters. Avoid docking without shields.",
        };
    }

    public function seedCandidates(): array
    {
        return [
            [
                'name' => 'Operator R-17',
                'species' => 'Human',
                'atmosphere' => 'O2',
                'gravity' => 'Standard',
                'tempMin' => -10,
                'tempMax' => 35,
                'comms' => 'Radio',
                'intent' => 'Alliance',
                'bioType' => 'Organic',
                'risk' => 55,
            ],
            [
                'name' => 'Xeno Trader “Murk”',
                'species' => 'Xeno',
                'atmosphere' => 'Methane',
                'gravity' => 'Low',
                'tempMin' => -140,
                'tempMax' => -60,
                'comms' => 'Pheromones',
                'intent' => 'Trade',
                'bioType' => 'Organic',
                'risk' => 40,
            ],
            [
                'name' => 'Automaton Core S-9',
                'species' => 'Automaton',
                'atmosphere' => 'Vacuum',
                'gravity' => 'High',
                'tempMin' => -200,
                'tempMax' => 120,
                'comms' => 'Text',
                'intent' => 'Alliance',
                'bioType' => 'Mechanical',
                'risk' => 20,
            ],
            [
                'name' => 'Warlord K-Null',
                'species' => 'Hybrid',
                'atmosphere' => 'O2',
                'gravity' => 'Standard',
                'tempMin' => 0,
                'tempMax' => 50,
                'comms' => 'Radio',
                'intent' => 'Conquest',
                'bioType' => 'Mechanical',
                'risk' => 95,
            ],
        ];
    }
}
