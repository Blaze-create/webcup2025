<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>IAstroMatch • Matches</title>

 <link rel="stylesheet" href="/css/matches.css">

</head>
<body>
<header>
  <div class="wrap">
    <div class="nav">
      <div class="brand">
        <h1>IAstroMatch</h1>
        <p>Matches • Secure channels unlocked</p>
      </div>
      <a class="link" href="match.html">← Back to Deck</a>
    </div>
  </div>
</header>

<main>
  <div class="grid">
    <section class="panel">
      <div class="top">
        <strong>Your Matches</strong>
        <span>Click “Open Chat” to enter a private channel.</span>
      </div>
      <div class="body" id="list"></div>
    </section>

    <aside class="panel">
      <div class="top">
        <strong>How it works</strong>
        <span>Two likes → match → chat unlocked.</span>
      </div>
      <div class="body">
        <div class="note" id="help">
MATCH TIP:
If you don’t see matches yet, go back to the deck and LIKE profiles.

Inside chat you’ll get:
• typing indicator
• online status
• icebreaker suggestions
        </div>
        <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
          <button class="btn ghost" id="clear">Clear Matches</button>
          <a class="btn" href="match.html" style="text-decoration:none; display:inline-flex; align-items:center;">Find More</a>
        </div>
      </div>
    </aside>
  </div>
</main>

<script src="/js/matches.js"></script>

</body>
</html>
