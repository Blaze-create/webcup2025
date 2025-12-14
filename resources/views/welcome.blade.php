<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IAstroMatch • Sector Landing</title>
    <style>
        :root {
            --brass-dark: #594622;
            --brass: #BF9A54;
            --brass-mid: #8C5D23;
            --rust: #402A10;
            --paper: #F2F2F2;

            --shadow: rgba(0, 0, 0, .35);
            --glass: rgba(242, 242, 242, .08);
            --glass2: rgba(242, 242, 242, .14);

            --radius: 22px;
            --radius2: 16px;

            --ease: cubic-bezier(.2, .8, .2, 1);
            --mono: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;
            --sans: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
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
                radial-gradient(1200px 700px at 20% 10%, rgba(191, 154, 84, .25), transparent 55%),
                radial-gradient(900px 600px at 80% 20%, rgba(140, 93, 35, .22), transparent 55%),
                radial-gradient(1200px 900px at 50% 80%, rgba(89, 70, 34, .35), transparent 60%),
                linear-gradient(180deg, #1b1208 0%, var(--rust) 45%, #1a1108 100%);
            overflow-x: hidden;
        }

        /* ---- Ambient film grain + scanlines ---- */
        .grain,
        .scanlines {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 2;
            mix-blend-mode: overlay;
        }

        .grain {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='180' height='180' filter='url(%23n)' opacity='.25'/%3E%3C/svg%3E");
            opacity: .10;
            transform: translateZ(0);
            animation: grain 6s steps(2, end) infinite;
        }

        @keyframes grain {
            0% {
                transform: translate(0, 0)
            }

            15% {
                transform: translate(-2%, 3%)
            }

            30% {
                transform: translate(4%, -2%)
            }

            45% {
                transform: translate(-3%, 1%)
            }

            60% {
                transform: translate(2%, -3%)
            }

            75% {
                transform: translate(-1%, -2%)
            }

            100% {
                transform: translate(0, 0)
            }
        }

        .scanlines {
            background:
                repeating-linear-gradient(180deg,
                    rgba(242, 242, 242, .03) 0px,
                    rgba(242, 242, 242, .03) 1px,
                    transparent 2px,
                    transparent 6px);
            opacity: .35;
        }

        /* ---- Top nav ---- */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            backdrop-filter: blur(10px);
            background: linear-gradient(180deg, rgba(64, 42, 16, .75), rgba(64, 42, 16, .35));
            border-bottom: 1px solid rgba(191, 154, 84, .22);
        }

        .wrap {
            width: min(1160px, 92vw);
            margin-inline: auto;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: .5px;
            user-select: none;
        }

        .badge {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            position: relative;
            background:
                radial-gradient(circle at 35% 30%, rgba(242, 242, 242, .22), transparent 55%),
                linear-gradient(145deg, rgba(191, 154, 84, .85), rgba(140, 93, 35, .75));
            box-shadow: 0 14px 35px rgba(0, 0, 0, .35), inset 0 1px 0 rgba(242, 242, 242, .3);
            overflow: hidden;
        }

        .badge::after {
            content: "";
            position: absolute;
            inset: -40%;
            background: conic-gradient(from 0deg, transparent 0 35%, rgba(242, 242, 242, .18) 45%, transparent 55% 100%);
            animation: sheen 2.6s var(--ease) infinite;
        }

        @keyframes sheen {
            0% {
                transform: rotate(0deg)
            }

            100% {
                transform: rotate(360deg)
            }
        }

        .brand h1 {
            font-size: 14px;
            margin: 0;
            text-transform: uppercase;
            color: var(--paper);
        }

        .brand span {
            display: block;
            font-family: var(--mono);
            font-size: 12px;
            color: rgba(242, 242, 242, .75);
        }

        .navlinks {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .chip {
            border: 1px solid rgba(191, 154, 84, .22);
            background: rgba(242, 242, 242, .06);
            color: rgba(242, 242, 242, .9);
            padding: 8px 10px;
            border-radius: 999px;
            font-size: 12px;
            text-decoration: none;
            transition: transform .2s var(--ease), background .2s var(--ease), border-color .2s var(--ease);
        }

        .chip:hover {
            transform: translateY(-2px);
            background: rgba(242, 242, 242, .10);
            border-color: rgba(191, 154, 84, .35);
        }

        .cta {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            border: 0;
            border-radius: 14px;
            padding: 11px 14px;
            font-weight: 700;
            letter-spacing: .35px;
            cursor: pointer;
            color: #1a1108;
            background: linear-gradient(145deg, var(--brass), #d8b87b);
            box-shadow: 0 14px 30px rgba(0, 0, 0, .35), inset 0 1px 0 rgba(242, 242, 242, .35);
            transition: transform .22s var(--ease), box-shadow .22s var(--ease), filter .22s var(--ease);
            will-change: transform;
        }

        .btn:hover {
            transform: translateY(-2px);
            filter: saturate(1.05);
        }

        .btn:active {
            transform: translateY(0px) scale(.98);
        }

        .btn-ghost {
            background: rgba(242, 242, 242, .06);
            color: rgba(242, 242, 242, .92);
            border: 1px solid rgba(191, 154, 84, .26);
            box-shadow: none;
        }

        /* ---- Hero section with 3D stage ---- */
        .hero {
            position: relative;
            padding: 56px 0 26px;
            overflow: hidden;
        }

        .stage {
            position: relative;
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 26px;
            align-items: stretch;
            perspective: 1200px;
        }

        @media (max-width: 960px) {
            .stage {
                grid-template-columns: 1fr;
            }
        }

        .heroCopy {
            position: relative;
            border-radius: var(--radius);
            padding: 26px 22px;
            background: linear-gradient(180deg, rgba(242, 242, 242, .08), rgba(242, 242, 242, .05));
            border: 1px solid rgba(191, 154, 84, .24);
            box-shadow: 0 24px 60px rgba(0, 0, 0, .35);
            transform-style: preserve-3d;
        }

        .kicker {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: var(--mono);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: rgba(242, 242, 242, .78);
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(242, 242, 242, .9), rgba(191, 154, 84, .65) 55%, rgba(140, 93, 35, .4));
            box-shadow: 0 0 0 6px rgba(191, 154, 84, .12);
            animation: pulse 1.6s var(--ease) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 6px rgba(191, 154, 84, .12);
            }

            50% {
                transform: scale(1.08);
                box-shadow: 0 0 0 10px rgba(191, 154, 84, .08);
            }
        }

        .headline {
            margin: 14px 0 10px;
            font-size: clamp(32px, 4.2vw, 56px);
            line-height: 1.03;
            letter-spacing: .2px;
            text-transform: uppercase;
        }

        .headline em {
            font-style: normal;
            color: var(--brass);
            text-shadow: 0 10px 26px rgba(191, 154, 84, .15);
        }

        .sub {
            margin: 0 0 18px;
            max-width: 58ch;
            color: rgba(242, 242, 242, .80);
            font-size: 14.5px;
            line-height: 1.6;
        }

        .heroActions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 14px;
            align-items: center;
        }

        .statRow {
            margin-top: 18px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        @media (max-width: 560px) {
            .statRow {
                grid-template-columns: 1fr;
            }
        }

        .stat {
            border-radius: var(--radius2);
            padding: 12px 12px;
            background: rgba(242, 242, 242, .06);
            border: 1px solid rgba(191, 154, 84, .20);
            transform: translateZ(30px);
        }

        .stat b {
            display: block;
            font-family: var(--mono);
            font-size: 12px;
            color: rgba(242, 242, 242, .72);
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        .stat strong {
            display: block;
            font-size: 18px;
            margin-top: 6px;
            color: var(--paper);
        }

        .stat strong span {
            color: rgba(242, 242, 242, .68);
            font-weight: 600;
            font-size: 12px;
            margin-left: 6px;
            font-family: var(--mono);
        }

        /* ---- Right side: 3D "console" with canvas radar ---- */
        .console {
            position: relative;
            border-radius: var(--radius);
            background: linear-gradient(180deg, rgba(242, 242, 242, .07), rgba(242, 242, 242, .03));
            border: 1px solid rgba(191, 154, 84, .24);
            box-shadow: 0 24px 60px rgba(0, 0, 0, .35);
            overflow: hidden;
            transform-style: preserve-3d;
            min-height: 420px;
        }

        .consoleTop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 14px 10px;
            border-bottom: 1px solid rgba(191, 154, 84, .18);
            background: linear-gradient(180deg, rgba(64, 42, 16, .55), rgba(64, 42, 16, .2));
        }

        .consoleTitle {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .consoleTitle strong {
            font-size: 13px;
            letter-spacing: .6px;
            text-transform: uppercase;
        }

        .consoleTitle span {
            font-family: var(--mono);
            font-size: 11px;
            color: rgba(242, 242, 242, .7);
        }

        .pills {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .pill {
            font-family: var(--mono);
            font-size: 11px;
            padding: 7px 9px;
            border-radius: 999px;
            border: 1px solid rgba(191, 154, 84, .22);
            background: rgba(242, 242, 242, .06);
            color: rgba(242, 242, 242, .8);
            user-select: none;
        }

        .radarWrap {
            position: relative;
            height: 320px;
            padding: 14px;
        }

        canvas#radar {
            width: 100%;
            height: 100%;
            border-radius: var(--radius2);
            background:
                radial-gradient(circle at 50% 50%, rgba(191, 154, 84, .08), transparent 60%),
                radial-gradient(circle at 50% 50%, rgba(242, 242, 242, .06), transparent 65%),
                linear-gradient(180deg, rgba(0, 0, 0, .25), rgba(0, 0, 0, .10));
            border: 1px solid rgba(191, 154, 84, .18);
            box-shadow: inset 0 0 0 1px rgba(242, 242, 242, .05);
            transform: translateZ(45px);
            display: block;
        }

        .consoleBottom {
            padding: 12px 14px 16px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            transform: translateZ(20px);
        }

        @media (max-width: 960px) {
            .consoleBottom {
                grid-template-columns: 1fr;
            }
        }

        .miniCard {
            border-radius: var(--radius2);
            padding: 12px 12px;
            background: rgba(242, 242, 242, .06);
            border: 1px solid rgba(191, 154, 84, .18);
        }

        .miniCard h3 {
            margin: 0 0 6px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: rgba(242, 242, 242, .85);
        }

        .miniCard p {
            margin: 0;
            font-size: 12.5px;
            line-height: 1.5;
            color: rgba(242, 242, 242, .72);
            font-family: var(--mono);
        }

        /* ---- Floating 3D ornaments ---- */
        .orb {
            position: absolute;
            inset: auto;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            filter: blur(1px);
            background:
                radial-gradient(circle at 35% 30%, rgba(242, 242, 242, .16), transparent 55%),
                radial-gradient(circle at 55% 60%, rgba(191, 154, 84, .18), transparent 60%),
                conic-gradient(from 210deg, rgba(191, 154, 84, .0), rgba(191, 154, 84, .15), rgba(140, 93, 35, .0));
            opacity: .85;
            transform: translate3d(0, 0, 0);
            animation: floaty 8s var(--ease) infinite;
            pointer-events: none;
            z-index: 0;
        }

        .orb.one {
            right: -180px;
            top: 40px;
            animation-duration: 9.5s;
        }

        .orb.two {
            left: -220px;
            top: 220px;
            width: 520px;
            height: 520px;
            opacity: .55;
            animation-duration: 11.5s;
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translate3d(0, 0, 0) rotate(0deg)
            }

            50% {
                transform: translate3d(0, -14px, 0) rotate(6deg)
            }
        }

        /* ---- Gear (SVG) ---- */
        .gear {
            position: absolute;
            right: 22px;
            bottom: 18px;
            width: 120px;
            height: 120px;
            opacity: .42;
            transform: translateZ(60px);
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, .25));
            pointer-events: none;
            animation: spin 10s linear infinite;
        }

        @keyframes spin {
            to {
                transform: translateZ(60px) rotate(360deg);
            }
        }

        /* ---- Sections below ---- */
        .section {
            padding: 18px 0 70px;
            position: relative;
            z-index: 1;
        }

        .grid3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            perspective: 1200px;
        }

        @media (max-width: 980px) {
            .grid3 {
                grid-template-columns: 1fr;
            }
        }

        .card3d {
            border-radius: var(--radius);
            padding: 16px 16px;
            background: linear-gradient(180deg, rgba(242, 242, 242, .07), rgba(242, 242, 242, .04));
            border: 1px solid rgba(191, 154, 84, .20);
            box-shadow: 0 22px 50px rgba(0, 0, 0, .30);
            transform-style: preserve-3d;
            position: relative;
            overflow: hidden;
            min-height: 160px;
        }

        .card3d::before {
            content: "";
            position: absolute;
            inset: -40%;
            background: radial-gradient(circle at var(--mx, 50%) var(--my, 40%), rgba(242, 242, 242, .18), transparent 55%);
            opacity: .7;
            transform: translateZ(40px);
        }

        .card3d h4 {
            margin: 0 0 8px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .card3d p {
            margin: 0;
            color: rgba(242, 242, 242, .78);
            line-height: 1.55;
            font-size: 13px;
        }

        .tag {
            display: inline-flex;
            font-family: var(--mono);
            font-size: 11px;
            margin-top: 12px;
            padding: 6px 8px;
            border-radius: 999px;
            border: 1px solid rgba(191, 154, 84, .25);
            background: rgba(242, 242, 242, .06);
            color: rgba(242, 242, 242, .78);
        }

        /* ---- Footer ---- */
        footer {
            border-top: 1px solid rgba(191, 154, 84, .18);
            padding: 24px 0 34px;
            color: rgba(242, 242, 242, .65);
            font-family: var(--mono);
            font-size: 12px;
            position: relative;
            z-index: 1;
        }

        /* ---- Reveal animation ---- */
        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity .7s var(--ease), transform .7s var(--ease);
        }

        .reveal.on {
            opacity: 1;
            transform: translateY(0);
        }

        /* ---- Reduced motion ---- */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
                scroll-behavior: auto !important;
            }

            .grain,
            .scanlines {
                display: none;
            }
        }

        .custom-loader {
            width: 50px;
            height: 50px;
            display: grid;
            color: #766DF4;
            background: radial-gradient(farthest-side, currentColor calc(100% - 6px), #0000 calc(100% - 5px) 0);
            -webkit-mask: radial-gradient(farthest-side, #0000 calc(100% - 13px), #000 calc(100% - 12px));
            border-radius: 50%;
            animation: s9 2s infinite linear;
        }

        .custom-loader::before,
        .custom-loader::after {
            content: "";
            grid-area: 1/1;
            background:
                linear-gradient(currentColor 0 0) center,
                linear-gradient(currentColor 0 0) center;
            background-size: 100% 10px, 10px 100%;
            background-repeat: no-repeat;
        }

        .custom-loader::after {
            transform: rotate(45deg);
        }

        @keyframes s9 {
            100% {
                transform: rotate(1turn)
            }
        }

        #spinner-container {
            position: fixed;
            inset: 0;
            /* top:0 right:0 bottom:0 left:0 */
            width: 100vw;
            height: 100vh;

            display: flex;
            align-items: center;
            /* vertical center */
            justify-content: center;
            /* horizontal center */

            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
        }

        .spinner-container {
            position: relative;
            width: 200px;
            height: 200px;
        }

        /* Outer gear ring */
        .gear-outer {
            position: absolute;
            width: 180px;
            height: 180px;
            top: 10px;
            left: 10px;
            border: 4px solid #BF9A54;
            border-radius: 50%;
            animation: rotate 3s linear infinite;
        }

        /* Gear teeth effect */
        .gear-outer::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow:
                0 -92px 0 -88px #BF9A54,
                64px -64px 0 -88px #BF9A54,
                92px 0 0 -88px #BF9A54,
                64px 64px 0 -88px #BF9A54,
                0 92px 0 -88px #BF9A54,
                -64px 64px 0 -88px #BF9A54,
                -92px 0 0 -88px #BF9A54,
                -64px -64px 0 -88px #BF9A54;
        }

        /* Middle ring - counter rotating */
        .gear-middle {
            position: absolute;
            width: 120px;
            height: 120px;
            top: 40px;
            left: 40px;
            border: 3px solid #8C5D23;
            border-radius: 50%;
            animation: rotate-reverse 2s linear infinite;
        }

        .gear-middle::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow:
                0 -62px 0 -59px #8C5D23,
                44px -44px 0 -59px #8C5D23,
                62px 0 0 -59px #8C5D23,
                44px 44px 0 -59px #8C5D23,
                0 62px 0 -59px #8C5D23,
                -44px 44px 0 -59px #8C5D23,
                -62px 0 0 -59px #8C5D23,
                -44px -44px 0 -59px #8C5D23;
        }

        /* Heart center */
        .heart-center {
            position: absolute;
            width: 40px;
            height: 40px;
            top: 80px;
            left: 80px;
            animation: pulse 1.5s ease-in-out infinite;
        }

        .heart-center::before,
        .heart-center::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 32px;
            background: #BF9A54;
            border-radius: 20px 20px 0 0;
        }

        .heart-center::before {
            left: 10px;
            transform: rotate(-45deg);
            transform-origin: 0 100%;
        }

        .heart-center::after {
            left: 10px;
            transform: rotate(45deg);
            transform-origin: 100% 100%;
        }

        /* Rivets */
        .rivet {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #594622;
            border-radius: 50%;
            border: 1px solid #BF9A54;
        }

        .rivet:nth-child(1) {
            top: 5px;
            left: 96px;
        }

        .rivet:nth-child(2) {
            top: 96px;
            right: 5px;
        }

        .rivet:nth-child(3) {
            bottom: 5px;
            left: 96px;
        }

        .rivet:nth-child(4) {
            top: 96px;
            left: 5px;
        }

        /* Loading text */
        .loading-text {
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            color: #F2F2F2;
            font-size: 14px;
            letter-spacing: 3px;
            text-transform: uppercase;
            opacity: 0.8;
        }

        .loading-text::after {
            content: '...';
            animation: dots 1.5s steps(3, end) infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes rotate-reverse {
            from {
                transform: rotate(360deg);
            }

            to {
                transform: rotate(0deg);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        @keyframes dots {

            0%,
            20% {
                content: '.';
            }

            40% {
                content: '..';
            }

            60%,
            100% {
                content: '...';
            }
        }
    </style>
</head>

<body>
    <div id="spinner-container">
        <!-- <div class="custom-loader"></div> -->

        <div class="spinner-container">
            <div class="gear-outer"></div>
            <div class="gear-middle"></div>
            <div class="heart-center"></div>
            <div class="rivet"></div>
            <div class="rivet"></div>
            <div class="rivet"></div>
            <div class="rivet"></div>
            <div class="loading-text">Connecting</div>
        </div>
    </div>

<script>
    const spinner = document.getElementById('spinner-container');

function showSpinner() {
  spinner.style.display = 'flex';
}

function hideSpinner() {
  spinner.style.display = 'none';
}

// Example: hide when page loads
window.addEventListener('load', hideSpinner);
</script>
    <div class="grain"></div>
    <div class="scanlines"></div>

    <div class="topbar">
        <div class="wrap">
            <div class="nav">
                <div class="brand">
                    <div class="badge" aria-hidden="true"></div>
                    <div>
                        <h1><img src="{{ asset('img/logo.png') }}" width="200" alt=""></h1>
                        <span>Interstellar Compatibility Console • Sector 7</span>
                    </div>
                </div>

                <div class="navlinks">
                    <a class="chip" href="#protocol">Protocol</a>
                    <a class="chip" href="#systems">Systems</a>
                    <a class="chip" href="#contact">First Contact</a>
                </div>

                <div class="cta">
                    @auth
                    <a class="btn btn-ghost" id="btnDemo" href="{{ route('match.page') }}">Find Match</a>
                    <a class="btn btn-ghost" id="btnDemo" href="{{ route('dashboard') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button class="btn btn-ghost" href={{ route('logout') }}>Log Out</button>
                    </form>
                    @endauth

                    @guest
                    <a class="btn btn-ghost" id="btnDemo" href="{{ route('login') }}">Login</a>
                    <a class="btn" id="btnLaunch" href="{{ route('register') }}">register</a>
                    @endguest

                </div>
            </div>
        </div>
    </div>

    <main>
        <section class="hero">
            <div class="orb one" aria-hidden="true"></div>
            <div class="orb two" aria-hidden="true"></div>

            <div class="wrap">
                <div class="stage">
                    <div class="heroCopy tilt reveal" data-tilt>
                        <div class="kicker">
                            <div class="dot"></div>
                            <span>Alliance Network • Diplomatic matchmaking</span>
                        </div>

                        <h2 class="headline">
                            Peace, powered by <em>compatibility</em>.
                        </h2>

                        <p class="sub">
                            IAstroMatch connects humans, automatons, xeno-traders and anomalies through a
                            command-grade compatibility engine—capable of delivering perfect matches…
                            or catastrophic diplomatic incidents.
                        </p>

                        <div class="heroActions">
                            <a class="btn" id="btnPrimary" href="{{ route('radar.index') }}">Start Compatibility Scan</a>
                            <button class="btn btn-ghost" id="btnSecondary">View Protocol Brief</button>
                        </div>

                        <div class="statRow">
                            <div class="stat">
                                <b>Signal</b>
                                <strong><span id="statSignal">LIVE</span> 447.3MHz</strong>
                            </div>
                            <div class="stat">
                                <b>Risk Index</b>
                                <strong><span id="statRisk">52</span> / 100</strong>
                            </div>
                            <div class="stat">
                                <b>Suggested Matches</b>
                                <strong><span id="statMatches">03</span> candidates</strong>
                            </div>
                        </div>
                    </div>

                    <aside class="console tilt reveal" data-tilt>
                        <div class="consoleTop">
                            <div class="consoleTitle">
                                <strong>Compatibility Radar</strong>
                                <span>Targeting: orbitals • anomalies • friendly signatures</span>
                            </div>
                            <div class="pills">
                                <div class="pill" id="pillMode">MODE: DIPLOMACY</div>
                                <div class="pill" id="pillLock">LOCK: SOFT</div>
                            </div>
                        </div>

                        <div class="radarWrap">
                            <canvas id="radar"></canvas>
                        </div>

                        <div class="consoleBottom">
                            <div class="miniCard" id="logCard">
                                <h3>AI Log</h3>
                                <p id="aiLog">SYSTEM ONLINE ▸ Awaiting operator scan…</p>
                            </div>
                            <div class="miniCard">
                                <h3>Output</h3>
                                <p id="aiOut">No matches computed. Run a scan to generate candidates.</p>
                            </div>
                        </div>

                        <!-- Decorative gear -->
                        <svg class="gear" viewBox="0 0 100 100" aria-hidden="true">
                            <defs>
                                <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0" stop-color="#BF9A54" stop-opacity=".9" />
                                    <stop offset="1" stop-color="#8C5D23" stop-opacity=".85" />
                                </linearGradient>
                            </defs>
                            <path fill="url(#g)" d="M58.7 7.7l4 7.9a35.7 35.7 0 0 1 8.6 3.6l8.3-3.2 7.1 12.3-6.8 5.6c.9 2.9 1.4 5.9 1.5 9l8.1 3.5-3.9 14-8.8-1.3a35.7 35.7 0 0 1-5.6 7.2l4 8.1-12.3 7.1-6-6.8a35.7 35.7 0 0 1-8.8 1.5l-3.4 8.1-14-3.9 1.4-8.8a35.7 35.7 0 0 1-7.2-5.6l-8.1 4-7.1-12.3 6.8-6a35.7 35.7 0 0 1-1.5-8.8l-8.1-3.4 3.9-14 8.8 1.4a35.7 35.7 0 0 1 5.6-7.2l-4-8.1 12.3-7.1 6 6.8a35.7 35.7 0 0 1 8.8-1.5l3.4-8.1 14 3.9zM50 33a17 17 0 1 0 0 34 17 17 0 0 0 0-34z" />
                        </svg>
                    </aside>
                </div>
            </div>
        </section>

        <section class="section" id="systems">
            <div class="wrap">
                <div class="grid3">
                    <div class="card3d tilt reveal" data-tilt>
                        <h4>Species-Aware Profiles</h4>
                        <p>
                            Atmosphere, gravity tolerance, thermal band, communication protocol and intent are
                            parsed into a compatibility signature—built to avoid “oops” level extinctions.
                        </p>
                        <span class="tag">MODULE: BIOSPHERE / MACHINE</span>
                    </div>
                    <div class="card3d tilt reveal" data-tilt>
                        <h4>Diplomatic AI Narration</h4>
                        <p>
                            The system generates a command-style brief: safe, risky, improbable or catastrophic.
                            Expect warnings, humor, and occasionally… classified red stamps.
                        </p>
                        <span class="tag">MODULE: STRATEGIC COMMENTARY</span>
                    </div>
                    <div class="card3d tilt reveal" data-tilt>
                        <h4>First Contact Channel</h4>
                        <p>
                            Open a message with the recommended protocol: radio, text, light pulses, pheromones,
                            or telepathy—depending on what won’t start a war.
                        </p>
                        <span class="tag">MODULE: COMMS / ETIQUETTE</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="protocol">
            <div class="wrap">
                <div class="card3d tilt reveal" data-tilt style="min-height:auto;">
                    <h4>Protocol Brief</h4>
                    <p style="max-width:85ch;">
                        Operator guidance: keep scans under 12 seconds, avoid direct telepathic pings on unknown
                        hybrids, and never accept a “Conquest (funny)” intent without a signed treaty.
                    </p>
                    <span class="tag">DIRECTIVE: AVOID INCIDENTS</span>
                </div>
            </div>
        </section>

        <section class="section" id="contact">
            <div class="wrap">
                <div class="card3d tilt reveal" data-tilt style="min-height:auto;">
                    <h4>Initiate First Contact</h4>
                    <p style="max-width:85ch;">
                        Enter the station to create your profile, run the AI scan, and receive a match list with
                        “safe / risky / catastrophic” classifications (and the exact protocol to say hello).
                    </p>
                    <div style="margin-top:12px; display:flex; gap:12px; flex-wrap:wrap;">
                        <button class="btn" id="btnEnter2">Enter Station</button>
                        <button class="btn btn-ghost" id="btnToggleMode">Toggle Mode</button>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <div class="wrap">
                <div>IAstroMatch • Alliance Interstellar Desk • “Build peace. Or chaos. Preferably peace.”</div>
            </div>
        </footer>
    </main>

    <script>
        // ---------------------------
        // 3D Tilt helper (no libs)
        // ---------------------------
        const tiltEls = [...document.querySelectorAll("[data-tilt]")];
        const clamp = (n, a, b) => Math.min(b, Math.max(a, n));

        function tiltMove(e, el) {
            const r = el.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width;
            const y = (e.clientY - r.top) / r.height;
            const rx = (0.5 - y) * 10;
            const ry = (x - 0.5) * 12;

            el.style.transform = `rotateX(${rx}deg) rotateY(${ry}deg) translateY(-2px)`;
            el.style.setProperty("--mx", (x * 100).toFixed(2) + "%");
            el.style.setProperty("--my", (y * 100).toFixed(2) + "%");
        }

        function tiltLeave(el) {
            el.style.transform = "";
            el.style.setProperty("--mx", "50%");
            el.style.setProperty("--my", "40%");
        }
        tiltEls.forEach(el => {
            el.addEventListener("mousemove", (e) => tiltMove(e, el));
            el.addEventListener("mouseleave", () => tiltLeave(el));
        });

        // ---------------------------
        // Reveal on scroll
        // ---------------------------
        const reveals = [...document.querySelectorAll(".reveal")];
        const io = new IntersectionObserver((entries) => {
            entries.forEach(en => {
                if (en.isIntersecting) en.target.classList.add("on");
            });
        }, {
            threshold: 0.15
        });
        reveals.forEach(el => io.observe(el));

        // ---------------------------
        // Radar Canvas (animated sweep + blips)
        // ---------------------------
        const canvas = document.getElementById("radar");
        const ctx = canvas.getContext("2d");

        function resizeCanvas() {
            const dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1));
            const rect = canvas.getBoundingClientRect();
            canvas.width = Math.floor(rect.width * dpr);
            canvas.height = Math.floor(rect.height * dpr);
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        let t = 0;
        let sweep = 0;
        let mode = "DIPLOMACY";

        const blips = Array.from({
            length: 9
        }, (_, i) => ({
            a: Math.random() * Math.PI * 2,
            r: 0.18 + Math.random() * 0.72,
            p: Math.random(),
            s: 0.8 + Math.random() * 1.8,
            kind: i % 3
        }));

        function drawRadar() {
            const w = canvas.getBoundingClientRect().width;
            const h = canvas.getBoundingClientRect().height;
            const cx = w / 2,
                cy = h / 2;
            const R = Math.min(w, h) * 0.44;

            ctx.clearRect(0, 0, w, h);

            // Subtle vignette
            const vg = ctx.createRadialGradient(cx, cy, R * 0.2, cx, cy, R * 1.25);
            vg.addColorStop(0, "rgba(191,154,84,0.10)");
            vg.addColorStop(1, "rgba(0,0,0,0)");
            ctx.fillStyle = vg;
            ctx.fillRect(0, 0, w, h);

            // Rings
            ctx.strokeStyle = "rgba(191,154,84,0.22)";
            ctx.lineWidth = 1;
            for (let i = 1; i <= 4; i++) {
                ctx.beginPath();
                ctx.arc(cx, cy, (R * i / 4), 0, Math.PI * 2);
                ctx.stroke();
            }

            // Cross lines
            ctx.strokeStyle = "rgba(242,242,242,0.10)";
            ctx.beginPath();
            ctx.moveTo(cx - R, cy);
            ctx.lineTo(cx + R, cy);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(cx, cy - R);
            ctx.lineTo(cx, cy + R);
            ctx.stroke();

            // Sweep wedge
            sweep += 0.012 + (mode === "CHAOS" ? 0.008 : 0);
            const ang = sweep;

            const wedge = ctx.createRadialGradient(cx, cy, 0, cx, cy, R);
            wedge.addColorStop(0, "rgba(191,154,84,0.18)");
            wedge.addColorStop(0.7, "rgba(191,154,84,0.10)");
            wedge.addColorStop(1, "rgba(191,154,84,0.0)");

            ctx.fillStyle = wedge;
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.arc(cx, cy, R, ang - 0.28, ang + 0.06);
            ctx.closePath();
            ctx.fill();

            // Sweep line
            ctx.strokeStyle = (mode === "CHAOS") ? "rgba(242,242,242,0.30)" : "rgba(191,154,84,0.28)";
            ctx.lineWidth = 1.5;
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.lineTo(cx + Math.cos(ang) * R, cy + Math.sin(ang) * R);
            ctx.stroke();

            // Blips
            t += 0.02;
            blips.forEach(b => {
                b.p = (b.p + 0.002 * b.s) % 1;
                const rr = R * b.r;
                const a = b.a + (mode === "CHAOS" ? Math.sin(t * 0.6 + rr * 0.01) * 0.02 : 0);
                const x = cx + Math.cos(a) * rr;
                const y = cy + Math.sin(a) * rr;

                // "Ping" intensity when sweep passes
                const d = Math.abs(((ang - a + Math.PI * 3) % (Math.PI * 2)) - Math.PI);
                const ping = Math.max(0, 1 - d / 0.22);

                const base = (mode === "CHAOS") ? 0.18 : 0.14;
                const glow = base + ping * 0.55;

                // Outer glow
                const g = ctx.createRadialGradient(x, y, 0, x, y, 18);
                g.addColorStop(0, `rgba(191,154,84,${glow})`);
                g.addColorStop(1, "rgba(191,154,84,0)");
                ctx.fillStyle = g;
                ctx.beginPath();
                ctx.arc(x, y, 18, 0, Math.PI * 2);
                ctx.fill();

                // Core dot
                ctx.fillStyle = (mode === "CHAOS") ? "rgba(242,242,242,0.85)" : "rgba(242,242,242,0.75)";
                ctx.beginPath();
                ctx.arc(x, y, 2.2 + ping * 1.1, 0, Math.PI * 2);
                ctx.fill();
            });

            // Center core
            ctx.fillStyle = "rgba(242,242,242,0.22)";
            ctx.beginPath();
            ctx.arc(cx, cy, 4, 0, Math.PI * 2);
            ctx.fill();
            ctx.strokeStyle = "rgba(191,154,84,0.35)";
            ctx.beginPath();
            ctx.arc(cx, cy, 8, 0, Math.PI * 2);
            ctx.stroke();

            requestAnimationFrame(drawRadar);
        }
        drawRadar();

        // ---------------------------
        // Demo "AI" text generation
        // ---------------------------
        const aiLog = document.getElementById("aiLog");
        const aiOut = document.getElementById("aiOut");
        const statRisk = document.getElementById("statRisk");
        const statMatches = document.getElementById("statMatches");
        const pillMode = document.getElementById("pillMode");
        const pillLock = document.getElementById("pillLock");

        function setLog(txt) {
            aiLog.textContent = txt;
        }

        function setOut(txt) {
            aiOut.textContent = txt;
        }

        function randInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function computeDemo() {
            // Slightly different outputs based on mode
            const baseRisk = mode === "CHAOS" ? randInt(62, 94) : randInt(28, 64);
            const matches = mode === "CHAOS" ? randInt(2, 6) : randInt(3, 8);

            statRisk.textContent = String(baseRisk);
            statMatches.textContent = String(matches).padStart(2, "0");

            const verdict =
                baseRisk < 40 ? "SAFE ALIGNMENT" :
                baseRisk < 70 ? "RISKY DIPLOMACY" :
                baseRisk < 85 ? "IMPROBABLE / VOLATILE" :
                "CATASTROPHIC POTENTIAL";

            const tone =
                mode === "CHAOS" ?
                "Unauthorized spectrum detected. Expect unpredictable outcomes." :
                "Treaty-compliant scan complete. Recommend cautious optimism.";

            setLog(`SCAN ▸ MODE=${mode} ▸ Risk=${baseRisk}/100 ▸ Candidates=${matches}`);
            setOut(`VERDICT: ${verdict}. ${tone}`);
        }

        function toggleMode() {
            mode = (mode === "DIPLOMACY") ? "CHAOS" : "DIPLOMACY";
            pillMode.textContent = `MODE: ${mode}`;
            pillLock.textContent = `LOCK: ${mode==="CHAOS" ? "HARD" : "SOFT"}`;
            setLog(`SYSTEM ▸ Mode switched to ${mode}. Recalibrating radar...`);
            setTimeout(computeDemo, 450);
        }

        // Buttons
        document.getElementById("btnPrimary").addEventListener("click", computeDemo);
        document.getElementById("btnDemo").addEventListener("click", computeDemo);
        document.getElementById("btnSecondary").addEventListener("click", () => {
            document.querySelector("#protocol").scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        });
        document.getElementById("btnToggleMode").addEventListener("click", toggleMode);

        function fakeEnter() {
            setLog("ACCESS ▸ Opening station gates…");
            setOut("NEXT: Profile creation → Compatibility scan → Match list → First contact channel.");
            // little micro feedback
            document.body.style.scrollBehavior = "smooth";
            document.querySelector("#systems").scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
            setTimeout(() => document.body.style.scrollBehavior = "", 650);
        }
        document.getElementById("btnLaunch").addEventListener("click", fakeEnter);
        document.getElementById("btnEnter2").addEventListener("click", fakeEnter);

        // Run a tiny boot sequence
        setTimeout(() => setLog("SYSTEM ONLINE ▸ Calibrating sweep…"), 450);
        setTimeout(() => setLog("SYSTEM ONLINE ▸ Awaiting operator scan…"), 1100);
    </script>
</body>

</html>