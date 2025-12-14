(() => {
  /* ============================
     DOM HELPERS
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
     STORAGE KEYS
  ============================ */
  const STORE = {
    likes: "dp_likes_v2",
    matches: "dp_matches_v2",
    chats: "dp_chats_v2"
  };

  /* ============================
     STATE
  ============================ */
  let deck = [];
  let index = 0;

  /* ============================
     UTILS
  ============================ */
  const clamp = (n, a, b) => Math.min(b, Math.max(a, n));

  function load(key, fallback) {
    try {
      const raw = localStorage.getItem(key);
      return raw ? JSON.parse(raw) : fallback;
    } catch {
      return fallback;
    }
  }

  function save(key, val) {
    localStorage.setItem(key, JSON.stringify(val));
  }

  function pop(msg) {
    if (!toast) return;
    toast.textContent = msg;
    toast.classList.add("on");
    setTimeout(() => toast.classList.remove("on"), 1200);
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, m =>
      ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m])
    );
  }

  /* ============================
     LIKES / MATCHES
  ============================ */
  function getLikes() { return load(STORE.likes, []); }
  function setLikes(v) { save(STORE.likes, v); }

  function getMatches() { return load(STORE.matches, []); }
  function setMatches(v) { save(STORE.matches, v); }

  function updateTopBar() {
    likesEl.textContent = getLikes().length;
    matchesEl.textContent = getMatches().length;
  }

  /* ============================
     MATCH CREATION (REAL)
  ============================ */
  function createMatch(profile) {
    const matches = getMatches();
    if (matches.some(m => m.profileId === profile.id)) return;

    const match = {
      id: profile.match_id ?? crypto.randomUUID(),
      profileId: profile.id,
      name: profile.name,
      createdAt: Date.now()
    };

    matches.unshift(match);
    setMatches(matches);

    pop("It’s a Match!");
    analystEl.textContent = `MATCH CONFIRMED ✅\n${profile.name} is now in your Matches list.`;
    updateTopBar();
  }

  /* ============================
     DECK RENDERING
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

    updateTopBar();
  }

  /* ============================
     SWIPE HANDLING
  ============================ */
  function next() {
    index++;
    render();
  }

  function commit(profile, action) {
    if (action === "LIKE" || action === "SUPER") {
      const likes = getLikes();
      likes.unshift({ profileId: profile.id, when: Date.now(), kind: action });
      setLikes(likes);

      if (profile.mutual === true) {
        createMatch(profile);
      } else {
        analystEl.textContent = `Signal sent to ${profile.name}. Awaiting response…`;
      }
    } else {
      analystEl.textContent = `PASS ▸ ${profile.name}`;
    }
    updateTopBar();
    next();
  }

  function attachDrag(card, profile) {
    let startX = 0, curX = 0, dragging = false;
    const threshold = 120;

    card.addEventListener("pointerdown", (e) => {
      dragging = true;
      startX = e.clientX;
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
  }

  /* ============================
     FETCH REAL MATCHES
  ============================ */
  async function fetchMatches() {
    const raw = localStorage.getItem("dp_operator_profile");
    if (!raw) {
      analystEl.textContent = "No scan profile found. Go back to Radar.";
      return;
    }

    const profile = JSON.parse(raw);

    analystEl.textContent = "Scanning database for compatible signals…";

    const res = await fetch("/radar/matches", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        ...(csrf ? { "X-CSRF-TOKEN": csrf } : {})
      },
      body: JSON.stringify(profile)
    });

    const data = await res.json();

    if (!data || data.ok !== true) {
      analystEl.textContent = "AI CORE ERROR. Try again.";
      return;
    }

    deck = data.results.map(r => ({
      id: r.id,
      name: r.name,
      species: r.species,
      intent: r.intent,
      comms: r.comms,
      compat: clamp(r.score ?? 70, 0, 100),
      risk: clamp(r.risk ?? 50, 0, 100),
      note: r.note ?? "Signal acquired from database.",
      mutual: r.mutual ?? false,
      match_id: r.match_id ?? null
    }));

    index = 0;
    analystEl.textContent = `SCAN COMPLETE ✅ ${deck.length} candidates loaded.`;
    pop("Candidates loaded.");
    render();
  }

  /* ============================
     BUTTONS
  ============================ */
  btnPass?.addEventListener("click", () => {
    const p = deck[index];
    if (p) commit(p, "PASS");
  });

  btnLike?.addEventListener("click", () => {
    const p = deck[index];
    if (p) commit(p, "LIKE");
  });

  btnSuper?.addEventListener("click", () => {
    const p = deck[index];
    if (p) commit(p, "SUPER");
  });

  btnScan?.addEventListener("click", fetchMatches);
  btnGoMatches?.addEventListener("click", () => location.href = "/matches");

  btnClear?.addEventListener("click", (e) => {
    e.preventDefault();
    localStorage.removeItem(STORE.likes);
    localStorage.removeItem(STORE.matches);
    localStorage.removeItem(STORE.chats);
    pop("Local data cleared.");
    updateTopBar();
  });

  /* ============================
     BOOT
  ============================ */
  updateTopBar();
  fetchMatches();

})();
