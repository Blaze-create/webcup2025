<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>IAstroMatch • Secure Channel</title>
  <style>
    :root {
      --brass-dark: #594622;
      --brass: #BF9A54;
      --rust: #402A10;
      --paper: #F2F2F2;
      --line: rgba(191, 154, 84, .22);
      --glass: rgba(242, 242, 242, .07);
      --shadow: rgba(0, 0, 0, .40);
      --mono: ui-monospace, Menlo, Consolas, monospace;
      --sans: system-ui, Segoe UI, Roboto, Arial;
      --ease: cubic-bezier(.2, .85, .2, 1);
    }

    * {
      box-sizing: border-box
    }

    html,
    body {
      height: 100%;
      margin: 0
    }

    body {
      font-family: var(--sans);
      color: var(--paper);
      background:
        radial-gradient(900px 600px at 20% 10%, rgba(191, 154, 84, .22), transparent 55%),
        radial-gradient(900px 600px at 80% 20%, rgba(140, 93, 35, .18), transparent 55%),
        linear-gradient(180deg, #1a1209, var(--rust));
    }

    header {
      position: sticky;
      top: 0;
      z-index: 5;
      background: linear-gradient(180deg, rgba(64, 42, 16, .78), rgba(64, 42, 16, .30));
      border-bottom: 1px solid var(--line);
      backdrop-filter: blur(10px);
    }

    .wrap {
      width: min(1100px, 92vw);
      margin: 0 auto;
    }

    .nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
      padding: 14px 0;
    }

    .brand h1 {
      margin: 0;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: .7px;
    }

    .brand p {
      margin: 0;
      font-family: var(--mono);
      font-size: 11px;
      color: rgba(242, 242, 242, .68);
    }

    a.link {
      font-family: var(--mono);
      font-size: 12px;
      text-decoration: none;
      color: rgba(242, 242, 242, .85);
      border: 1px solid rgba(191, 154, 84, .20);
      background: rgba(242, 242, 242, .06);
      padding: 9px 10px;
      border-radius: 999px;
    }

    a.link:hover {
      color: var(--brass);
      border-color: rgba(191, 154, 84, .35);
    }

    main {
      width: min(1100px, 92vw);
      margin: 0 auto;
      padding: 14px 0;
      display: grid;
      grid-template-columns: 1fr 360px;
      gap: 14px;
    }

    @media(max-width:900px) {
      main {
        grid-template-columns: 1fr;
      }
    }

    .panel {
      border: 1px solid var(--line);
      background: linear-gradient(180deg, rgba(242, 242, 242, .08), rgba(242, 242, 242, .04));
      border-radius: 18px;
      box-shadow: 0 24px 70px var(--shadow);
      overflow: hidden;
    }

    .chatTop {
      padding: 12px;
      border-bottom: 1px solid rgba(191, 154, 84, .16);
      background: linear-gradient(180deg, rgba(64, 42, 16, .55), rgba(64, 42, 16, .18));
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
    }

    .chatTop strong {
      font-size: 13px;
      letter-spacing: .8px;
      text-transform: uppercase;
    }

    .status {
      font-family: var(--mono);
      font-size: 11px;
      padding: 7px 9px;
      border-radius: 999px;
      border: 1px solid rgba(191, 154, 84, .22);
      background: rgba(242, 242, 242, .06);
      color: rgba(242, 242, 242, .82);
    }

    .status b {
      color: var(--brass);
    }

    .messages {
      height: 520px;
      overflow: auto;
      padding: 14px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      background: rgba(0, 0, 0, .14);
    }

    .msg {
      max-width: 72%;
      padding: 10px 12px;
      border-radius: 16px;
      font-size: 13px;
      line-height: 1.45;
      word-wrap: break-word;
    }

    .me {
      align-self: flex-end;
      background: linear-gradient(145deg, var(--brass), #d8b87b);
      color: #1a1108;
    }

    .them {
      align-self: flex-start;
      background: rgba(242, 242, 242, .08);
      border: 1px solid rgba(191, 154, 84, .22);
    }

    .time {
      margin-top: 6px;
      font-family: var(--mono);
      font-size: 10px;
      opacity: .70;
    }

    .typingRow {
      padding: 8px 14px;
      border-top: 1px solid rgba(191, 154, 84, .12);
      font-family: var(--mono);
      font-size: 11px;
      color: rgba(242, 242, 242, .70);
      min-height: 34px;
    }

    .typingDots {
      display: inline-block;
      width: 18px;
    }

    .typingDots::after {
      content: "...";
      animation: dots 1s infinite steps(4, end);
    }

    @keyframes dots {
      0% {
        content: "";
      }

      25% {
        content: ".";
      }

      50% {
        content: "..";
      }

      75% {
        content: "...";
      }

      100% {
        content: "";
      }
    }

    .inputBar {
      display: flex;
      gap: 10px;
      padding: 12px;
      border-top: 1px solid rgba(191, 154, 84, .16);
      background: rgba(0, 0, 0, .14);
    }

    .inputBar input {
      flex: 1;
      border-radius: 14px;
      border: 1px solid rgba(191, 154, 84, .20);
      background: rgba(0, 0, 0, .30);
      color: var(--paper);
      padding: 12px;
      outline: none;
    }

    .inputBar button {
      border: 0;
      border-radius: 14px;
      padding: 0 16px;
      font-weight: 900;
      letter-spacing: .3px;
      color: #1a1108;
      background: linear-gradient(145deg, var(--brass), #d8b87b);
      cursor: pointer;
      box-shadow: 0 14px 30px rgba(0, 0, 0, .35), inset 0 1px 0 rgba(242, 242, 242, .35);
    }

    .inputBar button:active {
      transform: scale(.98);
    }

    .sideTop {
      padding: 12px;
      border-bottom: 1px solid rgba(191, 154, 84, .16);
      background: linear-gradient(180deg, rgba(64, 42, 16, .55), rgba(64, 42, 16, .18));
    }

    .sideTop strong {
      display: block;
      font-size: 13px;
      letter-spacing: .8px;
      text-transform: uppercase;
    }

    .sideTop span {
      display: block;
      font-family: var(--mono);
      font-size: 11px;
      color: rgba(242, 242, 242, .68);
      margin-top: 4px;
      line-height: 1.4;
    }

    .sideBody {
      padding: 12px;
      display: grid;
      gap: 12px;
    }

    .box {
      border: 1px solid rgba(191, 154, 84, .18);
      background: rgba(0, 0, 0, .18);
      border-radius: 16px;
      padding: 12px;
    }

    .box h3 {
      margin: 0 0 8px;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: .8px;
    }

    .box p {
      margin: 0;
      font-family: var(--mono);
      font-size: 11.5px;
      color: rgba(242, 242, 242, .74);
      line-height: 1.55;
      white-space: pre-line;
    }

    .iceRow {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center;
    }

    .btnGhost {
      border: 1px solid rgba(191, 154, 84, .26);
      background: rgba(242, 242, 242, .06);
      color: rgba(242, 242, 242, .92);
      border-radius: 14px;
      padding: 10px 12px;
      cursor: pointer;
      font-weight: 800;
    }

    .btnGhost:hover {
      border-color: rgba(191, 154, 84, .40);
      color: var(--brass);
    }

    .small {
      font-family: var(--mono);
      font-size: 11px;
      color: rgba(242, 242, 242, .70);
    }

    .toast {
      position: fixed;
      left: 50%;
      bottom: 18px;
      transform: translateX(-50%);
      border: 1px solid rgba(191, 154, 84, .25);
      background: rgba(64, 42, 16, .72);
      backdrop-filter: blur(10px);
      border-radius: 999px;
      padding: 10px 12px;
      font-family: var(--mono);
      color: rgba(242, 242, 242, .88);
      box-shadow: 0 18px 50px rgba(0, 0, 0, .35);
      opacity: 0;
      pointer-events: none;
      transition: opacity .18s var(--ease), transform .18s var(--ease);
      z-index: 50;
      white-space: nowrap;
      max-width: min(92vw, 720px);
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .toast.on {
      opacity: 1;
      transform: translateX(-50%) translateY(-4px);
    }

    .chat-box {
      display: flex;
      flex-direction: column;
      gap: 10px;
      padding: 14px;
      max-height: 70vh;
      overflow: auto;
    }

    .msg-row {
      display: flex;
      width: 100%;
    }

    .msg-row.me {
      justify-content: flex-end;
    }

    .msg-row.them {
      justify-content: flex-start;
    }

    .bubble {
      max-width: min(560px, 78%);
      padding: 10px 12px;
      border-radius: 16px;
      position: relative;
      box-shadow: 0 6px 16px rgba(0, 0, 0, .18);
      border: 1px solid rgba(255, 255, 255, .08);
      backdrop-filter: blur(6px);
    }

    .msg-row.me .bubble {
      border-bottom-right-radius: 6px;
      background: rgba(120, 255, 210, .14);
    }

    .msg-row.them .bubble {
      border-bottom-left-radius: 6px;
      background: rgba(120, 170, 255, .14);
    }

    .bubble-text {
      white-space: pre-wrap;
      word-wrap: break-word;
      line-height: 1.35;
    }

    .bubble-meta {
      margin-top: 6px;
      font-size: 12px;
      opacity: .7;
      text-align: right;
    }

    /* Optional: tails */
    .msg-row.me .bubble::after {
      content: "";
      position: absolute;
      right: -6px;
      bottom: 10px;
      width: 10px;
      height: 10px;
      background: inherit;
      transform: rotate(45deg);
      border-right: 1px solid rgba(255, 255, 255, .08);
      border-bottom: 1px solid rgba(255, 255, 255, .08);
    }

    .msg-row.them .bubble::after {
      content: "";
      position: absolute;
      left: -6px;
      bottom: 10px;
      width: 10px;
      height: 10px;
      background: inherit;
      transform: rotate(45deg);
      border-left: 1px solid rgba(255, 255, 255, .08);
      border-bottom: 1px solid rgba(255, 255, 255, .08);
    }

    .empty-chat {
      opacity: .7;
      padding: 12px;
      text-align: center;
    }
  </style>
</head>

<body>
  <header>
    <div class="wrap">
      <div class="nav">
        <div class="brand">
          <h1>IAstroMatch</h1>
          <p>Secure Channel • Private match chat</p>
        </div>
        <a class="link" href="{{ route('matches.page') }}">← Back to Matches</a>
      </div>
    </div>
  </header>

  <main>
    <!-- Chat -->
    <section class="panel">
      <div class="chatTop">
        <strong id="title">{{$receiver->name}}</strong>
        <div class="status">ONLINE: <b id="online">YES</b></div>
      </div>


      @php $me = auth()->id(); @endphp

      <div class="chat-box" id="chatBox">
        @forelse ($messages as $msg)
        @php
        $isMe = (int)$msg->sender_id === (int)$me;
        @endphp

        <div class="msg-row {{ $isMe ? 'me' : 'them' }}">
          <div class="bubble">
            <div class="bubble-text">{{ $msg->body }}</div>
            <div class="bubble-meta">
              {{ $msg->created_at?->format('H:i') }}
            </div>
          </div>
        </div>
        @empty
        <div class="empty-chat">No messages yet.</div>
        @endforelse
      </div>

      </div>



      <div class="typingRow" id="typingRow"></div>

      <form class="inputBar" id="chatForm" method="POST" action="{{ route('chat.send', $user->id) }}">
        @csrf
        <input id="text" placeholder="Type a message…" autocomplete="off" name="body"/>
        <button id="send">Send</button>
      </form>
    </section>


    <!-- Side -->
    <aside class="panel">
      <div class="sideTop">
        <strong>Icebreakers</strong>
      </div>
      <div class="sideBody">
        <div class="box">
          <h3>Profile Snapshot</h3>
          <p id="snap">Loading…</p>
        </div>

        <div class="box">
          <h3>Suggested Openers</h3>
          <div class="iceRow">
            <button class="btnGhost" id="ice1">Insert #1</button>
            <button class="btnGhost" id="ice2">Insert #2</button>
            <button class="btnGhost" id="ice3">Insert #3</button>
          </div>
          <div class="small" style="margin-top:10px;">Tip: you can edit the message before sending.</div>
        </div>

        <div class="box">
          <h3>Channel Tools</h3>
          <div class="iceRow">
            <button class="btnGhost" id="toggleOnline">Toggle Online</button>
            <button class="btnGhost" id="clearChat">Clear Chat</button>
          </div>
          <div class="small" style="margin-top:10px;">Typing indicator appears when they “respond”.</div>
        </div>
      </div>
    </aside>
  </main>

  <div class="toast" id="toast">Toast</div>

  <!-- <script>
    (() => {
      const $ = (s) => document.querySelector(s);
      const messagesEl = $("#messages");
      const typingRow = $("#typingRow");
      const titleEl = $("#title");
      const onlineEl = $("#online");
      const snapEl = $("#snap");
      const input = $("#text");
      const sendBtn = $("#send");
      const toast = $("#toast");

      const ice1 = $("#ice1"),
        ice2 = $("#ice2"),
        ice3 = $("#ice3");
      const toggleOnline = $("#toggleOnline");
      const clearChat = $("#clearChat");

      const STORE = {
        matches: "dp_matches_v1",
        chats: "dp_chats_v1"
      };

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

      let t = null;

      function pop(msg) {
        toast.textContent = msg;
        toast.classList.add("on");
        clearTimeout(t);
        t = setTimeout(() => toast.classList.remove("on"), 1200);
      }

      function qs(name) {
        const u = new URL(location.href);
        return u.searchParams.get(name);
      }

      const matchId = qs("m");
      const matches = load(STORE.matches, []);
      const match = matches.find(m => m.id === matchId);

      if (!match) {
        titleEl.textContent = "Invalid match";
        snapEl.textContent = "This channel does not exist.\nGo back and open a valid match.";
        pop("Invalid match link.");
        return;
      }

      titleEl.textContent = `Channel: ${match.name}`;
      snapEl.textContent =
        `NAME: ${match.name}
SPECIES: ${match.species}
INTENT: ${match.intent}
COMMS: ${match.comms}
COMPAT: ${match.compat}%
RISK: ${match.risk}/100`;

      // Icebreakers (scripted “AI”)
      const openers = [
        match.opener || `Hello — your signal came through clean. Want to compare intents?`,
        `Quick question: if we had an airship date, where do we dock first?`,
        `I’m on ${match.comms}. What’s your favorite way to communicate when things get real?`,
      ];

      ice1.textContent = "Insert #1";
      ice2.textContent = "Insert #2";
      ice3.textContent = "Insert #3";

      ice1.addEventListener("click", () => {
        input.value = openers[0];
        input.focus();
        pop("Inserted opener.");
      });
      ice2.addEventListener("click", () => {
        input.value = openers[1];
        input.focus();
        pop("Inserted opener.");
      });
      ice3.addEventListener("click", () => {
        input.value = openers[2];
        input.focus();
        pop("Inserted opener.");
      });

      // Online + typing simulation
      let online = true;

      function setOnline(v) {
        online = v;
        onlineEl.textContent = online ? "YES" : "NO";
        pop(online ? "Online" : "Offline");
      }

      toggleOnline.addEventListener("click", () => setOnline(!online));

      // chat storage
      function getChats() {
        return load(STORE.chats, {});
      }

      function setChats(v) {
        save(STORE.chats, v);
      }

      function fmtTime(ts) {
        const d = new Date(ts);
        return d.toLocaleTimeString([], {
          hour: "2-digit",
          minute: "2-digit"
        });
      }

      function render() {
        messagesEl.innerHTML = "";
        const chats = getChats();
        const msgs = chats[matchId] || [];
        msgs.forEach(addMsg);
        messagesEl.scrollTop = messagesEl.scrollHeight;
      }

      function addMsg(m) {
        const div = document.createElement("div");
        div.className = "msg " + (m.from === "me" ? "me" : "them");
        div.innerHTML = `
      <div>${escapeHtml(m.text)}</div>
      <div class="time">${fmtTime(m.ts)}</div>
    `;
        messagesEl.appendChild(div);
      }

      function escapeHtml(str) {
        return String(str).replace(/[&<>"']/g, m => ({
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#39;'
        } [m]));
      }

      function pushMessage(from, text) {
        const chats = getChats();
        chats[matchId] = chats[matchId] || [];
        chats[matchId].push({
          id: (crypto.randomUUID ? crypto.randomUUID() : "msg_" + Date.now()),
          from,
          text,
          ts: Date.now()
        });
        setChats(chats);
      }

      function showTyping(on) {
        typingRow.innerHTML = on ?
          `<span>${escapeHtml(match.name)} is typing</span><span class="typingDots"></span>` :
          "";
      }

      function send() {
        const txt = input.value.trim();
        if (!txt) return;

        pushMessage("me", txt);
        input.value = "";
        showTyping(false);
        render();

        // simulate them responding (typing indicator + reply) only if online
        if (!online) return;

        showTyping(true);
        setTimeout(() => {
          showTyping(false);

          const replies = [
            "Signal received. That’s actually a good question.",
            "I like your style. What are you looking for right now?",
            "Okay, that made me smile. Tell me one thing you’re obsessed with.",
            "I’m listening. Continue, Operator.",
            "If we meet, do we do coffee or we go full airship dock tour?"
          ];

          // tiny “AI-ish” reply influenced by intent
          const intentReply = {
            Romance: "Romance protocols acknowledged. Are you more slow-burn or instant spark?",
            Alliance: "Alliance protocol accepted. What’s your ideal partnership dynamic?",
            Trade: "Trade mode noted. What do you bring to the table—skills, stories, or both?"
          } [match.intent];

          const reply = (Math.random() < 0.35 && intentReply) ? intentReply : replies[Math.floor(Math.random() * replies.length)];
          pushMessage("them", reply);
          render();
        }, 900 + Math.random() * 900);
      }

      sendBtn.addEventListener("click", send);
      input.addEventListener("keydown", (e) => {
        if (e.key === "Enter") send();
      });

      clearChat.addEventListener("click", () => {
        const chats = getChats();
        chats[matchId] = [];
        setChats(chats);
        showTyping(false);
        render();
        pop("Chat cleared.");
      });

      // boot
      render();
      setOnline(true);
    })();
  </script> -->
</body>

</html>