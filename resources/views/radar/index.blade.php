<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>IAstroMatch • Match Radar</title>

  <link rel="stylesheet" href="/css/radar.css">
</head>
<body>
  <div class="wrap">
    <header class="topbar">
      <div>
        <h1>IAstroMatch</h1>
        <p class="sub">Dieselpunk Command Radar • Compatibility Scan</p>
      </div>
      <div class="status">
        <span class="dot"></span>
        SYSTEM ONLINE
      </div>
    </header>

    <main class="grid">
      <section class="panel">
        <h2>Operator Profile</h2>

        <form id="profileForm" class="form">
          <div class="row">
            <label>Name (optional)</label>
            <input name="name" type="text" maxlength="80" placeholder="Operator callsign...">
          </div>

          <div class="row">
            <label>Species</label>
            <div class="chips">
              <label><input type="radio" name="species" value="Human" checked> Human</label>
              <label><input type="radio" name="species" value="Automaton"> Automaton</label>
              <label><input type="radio" name="species" value="Xeno"> Xeno</label>
              <label><input type="radio" name="species" value="Hybrid"> Hybrid</label>
            </div>
          </div>

          <div class="row">
            <label>Atmosphere</label>
            <div class="chips">
              <label><input type="radio" name="atmosphere" value="O2" checked> O2</label>
              <label><input type="radio" name="atmosphere" value="Methane"> Methane</label>
              <label><input type="radio" name="atmosphere" value="Vacuum"> Vacuum</label>
            </div>
          </div>

          <div class="row">
            <label>Gravity</label>
            <div class="chips">
              <label><input type="radio" name="gravity" value="Low"> Low</label>
              <label><input type="radio" name="gravity" value="Standard" checked> Standard</label>
              <label><input type="radio" name="gravity" value="High"> High</label>
            </div>
          </div>

          <div class="row split">
            <div>
              <label>Temp Min (°C)</label>
              <input name="tempMin" type="number" value="-10">
            </div>
            <div>
              <label>Temp Max (°C)</label>
              <input name="tempMax" type="number" value="35">
            </div>
          </div>

          <div class="row">
            <label>Communication</label>
            <div class="chips">
              <label><input type="radio" name="comms" value="Radio" checked> Radio</label>
              <label><input type="radio" name="comms" value="Text"> Text</label>
              <label><input type="radio" name="comms" value="Light"> Light</label>
              <label><input type="radio" name="comms" value="Pheromones"> Pheromones</label>
              <label><input type="radio" name="comms" value="Telepathy"> Telepathy</label>
            </div>
          </div>

          <div class="row split">
            <div>
              <label>Intent</label>
              <select name="intent">
                <option>Romance</option>
                <option selected>Alliance</option>
                <option>Trade</option>
                <option>Conquest</option>
              </select>
            </div>

            <div>
              <label>Bio Type</label>
              <select name="bioType">
                <option selected>Organic</option>
                <option>Mechanical</option>
                <option>Hybrid</option>
              </select>
            </div>
          </div>

          <div class="row">
            <label>Risk tolerance: <span id="riskLabel">55</span></label>
            <input id="risk" name="risk" type="range" min="0" max="100" value="55">
            <div class="rangeHints">
              <span>Diplomatic</span><span>Chaotic</span>
            </div>
          </div>

          <div class="actions">
            <button type="button" id="btnScan">Scan Matches</button>
            <button type="button" class="ghost" id="btnLoadDemo">Load Demo</button>
            <button type="button" class="ghost" id="btnClear">Clear Log</button>
          </div>
        </form>
      </section>

      <section class="panel radarPanel">
        <div class="radarHeader">
          <h2>Match Radar</h2>
          <p class="tiny">Echoes represent candidate profiles ranked by the AI core.</p>
        </div>

        <div class="radarWrap">
          <canvas id="radarCanvas" width="520" height="520"></canvas>
        </div>

        <div class="log">
          <h3>AI Log</h3>
          <div id="iaLog" class="logBox"></div>
        </div>
      </section>

      <section class="panel">
        <h2>Matches</h2>
        <div id="matchList" class="matchList"></div>
      </section>
    </main>
  </div>

  <script src="/js/radar.js"></script>
</body>
</html>
