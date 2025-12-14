const csrf = document.querySelector('meta[name="csrf-token"]')?.content;


const els = {
  btnScan: document.getElementById('btnScan'),
  btnLoadDemo: document.getElementById('btnLoadDemo'),
  btnClear: document.getElementById('btnClear'),
  iaLog: document.getElementById('iaLog'),
  matchList: document.getElementById('matchList'),
  canvas: document.getElementById('radarCanvas'),
  form: document.getElementById('profileForm'),
  risk: document.getElementById('risk'),
  riskLabel: document.getElementById('riskLabel'),
  overlay: document.getElementById('scanOverlay'),
};


const DEMO_PROFILE = {
  name: "Operator R-17",
  species: "Human",
  atmosphere: "O2",
  gravity: "Standard",
  tempMin: -10,
  tempMax: 35,
  comms: "Radio",
  intent: "Alliance",
  bioType: "Organic",
  risk: 55,
};

let sweep = 0;
let blips = [];
let animId = null;

function logLine(text, kind = "muted") {
  const div = document.createElement('div');
  div.className = `logLine ${kind}`;
  div.textContent = text;
  els.iaLog.prepend(div);
}

function clearLog() {
  els.iaLog.innerHTML = "";
}

function formToProfile() {
  const fd = new FormData(els.form);

  // radio values
  const profile = {
    name: (fd.get('name') || '').toString().trim() || null,
    species: fd.get('species'),
    atmosphere: fd.get('atmosphere'),
    gravity: fd.get('gravity'),
    tempMin: Number(fd.get('tempMin')),
    tempMax: Number(fd.get('tempMax')),
    comms: fd.get('comms'),
    intent: fd.get('intent'),
    bioType: fd.get('bioType'),
    risk: Number(fd.get('risk')),
  };

  return profile;
}

function setForm(profile) {
  const setRadio = (name, value) => {
    const el = els.form.querySelector(`input[name="${name}"][value="${value}"]`);
    if (el) el.checked = true;
  };

  els.form.querySelector('input[name="name"]').value = profile.name ?? "";
  setRadio('species', profile.species);
  setRadio('atmosphere', profile.atmosphere);
  setRadio('gravity', profile.gravity);
  els.form.querySelector('input[name="tempMin"]').value = profile.tempMin;
  els.form.querySelector('input[name="tempMax"]').value = profile.tempMax;
  setRadio('comms', profile.comms);
  els.form.querySelector('select[name="intent"]').value = profile.intent;
  els.form.querySelector('select[name="bioType"]').value = profile.bioType;
  els.form.querySelector('input[name="risk"]').value = profile.risk;
  els.riskLabel.textContent = String(profile.risk);
}

function tierToBadgeClass(tier) {
  return tier.toLowerCase();
}

// function renderMatches(results) {
//   els.matchList.innerHTML = "";

//   results.forEach((m, i) => {
//     const card = document.createElement('div');
//     card.className = 'card';

//     const top = document.createElement('div');
//     top.className = 'cardTop';

//     const left = document.createElement('div');
//     left.innerHTML = `<strong>${m.name}</strong><div class="meta">${m.species} • ${m.bioType} • Intent: ${m.intent}</div>`;

//     const badge = document.createElement('div');
//     badge.className = `badge ${tierToBadgeClass(m.tier)}`;
//     badge.textContent = `${m.tier} • ${m.score}%`;

//     top.appendChild(left);
//     top.appendChild(badge);

//     const p = document.createElement('p');
//     p.textContent = m.summary;

//     card.appendChild(top);
//     card.appendChild(p);

//     els.matchList.appendChild(card);

 
//     const angle = (Math.PI * 2) * ((i + 1) / (results.length + 1));
//     const dist = 60 + (1 - (m.score / 100)) * 160; // better score -> closer to center
//     blips.push({ angle, dist, score: m.score, tier: m.tier, label: m.name });
//   });
// }

//new fixed
function renderMatches(results) {
  els.matchList.innerHTML = "";
  blips = []; // important: reset blips before re-adding

  results.forEach((m, i) => {
    const card = document.createElement('div');
    card.className = 'card';
    card.style.cursor = 'pointer';

    // pick which id you want to open with:
    const idToOpen = m.user_id ?? m.profile_id; // prefer user_id if available

    card.addEventListener('click', () => {
      if (!idToOpen) {
        logLine("NO ID: This match has no DB id (likely seeded demo).", "bad");
        return;
      }
      window.location.href = `/match/${idToOpen}`;
    });

    const top = document.createElement('div');
    top.className = 'cardTop';

    const left = document.createElement('div');
    left.innerHTML = `<strong>${m.name}</strong>
      <div class="meta">${m.species} • ${m.bioType} • Intent: ${m.intent}</div>
      <div class="meta tiny">ID: ${idToOpen ?? "N/A"}</div>`;

    const badge = document.createElement('div');
    badge.className = `badge ${tierToBadgeClass(m.tier)}`;
    badge.textContent = `${m.tier} • ${m.score}%`;

    top.appendChild(left);
    top.appendChild(badge);

    const p = document.createElement('p');
    p.textContent = m.summary;

    card.appendChild(top);
    card.appendChild(p);
    els.matchList.appendChild(card);

    // radar blips
    const angle = (Math.PI * 2) * ((i + 1) / (results.length + 1));
    const dist = 60 + (1 - (m.score / 100)) * 160;
    blips.push({ angle, dist, score: m.score, tier: m.tier, label: m.name });
  });
}


function drawRadar() {
  const c = els.canvas;
  const ctx = c.getContext('2d');
  const w = c.width, h = c.height;
  const cx = w / 2, cy = h / 2;

  ctx.clearRect(0, 0, w, h);

  // rings
  ctx.globalAlpha = 1;
  ctx.lineWidth = 1;
  ctx.strokeStyle = 'rgba(255,255,255,.10)';
  [70, 140, 210].forEach(r => {
    ctx.beginPath();
    ctx.arc(cx, cy, r, 0, Math.PI * 2);
    ctx.stroke();
  });

  // cross lines
  ctx.beginPath();
  ctx.moveTo(cx, cy - 230); ctx.lineTo(cx, cy + 230);
  ctx.moveTo(cx - 230, cy); ctx.lineTo(cx + 230, cy);
  ctx.stroke();

  // sweep
  sweep += 0.025;
  const sx = cx + Math.cos(sweep) * 230;
  const sy = cy + Math.sin(sweep) * 230;

  ctx.strokeStyle = 'rgba(246,195,92,.25)';
  ctx.beginPath();
  ctx.moveTo(cx, cy);
  ctx.lineTo(sx, sy);
  ctx.stroke();

  // sweep glow wedge
  ctx.fillStyle = 'rgba(246,195,92,.06)';
  ctx.beginPath();
  ctx.moveTo(cx, cy);
  ctx.arc(cx, cy, 230, sweep - 0.25, sweep + 0.25);
  ctx.closePath();
  ctx.fill();

  // blips
  blips.forEach(b => {
    const x = cx + Math.cos(b.angle) * b.dist;
    const y = cy + Math.sin(b.angle) * b.dist;

    // glow
    ctx.fillStyle = 'rgba(246,195,92,.10)';
    ctx.beginPath();
    ctx.arc(x, y, 9, 0, Math.PI * 2);
    ctx.fill();

    // core
    const isBad = (b.tier === 'DISASTER' || b.tier === 'DANGEROUS');
    ctx.fillStyle = isBad ? 'rgba(255,91,91,.85)' : 'rgba(125,255,139,.85)';
    ctx.beginPath();
    ctx.arc(x, y, 3.5, 0, Math.PI * 2);
    ctx.fill();
  });

  animId = requestAnimationFrame(drawRadar);
}

async function scan() {
  blips = [];
  const profile = formToProfile();

  // simple local checks
  if (profile.tempMin > profile.tempMax) {
    logLine("ERROR: tempMin cannot be greater than tempMax.", "bad");
    return;
  }

  logLine("INIT: Feeding profile into AI Core...", "muted");

  const res = await fetch('/radar/matches', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
    },
    body: JSON.stringify(profile),
  }).catch(() => null);

  if (!res) {
    logLine("NETWORK FAILURE: Could not reach /radar/matches.", "bad");
    return;
  }

  const data = await res.json().catch(() => null);

  if (!data || data.ok !== true) {
    logLine("AI CORE ERROR: Server did not return ok:true. Check Laravel logs.", "bad");
    return;
  }

  logLine(`SCAN COMPLETE: ${data.count} candidate echoes received.`, "good");

  renderMatches(data.results);
}

function init() {
  els.risk.addEventListener('input', () => {
    els.riskLabel.textContent = els.risk.value;
  });

  els.btnClear.addEventListener('click', () => clearLog());

  els.btnLoadDemo.addEventListener('click', () => {
    setForm(DEMO_PROFILE);
    logLine("DEMO PROFILE LOADED.", "muted");
  });

  els.btnScan.addEventListener('click', () => {
    const profile = formToProfile();

    // Basic validation
    if (Number(profile.tempMin) > Number(profile.tempMax)) {
      logLine("ERROR: tempMin cannot be greater than tempMax.", "bad");
      return;
    }

    // Save profile for /match page to use
    localStorage.setItem('dp_operator_profile', JSON.stringify(profile));

    // Overlay animation
    if (els.overlay) els.overlay.classList.remove('hidden');

    els.btnScan.disabled = true;
    const oldText = els.btnScan.textContent;
    els.btnScan.textContent = 'SCANNING…';

    logLine("Initializing compatibility scan…", "muted");
    setTimeout(() => logLine("Cross-referencing candidates…", "muted"), 500);
    setTimeout(() => logLine("Lock acquired. Redirecting…", "muted"), 1100);

    setTimeout(() => {
      // hide overlay before leaving
      if (els.overlay) els.overlay.classList.add('hidden');

      els.btnScan.textContent = oldText; // optional
      window.location.href = "/match";
    }, 1600);

  }, { once: true });


  logLine("SYSTEM READY. Awaiting operator scan command.", "muted");
  if (!animId) drawRadar();
}

init();
