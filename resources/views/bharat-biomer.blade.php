<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bharat Biomer | Nature-powered Biological Solutions</title>
    <meta name="description"
        content="Responsive homepage concept for Bharat Biomer - biological farming solutions for better yield, crop health and soil vitality." />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        :root {
            --green-950: #052e16;
            --green-900: #064018;
            --green-800: #0f5b2a;
            --green-700: #137236;
            --green-600: #208143;
            --leaf: #62a934;
            --lime: #a9cf3a;
            --yellow: #f7cf1a;
            --cream: #fff8e8;
            --mint: #f1f8ee;
            --mint2: #e7f3df;
            --text: #172018;
            --muted: #607064;
            --line: #dbe8d5;
            --white: #fff;
            --shadow: 0 18px 45px rgba(5, 46, 22, .14);
            --shadow-soft: 0 10px 28px rgba(5, 46, 22, .10);
            --radius: 22px;
            --max: 1380px;
        }

        * {
            box-sizing: border-box
        }

        html {
            scroll-behavior: smooth
        }

        body {
            margin: 0;
            font-family: DM Sans, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background: #fff;
            overflow-x: hidden
        }

        a {
            text-decoration: none;
            color: inherit
        }

        img {
            max-width: 100%;
            display: block
        }

        .wrap {
            width: min(var(--max), calc(100% - 44px));
            margin: auto
        }

        .eyebrow {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 8px 12px;
            border-radius: 999px;
            background: #eef8e8;
            color: var(--green-800);
            font-weight: 800;
            font-size: 13px
        }

        .section {
            padding: 68px 0
        }

        .title {
            font-family: "Poppins", sans-serif;
            text-align: center;
            margin: 0 0 10px;
            font-size: clamp(28px, 4vw, 44px);
            line-height: 1.08;
            letter-spacing: -.04em
        }

        .sub {
            text-align: center;
            margin: 0 auto 34px;
            max-width: 680px;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.65
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 0;
            border-radius: 14px;
            padding: 15px 22px;
            font-weight: 900;
            cursor: pointer;
            transition: .25s ease;
            white-space: nowrap
        }

        .btn.primary {
            background: linear-gradient(135deg, var(--green-800), var(--green-600));
            color: #fff;
            box-shadow: 0 12px 24px rgba(19, 114, 54, .22)
        }

        .btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 36px rgba(19, 114, 54, .28)
        }

        .btn.secondary {
            background: #fff;
            color: var(--green-800);
            border: 1px solid #c9dcc6
        }

        .btn.yellow {
            background: var(--yellow);
            color: #17310f
        }

        .card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow-soft)
        }

        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, .88);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(219, 232, 213, .85)
        }

        .nav {
            height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 950;
            color: var(--green-800);
            line-height: .92;
            font-size: 26px;
            letter-spacing: -.04em
        }

        .logo-mark {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, #e9f7df, #fff);
            display: grid;
            place-items: center;
            border: 1px solid #d7ead0
        }

        .logo-mark svg {
            width: 31px
        }

        .links {
            display: flex;
            align-items: center;
            gap: 28px;
            font-weight: 800;
            font-size: 14px
        }

        .links a {
            color: #1f2a21
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 14px
        }

        .login {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 900
        }

        .register {
            padding: 13px 19px;
            border-radius: 14px;
            background: var(--green-800);
            color: #fff;
            font-weight: 900
        }

        .hamb {
            display: none;
            border: 0;
            background: #eef8e8;
            color: var(--green-800);
            border-radius: 12px;
            padding: 12px;
            font-size: 22px
        }

        .mobile-panel {
            display: none
        }

        .hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background: radial-gradient(circle at 84% 20%, #fff4bc 0 16%, transparent 34%), linear-gradient(110deg, #f7fbef 0%, #fff 42%, #edf7df 100%)
        }

        .hero:before {
            content: "";
            position: absolute;
            inset: auto -12% -42% -12%;
            height: 470px;
            background: radial-gradient(ellipse at center, rgba(169, 207, 58, .24), transparent 62%);
            z-index: -1
        }

        .hero-grid {
            min-height: 620px;
            display: grid;
            grid-template-columns: 1fr 1.05fr;
            align-items: center;
            gap: 38px;
            padding: 54px 0 44px
        }

        .hero h1 {
            font-size: clamp(42px, 6vw, 76px);
            line-height: .98;
            margin: 18px 0 22px;
            letter-spacing: -.02em;
            max-width: 650px
        }

        .hero h1 span {
            color: var(--green-700)
        }

        .hero p {
            font-size: 19px;
            line-height: 1.75;
            color: #435247;
            max-width: 560px;
            margin: 0 0 30px
        }

        .hero-ctas {
            display: flex;
            gap: 16px;
            flex-wrap: wrap
        }

        .hero-art {
            position: relative;
            min-height: 505px;
            display: grid;
            place-items: center
        }

        .sun {
            position: absolute;
            width: min(480px, 88vw);
            aspect-ratio: 1;
            border-radius: 50%;
            background: radial-gradient(circle, #fff8cf 0 28%, #dff0c6 48%, rgba(255, 255, 255, .15) 70%);
            box-shadow: inset 0 0 70px rgba(255, 255, 255, .7)
        }

        .farmer {
            position: absolute;
            top: 26px;
            width: 230px;
            height: 245px;
            border-radius: 120px 120px 80px 80px;
            background: linear-gradient(#f4dcc7, #e2b98f 48%, #fff 49%);
            box-shadow: var(--shadow);
            display: grid;
            place-items: center;
            font-size: 96px;
            overflow: hidden
        }

        .farmer:after {
            content: "";
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 78px;
            background: #fff;
            border-top: 10px solid #f8f1e5
        }

        .field {
            position: absolute;
            bottom: 48px;
            width: 92%;
            height: 190px;
            border-radius: 50% 50% 28px 28px;
            background: linear-gradient(180deg, #cadf79, #4d9b2d 45%, #2d751e);
            box-shadow: 0 25px 45px rgba(5, 46, 22, .18);
            overflow: hidden
        }

        .field:before {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(90deg, rgba(255, 255, 255, .18) 0 2px, transparent 2px 32px);
            transform: perspective(300px) rotateX(55deg);
            transform-origin: bottom
        }

        .plant {
            position: absolute;
            left: 42px;
            bottom: 105px;
            width: 160px;
            height: 230px
        }

        .stem {
            position: absolute;
            left: 78px;
            bottom: 0;
            width: 7px;
            height: 210px;
            background: var(--green-700);
            border-radius: 99px
        }

        .leaf {
            position: absolute;
            width: 74px;
            height: 38px;
            border-radius: 100% 0 100% 0;
            background: linear-gradient(135deg, #9dcc4d, #1f7a36);
            transform-origin: 100% 100%;
            box-shadow: 0 8px 18px rgba(10, 80, 30, .18)
        }

        .leaf.l1 {
            left: 11px;
            bottom: 44px;
            transform: rotate(28deg)
        }

        .leaf.l2 {
            right: 2px;
            bottom: 82px;
            transform: rotate(210deg)
        }

        .leaf.l3 {
            left: 0;
            bottom: 130px;
            transform: rotate(25deg)
        }

        .leaf.l4 {
            right: -3px;
            bottom: 163px;
            transform: rotate(212deg)
        }

        .products {
            position: absolute;
            bottom: 72px;
            display: flex;
            align-items: end;
            gap: 16px;
            z-index: 2
        }

        .bottle {
            width: 128px;
            height: 220px;
            border-radius: 20px 20px 28px 28px;
            background: linear-gradient(90deg, #fff, #e8f7e6);
            border: 1px solid #d2e2cc;
            box-shadow: 0 18px 32px rgba(5, 46, 22, .22);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center
        }

        .bottle:before {
            content: "";
            position: absolute;
            top: -30px;
            width: 62px;
            height: 34px;
            border-radius: 10px 10px 4px 4px;
            background: var(--green-700)
        }

        .pack {
            width: 150px;
            height: 205px;
            border-radius: 12px;
            background: linear-gradient(160deg, #1b813c, #99c840 78%);
            box-shadow: 0 20px 35px rgba(5, 46, 22, .25);
            color: #fff;
            padding: 24px 14px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, .5)
        }

        .bb {
            font-weight: 950;
            color: var(--green-800);
            font-size: 17px;
            line-height: 1.05
        }

        .bottle small {
            font-weight: 900;
            color: #5e7132
        }

        .pack strong {
            font-size: 22px
        }

        .pack small {
            display: block;
            margin-top: 8px;
            color: #eaf7d4
        }

        .float {
            position: absolute;
            right: 0;
            width: 160px;
            background: #fff;
            border: 1px solid #e2eddc;
            border-radius: 16px;
            padding: 14px 16px;
            box-shadow: var(--shadow-soft);
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 12px
        }

        .float svg {
            width: 28px;
            color: var(--green-700)
        }

        .float.f1 {
            top: 120px
        }

        .float.f2 {
            top: 205px
        }

        .float.f3 {
            top: 290px
        }

        .float.f4 {
            top: 375px
        }

        .petal {
            position: absolute;
            width: 24px;
            height: 42px;
            border-radius: 80% 0 80% 0;
            background: rgba(98, 169, 52, .24);
            animation: drift 7s infinite ease-in-out
        }

        .p1 {
            top: 90px;
            left: 18%;
            transform: rotate(20deg)
        }

        .p2 {
            top: 190px;
            left: 50%;
            animation-delay: 1s
        }

        .p3 {
            bottom: 120px;
            left: 8%;
            animation-delay: 2s
        }

        @keyframes drift {
            50% {
                transform: translateY(-18px) rotate(35deg);
                opacity: .55
            }
        }

        .problem {
            padding-top: 32px
        }

        .grid-5 {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 18px
        }

        .problem-card {
            padding: 28px 18px;
            text-align: center;
            border-radius: 18px;
            min-height: 212px
        }

        .icon {
            width: 66px;
            height: 66px;
            margin: 0 auto 16px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #f4fbef;
            color: var(--green-700);
            font-size: 34px
        }

        .problem-card h3,
        .why-card h3 {
            font-size: 17px;
            margin: 0 0 10px
        }

        .problem-card p,
        .why-card p {
            font-size: 14px;
            line-height: 1.55;
            color: #53645a;
            margin: 0
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px
        }

        .cat {
            overflow: hidden
        }

        .cat-img {
            height: 160px;
            position: relative;
            background: linear-gradient(135deg, #e5f9d8, #85bf48);
            overflow: hidden
        }

        .cat:nth-child(2) .cat-img {
            background: radial-gradient(circle, #67d1d2, #016c7a)
        }

        .cat:nth-child(3) .cat-img {
            background: radial-gradient(circle at 80% 20%, #ffe58a, #f0ba44)
        }

        .cat:nth-child(4) .cat-img {
            background: linear-gradient(135deg, #e6f8ff, #93c860)
        }

        .hill {
            position: absolute;
            inset: auto -10px 0;
            height: 80px;
            background: linear-gradient(135deg, #3fa24a, #91c840);
            border-radius: 50% 50% 0 0
        }

        .microbe {
            position: absolute;
            width: 120px;
            height: 55px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .18);
            border: 2px solid rgba(255, 255, 255, .28);
            top: 48px;
            left: 42px;
            transform: rotate(-18deg)
        }

        .nutrient {
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #7aaa24;
            color: white;
            display: grid;
            place-items: center;
            font-weight: 950
        }

        .n1 {
            top: 26px;
            left: 62px
        }

        .n2 {
            top: 70px;
            left: 150px
        }

        .n3 {
            top: 90px;
            left: 55%
        }

        .cat-body {
            padding: 0 22px 24px;
            position: relative
        }

        .cat-icon {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid var(--line);
            box-shadow: var(--shadow-soft);
            display: grid;
            place-items: center;
            font-size: 29px;
            margin: -29px 0 14px
        }

        .cat h3 {
            margin: 0 0 10px;
            font-size: 20px
        }

        .cat p {
            color: #526357;
            line-height: 1.58;
            margin: 0 0 18px
        }

        .circle-link {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid #bfd7b4;
            color: var(--green-700);
            display: grid;
            place-items: center;
            margin-left: auto;
            font-weight: 950
        }

        .why-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 18px
        }

        .why-card {
            text-align: center;
            padding: 30px 16px
        }

        .why-card .icon {
            background: #fff;
            color: var(--green-800);
            font-size: 42px
        }

        .finder-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 26px
        }

        .finder {
            padding: 40px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #fff, #f2fae8)
        }

        .finder:before {
            content: "";
            position: absolute;
            left: -50px;
            bottom: -55px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, #bde16d, transparent 65%);
            opacity: .5
        }

        .finder h2 {
            font-size: 31px;
            margin: 10px 0 12px
        }

        .finder p {
            color: #53645a;
            line-height: 1.65;
            max-width: 420px
        }

        .select {
            width: 100%;
            margin: 16px 0 20px;
            padding: 17px;
            border: 1px solid #d3e3cd;
            border-radius: 14px;
            background: #fff;
            font-size: 16px;
            font-weight: 800
        }

        .recommend {
            padding: 26px
        }

        .recommend h3 {
            margin: 0 0 16px
        }

        .rec {
            display: flex;
            gap: 16px;
            align-items: center;
            padding: 13px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fff;
            margin-bottom: 12px
        }

        .mini-product {
            width: 54px;
            height: 70px;
            border-radius: 8px;
            background: linear-gradient(#fff, #dcf1d5);
            border: 1px solid #d6e5d0;
            display: grid;
            place-items: center;
            color: var(--green-700);
            font-weight: 950
        }

        .rec strong {
            display: block
        }

        .rec small {
            display: block;
            color: #55655b;
            line-height: 1.45
        }

        .rec a {
            color: var(--green-700);
            font-weight: 900;
            font-size: 13px
        }

        .cycle {
            padding-top: 20px
        }

        .cycle-row {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 16px;
            position: relative
        }

        .cycle-row:before {
            content: "";
            position: absolute;
            top: 54px;
            left: 7%;
            right: 7%;
            height: 2px;
            background: linear-gradient(90deg, var(--green-600), #e1b425, #e66a2c, var(--leaf));
            z-index: 0
        }

        .stage {
            text-align: center;
            position: relative;
            z-index: 1;
            background: #fff
        }

        .stage-icon {
            width: 110px;
            height: 110px;
            margin: 0 auto 14px;
            border: 3px solid #82b74d;
            border-radius: 50%;
            background: #fff;
            display: grid;
            place-items: center;
            font-size: 43px
        }

        .stage:nth-child(4) .stage-icon {
            border-color: #f6c91f
        }

        .stage:nth-child(5) .stage-icon {
            border-color: #f47132
        }

        .stage h3 {
            margin: 0 0 8px
        }

        .stage p {
            font-size: 13px;
            color: #59685e;
            line-height: 1.5;
            margin: 0
        }

        .stats {
            background: linear-gradient(rgba(6, 64, 24, .25), rgba(6, 64, 24, .25)), linear-gradient(120deg, #78ad33, #1b6d2b);
            padding: 34px 0
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px
        }

        .stat {
            background: linear-gradient(145deg, rgba(5, 46, 22, .95), rgba(20, 91, 42, .88));
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 18px;
            padding: 30px;
            text-align: center;
            color: #fff;
            box-shadow: 0 20px 40px rgba(5, 46, 22, .22)
        }

        .stat i {
            font-style: normal;
            font-size: 42px
        }

        .stat strong {
            display: block;
            font-size: 42px;
            letter-spacing: -.04em;
            margin: 8px 0
        }

        .stat span {
            display: block;
            font-size: 18px;
            font-weight: 900
        }

        .stat small {
            color: #dcefd7
        }

        .stories-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 26px
        }

        .story-img {
            height: 220px;
            border-radius: 18px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #a9d36f, #246f32)
        }

        .story:nth-child(2) .story-img {
            background: linear-gradient(135deg, #d7eeb8, #7ba436)
        }

        .story:nth-child(3) .story-img {
            background: linear-gradient(135deg, #dfe7d6, #14532d)
        }

        .person {
            display: none;
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 110px;
            height: 150px;
            border-radius: 65px 65px 10px 10px;
            background: linear-gradient(#d9a97e 0 38%, #1a7c41 39%);
            box-shadow: 0 -10px 30px rgba(255, 255, 255, .18)
        }

        .play {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center
        }

        .play:before {
            content: "▶";
            width: 74px;
            height: 74px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .82);
            display: grid;
            place-items: center;
            color: var(--green-800);
            font-size: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .2)
        }

        .duration {
            position: absolute;
            right: 12px;
            bottom: 12px;
            background: rgba(0, 0, 0, .72);
            color: #fff;
            font-weight: 800;
            padding: 5px 9px;
            border-radius: 8px
        }

        .story h3 {
            font-size: 19px;
            margin: 16px 0 6px
        }

        .story p {
            color: #58685d;
            line-height: 1.55;
            margin: 0
        }

        .partner {
            padding: 0 0 66px
        }

        .partner-box {
            overflow: hidden;
            border-radius: 24px;
            background: linear-gradient(105deg, var(--green-900) 0 47%, transparent 47%), linear-gradient(120deg, #e9f8d9, #78b548);
            min-height: 280px;
            color: #fff;
            position: relative;
            padding: 46px
        }

        .partner-box h2 {
            position: relative;
            z-index: 1;
            font-size: clamp(34px, 4.5vw, 54px);
            line-height: 1;
            margin: 0 0 14px;
            max-width: 550px
        }

        .partner-box h2 span {
            color: var(--yellow)
        }

        .partner-box p {
            position: relative;
            z-index: 1;
            max-width: 520px;
            color: #e7f5e5;
            line-height: 1.65;
            font-size: 17px
        }

        .partner-ctas {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 14px;
            flex-wrap: wrap
        }

        .handshake {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(105deg, var(--green-900) 0 47%, transparent 47%), linear-gradient(120deg, #e9f8d9, #00000000);
            border-radius: 0;
            box-shadow: none;
            z-index: 0;
        }

        .stat i img {
            width: 60px;
        }

        .cat-icon img {
            padding: 10px;
        }

        .producthero {
            position: relative;
            z-index: 1;
            max-width: 480px;
        }

        .hero-badges .float img {
            width: 30px;
        }

        .hero-badges .float {
            width: 220px;
            z-index: 2;
        }

        .footer {
            background: #063f1b;
            color: #fff;
            padding: 42px 0 28px
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr repeat(5, 1fr);
            gap: 28px
        }

        .footer h4 {
            margin: 0 0 14px
        }

        .footer a,
        .footer p {
            display: block;
            color: #d5e8d2;
            margin: 0 0 10px;
            font-size: 14px;
            line-height: 1.5
        }

        .social {
            display: flex;
            gap: 10px;
            margin-top: 18px
        }

        .social a {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .24);
            display: grid;
            place-items: center
        }

        .copyright {
            border-top: 1px solid rgba(255, 255, 255, .16);
            margin-top: 28px;
            padding-top: 20px;
            text-align: center;
            color: #c9dfc5;
            font-size: 13px
        }

        @media(max-width:1050px) {

            .links,
            .nav-actions {
                display: none
            }

            .hamb {
                display: block
            }

            .mobile-panel.open {
                display: block;
                position: fixed;
                left: 18px;
                right: 18px;
                top: 92px;
                background: #fff;
                border: 1px solid var(--line);
                border-radius: 22px;
                padding: 18px;
                box-shadow: var(--shadow);
                z-index: 60
            }

            .mobile-panel a {
                display: block;
                padding: 14px;
                border-bottom: 1px solid #eef3eb;
                font-weight: 900
            }

            .hero-grid {
                grid-template-columns: 1fr;
                min-height: auto;
                text-align: center
            }

            .hero p {
                margin-left: auto;
                margin-right: auto
            }

            .hero-ctas {
                justify-content: center
            }

            .hero-art {
                min-height: 530px
            }

            .float {
                right: 4%;
            }

            .grid-5,
            .why-grid {
                grid-template-columns: repeat(3, 1fr)
            }

            .category-grid {
                grid-template-columns: repeat(2, 1fr)
            }

            .finder-grid {
                grid-template-columns: 1fr
            }

            .cycle-row {
                grid-template-columns: repeat(3, 1fr);
                row-gap: 34px
            }

            .cycle-row:before {
                display: none
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr)
            }

            .footer-grid {
                grid-template-columns: repeat(3, 1fr)
            }
        }

        @media(max-width:700px) {
            .wrap {
                width: min(100% - 28px, var(--max))
            }

            .section {
                padding: 48px 0
            }

            .nav {
                height: 72px
            }

            .logo {
                font-size: 21px
            }

            .logo-mark {
                width: 38px;
                height: 38px
            }

            .hero-grid {
                padding: 34px 0 24px;
                gap: 20px
            }

            .hero {
                text-align: left
            }

            .hero h1 {
                font-size: 44px
            }

            .hero p {
                font-size: 16px;
                margin-left: 0;
                text-align: left
            }

            .hero-ctas {
                justify-content: flex-start
            }

            .btn {
                width: 100%;
                padding: 15px 18px
            }

            .hero-art {
                min-height: 435px;
                transform: scale(.9);
                transform-origin: top center;
                margin-bottom: -40px
            }

            .farmer {
                width: 180px;
                height: 200px;
                font-size: 72px
            }

            .products {
                bottom: 66px
            }

            .bottle {
                width: 105px;
                height: 180px
            }

            .pack {
                width: 120px;
                height: 170px
            }

            .field {
                height: 145px
            }

            .plant {
                left: 18px;
                bottom: 80px;
                transform: scale(.75)
            }

            .float {
                position: relative;
                right: auto;
                top: auto !important;
                width: calc(50% - 8px);
                font-size: 12px;
                padding: 10px
            }

            .hero-badges {
                position: absolute;
                bottom: 4px;
                left: 0;
                right: 0;
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                justify-content: center
            }

            .grid-5,
            .why-grid,
            .category-grid,
            .stories-grid,
            .stats-grid,
            .footer-grid {
                grid-template-columns: 1fr
            }

            .problem-card {
                min-height: auto
            }

            .finder {
                padding: 28px 22px
            }

            .recommend {
                padding: 20px
            }

            .cycle-row {
                grid-template-columns: 1fr 1fr
            }

            .stage-icon {
                width: 92px;
                height: 92px;
                font-size: 36px
            }

            .stat {
                padding: 24px
            }

            .stat strong {
                font-size: 34px
            }

            .partner-box {
                background: linear-gradient(160deg, var(--green-900), var(--green-700));
                padding: 30px 22px 220px
            }

            .handshake {
                right: 50%;
                transform: translateX(50%);
                width: 220px;
                height: 185px
            }

            .footer {
                text-align: left
            }

            .cat-img {
                height: 135px
            }

            .story-img {
                height: 190px
            }
        }

        @media(max-width:430px) {
            .handshake {
                display: none;
            }

            .hero h1 {
                font-size: 38px
            }

            .hero-art {
                transform: scale(.82);
                margin-left: -8%;
                margin-right: -8%;
                min-height: 405px
            }

            .cycle-row {
                grid-template-columns: 1fr
            }

            .title {
                text-align: left
            }

            .sub {
                text-align: left;
                margin-left: 0
            }

            .problem .title,
            .problem .sub,
            .solutions .title,
            .solutions .sub,
            .why .title,
            .why .sub,
            .stories .title,
            .stories .sub {
                text-align: center
            }

            .stats-grid {
                gap: 12px
            }

            .footer-grid {
                gap: 18px
            }

            .mobile-panel {
                top: 80px
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="wrap nav">
            <a class="logo" href="{{ url('/') }}"><img src="{{ asset('assets/bharat-biomer/bblogo.webp') }}"
                    alt="logo" /></a>
            <nav class="links"><a href="#products">Products <i class="fa fa-chevron-down" aria-hidden="true"></i>
                </a><a href="#technology">Technology</a><a href="#crops">Crops</a><a href="#about">About</a><a
                    href="#stories">Blog</a><a href="#contact">Contact</a></nav>
            <div class="nav-actions"><a class="login" href="#"><i class="fa fa-user-o" aria-hidden="true"></i>
                    Login</a><a class="register" href="#">Register</a></div>
            <button class="hamb" id="hamb"><i class="fa fa-bars" aria-hidden="true"></i></button>
        </div>
        <div class="mobile-panel" id="mobilePanel"><a href="#products">Products</a><a
                href="#technology">Technology</a><a href="#crops">Crops</a><a href="#about">About</a><a
                href="#stories">Blog</a><a href="#contact">Contact</a><a href="#">Login</a><a href="#"
                class="register">Register</a></div>
    </header>

    <main>
        <section class="hero">
            <div class="wrap hero-grid">
                <div class="hero-copy">
                    <!-- <span class="eyebrow">🌱 Science-backed bio inputs for Indian farms</span> -->
                    <h1><span>Nature-powered</span> biological solutions for better crop yield</h1>
                    <p>Bharat Biomer helps farmers improve crop health, soil vitality, nutrient uptake and farm
                        productivity using science-backed biological inputs.</p>
                    <div class="hero-ctas"><a class="btn primary" href="#products">Explore Products <i
                                class="fa fa-long-arrow-right" aria-hidden="true"></i></a><a class="btn secondary"
                            href="#finder"><i class="fa fa-pagelines" aria-hidden="true"></i> Find Crop Solution</a>
                    </div>
                </div>
                <div class="hero-art" aria-label="Bharat Biomer farmer and product illustration">
                    <div class="sun"></div>
                    <div class="petal p1"></div>
                    <div class="petal p2"></div>
                    <div class="petal p3"></div>
                    <div class="producthero"><img src="{{ asset('assets/bharat-biomer/banner-1.png') }}"
                            alt="banner" /></div>
                    <div class="hero-badges">
                        <div class="float f1"><img src="{{ asset('assets/bharat-biomer/business-chart.png') }}"
                                alt="icon" /> Higher Yield</div>
                        <div class="float f2"><img src="{{ asset('assets/bharat-biomer/plant-1.png') }}"
                                alt="icon" /> Healthier Soil</div>
                        <div class="float f3"><img src="{{ asset('assets/bharat-biomer/plant.png') }}" alt="icon" />
                            Residue-Free</div>
                        <div class="float f4"><img src="{{ asset('assets/bharat-biomer/science.png') }}"
                                alt="icon" /> Science Backed</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section problem">
            <div class="wrap">
                <h2 class="title">Farming today needs more than fertilizers</h2>
                <p class="sub">Modern farmers face multiple crop and soil challenges every season.</p>
                <div class="grid-5">
                    <div class="card problem-card">
                        <div class="icon"><img src="{{ asset('assets/bharat-biomer/plant.png') }}" alt="icon" />
                        </div>
                        <h3>Soil degradation</h3>
                        <p>Continuous farming reduces soil organic matter and health.</p>
                    </div>
                    <div class="card problem-card">
                        <div class="icon"><img src="{{ asset('assets/bharat-biomer/root.png') }}" alt="icon" />
                        </div>
                        <h3>Weak root development</h3>
                        <p>Poor root growth limits nutrient and water absorption.</p>
                    </div>
                    <div class="card problem-card">
                        <div class="icon"><img src="{{ asset('assets/bharat-biomer/npk.png') }}" alt="icon" />
                        </div>
                        <h3>Nutrient inefficiency</h3>
                        <p>Nutrients are not utilized effectively by the crop.</p>
                    </div>
                    <div class="card problem-card">
                        <div class="icon"><img src="{{ asset('assets/bharat-biomer/pest.png') }}"
                                alt="icon" /></div>
                        <h3>Pest and stress impact</h3>
                        <p>Pests, diseases and climate stress reduce crop potential.</p>
                    </div>
                    <div class="card problem-card">
                        <div class="icon"><img src="{{ asset('assets/bharat-biomer/plant-1.png') }}"
                                alt="icon" /></div>
                        <h3>Lower yield and quality</h3>
                        <p>All these factors lead to low productivity and poor produce quality.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section solutions" id="products">
            <div class="wrap">
                <h2 class="title">Solutions by category</h2>
                <p class="sub">Clear, crop-focused biological inputs that support healthier growth and better yield
                    outcomes.</p>
                <div class="category-grid">
                    <article class="card cat">
                        <div class="cat-img"><img src="{{ asset('assets/bharat-biomer/farmer3.jpg') }}"></div>
                        <div class="cat-body">
                            <div class="cat-icon"><img src="{{ asset('assets/bharat-biomer/science.png') }}"
                                    alt="icon"></div>
                            <h3>Bio-Stimulants</h3>
                            <p>Enhance plant growth, boost immunity and improve yield naturally.</p><a
                                class="circle-link" href="#"><i class="fa fa-long-arrow-right"
                                    aria-hidden="true"></i></a>
                        </div>
                    </article>
                    <article class="card cat">
                        <div class="cat-img"><img src="{{ asset('assets/bharat-biomer/farmerland.jpg') }}"></div>
                        <div class="cat-body">
                            <div class="cat-icon"><img src="{{ asset('assets/bharat-biomer/research.png') }}"
                                    alt="icon"></div>
                            <h3>Microbial Solutions</h3>
                            <p>Beneficial microbes that improve soil health and nutrient availability.</p><a
                                class="circle-link" href="#"><i class="fa fa-long-arrow-right"
                                    aria-hidden="true"></i></a>
                        </div>
                    </article>
                    <article class="card cat">
                        <div class="cat-img"
                            style="background: url('{{ asset('assets/bharat-biomer/farmer1.jpg') }}'); background-size:cover;background-position: center;">
                        </div>
                        <div class="cat-body">
                            <div class="cat-icon"><img src="{{ asset('assets/bharat-biomer/plant.png') }}"
                                    alt="icon"></div>
                            <h3>Crop Nutrition Support</h3>
                            <p>Optimized nutrition for stronger growth and better productivity.</p><a
                                class="circle-link" href="#"><i class="fa fa-long-arrow-right"
                                    aria-hidden="true"></i></a>
                        </div>
                    </article>
                    <article class="card cat">
                        <div class="cat-img"><img src="{{ asset('assets/bharat-biomer/footerfarmer.jpg') }}"></div>
                        <div class="cat-body">
                            <div class="cat-icon"><img src="{{ asset('assets/bharat-biomer/plant-1.png') }}"
                                    alt="icon"></div>
                            <h3>Residue-free Farming</h3>
                            <p>Safe, natural solutions for clean produce and sustainable farming.</p><a
                                class="circle-link" href="#"><i class="fa fa-long-arrow-right"
                                    aria-hidden="true"></i></a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="section why" id="technology">
            <div class="wrap">
                <h2 class="title">Why Bharat Biomer</h2>
                <p class="sub">Built for performance, crop relevance and long-term soil health.</p>
                <div class="why-grid">
                    <div class="card why-card">
                        <div class="icon"><img src="{{ asset('assets/bharat-biomer/research.png') }}"
                                alt="icon"></div>
                        <h3>Research-driven formulations</h3>
                        <p>Developed using advanced R&D and scientific innovation.</p>
                    </div>
                    <div class="card why-card">
                        <div class="icon"><img src="{{ asset('assets/bharat-biomer/shield.png') }}"
                                alt="icon"></div>
                        <h3>Field-tested performance</h3>
                        <p>Proven results across diverse agro-climatic conditions.</p>
                    </div>
                    <div class="card why-card">
                        <div class="icon"><img src="{{ asset('assets/bharat-biomer/tour-guide.png') }}"
                                alt="icon"></div>
                        <h3>Crop-specific guidance</h3>
                        <p>Expert recommendations tailored to each crop and stage.</p>
                    </div>
                    <div class="card why-card">
                        <div class="icon"><img src="{{ asset('assets/bharat-biomer/science.png') }}"
                                alt="icon"></div>
                        <h3>Sustainable biological approach</h3>
                        <p>Eco-friendly solutions for long-term soil and environment health.</p>
                    </div>
                    <div class="card why-card">
                        <div class="icon"><img src="{{ asset('assets/bharat-biomer/heart-handshake.png') }}"
                                alt="icon"></div>
                        <h3>Reliable partner network</h3>
                        <p>Strong distribution and support network across India.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="finder">
            <div class="wrap finder-grid">
                <div class="card finder"><span class="eyebrow">🌿 Crop Solution Finder</span>
                    <h2>Find recommended solutions for your crop</h2>
                    <p>Select your crop and get recommended solutions designed for better performance.</p>
                    <label><strong>Choose crop</strong><select class="select" id="cropSelect">
                            <option>Wheat</option>
                            <option>Tomato</option>
                            <option>Cotton</option>
                            <option>Soybean</option>
                            <option>Paddy</option>
                        </select></label><button class="btn primary">View Recommended Solution <i
                            class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
                    <p><small>Personalized solutions based on crop needs and growth stage.</small></p>
                </div>
                <div class="card recommend">
                    <h3 id="recTitle">Recommended for Wheat</h3>
                    <div id="recList"></div>
                    <p style="text-align:center;color:#526357">Need expert help? Talk to our agronomist <strong
                            style="color:var(--green-700)">WhatsApp</strong></p>
                </div>
            </div>
        </section>

        <section class="section cycle" id="crops">
            <div class="wrap">
                <h2 class="title">Benefits across the crop cycle</h2>
                <p class="sub">From soil activation to yield quality, Bharat Biomer supports the complete crop
                    journey.</p>
                <div class="cycle-row">
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/plant.png') }}"
                                alt="icon" /></div>
                        <h3>Soil</h3>
                        <p>Improves soil structure and microbial activity.</p>
                    </div>
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/root.png') }}"
                                alt="icon" /></div>
                        <h3>Root</h3>
                        <p>Strengthens roots for better anchorage.</p>
                    </div>
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/npk.png') }}"
                                alt="icon" /></div>
                        <h3>Nutrient</h3>
                        <p>Enhances nutrient uptake and efficiency.</p>
                    </div>
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/flower-pot.png') }}"
                                alt="icon" /></div>
                        <h3>Flowering</h3>
                        <p>Promotes uniform flowering and setting.</p>
                    </div>
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/healthy-food.png') }}"
                                alt="icon" /></div>
                        <h3>Fruiting</h3>
                        <p>Supports healthy fruit development.</p>
                    </div>
                    <div class="stage">
                        <div class="stage-icon"><img src="{{ asset('assets/bharat-biomer/plant-1.png') }}"
                                alt="icon" /></div>
                        <h3>Yield</h3>
                        <p>Increases yield and improves quality.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="stats"
            style="background-image: url('{{ asset('assets/bharat-biomer/farmerland.jpg') }}'); background-size: cover; background-position: 50% 20%;">
            <div class="wrap stats-grid">
                <div class="stat"><i><img src="{{ asset('assets/bharat-biomer/group.png') }}" alt="icon"
                            style="margin: 0 auto;" /></i><strong>10,000+</strong><span>Farmers
                        Served</span><small>Across India</small></div>
                <div class="stat"><i><img src="{{ asset('assets/bharat-biomer/field.png') }}" alt="icon"
                            style="margin: 0 auto;" /></i><strong>2,50,000+</strong><span>Acres
                        Impacted</span><small>And Growing</small></div>
                <div class="stat"><i><img src="{{ asset('assets/bharat-biomer/scientific-research.png') }}"
                            alt="icon" style="margin: 0 auto;" /></i><strong>500+</strong><span>Crop
                        Trials</span><small>Successful Trials</small></div>
                <div class="stat"><i><img src="{{ asset('assets/bharat-biomer/helping-hand.png') }}"
                            alt="icon" style="margin: 0 auto;" /></i><strong>150+</strong><span>Channel
                        Partners</span><small>Pan India Network</small></div>
            </div>
        </section>

        <section class="section stories" id="stories">
            <div class="wrap">
                <h2 class="title">Stories from the field</h2>
                <p class="sub">Field outcomes, grower stories and dealer success journeys.</p>
                <div class="stories-grid">
                    <article class="story">
                        <div class="story-img"
                            style="background-image: url('{{ asset('assets/bharat-biomer/farmer1.jpg') }}'); background-position: center; background-size: cover;">
                            <div class="person"></div>
                            <div class="play"></div><span class="duration">2:15</span>
                        </div>
                        <h3>Tomato yield improvement</h3>
                        <p>See how Bharat Biomer solutions increased yield and quality.</p>
                    </article>
                    <article class="story">
                        <div class="story-img"
                            style="background-image: url('{{ asset('assets/bharat-biomer/farmer2.jpg') }}'); background-position: center; background-size: cover;">
                            <div class="person"></div>
                            <div class="play"></div><span class="duration">2:02</span>
                        </div>
                        <h3>Healthy cotton growth</h3>
                        <p>Stronger plants, more bolls and better profits for farmers.</p>
                    </article>
                    <article class="story">
                        <div class="story-img"
                            style="background-image: url('{{ asset('assets/bharat-biomer/farmer3.jpg') }}'); background-position: center; background-size: cover;">
                            <div class="person"></div>
                            <div class="play"></div><span class="duration">1:48</span>
                        </div>
                        <h3>Dealer success story</h3>
                        <p>Our partner sharing growth journey with Bharat Biomer.</p>
                    </article>
                </div>
                <div style="text-align:center;margin-top:28px"><a class="btn secondary" href="#">View More
                        Stories <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div>
            </div>
        </section>

        <section class="partner">
            <div class="wrap">
                <div class="partner-box"
                    style="background-image: url('{{ asset('assets/bharat-biomer/footerfarmer.jpg') }}'); background-size: cover; background-position: 50% 20%;">
                    <h2>Grow with us as a <span>channel partner</span></h2>
                    <p>Join our growing network of distributors and agri-partners. Let’s grow together for a sustainable
                        tomorrow.</p>
                    <div class="partner-ctas"><a class="btn yellow" href="#">Become a Distributor <i
                                class="fa fa-long-arrow-right" aria-hidden="true"></i></a><a class="btn secondary"
                            href="#">Talk to Sales <i class="fa fa-mobile" aria-hidden="true"></i></a></div>
                    <div class="handshake"></div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer" id="contact">
        <div class="wrap">
            <div class="footer-grid">
                <div><a class="logo" style="color:#fff;font-size:21px" href="#"><img
                            src="{{ asset('assets/bharat-biomer/footer-logo.svg') }}" alt="logo" /></a>
                    <p style="margin-top:14px">Nature-powered biological solutions for sustainable and productive
                        farming.</p>
                    <div class="social"><a>f</a><a>◎</a><a>▶</a><a>in</a></div>
                </div>
                <div>
                    <h4>PRODUCTS</h4><a>Bio-Stimulants</a><a>Microbial Solutions</a><a>Crop Nutrition
                        Support</a><a>Residue-free Farming</a><a>All Products</a>
                </div>
                <div>
                    <h4>CROPS</h4><a>Wheat</a><a>Paddy</a><a>Cotton</a><a>Soybean</a><a>All Crops</a>
                </div>
                <div>
                    <h4>COMPANY</h4><a>About Us</a><a>Our Technology</a><a>Our Team</a><a>Career</a><a>News & Media</a>
                </div>
                <div>
                    <h4>POLICIES</h4><a>Privacy Policy</a><a>Terms & Conditions</a><a>Refund Policy</a><a>Shipping
                        Policy</a>
                </div>
                <div>
                    <h4>CONTACT</h4><a>☎ +91 98765 43210</a><a>✉ info@bharatbiomer.com</a><a>📍 Bharat Biomer Pvt.
                        Ltd.<br>A-33, Agri Innovation Park,<br>Nashik, Maharashtra - 422003</a>
                </div>
            </div>
            <div class="copyright">© 2026 Bharat Biomer. All rights reserved.</div>
        </div>
    </footer>
    <script>
        const panel = document.getElementById('mobilePanel');
        document.getElementById('hamb').addEventListener('click', () => panel.classList.toggle('open'));
        const data = {
            Wheat: ['Bio-Root Plus|Enhances root growth and nutrient uptake for stronger plants.',
                'BB Micro Boost|Improves soil health and nutrient availability naturally.',
                'BB Crop Nutrition|Balanced nutrition for better yield and grain quality.'
            ],
            Tomato: ['Flower Max Bio|Supports flowering, fruit setting and uniform crop growth.',
                'Fruit Guard Plus|Improves fruit development, quality and marketability.',
                'Root Micro Boost|Builds active root-zone biology for better uptake.'
            ],
            Cotton: ['Boll Booster Bio|Supports healthy boll formation and plant vigor.',
                'Stress Shield|Helps crop withstand heat and climate stress.',
                'Soil Active Granules|Improves microbial activity and root performance.'
            ],
            Soybean: ['Nutrient Bio Fix|Improves nutrient use efficiency and early vigor.',
                'Root Zone Plus|Supports stronger root development and soil health.',
                'Yield Support Bio|Helps improve crop resilience and output.'
            ],
            Paddy: ['Paddy Root Active|Supports root growth and better tillering.',
                'Soil Micro Granules|Improves soil microbial balance and nutrient availability.',
                'Grain Quality Support|Supports healthy grain formation and quality.'
            ]
        };
        const crop = document.getElementById('cropSelect'),
            title = document.getElementById('recTitle'),
            list = document.getElementById('recList');

        function render() {
            title.textContent = 'Recommended for ' + crop.value;
            list.innerHTML = data[crop.value].map(x => {
                const [a, b] = x.split('|');
                return `<div class="rec"><div class="mini-product">BB</div><div><strong>${a}</strong><small>${b}</small><a href="#">View Details <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div></div>`
            }).join('')
        }
        crop.addEventListener('change', render);
        render();
    </script>
</body>

</html>
