(() => {
  /* ============================
     DOM
  ============================ */
  const $ = (s) => document.querySelector(s);

  const stack = $("#stack");
  const likesEl = $("#likes");
  const matchesEl = $("#matches");
  const analystEl = $("#analyst");
  const toast = $("#toast");

  const btnPass = $("#btnPass");
  const btnLike = $("#btnLike");
  const btnSuper = $("#btnSuper");
  const btnScan = $("#scan");
  const btnGoMatches = $("#goMatches");
  const btnClear = $("#clear");

  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

  /* ============================
     STATE
  ============================ */
  let deck = [];
  let index = 0;

  /* ============================
     UTILS
  ============================ */
  const clamp = (n, a, b) => Math.min(b, Math.max(a, n));

  let toastT = null;
  function pop(msg) {
    if (!toast) return;
    toast.textContent = msg;
    toast.classList.add("on");
    clearTimeout(toastT);
    toastT = setTimeout(() => toast.classList.remove("on"), 1200);
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, m =>
      ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m])
    );
  }

  /* ============================
     COUNTERS (DB)
  ============================ */
  let likesCount = 0; // only for UI feedback this session

  async function refreshMatchesCount() {
    // Uses your MatchController@myMatches (GET /matches-data)
    try {
      const res = await fetch('/matches-data', { headers: { 'Accept': 'application/json' } });
      const data = await res.json();
      if (data?.ok && Array.isArray(data.matches)) {
        matchesEl.textContent = String(data.matches.length);
      }
    } catch {
      // ignore
    }
  }

  async function refreshLikesCount() {
    try {
      const res = await fetch('/match/likes-count', { headers: { 'Accept': 'application/json' } });
      const data = await res.json();
      if (data?.ok) {
        likesEl.textContent = String(data.count ?? 0);
      }
    } catch {
      // ignore
    }
  }

  function updateLikesUi() {
    likesEl.textContent = String(likesCount);
  }

  /* ============================
     API: LIKE (DB)
  ============================ */
  async function sendLikeToDb(likedUserId) {
    const res = await fetch('/like', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
      },
      body: JSON.stringify({ liked_id: likedUserId }),
    });

    return res.json();
  }

  /* ============================
     DECK RENDER
  ============================ */
  function render() {
    stack.innerHTML = "";

    const slice = deck.slice(index, index + 3).reverse();
    if (!slice.length) {
      analystEl.textContent = "No more signals. Scan again.";
      pop("Deck empty.");
      return;
    }

    slice.forEach((p, i) => {
      const depth = slice.length - 1 - i;
      const card = document.createElement("div");
      card.className = "profile";

      card.style.transform = `
        translateY(${depth * 8}px)
        scale(${1 - depth * 0.04})
        rotateZ(${depth * -0.8}deg)
      `;
      card.style.opacity = String(1 - depth * 0.12);

      card.innerHTML = `
        <h2>${escapeHtml(p.name)}</h2>
        <div class="meta">
          <span class="chip">SPECIES: <b>${escapeHtml(p.species)}</b></span>
          <span class="chip">INTENT: <b>${escapeHtml(p.intent)}</b></span>
          <span class="chip">COMMS: <b>${escapeHtml(p.comms)}</b></span>
        </div>
        <div class="bio">${escapeHtml(p.note)}</div>
        <div class="bars">
          <div class="bar">
            <b>Compatibility</b>
            <div class="meter"><i style="width:${p.compat}%"></i></div>
          </div>
          <div class="bar">
            <b>Risk</b>
            <div class="meter"><i style="width:${p.risk}%"></i></div>
          </div>
        </div>
      `;

      if (depth === 0) attachDrag(card, p);
      stack.appendChild(card);
    });
  }

  function next() {
    index++;
    render();
  }

  function topProfile() {
    return deck[index] || null;
  }

  /* ============================
     ACTIONS (PASS / LIKE / SUPER)
  ============================ */
  async function commit(profile, action) {
    if (!profile) return;

    if (action === "PASS") {
      analystEl.textContent = `PASS ▸ ${profile.name}`;
      pop("Passed.");
      next();
      return;
    }

    // LIKE/SUPER => must have real user id
    if (!profile.user_id) {
      pop("Missing user_id in candidate data.");
      analystEl.textContent = "Backend must return user_id for candidates.";
      return;
    }

    // UI feedback
     likesEl.textContent = String((Number(likesEl.textContent) || 0) + 1);

    // Button glow feedback
    if (action === "LIKE" && btnLike) btnLike.classList.add("liked");
    if (action === "SUPER" && btnSuper) btnSuper.classList.add("liked");

    try {
      const data = await sendLikeToDb(profile.user_id);

      if (!data?.ok) {
        pop(data?.message || "Like failed.");
        analystEl.textContent = "LIKE failed. Check auth/route/CSRF.";
      } else {
        pop(action === "SUPER" ? "Super Like sent." : "Liked.");

        if (data.matched) {
          pop("It’s a Match!");
          analystEl.textContent =
`MATCH CONFIRMED ✅
${profile.name} is now in your Matches list.`;

          // Update matches counter from DB
          await refreshMatchesCount();

          // Optional: auto redirect to chat
          // window.location.href = `/chat/${data.match_id}`;
        } else {
          analystEl.textContent = `Signal sent to ${profile.name}. Awaiting response…`;
        }

        // move on
        next();
      }

    } catch {
      pop("Network error.");
      analystEl.textContent = "Network error sending like.";
    } finally {
      if (btnLike) btnLike.classList.remove("liked");
      if (btnSuper) btnSuper.classList.remove("liked");
    }
  }

  /* ============================
     DRAG SWIPE
  ============================ */
  function attachDrag(card, profile) {
    let startX = 0, curX = 0, dragging = false;
    const threshold = 120;

    card.addEventListener("pointerdown", (e) => {
      dragging = true;
      startX = e.clientX;
      curX = 0;
      card.setPointerCapture(e.pointerId);
      card.style.transition = "none";
    });

    card.addEventListener("pointermove", (e) => {
      if (!dragging) return;
      curX = e.clientX - startX;
      card.style.transform = `translateX(${curX}px) rotateZ(${clamp(curX / 10, -18, 18)}deg)`;
    });

    card.addEventListener("pointerup", () => {
      dragging = false;
      card.style.transition = "transform .25s ease";

      if (curX > threshold) commit(profile, "LIKE");
      else if (curX < -threshold) commit(profile, "PASS");
      else card.style.transform = "";
    });

    card.addEventListener("pointercancel", () => {
      dragging = false;
      card.style.transition = "transform .25s ease";
      card.style.transform = "";
    });
  }

  /* ============================
     FETCH REAL CANDIDATES (Radar)
  ============================ */
  async function fetchMatches() {
    const raw = localStorage.getItem("dp_operator_profile");
    if (!raw) {
      analystEl.textContent = "No scan profile found. Go back to Radar and press Scan.";
      pop("No scan profile found.");
      return;
    }

    let profile;
    try { profile = JSON.parse(raw); }
    catch {
      analystEl.textContent = "Scan profile corrupted. Re-scan from Radar.";
      pop("Bad scan profile.");
      return;
    }

    analystEl.textContent = "Scanning database for compatible signals…";

    const res = await fetch("/radar/matches", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        ...(csrf ? { "X-CSRF-TOKEN": csrf } : {})
      },
      body: JSON.stringify(profile)
    }).catch(() => null);

    if (!res) {
      analystEl.textContent = "NETWORK FAILURE: could not reach /radar/matches";
      pop("Network failure.");
      return;
    }

    const data = await res.json().catch(() => null);

    if (!data || data.ok !== true) {
      analystEl.textContent = "AI CORE ERROR. Try again.";
      pop("AI Core error.");
      return;
    }

    // IMPORTANT: backend must provide user_id (users.id) for likes to work
    deck = (data.results || []).map((r, idx) => ({
      user_id: r.user_id ?? r.id, // <-- prefer r.user_id. If backend sends user id in r.id, this still works.
      name: r.name || `Candidate #${idx + 1}`,
      species: r.species || "Unknown",
      intent: r.intent || r.primary_intent || "Unknown",
      comms: r.comms || r.communication_method || "Unknown",
      compat: clamp(Number(r.score ?? r.compat ?? 70), 0, 100),
      risk: clamp(Number(r.risk ?? r.risk_tolerance ?? 50), 0, 100),
      note: r.note || r.bio || "Signal acquired from database."
    }));

    index = 0;
    analystEl.textContent = `SCAN COMPLETE ✅ ${deck.length} candidates loaded.`;
    pop("Candidates loaded.");
    render();
  }

  /* ============================
     BUTTONS
  ============================ */
  btnPass?.addEventListener("click", () => commit(topProfile(), "PASS"));
  btnLike?.addEventListener("click", () => commit(topProfile(), "LIKE"));
  btnSuper?.addEventListener("click", () => commit(topProfile(), "SUPER"));

  btnScan?.addEventListener("click", fetchMatches);
  btnGoMatches?.addEventListener("click", () => location.href = "/matches");

  // "Clear demo" no longer clears DB. We'll just clear local scan profile + UI counters.
  btnClear?.addEventListener("click", (e) => {
    e.preventDefault();
    likesCount = 0;
    updateLikesUi();
    pop("Cleared local UI counters.");
    analystEl.textContent = "Cleared local counters. DB likes/matches remain saved.";
  });

  /* ============================
     BOOT
  ============================ */
  (async function boot() {
    likesCount = 0;
    updateLikesUi();

    // matches count from DB
    matchesEl.textContent = "0";
    await refreshMatchesCount();

    // load deck from DB-matching endpoint
    await fetchMatches();
  })();

})();
