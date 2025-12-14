(() => {
  const $ = (s)=>document.querySelector(s);
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

  const STORE = {
    likes: "dp_likes_v1",
    matches: "dp_matches_v1",
    profiles: "dp_profiles_v1",
    chats: "dp_chats_v1"
  };

  const clamp=(n,a,b)=>Math.min(b,Math.max(a,n));
  const rand=(a,b)=>Math.random()*(b-a)+a;
  const randi=(a,b)=>Math.floor(rand(a,b+1));
  const pick=(arr)=>arr[Math.floor(Math.random()*arr.length)];

  function load(key, fallback){
    try{
      const raw = localStorage.getItem(key);
      return raw ? JSON.parse(raw) : fallback;
    }catch{ return fallback; }
  }
  function save(key, val){ localStorage.setItem(key, JSON.stringify(val)); }

  let t=null;
  function pop(msg){
    toast.textContent = msg;
    toast.classList.add("on");
    clearTimeout(t);
    t=setTimeout(()=>toast.classList.remove("on"), 1200);
  }

  // Demo profiles
  const NAMES = [
    "Operator R-17","Xeno Trader “Murk”","Automaton Core A-9","Hybrid Diplomat Vela",
    "Airship Mechanic Sol","Archivist “Brassleaf”","Signalwright Kora","Coal-Runner Jax"
  ];
  const SPECIES = ["Human","Automaton","Xeno","Hybrid"];
  const INTENTS = ["Romance","Alliance","Trade"];
  const COMMS = ["Radio","Text","Light","Pheromones","Telepathy"];
  const NOTES = [
    "Likes slow dances and fast engines.",
    "Will flirt via engineering metaphors. It works.",
    "Collects postcards from abandoned stations.",
    "Suspiciously good at reading emotional telemetry.",
    "Wants a partner for stargazing and spare parts.",
    "Polite, direct, and weirdly charming."
  ];

  function makeOpener(p){
    return pick([
      `Greetings, ${p.name.split(" ")[0]}. Want to run a mutual scan?`,
      `Your signal reads "${p.intent}". Mine too. Coincidence?`,
      `I promise excellent radio etiquette. Hello.`,
      `If we were two gears… would we mesh or spark?`,
      `Quick question: tea, coffee, or engine coolant (joking).`
    ]);
  }

  function newProfile(){
    const p = {
      id: crypto.randomUUID ? crypto.randomUUID() : String(Date.now()) + "_" + Math.random().toString(16).slice(2),
      name: pick(NAMES),
      species: pick(SPECIES),
      intent: pick(INTENTS),
      comms: pick(COMMS),
      compat: randi(45, 96),
      risk: randi(10, 92),
      note: pick(NOTES),
    };
    p.opener = makeOpener(p);
    return p;
  }

  function genProfiles(){
    const count = randi(7, 10);
    return Array.from({length: count}, ()=>newProfile());
  }

  // likes/matches storage
  function getLikes(){ return load(STORE.likes, []); }
  function setLikes(v){ save(STORE.likes, v); }
  function getMatches(){ return load(STORE.matches, []); }
  function setMatches(v){ save(STORE.matches, v); }
  function getProfiles(){ return load(STORE.profiles, []); }
  function setProfiles(v){ save(STORE.profiles, v); }

  function updateTopBar(){
    likesEl.textContent = String(getLikes().length);
    matchesEl.textContent = String(getMatches().length);
  }

  // match creation (demo: "they like back" chance)
  function maybeCreateMatch(profile, kind){
    const matches = getMatches();
    if(matches.some(m=>m.profileId === profile.id)) return false;

    // probability of mutual match:
    // LIKE: 55%, SUPER: 85%
    const p = (kind === "SUPER") ? 0.85 : 0.55;
    const mutual = Math.random() < p;

    if(!mutual){
      analystEl.textContent =
`NO MUTUAL LOCK…
${profile.name} didn’t respond yet.
Try another signal.`;
      return false;
    }

    const matchId = crypto.randomUUID ? crypto.randomUUID() : "m_" + Date.now();
    const match = {
      id: matchId,
      profileId: profile.id,
      name: profile.name,
      species: profile.species,
      intent: profile.intent,
      comms: profile.comms,
      compat: profile.compat,
      risk: profile.risk,
      opener: profile.opener,
      createdAt: Date.now()
    };
    matches.unshift(match);
    setMatches(matches);

    // seed chat with an opener message (from "them") for instant dating feel
    const chats = load(STORE.chats, {});
    chats[matchId] = chats[matchId] || [];
    chats[matchId].push({
      id: "seed_"+Date.now(),
      from: "them",
      text: profile.opener,
      ts: Date.now()
    });
    save(STORE.chats, chats);

    analystEl.textContent =
`MATCH CONFIRMED ✅
${profile.name} is now in your Matches list.
Open the secure channel to chat.`;
    pop("It’s a Match! Chat unlocked.");
    updateTopBar();
    return true;
  }

  // Deck
  let deck = [];
  let index = 0;

  function escapeHtml(str){
    return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  }

  function render(){
    stack.innerHTML = "";
    const slice = deck.slice(index, index+3).reverse();
    if(slice.length === 0){
      analystEl.textContent = "Deck empty. Scan new profiles.";
      pop("Deck empty. Scan again.");
      return;
    }

    slice.forEach((p, i)=>{
      const depth = (slice.length-1-i);
      const card = document.createElement("div");
      card.className = "profile";
      card.dataset.id = p.id;

      const z = depth * -18;
      const s = 1 - depth * 0.04;
      const y = depth * 8;

      card.style.transform = `translateY(${y}px) scale(${s}) translateZ(${z}px) rotateZ(${depth * -0.8}deg)`;
      card.style.opacity = String(1 - depth * 0.10);

      card.innerHTML = `
        <h2>${escapeHtml(p.name)}</h2>
        <div class="meta">
          <span class="chip">SPECIES: <b style="color:var(--brass)">${escapeHtml(p.species)}</b></span>
          <span class="chip">INTENT: <b style="color:var(--brass)">${escapeHtml(p.intent)}</b></span>
          <span class="chip">COMMS: <b style="color:var(--brass)">${escapeHtml(p.comms)}</b></span>
        </div>
        <div class="bio">${escapeHtml(p.note)}</div>
        <div class="bars">
          <div class="bar">
            <b>Compatibility</b>
            <div class="meter"><i style="width:${p.compat}%;"></i></div>
          </div>
          <div class="bar">
            <b>Risk Index</b>
            <div class="meter"><i style="width:${p.risk}%;"></i></div>
          </div>
        </div>
      `;

      // only top is draggable
      if(depth === 0){
        attachDrag(card);
        analystEl.textContent =
`TOP SIGNAL:
${p.name} • ${p.compat}% compat • risk ${p.risk}/100
Like to attempt a mutual match.`;
        card.addEventListener("mousemove",(e)=>{
          const r = card.getBoundingClientRect();
          const x = (e.clientX - r.left)/r.width;
          const y2 = (e.clientY - r.top)/r.height;
          card.style.setProperty("--mx",(x*100).toFixed(1)+"%");
          card.style.setProperty("--my",(y2*100).toFixed(1)+"%");
        });
      }

      stack.appendChild(card);
    });

    updateTopBar();
  }

  function topProfile(){
    return deck[index] || null;
  }

  function next(){
    index++;
    render();
  }

  function commit(action){
    const p = topProfile();
    if(!p) return;

    if(action === "LIKE" || action === "SUPER"){
      const likes = getLikes();
      if(!likes.some(x=>x.profileId === p.id)){
        likes.unshift({ profileId: p.id, when: Date.now(), kind: action });
        setLikes(likes);
      }
      pop(action === "SUPER" ? "Super Like sent." : "Liked.");
      maybeCreateMatch(p, action);
    }else{
      pop("Passed.");
      analystEl.textContent = `PASS ▸ ${p.name}\nScanning next signal…`;
    }

    updateTopBar();
    next();
  }

  function fling(card, dir){
    // dir: -1 pass, +1 like, +2 super
    const x = dir > 0 ? 520 : -520;
    const rot = (dir > 0 ? 18 : -18) + (dir===2 ? 10 : 0);
    card.style.transition = "transform .28s var(--ease), opacity .28s var(--ease)";
    card.style.transform = `translateX(${x}px) translateY(-20px) rotateZ(${rot}deg)`;
    card.style.opacity = "0";
    setTimeout(()=>{
      commit(dir === -1 ? "PASS" : (dir===2 ? "SUPER" : "LIKE"));
    }, 150);
  }

  function attachDrag(card){
    let dragging=false, sx=0, sy=0, cx=0, cy=0;
    const threshold = 120;

    card.addEventListener("pointerdown",(e)=>{
      dragging=true;
      card.setPointerCapture(e.pointerId);
      sx=e.clientX; sy=e.clientY;
      cx=0; cy=0;
      card.style.transition="none";
    });
    card.addEventListener("pointermove",(e)=>{
      if(!dragging) return;
      cx = e.clientX - sx;
      cy = e.clientY - sy;
      const rot = clamp(cx/16, -18, 18);
      card.style.transform = `translateX(${cx}px) translateY(${cy*0.12}px) rotateZ(${rot}deg)`;
      if(cx > threshold) pop("Release to LIKE");
      else if(cx < -threshold) pop("Release to PASS");
    });
    const end=()=>{
      if(!dragging) return;
      dragging=false;
      if(cx > threshold) return fling(card, +1);
      if(cx < -threshold) return fling(card, -1);
      card.style.transition="transform .22s var(--ease)";
      card.style.transform="";
    };
    card.addEventListener("pointerup", end);
    card.addEventListener("pointercancel", end);
  }

  // buttons
  btnPass.addEventListener("click", ()=>{
    const c = stack.querySelector(".profile");
    if(c) fling(c, -1);
  });
  btnLike.addEventListener("click", ()=>{
    const c = stack.querySelector(".profile");
    if(c) fling(c, +1);
  });
  btnSuper.addEventListener("click", ()=>{
    const c = stack.querySelector(".profile");
    if(c) fling(c, 2);
  });

  btnScan.addEventListener("click", ()=>{
    deck = genProfiles();
    index = 0;
    setProfiles(deck);
    pop("New signals detected.");
    render();
  });

  btnGoMatches.addEventListener("click", ()=>location.href="matches.html");

  btnClear.addEventListener("click",(e)=>{
    e.preventDefault();
    localStorage.removeItem(STORE.likes);
    localStorage.removeItem(STORE.matches);
    localStorage.removeItem(STORE.profiles);
    localStorage.removeItem(STORE.chats);
    pop("Demo cleared.");
    deck = genProfiles();
    index = 0;
    setProfiles(deck);
    render();
  });

  // boot
  deck = getProfiles();
  if(!Array.isArray(deck) || deck.length === 0){
    deck = genProfiles();
    setProfiles(deck);
  }
  render();
})();