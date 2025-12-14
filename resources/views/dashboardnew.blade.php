<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>IAstroMatch • Operator Console</title>

<style>
/* ===============================
   GLOBAL THEME (Dieselpunk)
================================ */
:root {
  --bg-main: #0f0b06;
  --bg-panel: #1a140c;
  --border: rgba(255,170,70,.25);
  --text-main: #f3e8d0;
  --text-dim: rgba(243,232,208,.7);
  --accent: #fbbf24;
  --accent-soft: rgba(255,170,70,.2);
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  background: radial-gradient(circle at top, #1f170d, #0f0b06);
  color: var(--text-main);
  font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
  line-height: 1.5;
}

/* ===============================
   LAYOUT
================================ */
.wrap {
  max-width: 1200px;
  margin: auto;
  padding: 0 20px;
}

.topbar {
  background: linear-gradient(to bottom, #1a140c, #0f0b06);
  border-bottom: 1px solid var(--border);
  box-shadow: 0 0 30px rgba(255,170,70,.05);
}

.nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 18px 0;
}

.brand h1 {
  font-size: 20px;
  letter-spacing: .08em;
  color: var(--accent);
}

.brand p {
  font-size: 12px;
  color: var(--text-dim);
}

/* ===============================
   PANELS
================================ */
.grid {
  display: grid;
  grid-template-columns: 1.4fr .9fr;
  gap: 16px;
  padding: 24px 0;
}

.panel {
  background: linear-gradient(to bottom, #1a140c, #120e08);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 16px;
  box-shadow: inset 0 0 0 1px rgba(255,170,70,.05);
}

.panel h3 {
  font-size: 14px;
  letter-spacing: .12em;
  color: var(--accent);
  margin-bottom: 12px;
}

.panel p {
  font-size: 14px;
  color: var(--text-dim);
  margin-top: 6px;
}

/* ===============================
   BUTTONS
================================ */
.btn {
  display: inline-block;
  padding: 8px 14px;
  border-radius: 999px;
  font-size: 12px;
  letter-spacing: .08em;
  color: var(--accent);
  background: var(--accent-soft);
  border: 1px solid var(--border);
  text-decoration: none;
  cursor: pointer;
  transition: all .2s ease;
}

.btn:hover {
  background: rgba(255,170,70,.35);
}

/* ===============================
   LIST ITEMS
================================ */
.item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px dashed rgba(255,170,70,.15);
}

.item:last-child {
  border-bottom: none;
}

.small {
  font-size: 12px;
  color: var(--text-dim);
}

/* ===============================
   RESPONSIVE
================================ */
@media (max-width: 900px) {
  .grid {
    grid-template-columns: 1fr;
  }
}
</style>
</head>

<body>

<header class="topbar">
  <div class="wrap nav">
    <div class="brand">
      <h1>IASTROMATCH</h1>
      <p>Operator Console • Secure Channel</p>
    </div>

    <a class="btn" href="{{ route('home') }}">← Back</a>
  </div>
</header>

<main class="wrap">
  <div class="grid">

    {{-- LEFT COLUMN --}}
    <div>

      <div class="panel">
        <h3>ACCOUNT</h3>
        <p><b>Name:</b> {{ $user->name }}</p>
        <p><b>Email:</b> {{ $user->email }}</p>
        <p><b>Joined:</b> {{ $user->created_at->format('M d, Y') }}</p>
      </div>

      <div class="panel" style="margin-top:16px;">
        <h3>PROFILE</h3>

        @if($user->profile)
          <p><b>Operator:</b> {{ $user->profile->name }}</p>
          <p><b>Species:</b> {{ $user->profile->species }}</p>
          <p><b>Intent:</b> {{ $user->profile->intent }}</p>
          <p><b>Comms:</b> {{ $user->profile->comms }}</p>
          <p><b>Risk:</b> {{ $user->profile->risk }}/100</p>
          <p><b>Temp:</b> {{ $user->profile->tempMin }} → {{ $user->profile->tempMax }}</p>

          <div style="margin-top:12px;">
            <a class="btn" href="{{ route('radar.index') }}">Edit Profile</a>
          </div>
        @else
          <p>No profile detected.</p>
          <a class="btn" href="{{ route('radar.index') }}">Create Profile</a>
        @endif
      </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div>

      <div class="panel">
        <h3>PEOPLE YOU LIKED</h3>

        @forelse($likedUsers as $u)
          <div class="item">
            <div>
              <b>{{ $u->name }}</b><br>
              <span class="small">Operator #{{ $u->id }}</span>
            </div>
            <a class="btn" href="{{ url('/chat/'.$u->id) }}">Chat</a>
          </div>
        @empty
          <p>No likes yet.</p>
        @endforelse
      </div>

      <div class="panel" style="margin-top:16px;">
        <h3>MATCHES</h3>

        @forelse($matches as $m)
          <div class="item">
            <div>
              <b>{{ $m->name }}</b><br>
              <span class="small">Mutual link established</span>
            </div>
            <a class="btn" href="{{ url('/chat/'.$m->id) }}">Open Chat</a>
          </div>
        @empty
          <p>No matches yet.</p>
        @endforelse
      </div>

    </div>

  </div>
</main>

<script>
/* Minimal JS placeholder (future use) */
console.log("IAstroMatch Operator Dashboard loaded");
</script>

</body>
</html>
