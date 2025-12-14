(() => {
  const list = document.getElementById("list");
  const clearBtn = document.getElementById("clear");

  const STORE = {
    matches: "dp_matches_v1",
    chats: "dp_chats_v1"
  };

  function load(key, fallback){
    try{
      const raw = localStorage.getItem(key);
      return raw ? JSON.parse(raw) : fallback;
    }catch{ return fallback; }
  }
  function save(key, val){ localStorage.setItem(key, JSON.stringify(val)); }

  function render(){
    const matches = load(STORE.matches, []);
    list.innerHTML = "";

    if(!matches.length){
      list.innerHTML = `<div style="font-family:ui-monospace,Menlo,Consolas,monospace;color:rgba(242,242,242,.75);font-size:12px;">
        No matches yet. Go back to the deck and LIKE profiles.
      </div>`;
      return;
    }

    matches.forEach(m=>{
      const row = document.createElement("div");
      row.className = "match";
      row.innerHTML = `
        <div>
          <strong>${escapeHtml(m.name)}</strong>
          <small>${escapeHtml(m.intent)} • ${m.compat}% compat • risk ${m.risk}/100 • comms ${escapeHtml(m.comms)}</small>
        </div>
        <button class="btn" data-id="${m.id}">Open Chat</button>
      `;
      row.querySelector("button").addEventListener("click", ()=>{
        location.href = `chat.html?m=${encodeURIComponent(m.id)}`;
      });
      list.appendChild(row);
    });
  }

  function escapeHtml(str){
    return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  }

  clearBtn.addEventListener("click", ()=>{
    localStorage.removeItem(STORE.matches);
    localStorage.removeItem(STORE.chats);
    render();
  });

  render();
})();