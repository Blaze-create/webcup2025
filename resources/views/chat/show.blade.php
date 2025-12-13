<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IAstroMatch // Encrypted Channel</title>
  <link rel="stylesheet" href="{{ asset('css/dieselpunk.css') }}">
</head>

<body class="dp-body">
  <header class="dp-topbar">
    <div class="dp-brand">IASTROMATCH</div>
    <div class="dp-sub">ENCRYPTED CHANNEL // TARGET: {{ $candidateId }}</div>
    <a class="dp-btn dp-btn-secondary" href="{{ route('radar.index') }}">BACK TO RADAR</a>
  </header>

  <main class="dp-grid" style="grid-template-columns: 1fr;">
    <section class="dp-panel">
      <h2 class="dp-h2">TARGET DOSSIER</h2>
      <pre id="targetCard" class="dp-pre">Loading target...</pre>

      <h2 class="dp-h2" style="margin-top:14px;">TRANSMISSION</h2>
      <div class="dp-row">
        <select id="protocol" class="dp-select">
          <option value="RADIO-7B">RADIO-7B</option>
          <option value="DIPLOMATIC-PACKET">DIPLOMATIC-PACKET</option>
          <option value="TRADE-OFFER">TRADE-OFFER</option>
          <option value="CEASEFIRE-SIGNAL">CEASEFIRE-SIGNAL</option>
        </select>

        <button id="btnSuggest" class="dp-btn dp-btn-secondary">IA SUGGEST</button>
      </div>

      <div class="dp-chat">
        <div id="chatLog" class="dp-chatLog"></div>

        <div class="dp-row">
          <input id="msg" class="dp-input" placeholder="Type encrypted message..." />
          <button id="btnSend" class="dp-btn">TRANSMIT</button>
        </div>
      </div>

      <div id="chatHint" class="dp-hint"></div>
    </section>
  </main>

  <script>
    window.__CANDIDATE_ID__ = @json($candidateId);
  </script>

<script src="{{ asset('js/show.js') }}"></script> 

</body>
</html>
