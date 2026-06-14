<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photobooth</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700;800&family=Playfair+Display:wght@400;600&family=Cinzel:wght@400;600&family=Fredoka:wght@400;500&family=Orbitron:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-base:      #faf8f3;
            --bg-card:      #ffffff;
            --bg-elevated:  #fffbf5;
            --bg-input:     #ffffff;
            --border:       #e8ddd0;
            --border-hover: #dcc9b8;
            --primary:      #ff6b9d;
            --primary-dim:  rgba(255,107,157,0.1);
            --primary-glow: rgba(255,107,157,0.3);
            --success:      #6dd5a8;
            --success-dim:  rgba(109,213,168,0.15);
            --danger:       #ff6b9d;
            --danger-dim:   rgba(255,107,157,0.12);
            --warning:      #ffc966;
            --warning-dim:  rgba(255,201,102,0.12);
            --accent-blue:  #4db8ff;
            --accent-purple: #b875ff;
            --accent-yellow: #ffd966;
            --text-1:       #3d3a35;
            --text-2:       #8b8680;
            --text-3:       #a8a299;
            --radius-sm:    12px;
            --radius-md:    16px;
            --radius-lg:    20px;
            --radius-xl:    28px;
            --shadow:       0 8px 24px rgba(0,0,0,0.08);
            --font-display: 'Fredoka', sans-serif;
            --font-body:    'Fredoka', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background: linear-gradient(135deg, #faf8f3 0%, #f5f0e8 50%, #fffbf5 100%);
            background-attachment: fixed;
            color: var(--text-1);
            min-height: 100vh;
            line-height: 1.5;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative flowers - top right */
        body::before {
            content: '🌼 🌻 🌺 🌷 🌹 🌸';
            position: fixed;
            top: 20px;
            right: 30px;
            font-size: 50px;
            opacity: 0.25;
            pointer-events: none;
            z-index: 1;
            animation: float 8s ease-in-out infinite;
        }

        /* Decorative flowers - bottom left */
        body::after {
            content: '🌼 🌻 🌺 🌷 🌹 🌸';
            position: fixed;
            bottom: 50px;
            left: 30px;
            font-size: 45px;
            opacity: 0.25;
            pointer-events: none;
            z-index: 1;
            animation: float-reverse 10s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }

        @keyframes float-reverse {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(30px) rotate(-5deg); }
        }

        /* ═══════════════════════════════════
           TOP HEADER BAR
        ═══════════════════════════════════ */
        .app-header {
            position: sticky; top: 0; z-index: 100;
            background: linear-gradient(90deg, rgba(255,107,157,0.08) 0%, rgba(77,184,255,0.08) 50%, rgba(184,117,255,0.08) 100%);
            backdrop-filter: blur(16px);
            border-bottom: 2px solid var(--border);
            padding: 0 1.5rem;
            height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            position: relative;
        }

        .app-header::before {
            content: '🌼 🌻';
            position: absolute;
            left: 20px;
            font-size: 20px;
            opacity: 0.3;
        }

        .app-header::after {
            content: '🌺 🌷';
            position: absolute;
            right: 20px;
            font-size: 20px;
            opacity: 0.3;
        }
        .header-logo {
            font-family: var(--font-display);
            font-size: 18px; font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-1);
            display: flex; align-items: center; gap: 8px;
        }
        .header-logo i { color: var(--primary); font-size: 20px; }
        .header-logo span { color: var(--text-3); font-size: 11px; font-weight: 400; letter-spacing: 1px; text-transform: uppercase; }
        .header-badge {
            display: flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 20px;
            background: linear-gradient(90deg, rgba(109,213,168,0.15), rgba(77,184,255,0.15));
            border: 1px solid rgba(109,213,168,0.3);
            color: var(--success); font-size: 11px; font-weight: 600;
        }
        .header-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--success); animation: pulse-dot 2s infinite; }
        @keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:.3} }

        /* ═══════════════════════════════════
           STEPPER NAVIGATION
        ═══════════════════════════════════ */
        .stepper-wrap {
            padding: 1.5rem 1.5rem 0;
            max-width: 860px; margin: 0 auto;
            position: relative;
        }

        .stepper-wrap::before {
            content: '🦋 ✨ 🦋';
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 32px;
            opacity: 0.4;
            animation: flutter 3s ease-in-out infinite;
        }

        @keyframes flutter {
            0%, 100% { transform: translateX(-50%) translateY(0) rotate(0deg); }
            25% { transform: translateX(-40%) translateY(-10px) rotate(5deg); }
            50% { transform: translateX(-60%) translateY(-5px) rotate(-5deg); }
            75% { transform: translateX(-45%) translateY(-12px) rotate(3deg); }
        }
        .stepper {
            display: flex; align-items: center; gap: 0;
            background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
            border: 2px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 6px;
            position: relative;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .step-btn {
            flex: 1; position: relative;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px 16px;
            border: none; background: transparent;
            color: var(--text-3); font-family: var(--font-body);
            font-size: 13px; font-weight: 500; cursor: pointer;
            border-radius: var(--radius-lg); transition: all .2s;
            z-index: 1;
        }
        .step-btn .step-num {
            width: 22px; height: 22px; border-radius: 50%;
            background: var(--bg-elevated); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 600; flex-shrink: 0;
            transition: all .2s;
        }
        .step-btn.active {
            color: #fff;
            background: linear-gradient(90deg, #ff6b9d 0%, #ff8fb8 100%);
            box-shadow: 0 4px 12px rgba(255,107,157,0.3);
        }
        .step-btn.active .step-num {
            background: #fff; border-color: #fff; color: var(--primary);
            font-weight: 700;
        }
        .step-btn.done { color: var(--success); }
        .step-btn.done .step-num {
            background: rgba(109,213,168,0.15); border-color: var(--success); color: var(--success);
            font-weight: 600;
        }
        .step-btn:hover:not(.active):not([disabled]) { color: var(--text-2); background: var(--bg-elevated); }
        .step-btn[disabled] { cursor: not-allowed; opacity: .4; }
        .step-connector { width: 1px; height: 20px; background: var(--border); flex-shrink: 0; }
        .step-btn i { font-size: 15px; }

        /* ═══════════════════════════════════
           MAIN CONTENT WRAPPER
        ═══════════════════════════════════ */
        .app-body {
            max-width: 860px; margin: 0 auto;
            padding: 1.25rem 1.5rem 2rem;
            position: relative;
        }

        .app-body::before {
            content: '🎀';
            position: absolute;
            top: 10px;
            right: -30px;
            font-size: 40px;
            opacity: 0.3;
            animation: spin 4s linear infinite;
        }

        .app-body::after {
            content: '🎀';
            position: absolute;
            top: 50%;
            left: -25px;
            font-size: 40px;
            opacity: 0.3;
            animation: spin 4s linear infinite reverse;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ═══════════════════════════════════
           PANELS (STEP PANELS)
        ═══════════════════════════════════ */
        .panel {
            display: none;
            animation: fadeUp .25s ease;
        }
        .panel.active { display: block; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ═══════════════════════════════════
           SECTION TITLES
        ═══════════════════════════════════ */
        .section-label {
            font-size: 10px; font-weight: 600; letter-spacing: 1.2px;
            text-transform: uppercase; color: var(--text-3); margin-bottom: 10px;
        }

        /* ═══════════════════════════════════
           STEP 1 — SETTING
        ═══════════════════════════════════ */
        .setting-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 14px;
            margin-bottom: 14px;
        }
        @media (max-width: 580px) { .setting-grid { grid-template-columns: 1fr; } }

        .setting-card {
            background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
            border: 2px solid var(--border);
            border-radius: var(--radius-lg); padding: 1.1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .setting-card.full { grid-column: 1 / -1; }

        /* Orientation */
        .orient-row { display: flex; gap: 8px; }
        .orient-btn {
            flex: 1; padding: 12px 8px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
            color: var(--text-2); cursor: pointer;
            display: flex; flex-direction: column; align-items: center; gap: 5px;
            font-family: var(--font-body); font-size: 12px; transition: all .15s;
        }
        .orient-btn i { font-size: 22px; }
        .orient-btn.active {
            border-color: var(--primary);
            background: linear-gradient(135deg, rgba(255,107,157,0.1) 0%, rgba(255,165,200,0.1) 100%);
            color: var(--primary);
            font-weight: 600;
        }
        .orient-btn:hover:not(.active) { border-color: var(--border-hover); color: var(--text-1); }

        /* Layout picker */
        .layout-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 8px; }
        .layout-card {
            border: 2px solid var(--border); border-radius: var(--radius-md);
            background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
            padding: 10px 6px 8px;
            text-align: center; cursor: pointer; transition: all .15s;
        }
        .layout-card:hover { border-color: var(--border-hover); }
        .layout-card.active {
            border-color: var(--primary);
            background: linear-gradient(135deg, rgba(255,107,157,0.1) 0%, rgba(255,165,200,0.1) 100%);
        }
        .lc-icon {
            display: flex; gap: 3px; justify-content: center;
            align-items: flex-end; height: 30px; margin-bottom: 5px;
        }
        .lc-icon span {
            background: var(--border-hover); border-radius: 2px; display: block; transition: background .15s;
        }
        .layout-card.active .lc-icon span { background: var(--primary); }
        .lc1 span { width: 16px; height: 26px; }
        .lc2 span { width: 12px; height: 20px; }
        .lc4 span { width: 10px; height: 16px; }
        .lc6 span { width: 9px; height: 13px; }
        .lc-label { font-size: 11px; color: var(--text-2); }
        .layout-card.active .lc-label { color: var(--primary); }

        /* Themes */
        .theme-list { display: flex; flex-direction: column; gap: 6px; }
        .theme-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: var(--radius-md);
            border: 2px solid var(--border);
            background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
            cursor: pointer; transition: all .15s;
        }
        .theme-item:hover { border-color: var(--border-hover); }
        .theme-item.active {
            border-color: var(--primary);
            background: linear-gradient(135deg, rgba(255,107,157,0.1) 0%, rgba(255,165,200,0.1) 100%);
        }
        .theme-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .theme-name { font-size: 13px; font-weight: 500; flex: 1; }
        .theme-desc { font-size: 11px; color: var(--text-3); }
        .theme-item.active .theme-desc { color: rgba(124,92,252,.7); }

        /* Custom theme panel */
        .custom-panel {
            display: none; margin-top: 10px;
            background: var(--bg-elevated); border: 1px solid rgba(245,158,11,.2);
            border-radius: var(--radius-md); padding: 12px;
        }
        .custom-panel.open { display: block; animation: fadeUp .2s ease; }
        .color-row { display: flex; gap: 8px; margin-bottom: 10px; }
        .color-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .color-wrap label { font-size: 10px; color: var(--text-3); }
        .color-input {
            width: 36px; height: 36px; border-radius: 50%; border: 2px solid var(--border);
            padding: 2px; background: none; cursor: pointer;
        }
        .custom-row { margin-bottom: 8px; }
        .custom-row label { font-size: 11px; color: var(--text-2); display: flex; justify-content: space-between; margin-bottom: 4px; }
        .custom-row input[type=range] { width: 100%; accent-color: var(--warning); }
        .custom-row select, .custom-row input[type=text] {
            width: 100%; padding: 8px 12px; border-radius: var(--radius-sm);
            border: 2px solid var(--border);
            background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
            color: var(--text-1);
            font-family: var(--font-body); font-size: 12px; outline: none;
        }
        .custom-row select:focus, .custom-row input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,107,157,0.1);
        }
        .custom-row input[type=file] {
            padding: 5px 8px; font-size: 11px;
        }
        .btn-save-custom {
            width: 100%; padding: 8px; border-radius: var(--radius-sm);
            border: none; background: var(--warning); color: #000;
            font-family: var(--font-body); font-size: 12px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .btn-save-custom:hover { background: #d97706; }

        /* Frame text */
        .input-frame {
            width: 100%; padding: 12px 14px; border-radius: var(--radius-md);
            border: 2px solid var(--border);
            background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
            color: var(--text-1); font-family: var(--font-body); font-size: 13px;
            outline: none; transition: all .15s;
        }
        .input-frame:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,107,157,0.1);
        }
        .char-count { font-size: 11px; color: var(--text-3); text-align: right; margin-top: 4px; }

        /* ═══════════════════════════════════
           NAV BUTTONS (bottom of each panel)
        ═══════════════════════════════════ */
        .nav-row {
            display: flex; gap: 8px; margin-top: 16px;
        }
        .btn-primary-action {
            flex: 1; padding: 12px 20px;
            border: none; border-radius: var(--radius-md);
            background: linear-gradient(90deg, var(--primary) 0%, #ff8fb8 100%);
            color: #fff;
            font-family: var(--font-display); font-size: 13px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all .15s; letter-spacing: 0.3px;
            box-shadow: 0 4px 12px rgba(255,107,157,0.3);
        }
        .btn-primary-action:hover:not([disabled]) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,157,0.4);
        }
        .btn-primary-action[disabled] { opacity: .35; cursor: not-allowed; }
        .btn-secondary-action {
            padding: 12px 16px;
            border: 2px solid var(--border); border-radius: var(--radius-md);
            background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
            color: var(--text-1);
            font-family: var(--font-body); font-size: 13px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            transition: all .15s;
        }
        .btn-secondary-action:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(255,107,157,0.15);
        }

        /* ═══════════════════════════════════
           STEP 2 — CAMERA SESSION
        ═══════════════════════════════════ */
        .camera-wrap {
            background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
            border: 3px dashed var(--border);
            border-radius: var(--radius-lg); overflow: hidden;
            margin-bottom: 14px; position: relative;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        .camera-box {
            background: #000; position: relative;
            width: 100%; aspect-ratio: 4/3;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .camera-box.portrait { aspect-ratio: 3/4; }
        #webcam {
            width: 100%; height: 100%; object-fit: contain;
            transform: scaleX(-1); display: none;
            background: #000;
        }
        .cam-placeholder {
            display: flex; flex-direction: column; align-items: center; gap: 10px;
            color: var(--text-3); text-align: center; padding: 2rem;
        }
        .cam-placeholder i { font-size: 48px; color: var(--text-3); }
        .cam-placeholder h3 { font-size: 16px; color: var(--text-2); font-weight: 500; }
        .cam-placeholder p { font-size: 12px; max-width: 220px; line-height: 1.6; }
        .btn-start-cam {
            margin-top: 4px; padding: 10px 24px;
            border: none; border-radius: var(--radius-md);
            background: linear-gradient(90deg, var(--primary) 0%, #ff8fb8 100%);
            color: #fff;
            font-family: var(--font-body); font-size: 13px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            transition: all .15s;
            box-shadow: 0 4px 12px rgba(255,107,157,0.3);
        }
        .btn-start-cam:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,157,0.4);
        }

        /* Countdown overlay */
        .countdown-overlay {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            background: rgba(0,0,0,0.55); opacity: 0; pointer-events: none;
            z-index: 10; transition: opacity .2s;
        }
        .countdown-overlay.visible { opacity: 1; }
        .countdown-number {
            font-family: var(--font-display); font-size: 96px; font-weight: 800;
            color: #fff; line-height: 1;
            animation: pop .5s cubic-bezier(0.175,0.885,0.32,1.275) forwards;
        }
        @keyframes pop { 0%{transform:scale(1.6);opacity:0} 60%{transform:scale(1);opacity:1} 100%{transform:scale(1);opacity:1} }

        /* Flash overlay */
        .flash-overlay {
            position: fixed; inset: 0; background: #fff;
            opacity: 0; pointer-events: none; z-index: 9999;
            transition: opacity .08s;
        }
        .flash-overlay.flash { opacity: 1; }

        /* Capture status badge */
        .capture-status {
            position: absolute; top: 12px; left: 12px;
            display: flex; align-items: center; gap: 6px;
            background: rgba(0,0,0,0.65); backdrop-filter: blur(4px);
            border-radius: 20px; padding: 4px 10px; color: #fff;
            font-size: 11px; font-weight: 500;
            opacity: 0; transition: opacity .2s;
        }
        .capture-status.visible { opacity: 1; }
        .capture-status::before {
            content: ''; width: 6px; height: 6px; border-radius: 50%;
            background: var(--danger); animation: pulse-dot 1s infinite;
        }

        /* Mini thumbs progress */
        .mini-progress {
            position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%);
            display: flex; gap: 5px; align-items: flex-end;
        }
        .mini-thumb {
            width: 44px; height: 33px; border-radius: 4px;
            background: rgba(255,255,255,0.12); border: 1.5px solid rgba(255,255,255,0.2);
            overflow: hidden; transition: all .2s;
        }
        .mini-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .mini-thumb.captured { border-color: var(--primary); }

        /* Progress dots */
        .prog-dots { display: flex; gap: 6px; justify-content: center; margin: 12px 0; }
        .prog-dot {
            height: 4px; border-radius: 2px; background: var(--bg-elevated);
            transition: all .3s;
        }
        .prog-dot.pending { width: 24px; }
        .prog-dot.active  { width: 32px; background: var(--primary); }
        .prog-dot.done    { width: 24px; background: var(--success); }

        /* Camera controls */
        .camera-controls {
            display: flex; flex-direction: column; align-items: center; gap: 6px;
            padding: 14px 1.1rem;
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius-lg);
        }
        .btn-capture {
            width: 64px; height: 64px; border-radius: 50%;
            border: 3px solid rgba(239,68,68,0.35);
            background: var(--danger); color: #fff;
            font-size: 26px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all .15s; position: relative; flex-shrink: 0;
        }
        .btn-capture:hover:not([disabled]) { background: #c53030; transform: scale(1.05); }
        .btn-capture[disabled] { opacity: .35; cursor: not-allowed; transform: none; }
        .btn-capture::after {
            content: ''; position: absolute; inset: -5px;
            border-radius: 50%; border: 1px solid rgba(239,68,68,0.2);
        }
        .capture-hint { font-size: 11px; color: var(--text-3); }

        /* ═══════════════════════════════════
           STEP 3 — RESULTS
        ═══════════════════════════════════ */
        .result-layout {
            display: grid; grid-template-columns: auto 1fr; gap: 14px; align-items: start;
        }
        @media (max-width: 580px) { .result-layout { grid-template-columns: 1fr; } }

        .strip-col {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius-lg); padding: 1rem;
            display: flex; align-items: center; justify-content: center;
            min-width: 160px;
        }
        .final-strip-img {
            max-width: 200px; max-height: 480px;
            border-radius: var(--radius-sm); display: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.6);
        }
        .strip-placeholder {
            display: flex; flex-direction: column; align-items: center;
            gap: 8px; padding: 1.5rem; text-align: center; color: var(--text-3);
        }
        .strip-placeholder i { font-size: 36px; }
        .strip-placeholder p { font-size: 12px; }

        .actions-col {
            display: flex; flex-direction: column; gap: 12px;
        }
        .actions-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius-lg); padding: 1.1rem;
        }

        /* Filters */
        .filter-row { display: flex; flex-wrap: wrap; gap: 6px; }
        .filter-chip {
            padding: 5px 13px; border-radius: 20px;
            border: 1px solid var(--border); background: var(--bg-elevated);
            color: var(--text-2); font-family: var(--font-body); font-size: 12px;
            cursor: pointer; transition: all .15s;
        }
        .filter-chip:hover { border-color: var(--border-hover); color: var(--text-1); }
        .filter-chip.active { background: var(--primary-dim); border-color: var(--primary); color: var(--primary); }

        /* Action buttons */
        .btn-print {
            width: 100%; padding: 12px; border-radius: var(--radius-md);
            border: none; background: var(--success); color: #fff;
            font-family: var(--font-display); font-size: 13px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background .15s;
        }
        .btn-print:hover { background: #16a34a; }
        .btn-dl {
            flex: 1; padding: 11px 16px; border-radius: var(--radius-md);
            border: none; background: var(--primary); color: #fff;
            font-family: var(--font-body); font-size: 13px; font-weight: 500;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: background .15s;
        }
        .btn-dl:hover { background: #6344e8; }
        .btn-retake {
            padding: 11px 14px; border-radius: var(--radius-md);
            border: 1px solid rgba(239,68,68,0.3); background: var(--danger-dim);
            color: var(--danger); font-family: var(--font-body); font-size: 13px;
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            transition: all .15s;
        }
        .btn-retake:hover { background: rgba(239,68,68,.2); }

        /* ═══════════════════════════════════
           HIDDEN CANVAS + PRINT
        ═══════════════════════════════════ */
        #hidden-canvas { display: none; }
        #print-container { display: none; }
        @media print {
            body > *:not(#print-container) { display: none !important; }
            #print-container {
                display: flex !important; align-items: center; justify-content: center;
                width: 100%; height: 100vh;
            }
            #print-image { max-width: 100%; max-height: 100vh; }
        }
    </style>
</head>
<body>

<div class="flash-overlay" id="flash-overlay"></div>

<!-- HEADER -->
<header class="app-header">
    <div class="header-logo">
        <i class="bi bi-camera2"></i>
        Photobooth
        <span>v1.0</span>
    </div>

</header>

<!-- STEPPER -->
<div class="stepper-wrap">
    <div class="stepper">
        <button class="step-btn active" id="s1" onclick="goStep(1)">
            <span class="step-num" id="sn1">1</span>
            <i class="bi bi-sliders"></i> Setting
        </button>
        <div class="step-connector"></div>
        <button class="step-btn" id="s2" onclick="goStep(2)" disabled>
            <span class="step-num" id="sn2">2</span>
            <i class="bi bi-camera-video"></i> Sesi Foto
        </button>
        <div class="step-connector"></div>
        <button class="step-btn" id="s3" onclick="goStep(3)" disabled>
            <span class="step-num" id="sn3">3</span>
            <i class="bi bi-images"></i> Hasil Foto
        </button>
    </div>
</div>

<!-- BODY -->
<div class="app-body">

    <!-- ═══════ STEP 1: SETTING ═══════ -->
    <div class="panel active" id="panel1">

        <div class="setting-grid">

            <!-- Orientasi -->
            <div class="setting-card">
                <p class="section-label"><i class="bi bi-aspect-ratio"></i> Orientasi Layar</p>
                <div class="orient-row">
                    <button class="orient-btn active" id="ob-land" onclick="setOrient('landscape')">
                        <i class="bi bi-tablet-landscape-fill" style="transform:none"></i>
                        Landscape (4:3)
                    </button>
                    <button class="orient-btn" id="ob-port" onclick="setOrient('portrait')">
                        <i class="bi bi-tablet-fill"></i>
                        Portrait (3:4)
                    </button>
                </div>
            </div>

            <!-- Tata Letak -->
            <div class="setting-card">
                <p class="section-label"><i class="bi bi-grid-3x3-gap"></i> Tata Letak</p>
                <div class="layout-grid">
                    <div class="layout-card" onclick="setLayout(this,1,'single')">
                        <div class="lc-icon lc1"><span></span></div>
                        <div class="lc-label">1 Foto</div>
                    </div>
                    <div class="layout-card" onclick="setLayout(this,2,'double')">
                        <div class="lc-icon lc2"><span></span><span></span></div>
                        <div class="lc-label">2 Foto</div>
                    </div>
                    <div class="layout-card active" onclick="setLayout(this,4,'quad')">
                        <div class="lc-icon lc4"><span></span><span></span><span></span><span></span></div>
                        <div class="lc-label">4 Foto</div>
                    </div>
                    <div class="layout-card" onclick="setLayout(this,6,'hexa')">
                        <div class="lc-icon lc6"><span></span><span></span><span></span><span></span><span></span><span></span></div>
                        <div class="lc-label">6 Foto</div>
                    </div>
                </div>
            </div>

            <!-- Tema -->
            <div class="setting-card full">
                <p class="section-label"><i class="bi bi-palette"></i> Tema Bingkai</p>
                <div class="theme-list">
                    <div class="theme-item active" data-theme="retro" onclick="setTheme(this)" style="border-left: 3px solid #c9a96e;">
                        <div class="theme-dot" style="background:#c9a96e"></div>
                        <div><div class="theme-name">Retro</div><div class="theme-desc">Warm vintage amber tones</div></div>
                    </div>
                    <div class="theme-item" data-theme="pastel" onclick="setTheme(this)" style="border-left: 3px solid #e599b0;">
                        <div class="theme-dot" style="background:#e599b0"></div>
                        <div><div class="theme-name">Pastel</div><div class="theme-desc">Soft cute hearts & pastels</div></div>
                    </div>
                    <div class="theme-item" data-theme="cyberpunk" onclick="setTheme(this)" style="border-left: 3px solid #00ffe7;">
                        <div class="theme-dot" style="background:#00ffe7"></div>
                        <div><div class="theme-name">Cyberpunk</div><div class="theme-desc">Neon cyber grid overlay</div></div>
                    </div>
                    <div class="theme-item" data-theme="party" onclick="setTheme(this)" style="border-left: 3px solid #f472b6;">
                        <div class="theme-dot" style="background:#f472b6"></div>
                        <div><div class="theme-name">Party</div><div class="theme-desc">Confetti & celebration vibes</div></div>
                    </div>
                    <div class="theme-item" data-theme="minimal" onclick="setTheme(this)" style="border-left: 3px solid #555;">
                        <div class="theme-dot" style="background:#555"></div>
                        <div><div class="theme-name">Minimal Dark</div><div class="theme-desc">Clean monochrome black</div></div>
                    </div>
                    <div class="theme-item" data-theme="custom" onclick="setTheme(this)" style="border-left: 3px solid #f59e0b;">
                        <div class="theme-dot" style="background:#f59e0b"></div>
                        <div><div class="theme-name">Tema Kustom</div><div class="theme-desc">Buat tema pilihan sendiri</div></div>
                    </div>
                </div>

                <!-- Custom Theme Panel -->
                <div class="custom-panel" id="custom-theme-panel">
                    <p class="section-label" style="color:var(--warning);margin-top:10px;margin-bottom:8px"><i class="bi bi-brush"></i> Desainer Tema</p>
                    <div class="color-row">
                        <div class="color-wrap">
                            <label>Latar</label>
                            <input type="color" id="custom-bg-color" class="color-input" value="#ffffff">
                        </div>
                        <div class="color-wrap">
                            <label>Bingkai</label>
                            <input type="color" id="custom-border-color" class="color-input" value="#111111">
                        </div>
                        <div class="color-wrap">
                            <label>Teks</label>
                            <input type="color" id="custom-text-color" class="color-input" value="#111111">
                        </div>
                    </div>
                    <div class="custom-row">
                        <label>Tebal Bingkai <span id="custom-border-width-val">12px</span></label>
                        <input type="range" id="custom-border-width" min="4" max="32" value="12">
                    </div>
                    <div class="custom-row">
                        <label>Font Teks</label>
                        <select id="custom-font-family">
                            <option value="DM Sans">DM Sans (Modern)</option>
                            <option value="Playfair Display">Playfair (Elegant Serif)</option>
                            <option value="Cinzel">Cinzel (Classic Roman)</option>
                            <option value="Fredoka">Fredoka (Cute Round)</option>
                            <option value="Orbitron">Orbitron (Cyber Tech)</option>
                        </select>
                    </div>
                    <div class="custom-row">
                        <label>Ornamen Overlay</label>
                        <select id="custom-overlay-type">
                            <option value="none">Polos (Tanpa Ornamen)</option>
                            <option value="retro">Retro Vignette & Lines</option>
                            <option value="pastel">Cute Hearts Stickers</option>
                            <option value="cyberpunk">Cyber Grid Overlay</option>
                            <option value="party">Confetti Particles</option>
                        </select>
                    </div>
                    <div class="custom-row">
                        <label>Upload Gambar Bingkai / Hiasan (PNG/JPG)</label>
                        <input type="file" id="custom-bg-image" accept="image/*" class="form-control form-control-sm" style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-sm);padding:5px;color:var(--text-1);font-size:11px;width:100%">
                        <div id="custom-image-preview-wrapper" style="display:none;margin-top:8px;position:relative;">
                            <img id="custom-image-preview" src="" style="max-width:100%;max-height:70px;border-radius:4px;border:1px solid var(--border);">
                            <button type="button" id="btn-clear-bg-image" onclick="clearBgImage()" style="position:absolute;top:2px;right:2px;background:rgba(0,0,0,.7);border:none;border-radius:50%;width:20px;height:20px;color:#ef4444;cursor:pointer;font-size:12px;display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                    <div class="custom-row" id="image-type-group" style="display:none">
                        <label>Penerapan Gambar</label>
                        <select id="custom-image-type">
                            <option value="overlay">Bingkai Overlay (Di Atas Foto)</option>
                            <option value="background">Latar Belakang (Di Bawah Foto)</option>
                        </select>
                    </div>
                    <div style="border-top:1px solid var(--border);padding-top:10px;margin-top:4px">
                        <div class="custom-row">
                            <label>Simpan Sebagai Template</label>
                            <input type="text" id="custom-theme-name" placeholder="Nama template..." maxlength="20">
                        </div>
                        <button type="button" id="btn-save-custom-theme" class="btn-save-custom" onclick="saveCustomTheme()">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Simpan Template
                        </button>
                    </div>
                </div>
            </div>

            <!-- Frame Text -->
            <div class="setting-card full">
                <p class="section-label"><i class="bi bi-fonts"></i> Teks Bingkai</p>
                <input type="text" id="frame-text" class="input-frame" value="Photobooth 2026" placeholder="Ketik label foto..." maxlength="25">
                <div class="char-count" id="frame-text-count">9/25</div>
            </div>

        </div>

        <div class="nav-row">
            <button class="btn-primary-action" onclick="goStep(2)">
                Lanjut ke Sesi Foto <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>

    <!-- ═══════ STEP 2: SESI FOTO ═══════ -->
    <div class="panel" id="panel2">

        <!-- Camera Area -->
        <div class="camera-wrap">
            <div class="camera-box" id="camera-box">
                <video id="webcam" autoplay playsinline muted></video>

                <div class="cam-placeholder" id="cam-placeholder">
                    <i class="bi bi-camera"></i>
                    <h3>Kamera Belum Aktif</h3>
                    <p>Nyalakan kamera untuk memulai sesi pengambilan foto</p>
                    <button class="btn-start-cam" onclick="startCamera()">
                        <i class="bi bi-power"></i> Nyalakan Kamera
                    </button>
                </div>

                <div class="countdown-overlay" id="countdown-overlay">
                    <span class="countdown-number" id="countdown-number">3</span>
                </div>

                <div class="capture-status" id="capture-status">
                    <span id="capture-status-text">FOTO 1/4</span>
                </div>

                <div class="mini-progress" id="mini-progress"></div>

                <div class="flash-overlay" id="flash-overlay" style="position:absolute;inset:0;background:#fff;opacity:0;pointer-events:none;z-index:20;transition:opacity .08s"></div>
            </div>
        </div>

        <!-- Progress Dots -->
        <div class="prog-dots" id="prog-dots"></div>

        <!-- Capture Controls -->
        <div class="camera-controls">
            <button class="btn-capture" id="btn-capture" onclick="startCapture()" disabled aria-label="Mulai jepretan foto">
                <i class="bi bi-camera-fill"></i>
            </button>
            <span class="capture-hint">Klik tombol merah untuk memulai jepretan foto</span>
        </div>

        <div class="nav-row">
            <button class="btn-secondary-action" onclick="goStep(1)">
                <i class="bi bi-arrow-left"></i> Kembali
            </button>
            <button class="btn-primary-action" id="btn-to-result" onclick="goStep(3)" disabled>
                Lihat Hasil <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>

    <!-- ═══════ STEP 3: HASIL FOTO ═══════ -->
    <div class="panel" id="panel3">

        <div class="result-layout">

            <!-- Strip Preview -->
            <div class="strip-col">
                <div class="strip-placeholder" id="strip-placeholder">
                    <i class="bi bi-images"></i>
                    <p>Belum ada foto</p>
                </div>
                <img id="final-strip-img" class="final-strip-img" src="" alt="Hasil Photobooth Strip">
            </div>

            <!-- Actions -->
            <div class="actions-col">

                <div class="actions-card">
                    <p class="section-label" style="margin-bottom:10px"><i class="bi bi-magic"></i> Efek Filter Foto</p>
                    <div class="filter-row">
                        <button class="filter-chip active" data-filter="normal" onclick="setFilter(this,'normal')">Normal</button>
                        <button class="filter-chip" data-filter="vintage" onclick="setFilter(this,'vintage')">Vintage</button>
                        <button class="filter-chip" data-filter="bw" onclick="setFilter(this,'bw')">B&W</button>
                        <button class="filter-chip" data-filter="cyber" onclick="setFilter(this,'cyber')">Cyber</button>
                        <button class="filter-chip" data-filter="warm" onclick="setFilter(this,'warm')">Warm</button>
                    </div>
                </div>

                <div class="actions-card">
                    <p class="section-label" style="margin-bottom:10px"><i class="bi bi-share"></i> Aksi</p>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <button class="btn-print" onclick="printPhoto()">
                            <i class="bi bi-printer-fill"></i> Cetak Foto Sekarang
                        </button>
                        <div style="display:flex;gap:8px">
                            <button class="btn-dl" onclick="downloadPhoto()">
                                <i class="bi bi-download"></i> Unduh PNG
                            </button>
                            <button class="btn-retake" onclick="retakePhoto()">
                                <i class="bi bi-trash"></i> Hapus & Ulang
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="nav-row">
            <button class="btn-secondary-action" onclick="goStep(2)">
                <i class="bi bi-arrow-left"></i> Kembali ke Kamera
            </button>
        </div>
    </div>

</div>

<!-- Hidden canvas for compositing -->
<canvas id="hidden-canvas"></canvas>
<div id="print-container"><img id="print-image" src="" alt="Cetak Photobooth"></div>

<script>
// ═══════════════════════════════════════════════════════════
//  STATE
// ═══════════════════════════════════════════════════════════
const state = {
    step: 1,
    orientation: 'landscape',
    layoutId: 'quad',
    totalPhotos: 4,
    theme: 'retro',
    frameText: 'Photobooth 2026',
    currentFilter: 'normal',
    photos: [],          // array of dataURL
    customBgImageData: null,
    customBgImageType: 'overlay',
    stream: null,
    capturing: false,
};

// ═══════════════════════════════════════════════════════════
//  STEPPER
// ═══════════════════════════════════════════════════════════
function goStep(n) {
    if (n === state.step) return;

    // update panels
    document.querySelectorAll('.panel').forEach((p, i) => {
        p.classList.toggle('active', i + 1 === n);
    });

    // update step buttons appearance
    [1, 2, 3].forEach(i => {
        const btn = document.getElementById('s' + i);
        const num = document.getElementById('sn' + i);
        btn.classList.remove('active', 'done');
        if (i === n) btn.classList.add('active');
        else if (i < n) btn.classList.add('done');

        if (i < n) {
            num.innerHTML = '<i class="bi bi-check" style="font-size:12px"></i>';
        } else {
            num.textContent = i;
        }
    });

    state.step = n;
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // entering step 3 → render strip
    if (n === 3 && state.photos.length > 0) {
        renderStrip(state.currentFilter);
    }
}

// ═══════════════════════════════════════════════════════════
//  STEP 1 INTERACTIONS
// ═══════════════════════════════════════════════════════════
function setOrient(v) {
    state.orientation = v;
    document.getElementById('ob-land').classList.toggle('active', v === 'landscape');
    document.getElementById('ob-port').classList.toggle('active', v === 'portrait');
    const box = document.getElementById('camera-box');
    if (box) box.classList.toggle('portrait', v === 'portrait');
}

function setLayout(el, count, id) {
    document.querySelectorAll('.layout-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    state.totalPhotos = count;
    state.layoutId = id;
    buildProgressDots();
    buildMiniThumbs();
}

const THEMES = {
    retro:     { bgColor:'#f5e6c8', borderColor:'#c9a96e', textColor:'#5c3d1e', borderWidth:14, font:'Playfair Display', overlayType:'retro' },
    pastel:    { bgColor:'#fce4ec', borderColor:'#e599b0', textColor:'#ad1457', borderWidth:12, font:'Fredoka',           overlayType:'pastel' },
    cyberpunk: { bgColor:'#0a0a0a', borderColor:'#00ffe7', textColor:'#00ffe7', borderWidth:10, font:'Orbitron',          overlayType:'cyberpunk' },
    party:     { bgColor:'#fff8e1', borderColor:'#f472b6', textColor:'#be185d', borderWidth:13, font:'Fredoka',           overlayType:'party' },
    minimal:   { bgColor:'#111',    borderColor:'#444',    textColor:'#fff',    borderWidth:10, font:'DM Sans',           overlayType:'none' },
};

function setTheme(el) {
    document.querySelectorAll('.theme-item').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    const themeId = el.dataset.theme;
    state.theme = themeId;
    const cp = document.getElementById('custom-theme-panel');
    if (themeId === 'custom') {
        cp.classList.add('open');
    } else {
        cp.classList.remove('open');
    }
}

// Custom theme controls
document.getElementById('custom-border-width').addEventListener('input', function() {
    document.getElementById('custom-border-width-val').textContent = this.value + 'px';
});
document.getElementById('frame-text').addEventListener('input', function() {
    state.frameText = this.value;
    const len = this.value.length;
    document.getElementById('frame-text-count').textContent = len + '/25';
});

document.getElementById('custom-bg-image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        state.customBgImageData = ev.target.result;
        document.getElementById('custom-image-preview').src = ev.target.result;
        document.getElementById('custom-image-preview-wrapper').style.display = 'block';
        document.getElementById('image-type-group').style.display = 'block';
    };
    reader.readAsDataURL(file);
});

function clearBgImage() {
    state.customBgImageData = null;
    document.getElementById('custom-bg-image').value = '';
    document.getElementById('custom-image-preview-wrapper').style.display = 'none';
    document.getElementById('image-type-group').style.display = 'none';
}

function saveCustomTheme() {
    const name = document.getElementById('custom-theme-name').value.trim();
    if (!name) { alert('Masukkan nama template terlebih dahulu.'); return; }
    const saved = JSON.parse(localStorage.getItem('expo_custom_themes') || '[]');
    const theme = {
        id: 'custom_' + Date.now(),
        name,
        bgColor:      document.getElementById('custom-bg-color').value,
        borderColor:  document.getElementById('custom-border-color').value,
        textColor:    document.getElementById('custom-text-color').value,
        borderWidth:  parseInt(document.getElementById('custom-border-width').value),
        font:         document.getElementById('custom-font-family').value,
        overlayType:  document.getElementById('custom-overlay-type').value,
        bgImage:      state.customBgImageData || '',
        imageType:    document.getElementById('custom-image-type').value,
        isCustom: true
    };
    saved.push(theme);
    localStorage.setItem('expo_custom_themes', JSON.stringify(saved));
    alert('Template "' + name + '" berhasil disimpan!');
    addSavedThemeToList(theme);
}

function addSavedThemeToList(theme) {
    const list = document.querySelector('.theme-list');
    const item = document.createElement('div');
    item.className = 'theme-item';
    item.dataset.theme = theme.id;
    item.style.borderLeft = '3px solid ' + theme.borderColor;
    item.innerHTML = `
        <div class="theme-dot" style="background:${theme.borderColor}"></div>
        <div style="flex:1">
            <div class="theme-name">${theme.name}</div>
            <div class="theme-desc">Template kustom tersimpan</div>
        </div>
        <button onclick="deleteSavedTheme('${theme.id}', this)" style="background:none;border:none;cursor:pointer;color:var(--danger);font-size:13px" title="Hapus">
            <i class="bi bi-trash"></i>
        </button>
    `;
    item.querySelector('.theme-name').parentElement.parentElement.addEventListener('click', function(e) {
        if (e.target.closest('button')) return;
        setTheme(item);
    });
    // insert before custom row
    const customItem = document.querySelector('[data-theme="custom"]');
    list.insertBefore(item, customItem);
}

function deleteSavedTheme(id, btn) {
    if (!confirm('Hapus template ini?')) return;
    const saved = JSON.parse(localStorage.getItem('expo_custom_themes') || '[]');
    localStorage.setItem('expo_custom_themes', JSON.stringify(saved.filter(t => t.id !== id)));
    btn.closest('.theme-item').remove();
}

// Load saved custom themes on start
(function loadSavedThemes() {
    const saved = JSON.parse(localStorage.getItem('expo_custom_themes') || '[]');
    saved.forEach(addSavedThemeToList);
})();

// ═══════════════════════════════════════════════════════════
//  STEP 2 — CAMERA
// ═══════════════════════════════════════════════════════════
function buildProgressDots() {
    const wrap = document.getElementById('prog-dots');
    wrap.innerHTML = '';
    const total = state.totalPhotos;
    const taken = state.photos.length;
    for (let i = 0; i < total; i++) {
        const d = document.createElement('div');
        d.className = 'prog-dot ' + (i < taken ? 'done' : i === taken ? 'active' : 'pending');
        d.style.width = (i === taken && i < total ? '32px' : '24px');
        wrap.appendChild(d);
    }
}

function buildMiniThumbs() {
    const wrap = document.getElementById('mini-progress');
    wrap.innerHTML = '';
    for (let i = 0; i < state.totalPhotos; i++) {
        const div = document.createElement('div');
        div.className = 'mini-thumb' + (i < state.photos.length ? ' captured' : '');
        div.id = 'thumb-' + i;
        if (state.photos[i]) {
            const img = document.createElement('img');
            img.src = state.photos[i];
            div.appendChild(img);
        }
        wrap.appendChild(div);
    }
}

async function startCamera() {
    try {
        state.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 960 } }, audio: false });
        const video = document.getElementById('webcam');
        video.srcObject = state.stream;
        video.style.display = 'block';
        document.getElementById('cam-placeholder').style.display = 'none';
        document.getElementById('btn-capture').disabled = false;
        buildProgressDots();
        buildMiniThumbs();
        // unlock step 2 nav
        document.getElementById('s2').disabled = false;
    } catch(err) {
        alert('Tidak dapat mengakses kamera: ' + err.message);
    }
}

function startCapture() {
    if (state.capturing) return;
    state.photos = [];
    buildMiniThumbs();
    captureLoop(0);
}

function captureLoop(idx) {
    if (idx >= state.totalPhotos) {
        // all done
        document.getElementById('capture-status').classList.remove('visible');
        const btnResult = document.getElementById('btn-to-result');
        btnResult.disabled = false;
        document.getElementById('s3').disabled = false;
        return;
    }

    state.capturing = true;
    document.getElementById('btn-capture').disabled = true;

    const statusEl = document.getElementById('capture-status');
    const statusTxt = document.getElementById('capture-status-text');
    statusEl.classList.add('visible');
    statusTxt.textContent = 'FOTO ' + (idx + 1) + '/' + state.totalPhotos;

    buildProgressDots();

    const cdOverlay = document.getElementById('countdown-overlay');
    const cdNum = document.getElementById('countdown-number');
    cdOverlay.classList.add('visible');

    let count = 3;

    function showTick(n) {
        cdNum.textContent = n;
        // restart animation
        cdNum.style.animation = 'none';
        cdNum.offsetHeight; // reflow
        cdNum.style.animation = '';
    }
    showTick(count);

    const tick = setInterval(() => {
        count--;
        if (count > 0) {
            showTick(count);
        } else {
            clearInterval(tick);
            cdOverlay.classList.remove('visible');
            doSnap(idx);
        }
    }, 1000);
}

function doSnap(idx) {
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('hidden-canvas');
    canvas.width  = video.videoWidth  || 1280;
    canvas.height = video.videoHeight || 960;
    const ctx = canvas.getContext('2d');
    ctx.save();
    ctx.scale(-1, 1);
    ctx.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
    ctx.restore();

    // flash
    const fl = document.getElementById('flash-overlay');
    fl.style.opacity = '1';
    setTimeout(() => { fl.style.opacity = '0'; }, 120);

    const dataUrl = canvas.toDataURL('image/jpeg', 0.92);
    state.photos.push(dataUrl);

    // update thumb
    buildMiniThumbs();
    buildProgressDots();

    state.capturing = false;

    setTimeout(() => {
        captureLoop(idx + 1);
    }, 600);
}

// ═══════════════════════════════════════════════════════════
//  STEP 3 — RENDER STRIP
// ═══════════════════════════════════════════════════════════
function getThemeConfig() {
    if (state.theme === 'custom') {
        return {
            bgColor:     document.getElementById('custom-bg-color').value,
            borderColor: document.getElementById('custom-border-color').value,
            textColor:   document.getElementById('custom-text-color').value,
            borderWidth: parseInt(document.getElementById('custom-border-width').value),
            font:        document.getElementById('custom-font-family').value,
            overlayType: document.getElementById('custom-overlay-type').value,
            bgImage:     state.customBgImageData || '',
            imageType:   document.getElementById('custom-image-type').value,
        };
    }
    if (state.theme.startsWith('custom_')) {
        const saved = JSON.parse(localStorage.getItem('expo_custom_themes') || '[]');
        const t = saved.find(x => x.id === state.theme);
        if (t) return t;
    }
    return THEMES[state.theme] || THEMES['retro'];
}

function applyFilter(ctx, w, h, filter) {
    if (filter === 'normal') return;
    const imgData = ctx.getImageData(0, 0, w, h);
    const d = imgData.data;
    for (let i = 0; i < d.length; i += 4) {
        let r = d[i], g = d[i+1], b = d[i+2];
        if (filter === 'bw') {
            const gray = 0.299*r + 0.587*g + 0.114*b;
            d[i] = d[i+1] = d[i+2] = gray;
        } else if (filter === 'vintage') {
            d[i]   = Math.min(255, r * 1.1 + 30);
            d[i+1] = Math.min(255, g * 0.9 + 10);
            d[i+2] = Math.min(255, b * 0.7);
        } else if (filter === 'warm') {
            d[i]   = Math.min(255, r * 1.15);
            d[i+1] = Math.min(255, g * 1.0);
            d[i+2] = Math.min(255, b * 0.8);
        } else if (filter === 'cyber') {
            d[i]   = Math.min(255, r * 0.5);
            d[i+1] = Math.min(255, g * 1.2);
            d[i+2] = Math.min(255, b * 1.3);
        }
    }
    ctx.putImageData(imgData, 0, 0);
}

function drawOverlay(ctx, x, y, w, h, overlayType, borderColor) {
    if (overlayType === 'retro') {
        // vignette
        const grad = ctx.createRadialGradient(x+w/2, y+h/2, h*0.3, x+w/2, y+h/2, h*0.8);
        grad.addColorStop(0, 'rgba(0,0,0,0)');
        grad.addColorStop(1, 'rgba(0,0,0,0.35)');
        ctx.fillStyle = grad; ctx.fillRect(x, y, w, h);
        // lines
        ctx.strokeStyle = 'rgba(255,220,120,0.07)';
        ctx.lineWidth = 1;
        for (let i = y; i < y+h; i += 6) {
            ctx.beginPath(); ctx.moveTo(x, i); ctx.lineTo(x+w, i); ctx.stroke();
        }
    } else if (overlayType === 'pastel') {
        const hearts = ['♥','♡','❤','💕'];
        ctx.font = '18px serif';
        ctx.globalAlpha = 0.18;
        for (let i = 0; i < 16; i++) {
            ctx.fillStyle = ['#ff9ab0','#ffb3d1','#ff6b9d','#ffc0d4'][i%4];
            ctx.fillText(hearts[i%4], x + Math.random()*w, y + Math.random()*h);
        }
        ctx.globalAlpha = 1;
    } else if (overlayType === 'cyberpunk') {
        ctx.strokeStyle = 'rgba(0,255,231,0.12)';
        ctx.lineWidth = 0.5;
        for (let gx = x; gx < x+w; gx += 20) {
            ctx.beginPath(); ctx.moveTo(gx, y); ctx.lineTo(gx, y+h); ctx.stroke();
        }
        for (let gy = y; gy < y+h; gy += 20) {
            ctx.beginPath(); ctx.moveTo(x, gy); ctx.lineTo(x+w, gy); ctx.stroke();
        }
        const cg = ctx.createRadialGradient(x+w/2, y, w*0.6, x+w/2, y, w*1.2);
        cg.addColorStop(0, 'rgba(0,255,231,0.07)');
        cg.addColorStop(1, 'rgba(0,0,0,0)');
        ctx.fillStyle = cg; ctx.fillRect(x, y, w, h);
    } else if (overlayType === 'party') {
        const cols = ['#f472b6','#facc15','#34d399','#60a5fa','#f97316'];
        for (let i = 0; i < 28; i++) {
            ctx.fillStyle = cols[i % cols.length];
            ctx.globalAlpha = 0.6;
            ctx.beginPath();
            ctx.arc(x + Math.random()*w, y + Math.random()*h, Math.random()*4+2, 0, Math.PI*2);
            ctx.fill();
        }
        ctx.globalAlpha = 1;
    }
}

// Draw one photo onto canvas with center-crop to target ratio
function drawPhotoCropped(ctx, img, destX, destY, destW, destH) {
    const srcW = img.naturalWidth  || img.width;
    const srcH = img.naturalHeight || img.height;
    const destRatio = destW / destH;
    const srcRatio  = srcW / srcH;
    let cropX = 0, cropY = 0, cropW = srcW, cropH = srcH;
    if (srcRatio > destRatio) {
        cropW = srcH * destRatio;
        cropX = (srcW - cropW) / 2;
    } else {
        cropH = srcW / destRatio;
        cropY = (srcH - cropH) / 2;
    }
    ctx.drawImage(img, cropX, cropY, cropW, cropH, destX, destY, destW, destH);
}

async function renderStrip(filter) {
    const theme   = getThemeConfig();
    const photos  = state.photos;
    const n       = photos.length;
    if (n === 0) return;

    const isPortrait = state.orientation === 'portrait';
    const bw         = theme.borderWidth || 12;
    const gap        = 10;
    const textAreaH  = 56;

    let stripW, stripH, cols, photoW, photoH;

    if (!isPortrait) {
        // LANDSCAPE: single column, each photo 4:3
        photoW  = 480;
        photoH  = Math.round(photoW * 3 / 4);   // 360
        cols    = 1;
        stripW  = photoW + bw * 2;
        stripH  = bw + n * photoH + (n - 1) * gap + bw + textAreaH;
    } else {
        // PORTRAIT: 2-column grid, each photo 3:4 (center-cropped from landscape cam)
        cols    = n === 1 ? 1 : 2;
        const innerW = 560;
        photoW  = cols === 1 ? innerW : Math.floor((innerW - gap) / 2);
        photoH  = Math.round(photoW * 4 / 3);
        const rows = Math.ceil(n / cols);
        stripW  = innerW + bw * 2;
        stripH  = bw + rows * photoH + (rows - 1) * gap + bw + textAreaH;
    }

    const canvas = document.getElementById('hidden-canvas');
    canvas.width  = stripW;
    canvas.height = stripH;
    const ctx = canvas.getContext('2d');

    // background
    ctx.fillStyle = theme.bgColor;
    ctx.fillRect(0, 0, stripW, stripH);

    if (theme.bgImage && theme.imageType === 'background') {
        await drawImage(ctx, theme.bgImage, 0, 0, stripW, stripH);
    }

    // draw photos in grid
    for (let i = 0; i < n; i++) {
        const col = i % cols;
        const row = Math.floor(i / cols);
        const px  = bw + col * (photoW + gap);
        const py  = bw + row * (photoH + gap);

        const img   = await loadImage(photos[i]);
        const fc    = document.createElement('canvas');
        fc.width    = photoW;
        fc.height   = photoH;
        const fctx  = fc.getContext('2d');
        // center-crop so portrait frames look natural
        drawPhotoCropped(fctx, img, 0, 0, photoW, photoH);
        applyFilter(fctx, photoW, photoH, filter);

        ctx.drawImage(fc, px, py, photoW, photoH);
        drawOverlay(ctx, px, py, photoW, photoH, theme.overlayType, theme.borderColor);
    }

    // border frame
    ctx.strokeStyle = theme.borderColor;
    ctx.lineWidth   = bw;
    ctx.strokeRect(bw / 2, bw / 2, stripW - bw, stripH - textAreaH - bw / 2);

    // text area
    const textY = stripH - textAreaH;
    ctx.fillStyle = theme.bgColor;
    ctx.fillRect(0, textY, stripW, textAreaH);

    const fontName = theme.font || 'DM Sans';
    ctx.fillStyle  = theme.textColor;
    ctx.font       = `600 18px "${fontName}", sans-serif`;
    ctx.textAlign  = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(state.frameText || 'Photobooth 2026', stripW / 2, textY + textAreaH / 2 - 4);

    const dateStr = new Date().toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });
    ctx.font       = `400 11px "${fontName}", sans-serif`;
    ctx.globalAlpha = 0.5;
    ctx.fillText(dateStr, stripW / 2, textY + textAreaH / 2 + 14);
    ctx.globalAlpha = 1;

    if (theme.bgImage && theme.imageType === 'overlay') {
        await drawImage(ctx, theme.bgImage, 0, 0, stripW, stripH);
    }

    const dataUrl = canvas.toDataURL('image/png');
    const imgEl   = document.getElementById('final-strip-img');
    imgEl.src     = dataUrl;
    imgEl.style.display = 'block';
    document.getElementById('strip-placeholder').style.display = 'none';
}

function loadImage(src) {
    return new Promise((res, rej) => {
        const img = new Image();
        img.onload = () => res(img);
        img.onerror = rej;
        img.src = src;
    });
}

function drawImage(ctx, src, x, y, w, h) {
    return new Promise((res) => {
        const img = new Image();
        img.onload = () => { ctx.drawImage(img, x, y, w, h); res(); };
        img.onerror = res;
        img.src = src;
    });
}

// ═══════════════════════════════════════════════════════════
//  FILTER
// ═══════════════════════════════════════════════════════════
function setFilter(el, filter) {
    document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    state.currentFilter = filter;
    if (state.photos.length > 0) renderStrip(filter);
}

// ═══════════════════════════════════════════════════════════
//  ACTIONS
// ═══════════════════════════════════════════════════════════
function downloadPhoto() {
    const img = document.getElementById('final-strip-img');
    if (!img.src || img.src === window.location.href) return;
    const a = document.createElement('a');
    a.href = img.src;
    a.download = 'expo_photobooth_' + Date.now() + '.png';
    a.click();
}

function printPhoto() {
    const img = document.getElementById('final-strip-img');
    if (!img.src || img.src === window.location.href) return;
    document.getElementById('print-image').src = img.src;
    window.print();
}

function retakePhoto() {
    if (!confirm('Hapus semua foto dan mulai ulang?')) return;
    state.photos = [];
    state.capturing = false;
    document.getElementById('final-strip-img').style.display = 'none';
    document.getElementById('strip-placeholder').style.display = 'flex';
    document.getElementById('btn-to-result').disabled = true;
    document.getElementById('s3').disabled = true;
    buildProgressDots();
    buildMiniThumbs();
    document.getElementById('capture-status').classList.remove('visible');
    document.getElementById('btn-capture').disabled = (state.stream === null);
    goStep(2);
}

// ═══════════════════════════════════════════════════════════
//  INIT
// ═══════════════════════════════════════════════════════════
buildProgressDots();
buildMiniThumbs();
document.getElementById('frame-text-count').textContent = document.getElementById('frame-text').value.length + '/25';
</script>
</body>
</html>