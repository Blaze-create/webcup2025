<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold tracking-wide text-amber-300">
                IAstroMatch • Operator Console
            </h2>

            <a href="{{ route('match.page') }}"
               class="text-sm px-4 py-2 rounded-full
                      bg-amber-500/10 text-amber-300
                      border border-amber-500/30
                      hover:bg-amber-500/20 transition">
                ← Back to Matches
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ACCOUNT PANEL --}}
            <div class="rounded-xl border border-amber-500/20
                        bg-gradient-to-b from-[#1a140c] to-[#0f0b06]
                        shadow-[0_0_30px_rgba(255,170,70,0.05)]
                        p-6">

                <h3 class="text-amber-200 font-semibold mb-4 tracking-wide">
                    ACCOUNT INFORMATION
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-amber-100/80">
                    <div>
                        <div class="text-xs uppercase opacity-60">Name</div>
                        <div class="mt-1 font-medium text-amber-200">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs uppercase opacity-60">Email</div>
                        <div class="mt-1">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs uppercase opacity-60">Joined</div>
                        <div class="mt-1">
                            {{ $user->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- PROFILE PANEL --}}
            <div class="rounded-xl border border-amber-500/20
                        bg-gradient-to-b from-[#1a140c] to-[#0f0b06]
                        shadow-[0_0_30px_rgba(255,170,70,0.05)]
                        p-6">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-amber-200 font-semibold tracking-wide">
                        OPERATOR PROFILE
                    </h3>

                    @if($user->profile)
                        <span class="text-xs px-3 py-1 rounded-full
                                     bg-green-500/10 text-green-400
                                     border border-green-500/30">
                            PROFILE ACTIVE
                        </span>
                    @endif
                </div>

                @if($user->profile)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">

                        @php($p = $user->profile)

                        <div>
                            <div class="text-xs uppercase opacity-60">Callsign</div>
                            <div class="mt-1 font-medium text-amber-200">
                                {{ $p->name }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs uppercase opacity-60">Species</div>
                            <div class="mt-1">{{ $p->species }}</div>
                        </div>

                        <div>
                            <div class="text-xs uppercase opacity-60">Intent</div>
                            <div class="mt-1">{{ $p->intent }}</div>
                        </div>

                        <div>
                            <div class="text-xs uppercase opacity-60">Atmosphere</div>
                            <div class="mt-1">{{ $p->atmosphere }}</div>
                        </div>

                        <div>
                            <div class="text-xs uppercase opacity-60">Gravity</div>
                            <div class="mt-1">{{ $p->gravity }}</div>
                        </div>

                        <div>
                            <div class="text-xs uppercase opacity-60">Comms</div>
                            <div class="mt-1">{{ $p->comms }}</div>
                        </div>

                        <div>
                            <div class="text-xs uppercase opacity-60">Bio Type</div>
                            <div class="mt-1">{{ $p->bioType }}</div>
                        </div>

                        <div>
                            <div class="text-xs uppercase opacity-60">Risk Index</div>
                            <div class="mt-1 text-amber-300 font-semibold">
                                {{ $p->risk }} / 100
                            </div>
                        </div>

                        <div>
                            <div class="text-xs uppercase opacity-60">Temperature</div>
                            <div class="mt-1">
                                {{ $p->tempMin }}° → {{ $p->tempMax }}°
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-amber-100/70">
                        No operator profile detected.
                    </div>

                    <a href="{{ route('radar.index') }}"
                       class="inline-block mt-4 px-5 py-2 rounded-full
                              bg-amber-500/20 text-amber-300
                              border border-amber-500/40
                              hover:bg-amber-500/30 transition">
                        Initialize Profile
                    </a>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
