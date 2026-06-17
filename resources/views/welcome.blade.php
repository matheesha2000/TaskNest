<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskNest — Get things done</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:      #0F1117;
            --ink-soft: #4B5162;
            --ink-mute: #9299AD;
            --surface:  #F7F8FA;
            --white:    #FFFFFF;
            --accent:   #5B5FEF;
            --accent-lt:#EDEDFF;
            --accent-dk:#3F43C7;
            --pro:      #7C3AED;
            --pro-lt:   #F3EEFF;
            --green:    #059669;
            --border:   #E4E6EE;
            --radius:   12px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--white);
            color: var(--ink);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ── NAV ─────────────────────────────────────────── */
        nav {
            position: sticky; top: 0; z-index: 50;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 40px; height: 64px;
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }
        .nav-logo {
            font-family: 'Instrument Serif', serif;
            font-size: 22px; color: var(--ink);
            text-decoration: none; letter-spacing: -0.3px;
        }
        .nav-logo span { color: var(--accent); }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .btn-ghost {
            padding: 8px 18px; border-radius: 8px;
            font-size: 14px; font-weight: 500;
            color: var(--ink-soft); text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }
        .btn-ghost:hover { background: var(--surface); color: var(--ink); }
        .btn-primary {
            padding: 8px 20px; border-radius: 8px;
            font-size: 14px; font-weight: 600;
            background: var(--accent); color: var(--white);
            text-decoration: none;
            transition: background 0.15s, transform 0.1s;
        }
        .btn-primary:hover { background: var(--accent-dk); transform: translateY(-1px); }

        /* ── HERO ─────────────────────────────────────────── */
        .hero {
            min-height: calc(100vh - 64px);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center;
            padding: 80px 24px 60px;
            position: relative;
            overflow: hidden;
        }

        /* Signature: animated grid of tasks in background */
        .hero-grid {
            position: absolute; inset: 0;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px; padding: 20px;
            opacity: 0.045;
            pointer-events: none;
            transform: rotate(-5deg) scale(1.15);
        }
        .hero-grid-card {
            background: var(--ink);
            border-radius: 8px; height: 52px;
            animation: floatCard 6s ease-in-out infinite;
        }
        .hero-grid-card:nth-child(odd)  { animation-delay: -2s; }
        .hero-grid-card:nth-child(3n)   { animation-delay: -4s; height: 36px; }
        .hero-grid-card:nth-child(4n)   { animation-delay: -1s; height: 64px; }
        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }

        .hero-eyebrow {
            display: inline-flex; align-items: center; gap-6px;
            background: var(--accent-lt); color: var(--accent);
            font-size: 12px; font-weight: 600; letter-spacing: 0.06em;
            text-transform: uppercase; padding: 5px 14px;
            border-radius: 100px; margin-bottom: 28px;
            border: 1px solid rgba(91,95,239,0.2);
        }
        .hero-eyebrow::before {
            content: ''; display: inline-block;
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--accent); margin-right: 8px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.8); }
        }

        .hero-title {
            font-family: 'Instrument Serif', serif;
            font-size: clamp(44px, 7vw, 80px);
            line-height: 1.05; letter-spacing: -1.5px;
            color: var(--ink); margin-bottom: 20px;
            max-width: 780px;
        }
        .hero-title em {
            font-style: italic; color: var(--accent);
        }
        .hero-sub {
            font-size: clamp(16px, 2vw, 19px);
            color: var(--ink-soft); max-width: 500px;
            margin-bottom: 40px; line-height: 1.65;
        }

        .hero-cta {
            display: flex; align-items: center;
            gap: 12px; flex-wrap: wrap; justify-content: center;
        }
        .btn-hero {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 28px; border-radius: 10px;
            font-size: 15px; font-weight: 600;
            text-decoration: none; transition: all 0.15s;
        }
        .btn-hero-primary {
            background: var(--accent); color: var(--white);
            box-shadow: 0 4px 20px rgba(91,95,239,0.35);
        }
        .btn-hero-primary:hover {
            background: var(--accent-dk);
            box-shadow: 0 6px 28px rgba(91,95,239,0.45);
            transform: translateY(-2px);
        }
        .btn-hero-secondary {
            background: var(--white); color: var(--ink);
            border: 1.5px solid var(--border);
        }
        .btn-hero-secondary:hover {
            border-color: var(--accent); color: var(--accent);
            transform: translateY(-1px);
        }
        .arrow { font-size: 18px; line-height: 1; transition: transform 0.15s; }
        .btn-hero-primary:hover .arrow { transform: translateX(3px); }

        .hero-trust {
            margin-top: 48px;
            display: flex; align-items: center; gap: 20px;
            font-size: 13px; color: var(--ink-mute);
            flex-wrap: wrap; justify-content: center;
        }
        .hero-trust-item { display: flex; align-items: center; gap: 6px; }
        .hero-trust-item::before {
            content: '✓'; font-size: 12px; font-weight: 700;
            color: var(--green);
        }

        /* ── TASK PREVIEW ────────────────────────────────── */
        .preview-section {
            background: var(--surface);
            padding: 80px 24px;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .preview-inner { max-width: 960px; margin: 0 auto; }
        .preview-label {
            font-size: 12px; font-weight: 600; letter-spacing: 0.07em;
            text-transform: uppercase; color: var(--ink-mute); margin-bottom: 12px;
        }
        .preview-title {
            font-family: 'Instrument Serif', serif;
            font-size: clamp(28px, 4vw, 42px);
            letter-spacing: -0.5px; margin-bottom: 14px;
        }
        .preview-sub {
            font-size: 16px; color: var(--ink-soft); margin-bottom: 48px; max-width: 540px;
        }

        /* Fake task board */
        .task-board {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;
        }
        .task-col-head {
            font-size: 12px; font-weight: 600; letter-spacing: 0.04em;
            text-transform: uppercase; color: var(--ink-mute);
            margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
        }
        .col-count {
            background: var(--border); color: var(--ink-soft);
            font-size: 11px; font-weight: 700;
            padding: 1px 7px; border-radius: 100px;
        }
        .task-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 14px 16px; margin-bottom: 10px;
            transition: box-shadow 0.15s, transform 0.15s;
        }
        .task-card:hover {
            box-shadow: 0 4px 16px rgba(15,17,23,0.08);
            transform: translateY(-2px);
        }
        .task-card-title {
            font-size: 13px; font-weight: 500; color: var(--ink);
            margin-bottom: 8px; line-height: 1.4;
        }
        .task-card-title.done {
            text-decoration: line-through; color: var(--ink-mute);
        }
        .task-meta { display: flex; align-items: center; justify-content: space-between; }
        .chip {
            font-size: 10px; font-weight: 600; letter-spacing: 0.04em;
            padding: 2px 8px; border-radius: 100px; text-transform: uppercase;
        }
        .chip-high   { background: #FEE2E2; color: #B91C1C; }
        .chip-medium { background: #FEF3C7; color: #92400E; }
        .chip-low    { background: #DCFCE7; color: #166534; }
        .chip-pro    { background: var(--pro-lt); color: var(--pro); }
        .due-date { font-size: 11px; color: var(--ink-mute); }
        .due-date.overdue { color: #DC2626; font-weight: 500; }

        /* ── FEATURES ────────────────────────────────────── */
        .features-section { padding: 100px 24px; }
        .features-inner { max-width: 1040px; margin: 0 auto; }
        .section-header { text-align: center; margin-bottom: 64px; }
        .section-eyebrow {
            font-size: 12px; font-weight: 600; letter-spacing: 0.07em;
            text-transform: uppercase; color: var(--accent); margin-bottom: 10px;
        }
        .section-title {
            font-family: 'Instrument Serif', serif;
            font-size: clamp(28px, 4vw, 44px);
            letter-spacing: -0.5px; margin-bottom: 14px;
        }
        .section-sub { font-size: 16px; color: var(--ink-soft); max-width: 480px; margin: 0 auto; }

        .features-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px;
            background: var(--border); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
        }
        .feature-cell {
            background: var(--white);
            padding: 32px 28px;
            transition: background 0.15s;
        }
        .feature-cell:hover { background: var(--surface); }
        .feature-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--accent-lt); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; margin-bottom: 16px;
        }
        .feature-icon.purple { background: var(--pro-lt); color: var(--pro); }
        .feature-icon.green  { background: #DCFCE7; color: var(--green); }
        .feature-title { font-size: 15px; font-weight: 600; margin-bottom: 8px; }
        .feature-desc  { font-size: 13px; color: var(--ink-soft); line-height: 1.6; }
        .feature-pro   {
            display: inline-block; font-size: 10px; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            color: var(--pro); background: var(--pro-lt);
            padding: 2px 8px; border-radius: 100px; margin-top: 10px;
        }

        /* ── PRICING ────────────────────────────────────── */
        .pricing-section {
            background: var(--surface);
            padding: 100px 24px;
            border-top: 1px solid var(--border);
        }
        .pricing-inner { max-width: 800px; margin: 0 auto; }
        .pricing-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 48px; }

        .plan-card {
            background: var(--white); border: 1.5px solid var(--border);
            border-radius: 16px; padding: 32px;
            position: relative; overflow: hidden;
        }
        .plan-card.featured {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(91,95,239,0.08);
        }
        .plan-badge {
            position: absolute; top: 20px; right: 20px;
            background: var(--accent); color: var(--white);
            font-size: 10px; font-weight: 700; letter-spacing: 0.06em;
            text-transform: uppercase; padding: 3px 10px; border-radius: 100px;
        }
        .plan-name { font-size: 13px; font-weight: 600; color: var(--ink-mute); text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 8px; }
        .plan-price {
            font-family: 'Instrument Serif', serif;
            font-size: 44px; letter-spacing: -1px; color: var(--ink); line-height: 1;
        }
        .plan-price span { font-size: 18px; font-family: 'Inter', sans-serif; color: var(--ink-mute); }
        .plan-desc { font-size: 13px; color: var(--ink-soft); margin: 12px 0 24px; }
        .plan-features { list-style: none; margin-bottom: 28px; }
        .plan-features li {
            font-size: 13px; color: var(--ink-soft);
            padding: 7px 0; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .plan-features li:last-child { border-bottom: none; }
        .plan-features li::before { content: '✓'; color: var(--green); font-weight: 700; }
        .plan-features li.locked::before { content: '—'; color: var(--border); font-weight: 400; }
        .plan-features li.locked { color: var(--ink-mute); }

        .btn-plan {
            display: block; text-align: center; width: 100%;
            padding: 12px; border-radius: 9px;
            font-size: 14px; font-weight: 600; text-decoration: none;
            transition: all 0.15s;
        }
        .btn-plan-outline {
            border: 1.5px solid var(--border); color: var(--ink);
        }
        .btn-plan-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-plan-filled {
            background: var(--accent); color: var(--white);
            box-shadow: 0 4px 16px rgba(91,95,239,0.3);
        }
        .btn-plan-filled:hover { background: var(--accent-dk); transform: translateY(-1px); }

        /* ── CTA BANNER ──────────────────────────────────── */
        .cta-section {
            padding: 100px 24px;
            background: var(--ink);
            text-align: center;
            position: relative; overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute; top: -60%; left: 50%;
            transform: translateX(-50%);
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(91,95,239,0.25) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-title {
            font-family: 'Instrument Serif', serif;
            font-size: clamp(32px, 5vw, 56px);
            color: var(--white); letter-spacing: -1px;
            margin-bottom: 16px; position: relative;
        }
        .cta-title em { font-style: italic; color: #A5B4FC; }
        .cta-sub { font-size: 16px; color: rgba(255,255,255,0.55); margin-bottom: 36px; position: relative; }
        .btn-cta {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 32px; border-radius: 10px;
            font-size: 15px; font-weight: 600;
            background: var(--white); color: var(--ink);
            text-decoration: none; position: relative;
            transition: all 0.15s;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,0.4); }

        /* ── FOOTER ──────────────────────────────────────── */
        footer {
            background: var(--ink); border-top: 1px solid rgba(255,255,255,0.06);
            padding: 28px 40px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }
        .footer-logo {
            font-family: 'Instrument Serif', serif;
            font-size: 18px; color: var(--white); text-decoration: none;
        }
        .footer-logo span { color: #818CF8; }
        footer p { font-size: 12px; color: rgba(255,255,255,0.3); }

        /* ── RESPONSIVE ──────────────────────────────────── */
        @media (max-width: 768px) {
            nav { padding: 0 20px; }
            .hero { padding: 60px 20px; }
            .task-board { grid-template-columns: 1fr; }
            .features-grid { grid-template-columns: 1fr; }
            .pricing-grid { grid-template-columns: 1fr; }
            .task-board .task-col:nth-child(n+2) { display: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav>
    <a href="/" class="nav-logo">Task<span>Nest</span></a>
    <div class="nav-links">
        @auth
            <a href="{{ route('dashboard') }}" class="btn-ghost">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn-ghost">Sign in</a>
            <a href="{{ route('register') }}" class="btn-primary">Get started free</a>
        @endauth
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-grid" aria-hidden="true">
        @for($i = 0; $i < 40; $i++)
            <div class="hero-grid-card"></div>
        @endfor
    </div>

    <div class="hero-eyebrow">Free to start — no card required</div>

    <h1 class="hero-title">
        Every task, every<br>
        deadline, <em>under control</em>
    </h1>
    <p class="hero-sub">
        TaskNest helps you organize your work, track progress, and hit deadlines — with a clean interface that gets out of your way.
    </p>

    <div class="hero-cta">
        @auth
            <a href="{{ route('dashboard') }}" class="btn-hero btn-hero-primary">
                Go to dashboard <span class="arrow">→</span>
            </a>
        @else
            <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">
                Start for free <span class="arrow">→</span>
            </a>
            <a href="{{ route('login') }}" class="btn-hero btn-hero-secondary">
                Sign in
            </a>
        @endauth
    </div>

    <div class="hero-trust">
        <div class="hero-trust-item">No credit card required</div>
        <div class="hero-trust-item">Free plan forever</div>
        <div class="hero-trust-item">Upgrade anytime</div>
    </div>
</section>

<!-- TASK PREVIEW -->
<section class="preview-section">
    <div class="preview-inner">
        <p class="preview-label">How it works</p>
        <h2 class="preview-title">Your tasks, organized the way you think</h2>
        <p class="preview-sub">Create tasks, set due dates, and move them through stages as you work. Pro users get priorities, categories, and smart filters on top.</p>

        <div class="task-board">
            <!-- Pending -->
            <div class="task-col">
                <div class="task-col-head">
                    Pending <span class="col-count">3</span>
                </div>
                <div class="task-card">
                    <p class="task-card-title">Write Q3 project proposal</p>
                    <div class="task-meta">
                        <span class="chip chip-high">High</span>
                        <span class="due-date overdue">Aug 28 · overdue</span>
                    </div>
                </div>
                <div class="task-card">
                    <p class="task-card-title">Review pull requests for auth module</p>
                    <div class="task-meta">
                        <span class="chip chip-medium">Medium</span>
                        <span class="due-date">Sep 5</span>
                    </div>
                </div>
                <div class="task-card">
                    <p class="task-card-title">Set up staging environment</p>
                    <div class="task-meta">
                        <span class="chip chip-low">Low</span>
                        <span class="due-date">Sep 12</span>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="task-col">
                <div class="task-col-head">
                    In Progress <span class="col-count">2</span>
                </div>
                <div class="task-card">
                    <p class="task-card-title">Build payment integration with Stripe</p>
                    <div class="task-meta">
                        <span class="chip chip-high">High</span>
                        <span class="due-date">Sep 3</span>
                    </div>
                </div>
                <div class="task-card">
                    <p class="task-card-title">Update user onboarding flow</p>
                    <div class="task-meta">
                        <span class="chip chip-medium">Medium</span>
                        <span class="due-date">Sep 6</span>
                    </div>
                </div>
                <div class="task-card" style="border-style: dashed; opacity: 0.4;">
                    <p class="task-card-title" style="color: var(--ink-mute); font-size:12px;">Drop a task here…</p>
                </div>
            </div>

            <!-- Completed -->
            <div class="task-col">
                <div class="task-col-head">
                    Completed <span class="col-count">3</span>
                </div>
                <div class="task-card" style="opacity: 0.7;">
                    <p class="task-card-title done">Design system tokens setup</p>
                    <div class="task-meta">
                        <span class="chip chip-pro">Pro</span>
                        <span class="due-date">Done</span>
                    </div>
                </div>
                <div class="task-card" style="opacity: 0.7;">
                    <p class="task-card-title done">Database schema finalized</p>
                    <div class="task-meta">
                        <span class="chip chip-low">Low</span>
                        <span class="due-date">Done</span>
                    </div>
                </div>
                <div class="task-card" style="opacity: 0.7;">
                    <p class="task-card-title done">Laravel Breeze authentication</p>
                    <div class="task-meta">
                        <span class="chip chip-medium">Medium</span>
                        <span class="due-date">Done</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features-section">
    <div class="features-inner">
        <div class="section-header">
            <p class="section-eyebrow">Features</p>
            <h2 class="section-title">Everything you need to stay on track</h2>
            <p class="section-sub">Built for individuals who want to stop losing tasks in notebooks and spreadsheets.</p>
        </div>

        <div class="features-grid">
            <div class="feature-cell">
                <div class="feature-icon">✓</div>
                <h3 class="feature-title">Task Management</h3>
                <p class="feature-desc">Create, edit, and organize tasks with titles, descriptions, due dates, and status tracking across Pending, In Progress, and Completed.</p>
            </div>
            <div class="feature-cell">
                <div class="feature-icon">📅</div>
                <h3 class="feature-title">Due Date Tracking</h3>
                <p class="feature-desc">Set deadlines on any task. Overdue tasks are highlighted automatically so nothing slips through the cracks.</p>
            </div>
            <div class="feature-cell">
                <div class="feature-icon purple">🔒</div>
                <h3 class="feature-title">Priority Levels</h3>
                <p class="feature-desc">Mark tasks as High, Medium, or Low priority to focus on what actually matters today.</p>
                <span class="feature-pro">Pro only</span>
            </div>
            <div class="feature-cell">
                <div class="feature-icon purple">🏷</div>
                <h3 class="feature-title">Categories</h3>
                <p class="feature-desc">Group tasks by project, context, or any label that makes sense to you. Filter your list in one click.</p>
                <span class="feature-pro">Pro only</span>
            </div>
            <div class="feature-cell">
                <div class="feature-icon purple">📊</div>
                <h3 class="feature-title">Dashboard Analytics</h3>
                <p class="feature-desc">See a live breakdown of your task progress — total, pending, in progress, completed, and overdue — all in one view.</p>
                <span class="feature-pro">Pro only</span>
            </div>
            <div class="feature-cell">
                <div class="feature-icon green">🔐</div>
                <h3 class="feature-title">Secure & Private</h3>
                <p class="feature-desc">Your tasks are private to you. Built on Laravel with Breeze authentication — industry-standard password hashing and session security.</p>
            </div>
        </div>
    </div>
</section>

<!-- PRICING -->
<section class="pricing-section">
    <div class="pricing-inner">
        <div class="section-header">
            <p class="section-eyebrow">Pricing</p>
            <h2 class="section-title">Start free, upgrade when you're ready</h2>
            <p class="section-sub">No surprise charges. Cancel or downgrade any time.</p>
        </div>

        <div class="pricing-grid">
            <!-- Free plan -->
            <div class="plan-card">
                <p class="plan-name">Free</p>
                <p class="plan-price">$0 <span>/ forever</span></p>
                <p class="plan-desc">Great for personal use and getting started.</p>
                <ul class="plan-features">
                    <li>Up to 10 tasks</li>
                    <li>Pending / In Progress / Completed</li>
                    <li>Due date tracking</li>
                    <li class="locked">Priority levels</li>
                    <li class="locked">Categories & filters</li>
                    <li class="locked">Dashboard analytics</li>
                    <li class="locked">Overdue notifications</li>
                </ul>
                <a href="{{ route('register') }}" class="btn-plan btn-plan-outline">Get started free</a>
            </div>

            <!-- Pro plan -->
            <div class="plan-card featured">
                <div class="plan-badge">Most popular</div>
                <p class="plan-name">Pro</p>
                <p class="plan-price">$9 <span>.99 / month</span></p>
                <p class="plan-desc">For people serious about getting things done.</p>
                <ul class="plan-features">
                    <li>Unlimited tasks</li>
                    <li>Pending / In Progress / Completed</li>
                    <li>Due date tracking</li>
                    <li>Priority levels (High / Medium / Low)</li>
                    <li>Categories & smart filters</li>
                    <li>Dashboard analytics</li>
                    <li>Overdue task notifications</li>
                </ul>
                <a href="{{ route('register') }}" class="btn-plan btn-plan-filled">Start Pro — $9.99/mo</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA BANNER -->
<section class="cta-section">
    <h2 class="cta-title">Ready to clear your head?<br><em>Start organizing today.</em></h2>
    <p class="cta-sub">Takes 30 seconds to sign up. No credit card needed.</p>
    @auth
        <a href="{{ route('dashboard') }}" class="btn-cta">Open dashboard →</a>
    @else
        <a href="{{ route('register') }}" class="btn-cta">Create free account →</a>
    @endauth
</section>

<!-- FOOTER -->
<footer>
    <a href="/" class="footer-logo">Task<span>Nest</span></a>
    <p>Built with Laravel · &copy; {{ date('Y') }} TaskFlow. All rights reserved.</p>
</footer>

</body>
</html>