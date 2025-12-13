document.addEventListener('DOMContentLoaded', () => {
  const candidateId = window.__CANDIDATE_ID__;
  const targetCard = document.getElementById('targetCard');
  const chatLog = document.getElementById('chatLog');
  const msg = document.getElementById('msg');
  const protocol = document.getElementById('protocol');
  const btnSend = document.getElementById('btnSend');
  const btnSuggest = document.getElementById('btnSuggest');
  const hint = document.getElementById('chatHint');

  const KEY = `iastromatch_chat_${candidateId}`;

  function loadTarget() {
    // get last scan results from localStorage (saved by radar page)
    const raw = localStorage.getItem('iastromatch_last_results');
    if (!raw) {
      targetCard.textContent = "No scan data found. Return to radar and scan first.";
      return;
    }
    const data = JSON.parse(raw);
    const found = (data.results || []).find(r => r.candidate?.id === candidateId);
    targetCard.textContent = found ? JSON.stringify(found.candidate, null, 2) : "Target not found in scan data.";
  }

  function loadChat() {
    const raw = localStorage.getItem(KEY);
    return raw ? JSON.parse(raw) : [];
  }

  function saveChat(items) {
    localStorage.setItem(KEY, JSON.stringify(items));
  }

  function addBubble(type, text) {
    const div = document.createElement('div');
    div.className = `dp-bubble ${type}`;
    div.textContent = text;
    chatLog.appendChild(div);
    chatLog.scrollTop = chatLog.scrollHeight;
  }

  function render() {
    chatLog.innerHTML = '';
    const items = loadChat();
    items.forEach(i => addBubble(i.type, i.text));
  }

  function suggestReply() {
    const p = protocol.value;
    const suggestions = {
      'RADIO-7B': [
        "Radio check. Identify yourself and state intention.",
        "Requesting safe docking corridor. Respond with clearance code.",
      ],
      'DIPLOMATIC-PACKET': [
        "We seek alliance and mutual non-aggression. Confirm terms.",
        "Cultural respect protocol initiated. Exchange symbols of peace.",
      ],
      'TRADE-OFFER': [
        "Offering fuel cells + spare parts in exchange for navigation charts.",
        "Proposing resource exchange under neutral charter.",
      ],
      'CEASEFIRE-SIGNAL': [
        "Ceasefire request. Stand down weapons systems. Confirm receipt.",
        "We request immediate de-escalation and communication channel.",
      ],
    };

    const pick = (suggestions[p] || ["State your intent clearly."])[Math.floor(Math.random() * 2)];
    msg.value = pick;
    hint.textContent = "IA suggestion loaded into input.";
  }

  btnSend.addEventListener('click', () => {
    const text = msg.value.trim();
    if (!text) return;

    const p = protocol.value;
    const items = loadChat();

    items.push({ type: 'me', text: `[${p}] ${text}` });
    items.push({ type: 'sys', text: `ACK: Signal forwarded via ${p}. (Simulated response pending...)` });

    saveChat(items);
    msg.value = '';
    render();
  });

  btnSuggest.addEventListener('click', suggestReply);
  msg.addEventListener('keydown', (e) => { if (e.key === 'Enter') btnSend.click(); });

  loadTarget();
  render();
});
