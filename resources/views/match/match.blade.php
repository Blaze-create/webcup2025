<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>IAstroMatch • Match Console</title>

  <link rel="stylesheet" href="/css/match.css">

</head>
<body>
<header>
  <div class="wrap">
    <div class="nav">
      <div class="brand">
        <div class="badge"></div>
        <div>
          <h1>IAstroMatch</h1>
          <p>Swipe Console • Like → Match → Chat</p>
        </div>
      </div>
      <div class="pills">
        <div class="pill">LIKES: <b id="likes">0</b></div>
        <div class="pill">MATCHES: <b id="matches">0</b></div>
        <div class="pill"><a href="matches.html">Open Matches →</a></div>
        <div class="pill"><a href="#" id="clear">Clear demo</a></div>
      </div>
    </div>
  </div>
</header>

<main>
  <section class="card">
    <div class="top">
      <div class="title">
        <strong>Compatibility Deck</strong>
        <span>Drag card → right to LIKE, left to PASS.\nWhen a match happens, chat unlocks instantly.</span>
      </div>
      <div class="pill">SYSTEM: <b id="sys">ONLINE</b></div>
    </div>

    <div class="body">
      <div class="deck">
        <div class="stack" id="stack"></div>
        <div class="actions">
          <button class="btnRound" id="btnPass" title="Pass">✕</button>
          <button class="btnRound" id="btnSuper" title="Super Like">✶</button>
          <button class="btnRound" id="btnLike" title="Like">♥</button>
        </div>
      </div>

      <aside class="side">
        <h3>Match Analyst</h3>
        <p id="analyst">Click LIKE to create mutual matches (demo).\nThen go to “Open Matches” to chat.</p>

        <button class="smallBtn" id="scan">Scan New Profiles</button>
        <button class="smallBtn ghost" id="goMatches">Go to Matches</button>
      </aside>
    </div>
  </section>

  <div class="toast" id="toast">Toast</div>
</main>

  <script src="/js/match.js"></script>

</body>
</html>
