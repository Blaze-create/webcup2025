<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>IAstroMatch • Registration</title>
  <style>
    :root {
      --brass-dark: #594622;
      --brass: #BF9A54;
      --brass-mid: #8C5D23;
      --rust: #402A10;
      --paper: #F2F2F2;

      --glass: rgba(242, 242, 242, .07);
      --glass2: rgba(242, 242, 242, .12);
      --line: rgba(191, 154, 84, .22);
      --shadow: rgba(0, 0, 0, .40);

      --ease: cubic-bezier(.2, .85, .2, 1);
      --mono: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;
      --sans: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
      --radius: 18px;
      --radius2: 14px;
    }

    * {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
    }

    body {
      margin: 0;
      font-family: var(--sans);
      color: var(--paper);
      background:
        radial-gradient(1200px 700px at 18% 10%, rgba(191, 154, 84, .22), transparent 55%),
        radial-gradient(900px 600px at 80% 20%, rgba(140, 93, 35, .18), transparent 55%),
        radial-gradient(1200px 900px at 50% 88%, rgba(89, 70, 34, .34), transparent 60%),
        linear-gradient(180deg, #1a1209 0%, var(--rust) 55%, #161007 100%);
      overflow: hidden;
    }

    /* Ambient overlays */
    .grain,
    .scanlines {
      position: fixed;
      inset: 0;
      pointer-events: none;
      z-index: 2;
      mix-blend-mode: overlay;
    }

    .grain {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='180' height='180' filter='url(%23n)' opacity='.22'/%3E%3C/svg%3E");
      opacity: .10;
      animation: grain 6s steps(2, end) infinite;
      transform: translateZ(0);
    }

    @keyframes grain {
      0% {
        transform: translate(0, 0)
      }

      20% {
        transform: translate(-2%, 3%)
      }

      40% {
        transform: translate(3%, -2%)
      }

      60% {
        transform: translate(-3%, 1%)
      }

      80% {
        transform: translate(2%, -3%)
      }

      100% {
        transform: translate(0, 0)
      }
    }

    .scanlines {
      background: repeating-linear-gradient(180deg, rgba(242, 242, 242, .03) 0px, rgba(242, 242, 242, .03) 1px, transparent 2px, transparent 6px);
      opacity: .35;
    }

    /* Top bar */
    header {
      position: sticky;
      top: 0;
      z-index: 10;
      backdrop-filter: blur(10px);
      background: linear-gradient(180deg, rgba(64, 42, 16, .78), rgba(64, 42, 16, .30));
      border-bottom: 1px solid rgba(191, 154, 84, .22);
    }

    .wrap {
      width: min(1040px, 92vw);
      margin-inline: auto;
    }

    .nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      padding: 14px 0;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      user-select: none;
    }

    .badge {
      width: 44px;
      height: 44px;
      border-radius: 16px;
      background:
        radial-gradient(circle at 35% 30%, rgba(242, 242, 242, .22), transparent 55%),
        linear-gradient(145deg, rgba(191, 154, 84, .85), rgba(140, 93, 35, .75));
      box-shadow: 0 14px 35px rgba(0, 0, 0, .35), inset 0 1px 0 rgba(242, 242, 242, .3);
      position: relative;
      overflow: hidden;
    }

    .badge::after {
      content: "";
      position: absolute;
      inset: -40%;
      background: conic-gradient(from 0deg, transparent 0 35%, rgba(242, 242, 242, .18) 45%, transparent 55% 100%);
      animation: sheen 2.8s var(--ease) infinite;
    }

    @keyframes sheen {
      to {
        transform: rotate(360deg);
      }
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
      color: rgba(242, 242, 242, .70);
    }

    .link {
      font-family: var(--mono);
      font-size: 12px;
      color: rgba(242, 242, 242, .80);
      text-decoration: none;
      border: 1px solid rgba(191, 154, 84, .20);
      background: rgba(242, 242, 242, .06);
      padding: 9px 10px;
      border-radius: 999px;
      transition: transform .18s var(--ease), background .18s var(--ease), border-color .18s var(--ease);
    }

    .link:hover {
      transform: translateY(-2px);
      background: rgba(242, 242, 242, .10);
      border-color: rgba(191, 154, 84, .35);
    }

    main {
      position: relative;
      z-index: 3;
      height: calc(100% - 73px);
      display: grid;
      place-items: center;
      padding: 18px 0;
    }

    .card {
      width: min(520px, 92vw);
      border-radius: var(--radius);
      border: 1px solid var(--line);
      background: linear-gradient(180deg, rgba(242, 242, 242, .08), rgba(242, 242, 242, .04));
      box-shadow: 0 24px 70px rgba(0, 0, 0, .40);
      overflow: hidden;
      transform-style: preserve-3d;
    }

    .cardTop {
      padding: 14px 14px 12px;
      border-bottom: 1px solid rgba(191, 154, 84, .16);
      background: linear-gradient(180deg, rgba(64, 42, 16, .55), rgba(64, 42, 16, .18));
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 10px;
    }

    .cardTop .title {
      display: flex;
      flex-direction: column;
      gap: 4px;
      transform: translateZ(18px);
    }

    .cardTop strong {
      font-size: 13px;
      letter-spacing: .8px;
      text-transform: uppercase;
    }

    .cardTop span {
      font-family: var(--mono);
      font-size: 11px;
      color: rgba(242, 242, 242, .68);
      line-height: 1.4;
    }

    .chip {
      font-family: var(--mono);
      font-size: 11px;
      padding: 7px 9px;
      border-radius: 999px;
      border: 1px solid rgba(191, 154, 84, .22);
      background: rgba(242, 242, 242, .06);
      color: rgba(242, 242, 242, .82);
      transform: translateZ(14px);
      user-select: none;
      white-space: nowrap;
    }

    form {
      padding: 14px;
      display: grid;
      gap: 12px;
      transform: translateZ(10px);
    }

    .row {
      display: grid;
      gap: 7px;
    }

    label {
      font-family: var(--mono);
      font-size: 11px;
      color: rgba(242, 242, 242, .78);
      letter-spacing: .6px;
      text-transform: uppercase;
    }

    .inputWrap {
      position: relative;
      border-radius: 14px;
      border: 1px solid rgba(191, 154, 84, .20);
      background: rgba(0, 0, 0, .18);
      overflow: hidden;
      transition: border-color .18s var(--ease), transform .18s var(--ease);
    }

    .inputWrap:focus-within {
      border-color: rgba(191, 154, 84, .40);
      transform: translateY(-1px);
    }

    input {
      width: 100%;
      padding: 12px 12px;
      background: transparent;
      border: 0;
      outline: none;
      color: rgba(242, 242, 242, .92);
      font-size: 14px;
    }

    input::placeholder {
      color: rgba(242, 242, 242, .45);
    }

    .toggle {
      position: absolute;
      right: 8px;
      top: 50%;
      transform: translateY(-50%);
      border: 1px solid rgba(191, 154, 84, .22);
      background: rgba(242, 242, 242, .06);
      color: rgba(242, 242, 242, .85);
      border-radius: 12px;
      padding: 7px 10px;
      cursor: pointer;
      font-family: var(--mono);
      font-size: 11px;
      user-select: none;
      transition: transform .18s var(--ease), background .18s var(--ease), border-color .18s var(--ease);
    }

    .toggle:hover {
      transform: translateY(-50%) translateY(-2px);
      background: rgba(242, 242, 242, .10);
      border-color: rgba(191, 154, 84, .35);
    }

    .hint {
      font-family: var(--mono);
      font-size: 11px;
      color: rgba(242, 242, 242, .68);
      line-height: 1.4;
      min-height: 16px;
    }

    .hint.good {
      color: rgba(242, 242, 242, .82);
    }

    .hint.bad {
      color: rgba(242, 242, 242, .82);
    }

    .meter {
      height: 8px;
      border-radius: 999px;
      background: rgba(242, 242, 242, .08);
      overflow: hidden;
      border: 1px solid rgba(191, 154, 84, .12);
    }

    .meter>i {
      display: block;
      height: 100%;
      width: 0%;
      background: linear-gradient(90deg, rgba(191, 154, 84, .50), rgba(242, 242, 242, .20));
      transition: width .2s var(--ease);
    }

    .actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 6px;
    }

    .btn {
      border: 0;
      border-radius: 14px;
      padding: 11px 12px;
      font-weight: 900;
      letter-spacing: .35px;
      cursor: pointer;
      color: #1a1108;
      background: linear-gradient(145deg, var(--brass), #d8b87b);
      box-shadow: 0 14px 30px rgba(0, 0, 0, .35), inset 0 1px 0 rgba(242, 242, 242, .35);
      transition: transform .2s var(--ease), filter .2s var(--ease);
      user-select: none;
      flex: 1;
    }

    .btn:hover {
      transform: translateY(-2px);
      filter: saturate(1.05);
    }

    .btn:active {
      transform: translateY(0) scale(.98);
    }

    .btn.ghost {
      background: rgba(242, 242, 242, .06);
      color: rgba(242, 242, 242, .92);
      border: 1px solid rgba(191, 154, 84, .26);
      box-shadow: none;
      font-weight: 800;
      flex: 0 0 auto;
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

    /* 3D tilt target */
    [data-tilt] {
      transform-style: preserve-3d;
      will-change: transform;
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
      * {
        animation: none !important;
        transition: none !important;
      }

      .grain,
      .scanlines {
        display: none;
      }
    }
  </style>
</head>

<body>
  <div class="grain"></div>
  <div class="scanlines"></div>

  <header>
    <div class="wrap">
      <div class="nav">
        <div class="brand">
          <div class="badge" aria-hidden="true"></div>
          <div>
            <h1><a href="{{ route('home') }}"><img src="{{ asset('img/logo.png') }}" width="200" alt=""></a></h1>
            <p>Login • Station Access Request</p>
          </div>
        </div>
        <a class="link" href="{{route('home')}}">← Back to Home</a>
      </div>
    </div>
  </header>

  <main>
    <section class="card" data-tilt id="card">
      <div class="cardTop">
        <div class="title">
          <strong>Login into your Account</strong>
          <span>Secure credentials for the matchmaking console.<br />Interstellar Compatibility console.</span>
        </div>
        <div class="chip" id="chipStatus">READY</div>
      </div>

      <form id="form" method="post" action="{{ route('login') }}">
        @csrf


        <div class="row">
          <label for="email">Email</label>
          <div class="inputWrap">
            <input id="email" name="email" type="email" placeholder="operator@station.net" autocomplete="email" required />
          </div>
          <div class="hint" id="emailHint"></div>
        </div>

        <div class="row">
          <label for="password">Password</label>
          <div class="inputWrap">
            <input id="password" name="password" type="password" placeholder="enter your password" autocomplete="new-password" required />
            <button class="toggle" type="button" id="togglePass">SHOW</button>
          </div>
          <div class="meter" aria-hidden="true"><i id="pwBar"></i></div>
          <div class="hint" id="pwHint"></div>
        </div>

        <div class="actions">
          <button class="btn" id="btnCreate" type="submit">Login</button>
          <a class="btn ghost"href="{{ route('register') }}">dont have an account</a>
        </div>

        <div class="hint" id="finalHint"></div>
      </form>
    </section>

    <div class="toast" id="toast">Toast</div>
  </main>
</body>

</html>