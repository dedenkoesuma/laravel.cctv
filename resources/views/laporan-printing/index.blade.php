<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Printing — Toko Print</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
  :root {
    --bg: #090D16;
    --bg-subtle: #0B0F19;
    --panel: #111827;
    --panel-raised: #1A2234;
    --panel-hover: #1F293D;
    --line: rgba(255, 255, 255, 0.08);
    --line-soft: rgba(255, 255, 255, 0.04);
    --text: #F8FAFC;
    --text-dim: #94A3B8;
    --text-faint: #64748B;
    --primary: #DC2626;
    --primary-glow: rgba(220, 38, 38, 0.25);
    --amber: #F59E0B;
    --amber-glow: rgba(245, 158, 11, 0.2);
    --green: #10B981;
    --green-glow: rgba(16, 185, 129, 0.2);
    --red: #EF4444;
    --red-glow: rgba(239, 68, 68, 0.2);
    --violet: #8B5CF6;
    --violet-glow: rgba(139, 92, 246, 0.2);
    --cyan: #06B6D4;
    --radius-s: 6px;
    --radius-m: 12px;
    --radius-l: 16px;
    --ease: cubic-bezier(.22, .9, .34, 1);
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    background: var(--bg);
    color: var(--text);
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-size: 14px;
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
  }
  .mono { font-family: 'JetBrains Mono', monospace; }
  a { color: inherit; text-decoration: none; }

  /* Background Ambient Glow */
  .ambient {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    background: radial-gradient(720px 420px at 15% -5%, rgba(220, 38, 38, 0.08), transparent 65%),
                radial-gradient(640px 380px at 85% 10%, rgba(139, 92, 246, 0.06), transparent 65%),
                radial-gradient(600px 360px at 50% 90%, rgba(16, 185, 129, 0.04), transparent 65%);
    opacity: 0;
    animation: ambientIn 2s var(--ease) forwards;
  }
  @keyframes ambientIn { to { opacity: 1; } }

  .shell { display: flex; min-height: 100vh; position: relative; z-index: 1; }

  /* Sidebar Modern Executive */
  .sidebar {
    width: 240px;
    min-width: 240px;
    background: #090D16;
    border-right: 1px solid var(--line);
    display: flex;
    flex-direction: column;
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
    z-index: 20;
  }
  .sb-brand {
    padding: 1.25rem 1.2rem;
    border-bottom: 1px solid var(--line);
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .brand-logo-box {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #fff;
    box-shadow: 0 4px 12px var(--primary-glow);
    flex-shrink: 0;
  }
  .brand-name { font-size: 15px; font-weight: 700; color: #fff; letter-spacing: -0.01em; }
  .brand-sub { font-size: 11px; color: var(--text-faint); font-weight: 500; }

  .sb-nav { padding: 1rem 0.75rem; flex: 1; display: flex; flex-direction: column; gap: 4px; }
  .sb-section {
    font-size: 10px;
    font-weight: 700;
    color: var(--text-faint);
    padding: 0.85rem 0.65rem 0.35rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }
  .sb-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-dim);
    border-radius: var(--radius-s);
    transition: all 0.2s var(--ease);
    position: relative;
  }
  .sb-item i { font-size: 15px; width: 18px; text-align: center; }
  .sb-item:hover {
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    transform: translateX(2px);
  }
  .sb-item.active {
    background: rgba(220, 38, 38, 0.12);
    color: #fff;
    font-weight: 600;
  }
  .sb-item.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 6px;
    bottom: 6px;
    width: 3px;
    background: var(--primary);
    border-radius: 0 3px 3px 0;
  }
  .sb-badge {
    margin-left: auto;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 9999px;
    background: rgba(220, 38, 38, 0.18);
    color: #F87171;
    border: 1px solid rgba(220, 38, 38, 0.3);
  }
  .sb-badge-cctv {
    background: rgba(37, 99, 235, 0.18);
    color: #60A5FA;
    border: 1px solid rgba(37, 99, 235, 0.3);
  }

  /* Content Area */
  .content {
    flex: 1;
    padding: 28px 36px 80px;
    position: relative;
    z-index: 1;
    overflow-x: hidden;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  @keyframes scanLine { to { width: 340px; } }
  @keyframes markPulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, .3); } 50% { box-shadow: 0 0 0 6px rgba(245, 158, 11, 0); } }
  @keyframes fillBar { from { transform: scaleX(0); } to { transform: scaleX(1); } }

  /* Topbar */
  .topbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 22px;
    border-bottom: 1px solid var(--line);
    margin-bottom: 26px;
    position: relative;
  }
  .topbar::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -1px;
    height: 1px;
    width: 0;
    background: linear-gradient(90deg, var(--primary), var(--amber), transparent);
    animation: scanLine 1.2s var(--ease) forwards;
  }
  .topbar .id-line {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-faint);
    font-size: 11.5px;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    margin-bottom: 8px;
    opacity: 0;
    animation: fadeUp .5s var(--ease) forwards;
  }
  .id-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--primary);
    box-shadow: 0 0 8px var(--primary-glow);
  }
  .topbar h1 {
    font-size: 26px;
    font-weight: 700;
    letter-spacing: -0.02em;
    font-family: 'Space Grotesk', sans-serif;
    color: #fff;
    opacity: 0;
    animation: fadeUp .55s var(--ease) forwards;
    animation-delay: .05s;
  }
  .topbar .sub {
    color: var(--text-dim);
    font-size: 13px;
    margin-top: 4px;
    max-width: 520px;
    opacity: 0;
    animation: fadeUp .55s var(--ease) forwards;
    animation-delay: .1s;
  }
  .topbar-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    opacity: 0;
    animation: fadeUp .5s var(--ease) forwards;
    animation-delay: .15s;
  }

  /* Buttons */
  .btn {
    border: none;
    padding: 8px 16px;
    border-radius: 9999px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    font-family: 'Plus Jakarta Sans', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all .2s var(--ease);
    text-decoration: none;
  }
  .btn:hover { transform: translateY(-1px); }
  .btn:active { transform: translateY(0) scale(.98); }
  
  .btn-dashboard {
    background: linear-gradient(135deg, rgba(220, 38, 38, 0.15) 0%, rgba(15, 23, 42, 0.8) 100%);
    color: #F87171;
    border: 1px solid rgba(220, 38, 38, 0.35);
    box-shadow: 0 4px 14px rgba(220, 38, 38, 0.2);
  }
  .btn-dashboard:hover {
    background: rgba(220, 38, 38, 0.25);
    color: #fff;
    border-color: rgba(220, 38, 38, 0.6);
  }

  .btn-primary {
    background: linear-gradient(135deg, var(--amber) 0%, #D97706 100%);
    color: #111;
    font-weight: 700;
    box-shadow: 0 4px 14px var(--amber-glow);
  }
  .btn-primary:hover {
    filter: brightness(1.1);
    box-shadow: 0 6px 18px rgba(245, 158, 11, 0.35);
  }

  .btn-ghost {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--line);
    color: var(--text-dim);
  }
  .btn-ghost:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border-color: rgba(255, 255, 255, 0.15);
  }

  /* Filter form modern */
  .filter-form {
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: var(--radius-m);
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 14px;
    opacity: 0;
    animation: fadeUp .5s var(--ease) forwards;
    animation-delay: .18s;
    box-shadow: 0 8px 24px -6px rgba(0, 0, 0, 0.3);
  }
  .filter-form label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-faint);
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }
  .filter-form select,
  .filter-form input[type=date] {
    background: var(--panel-raised);
    border: 1px solid var(--line);
    color: var(--text);
    font-family: 'JetBrains Mono', monospace;
    font-size: 12.5px;
    padding: 8px 14px;
    border-radius: 8px;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
  }
  .filter-form select:focus,
  .filter-form input:focus {
    border-color: var(--amber);
    box-shadow: 0 0 0 3px var(--amber-glow);
  }
  .filter-label {
    font-size: 12px;
    color: var(--text-faint);
    margin-left: auto;
    align-self: center;
    font-family: 'JetBrains Mono', monospace;
  }

  /* Summary 5 Cards */
  .summary-band {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 16px;
    opacity: 0;
    animation: fadeUp .55s var(--ease) forwards;
    animation-delay: .24s;
  }
  .sum-card {
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: var(--radius-m);
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    position: relative;
    overflow: hidden;
    transition: transform .2s var(--ease), border-color .2s;
  }
  .sum-card:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.15);
  }
  .sum-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--line);
  }
  .sum-card.accent-amber::before { background: var(--amber); }
  .sum-card.accent-green::before { background: var(--green); }
  .sum-card.accent-red::before { background: var(--red); }
  .sum-card.accent-violet::before { background: var(--violet); }

  .sum-card .lbl {
    font-size: 11.5px;
    font-weight: 600;
    color: var(--text-dim);
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }
  .sum-card .val {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 21px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
    color: var(--text);
  }
  .sum-card .val.green { color: var(--green); }
  .sum-card .val.red { color: var(--red); }
  .sum-card .val.violet { color: var(--violet); }
  .sum-card .note { font-size: 11px; color: var(--text-faint); }

  /* Hero Laba Rugi Card */
  .hero-laba-card {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, var(--panel) 100%);
    border: 1px solid rgba(16, 185, 129, 0.25);
    border-radius: var(--radius-l);
    padding: 24px 28px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    opacity: 0;
    animation: fadeUp .55s var(--ease) forwards;
    animation-delay: .3s;
    box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.3);
  }
  .hero-laba-card.neg {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.08) 0%, var(--panel) 100%);
    border-color: rgba(239, 68, 68, 0.25);
  }
  .hero-laba-left { display: flex; align-items: center; gap: 18px; }
  .hero-laba-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: rgba(16, 185, 129, 0.15);
    color: var(--green);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    border: 1px solid rgba(16, 185, 129, 0.3);
  }
  .hero-laba-card.neg .hero-laba-icon {
    background: rgba(239, 68, 68, 0.15);
    color: var(--red);
    border-color: rgba(239, 68, 68, 0.3);
  }
  .hero-laba-title { font-size: 13px; font-weight: 600; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.05em; }
  .hero-laba-val {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 32px;
    font-weight: 800;
    letter-spacing: -0.02em;
    font-variant-numeric: tabular-nums;
  }
  .hero-laba-val.pos { color: var(--green); }
  .hero-laba-val.neg { color: var(--red); }
  .hero-laba-badge {
    padding: 6px 14px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid var(--line);
    color: var(--text-dim);
  }

  /* Lembar Paper Card */
  .lembar-card {
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: var(--radius-m);
    padding: 18px 24px;
    margin-bottom: 26px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    opacity: 0;
    animation: fadeUp .55s var(--ease) forwards;
    animation-delay: .34s;
  }
  .lembar-card .lbl { font-size: 12px; font-weight: 600; color: var(--text-dim); }
  .lembar-card .val {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 26px;
    font-weight: 700;
    color: var(--violet);
    font-variant-numeric: tabular-nums;
  }
  .lembar-card .note { font-size: 11px; color: var(--text-faint); }

  /* Sections & Split Grid */
  .section { margin-bottom: 28px; }
  .section-head {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 14px;
  }
  .section-head h2 {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .section-head .count {
    font-family: 'JetBrains Mono', monospace;
    font-size: 12px;
    color: var(--text-faint);
    background: rgba(255, 255, 255, 0.05);
    padding: 3px 10px;
    border-radius: 9999px;
  }
  .split { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

  /* Gauge Panel */
  .gauge-panel {
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: var(--radius-m);
    padding: 20px 22px;
    opacity: 0;
    animation: fadeUp .55s var(--ease) forwards;
    animation-delay: .38s;
  }
  .gauge-row {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2px 12px;
    padding: 12px 0;
    border-bottom: 1px solid var(--line-soft);
  }
  .gauge-row:last-child { border-bottom: none; }
  .gauge-row .name { font-size: 13px; font-weight: 600; color: var(--text); }
  .gauge-row .gcount { font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 600; color: var(--amber); text-align: right; }
  .gauge-track {
    grid-column: 1/-1;
    height: 6px;
    background: rgba(255, 255, 255, 0.06);
    border-radius: 9999px;
    overflow: hidden;
    margin-top: 6px;
  }
  .gauge-fill {
    height: 100%;
    background: linear-gradient(90deg, #F59E0B, #DC2626);
    border-radius: 9999px;
    transform-origin: left;
    transform: scaleX(0);
    animation: fillBar 1s var(--ease) forwards;
  }

  /* Platform Panel */
  .platform-panel {
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: var(--radius-m);
    padding: 20px 22px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    opacity: 0;
    animation: fadeUp .55s var(--ease) forwards;
    animation-delay: .42s;
  }
  .platform-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 18px;
    background: var(--panel-raised);
    border-radius: var(--radius-s);
    border: 1px solid var(--line);
    transition: all .2s;
  }
  .platform-row:hover {
    border-color: var(--violet);
    transform: translateX(2px);
  }
  .platform-name {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    font-size: 13.5px;
    color: #fff;
  }
  .platform-swatch {
    width: 10px;
    height: 10px;
    border-radius: 3px;
    background: var(--violet);
    box-shadow: 0 0 8px var(--violet-glow);
  }
  .platform-figs { display: flex; gap: 24px; text-align: right; }
  .platform-figs .k { font-size: 10.5px; color: var(--text-faint); margin-bottom: 2px; text-transform: uppercase; font-weight: 600; }
  .platform-figs .v { font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 600; font-variant-numeric: tabular-nums; color: var(--text); }
  .platform-empty { font-size: 12.5px; color: var(--text-faint); padding: 20px; text-align: center; }

  /* Table Modern */
  .tbl-panel {
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: var(--radius-m);
    overflow: hidden;
    opacity: 0;
    animation: fadeUp .55s var(--ease) forwards;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
  }
  table { width: 100%; border-collapse: collapse; }
  thead th {
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-faint);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 13px 18px;
    background: var(--panel-raised);
    border-bottom: 1px solid var(--line);
  }
  tbody td {
    padding: 12px 18px;
    border-bottom: 1px solid var(--line-soft);
    font-size: 13px;
    vertical-align: middle;
  }
  tbody tr:last-child td { border-bottom: none; }
  tbody tr:hover { background: var(--panel-hover); }
  .ord { font-family: 'JetBrains Mono', monospace; color: #60A5FA; font-weight: 600; font-size: 12.5px; }
  .tag {
    display: inline-flex;
    align-items: center;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 20px;
    background: rgba(139, 92, 246, 0.15);
    color: #C4B5FD;
    border: 1px solid rgba(139, 92, 246, 0.3);
  }
  .st {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
  }
  .st::before { content: ''; width: 7px; height: 7px; border-radius: 50%; }
  .st-proses { color: var(--amber); }
  .st-proses::before { background: var(--amber); animation: markPulse 2s ease-in-out infinite; }
  .st-selesai { color: var(--green); }
  .st-selesai::before { background: var(--green); }
  .st-batal { color: var(--red); }
  .st-batal::before { background: var(--red); }
  .dim { color: var(--text-faint); font-size: 12px; font-family: 'JetBrains Mono', monospace; }

  /* Ledger Panels */
  .ledger-panel {
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: var(--radius-m);
    overflow: hidden;
    opacity: 0;
    animation: fadeUp .55s var(--ease) forwards;
  }
  .ledger-row {
    display: grid;
    grid-template-columns: 60px 1fr auto;
    gap: 12px;
    align-items: center;
    padding: 12px 18px;
    border-bottom: 1px solid var(--line-soft);
    transition: background .15s;
  }
  .ledger-row:hover { background: var(--panel-hover); }
  .ledger-row:last-child { border-bottom: none; }
  .l-date { font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 600; color: var(--text-faint); }
  .l-cat { font-size: 13px; font-weight: 600; color: #fff; }
  .l-ref { font-size: 11.5px; color: var(--text-faint); margin-top: 1px; }
  .l-amt {
    font-family: 'JetBrains Mono', monospace;
    font-size: 13.5px;
    font-weight: 700;
    text-align: right;
    font-variant-numeric: tabular-nums;
  }
  .l-amt.in { color: var(--green); }
  .l-amt.in::before { content: '+ '; }
  .l-amt.out { color: var(--red); }
  .l-amt.out::before { content: '– '; }

  .empty-state { padding: 40px 20px; text-align: center; color: var(--text-faint); font-size: 12.5px; }
  .empty-state .glyph { font-family: 'JetBrains Mono', monospace; font-size: 18px; margin-bottom: 8px; opacity: 0.5; }

  .footer-note {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid var(--line);
    font-size: 11.5px;
    color: var(--text-faint);
    display: flex;
    justify-content: space-between;
    opacity: 0;
    animation: fadeUp .5s var(--ease) forwards;
    animation-delay: 1.2s;
  }

  @media print {
    .sidebar, .no-print, .ambient { display: none !important; }
    body { background: #fff; color: #000; }
    .content { padding: 20px; }
  }
  @media (max-width: 992px) {
    .summary-band { grid-template-columns: repeat(2, 1fr); }
    .split { grid-template-columns: 1fr; }
  }
  @media (max-width: 768px) {
    .sidebar { display: none; }
    .content { padding: 20px 16px; }
    .summary-band { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>
<div class="ambient"></div>
<div class="shell">

  {{-- Sidebar Executive --}}
  <div class="sidebar no-print">
    <div class="sb-brand">
      <div class="brand-logo-box">
        <i class="bi bi-printer-fill"></i>
      </div>
      <div>
        <div class="brand-name">Toko Print</div>
        <div class="brand-sub">Management Suite</div>
      </div>
    </div>

    <div class="sb-nav">
      <div class="sb-section">Utama</div>
      <a href="{{ route('admin.dashboard') }}" class="sb-item">
        <i class="bi bi-shield-lock-fill text-danger"></i>
        <span>Dashboard Admin</span>
        <span class="sb-badge sb-badge-cctv">CCTV</span>
      </a>
      <a href="{{ route('dashboard') }}" class="sb-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard Print</span>
      </a>

      <div class="sb-section">Transaksi</div>
      <a href="{{ route('pesanan-online.index') }}" class="sb-item {{ request()->routeIs('pesanan-online.*') ? 'active' : '' }}">
        <i class="bi bi-cart3"></i>
        <span>Pesanan Online</span>
      </a>
      <a href="{{ route('pesanan-offline.index') }}" class="sb-item {{ request()->routeIs('pesanan-offline.*') ? 'active' : '' }}">
        <i class="bi bi-shop"></i>
        <span>Pesanan Offline</span>
      </a>
      <a href="{{ route('invoice.index') }}" class="sb-item {{ request()->routeIs('invoice.*') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i>
        <span>Invoice</span>
      </a>

      <div class="sb-section">Keuangan</div>
      <a href="{{ route('uang-masuk.index') }}" class="sb-item {{ request()->routeIs('uang-masuk.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-down-left-circle text-success"></i>
        <span>Uang Masuk</span>
      </a>
      <a href="{{ route('uang-keluar.index') }}" class="sb-item {{ request()->routeIs('uang-keluar.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-up-right-circle text-danger"></i>
        <span>Uang Keluar</span>
      </a>
      <a href="{{ route('laporan-printing.index') }}" class="sb-item {{ request()->routeIs('laporan-printing.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-bar-graph text-warning"></i>
        <span>Laporan Printing</span>
        <span class="sb-badge">Aktif</span>
      </a>

      <div class="sb-section">Produk</div>
      <a href="#" class="sb-item">
        <i class="bi bi-layers"></i>
        <span>Tipe Kertas</span>
      </a>
      <a href="#" class="sb-item">
        <i class="bi bi-box-seam"></i>
        <span>Stok Kertas</span>
      </a>
    </div>
  </div>

  {{-- Content Area --}}
  <div class="content">

    {{-- Topbar --}}
    <div class="topbar no-print">
      <div>
        <div class="id-line">
          <span class="id-dot"></span>
          <span>Toko Print · Laporan Analitik Keuangan & Produksi</span>
        </div>
        <h1>Laporan Printing & Offset</h1>
        <div class="sub">Rekap pesanan online & offline, arus kas masuk/keluar, dan kalkulasi laba rugi riil.</div>
      </div>
      <div class="topbar-actions">
        {{-- TOMBOL KE DASHBOARD ADMIN --}}
        <a href="{{ route('admin.dashboard') }}" class="btn btn-dashboard">
          <i class="bi bi-arrow-left"></i>
          <i class="bi bi-grid-fill"></i>
          <span>Dashboard Admin</span>
        </a>
        <button onclick="window.print()" class="btn btn-ghost">
          <i class="bi bi-printer"></i>
          <span>Print</span>
        </button>
        <a href="{{ route('laporan-printing.pdf', request()->query()) }}" class="btn btn-primary">
          <i class="bi bi-download"></i>
          <span>Export PDF</span>
        </a>
      </div>
    </div>

    {{-- Print Header --}}
    <div class="text-center mb-4" style="display:none" id="print-head">
      <h2 style="font-size:20px;font-weight:700">LAPORAN PRINTING & OFFSET</h2>
      <p style="font-size:13px">{{ $periodeLabel }} — Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('laporan-printing.index') }}" class="filter-form no-print">
      <div>
        <label>Periode Waktu</label>
        <select name="periode" id="periode-select">
          <option value="hari"   {{ $periode=='hari'   ?'selected':'' }}>Hari ini</option>
          <option value="minggu" {{ $periode=='minggu' ?'selected':'' }}>Minggu ini</option>
          <option value="bulan"  {{ $periode=='bulan'  ?'selected':'' }}>Bulan ini</option>
          <option value="tahun"  {{ $periode=='tahun'  ?'selected':'' }}>Tahun ini</option>
          <option value="semua"  {{ $periode=='semua'  ?'selected':'' }}>Semua data</option>
          <option value="custom" {{ $periode=='custom' ?'selected':'' }}>Custom tanggal</option>
        </select>
      </div>
      <div>
        <label>Dari Tanggal</label>
        <input type="date" name="dari"
               value="{{ $dari?->toDateString() }}"
               onchange="document.getElementById('periode-select').value='custom'">
      </div>
      <div>
        <label>Sampai Tanggal</label>
        <input type="date" name="sampai"
               value="{{ $sampai?->toDateString() }}"
               onchange="document.getElementById('periode-select').value='custom'">
      </div>
      <button type="submit" class="btn btn-primary" style="padding: 9px 20px; border-radius: 8px;">
        <i class="bi bi-funnel-fill"></i> Terapkan Filter
      </button>
      <span class="filter-label"><i class="bi bi-calendar-event me-1"></i>{{ $periodeLabel }}</span>
    </form>

    {{-- 5 KPI Metric Cards --}}
    <div class="summary-band">
      <div class="sum-card accent-amber">
        <div class="lbl">Total Pesanan</div>
        <div class="val count-up" data-format="number" data-value="{{ $ringkasanPesanan['total_pesanan'] + $ringkasanOffline['total_pesanan'] }}">0</div>
        <div class="note"><span class="text-success fw-bold">{{ $ringkasanPesanan['total_selesai'] + $ringkasanOffline['total_selesai'] }}</span> selesai dikerjakan</div>
      </div>
      <div class="sum-card accent-green">
        <div class="lbl">Omzet (Selesai)</div>
        <div class="val green count-up" data-format="currency" data-value="{{ $ringkasanPesanan['total_omzet'] + $ringkasanOffline['total_omzet'] }}">Rp 0</div>
        <div class="note">dari pesanan berstatus selesai</div>
      </div>
      <div class="sum-card accent-green">
        <div class="lbl">Total Uang Masuk</div>
        <div class="val green count-up" data-format="currency" data-value="{{ $totalMasuk }}">Rp 0</div>
        <div class="note">arus kas masuk tercatat</div>
      </div>
      <div class="sum-card accent-red">
        <div class="lbl">Total Uang Keluar</div>
        <div class="val red count-up" data-format="currency" data-value="{{ $totalKeluar }}">Rp 0</div>
        <div class="note">beban operasional tercatat</div>
      </div>
      <div class="sum-card accent-violet">
        <div class="lbl">Jasa Potong</div>
        <div class="val violet count-up" data-format="number" data-value="{{ $ringkasanPesanan['total_jasa_potong'] + $ringkasanOffline['total_jasa_potong'] }}">0</div>
        <div class="note">order memakai jasa potong</div>
      </div>
    </div>

    {{-- Hero Laba / Rugi Card --}}
    <div class="hero-laba-card {{ $labaRugi >= 0 ? '' : 'neg' }}">
      <div class="hero-laba-left">
        <div class="hero-laba-icon">
          <i class="bi {{ $labaRugi >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}"></i>
        </div>
        <div>
          <div class="hero-laba-title">Kalkulasi Laba / Rugi Bersih</div>
          <div class="hero-laba-val {{ $labaRugi >= 0 ? 'pos' : 'neg' }} count-up"
               data-format="currency"
               data-value="{{ abs($labaRugi) }}"
               data-prefix="{{ $labaRugi < 0 ? '– ' : '' }}">Rp 0</div>
        </div>
      </div>
      <div class="hero-laba-badge">
        <i class="bi bi-info-circle me-1"></i> Formulasi: Omzet (Selesai) − Total Uang Keluar
      </div>
    </div>

    {{-- Total Lembar Card --}}
    <div class="lembar-card">
      <div>
        <div class="lbl">Total Lembar Kertas Terpakai</div>
        <div class="val count-up" data-format="number" data-value="{{ $totalLembarKeseluruhan }}">0</div>
      </div>
      <div class="note"><i class="bi bi-stack me-1"></i> Gabungan pesanan online + offline (tidak termasuk order dibatalkan)</div>
    </div>

    {{-- Pemakaian Kertas + Pesanan Platform --}}
    <div class="section">
      <div class="split">
        <div>
          <div class="section-head">
            <h2><i class="bi bi-layers-fill text-warning"></i> Pemakaian Kertas per Tipe</h2>
            <span class="count">{{ count($pemakaianKertas) }} tipe</span>
          </div>
          <div class="gauge-panel">
            @php $maxLembar = collect($pemakaianKertas)->max('total_lembar') ?: 1; @endphp
            @forelse($pemakaianKertas as $tipe => $row)
              @php $pct = round(($row['total_lembar'] / $maxLembar) * 100); $i = $loop->index; @endphp
              <div class="gauge-row">
                <span class="name">{{ $tipe }}</span>
                <span class="gcount">{{ number_format($row['total_lembar'], 0, ',', '.') }} lembar</span>
                <div class="gauge-track">
                  <div class="gauge-fill" style="width:{{ $pct }}%;animation-delay:{{ 0.4 + $i * 0.05 }}s"></div>
                </div>
              </div>
            @empty
              <div class="empty-state"><div class="glyph">— · —</div>Belum ada data pemakaian kertas.</div>
            @endforelse
          </div>
        </div>

        <div>
          <div class="section-head">
            <h2><i class="bi bi-shop-window text-info"></i> Pesanan per Platform (Selesai)</h2>
            <span class="count">{{ count($ringkasanPesanan['per_platform']) }} platform</span>
          </div>
          <div class="platform-panel">
            @forelse($ringkasanPesanan['per_platform'] as $platform => $row)
              <div class="platform-row">
                <div class="platform-name">
                  <span class="platform-swatch"></span>
                  <span>{{ $platform }}</span>
                </div>
                <div class="platform-figs">
                  <div>
                    <div class="k">Order</div>
                    <div class="v">{{ $row['jumlah'] }}</div>
                  </div>
                  <div>
                    <div class="k">Omzet</div>
                    <div class="v text-success">Rp {{ number_format($row['omzet'], 0, ',', '.') }}</div>
                  </div>
                </div>
              </div>
            @empty
              <div class="platform-empty">Belum ada data platform pada periode ini.</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    {{-- Detail Pesanan Online --}}
    <div class="section">
      <div class="section-head">
        <h2><i class="bi bi-globe text-primary"></i> Detail Pesanan Online</h2>
        <span class="count">{{ count($pesanan) }} pesanan</span>
      </div>
      <div class="tbl-panel">
        <table>
          <thead>
            <tr>
              <th>No. Order</th>
              <th>Nama Pelanggan</th>
              <th>Platform</th>
              <th>Total Biaya</th>
              <th>Status</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pesanan as $i => $p)
              <tr>
                <td class="ord">{{ $p->no_order }}</td>
                <td class="fw-semibold text-white">{{ $p->pelanggan }}</td>
                <td><span class="tag">{{ $p->platform }}</span></td>
                <td class="fw-bold">{{ $p->total_rupiah }}</td>
                <td>
                  <span class="st {{ $p->status==='Selesai' ? 'st-selesai' : ($p->status==='Dibatalkan' ? 'st-batal' : 'st-proses') }}">
                    {{ $p->status }}
                  </span>
                </td>
                <td class="dim">{{ $p->tanggal }}</td>
              </tr>
            @empty
              <tr><td colspan="6"><div class="empty-state"><div class="glyph">— · · —</div>Belum ada data pesanan online pada periode ini.</div></td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Detail Pesanan Offline --}}
    <div class="section">
      <div class="section-head">
        <h2><i class="bi bi-person-workspace text-success"></i> Detail Pesanan Offline</h2>
        <span class="count">{{ count($pesananOffline) }} pesanan</span>
      </div>
      <div class="tbl-panel">
        <table>
          <thead>
            <tr>
              <th>No. Order</th>
              <th>Nama Pelanggan</th>
              <th>Total Biaya</th>
              <th>Status</th>
              <th>Jasa Potong</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pesananOffline as $i => $p)
              <tr>
                <td class="ord">{{ $p->no_order }}</td>
                <td class="fw-semibold text-white">{{ $p->pelanggan }}</td>
                <td class="fw-bold">{{ $p->total_rupiah }}</td>
                <td>
                  <span class="st {{ $p->status==='Selesai' ? 'st-selesai' : ($p->status==='Dibatalkan' ? 'st-batal' : 'st-proses') }}">
                    {{ $p->status }}
                  </span>
                </td>
                <td>
                  <span class="badge" style="background: rgba(255,255,255,0.06); color: {{ $p->jasa_potong ? '#34d399' : '#94a3b8' }}; padding: 3px 8px; border-radius: 6px; font-size: 11px;">
                    {{ $p->jasa_potong ? 'Ya' : 'Tidak' }}
                  </span>
                </td>
                <td class="dim">{{ $p->tanggal }}</td>
              </tr>
            @empty
              <tr><td colspan="6"><div class="empty-state"><div class="glyph">— · · —</div>Belum ada data pesanan offline pada periode ini.</div></td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Uang Masuk & Keluar Ledger --}}
    <div class="section">
      <div class="split">
        <div>
          <div class="section-head">
            <h2><i class="bi bi-box-arrow-in-down-left text-success"></i> Buku Uang Masuk</h2>
            <span class="count">{{ count($uangMasuk) }} entri</span>
          </div>
          <div class="ledger-panel">
            @forelse($uangMasuk as $i => $u)
              <div class="ledger-row">
                <span class="l-date">{{ \Carbon\Carbon::parse($u->tanggal)->format('d/m') }}</span>
                <div>
                  <div class="l-cat">{{ $u->kategori }}</div>
                  <div class="l-ref">{{ $u->keterangan }}</div>
                </div>
                <span class="l-amt in">Rp {{ number_format($u->jumlah, 0, ',', '.') }}</span>
              </div>
            @empty
              <div class="empty-state"><div class="glyph">— · —</div>Belum ada catatan kas masuk.</div>
            @endforelse
          </div>
        </div>

        <div>
          <div class="section-head">
            <h2><i class="bi bi-box-arrow-up-right text-danger"></i> Buku Uang Keluar</h2>
            <span class="count">{{ count($uangKeluar) }} entri</span>
          </div>
          <div class="ledger-panel">
            @forelse($uangKeluar as $i => $u)
              <div class="ledger-row">
                <span class="l-date">{{ \Carbon\Carbon::parse($u->tanggal)->format('d/m') }}</span>
                <div>
                  <div class="l-cat">{{ $u->kategori }}</div>
                  <div class="l-ref">{{ $u->keterangan }}</div>
                </div>
                <span class="l-amt out">Rp {{ number_format($u->jumlah, 0, ',', '.') }}</span>
              </div>
            @empty
              <div class="empty-state"><div class="glyph">— · —</div>Belum ada catatan kas keluar.</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    {{-- Footer Info --}}
    <div class="footer-note no-print">
      <span><i class="bi bi-shield-check text-success me-1"></i> Laporan digenerate otomatis · Toko Print Management Suite</span>
      <span class="mono">{{ $periodeLabel }}</span>
    </div>

  </div>{{-- end content --}}
</div>{{-- end shell --}}

<script>
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function animateCount(el){
    const target = parseFloat(el.dataset.value || '0');
    const format = el.dataset.format || 'number';
    const prefix = el.dataset.prefix || '';
    const duration = 1000;
    const start = performance.now();
    function render(value){
      const formatted = format === 'currency'
        ? 'Rp ' + value.toLocaleString('id-ID')
        : value.toLocaleString('id-ID');
      el.textContent = prefix + formatted;
    }
    if(reduceMotion){ render(target); return; }
    function frame(now){
      const t = Math.min(1, (now - start) / duration);
      const eased = 1 - Math.pow(1 - t, 3);
      render(Math.round(target * eased));
      if(t < 1) requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
  }

  window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.count-up').forEach(el => {
      setTimeout(() => animateCount(el), 200);
    });
  });

  // Show print header on print
  window.onbeforeprint = () => { document.getElementById('print-head').style.display = 'block'; }
  window.onafterprint  = () => { document.getElementById('print-head').style.display = 'none'; }
</script>
</body>
</html>