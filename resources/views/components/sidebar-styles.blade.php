<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#0E1015;--sidebar:#111319;--panel:#171A21;--panel-raised:#1D2129;
    --line:#262B35;--line-soft:#1F232C;
    --text:#E9EBF0;--text-dim:#9096A2;--text-faint:#5B616D;
    --amber:#F5A524;--amber-dim:#5C4419;
    --green:#3ADC91;--green-dim:#173A2C;
    --red:#FB5B5B;--red-dim:#3A1C1C;
    --violet:#9A8CFB;--violet-dim:#28223F;
    --blue:#5EB1F5;--blue-dim:#183246;
    --radius-s:4px;--radius-m:8px;
    --ease:cubic-bezier(.22,.9,.34,1);
  }

  /* Sidebar */
  .sidebar{width:230px;flex-shrink:0;background:var(--sidebar);border-right:1px solid var(--line);padding:22px 14px;position:sticky;top:0;height:100vh;overflow-y:auto;z-index:2;opacity:0;transform:translateX(-14px);animation:slideIn .7s var(--ease) forwards;font-family:'Inter',sans-serif;}
  @keyframes slideIn{to{opacity:1;transform:translateX(0);}}
  .sidebar a{color:inherit;text-decoration:none;}
  .brand{display:flex;align-items:center;gap:10px;padding:4px 8px 20px;margin-bottom:14px;border-bottom:1px solid var(--line);}
  .brand-mark{width:32px;height:32px;border-radius:7px;background:linear-gradient(135deg,var(--amber),#C97A0F);display:flex;align-items:center;justify-content:center;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:14px;color:#1A1204;animation:markPulse 3.2s ease-in-out infinite;animation-delay:1.4s;}
  @keyframes markPulse{0%,100%{box-shadow:0 0 0 0 rgba(245,165,36,0.28);}50%{box-shadow:0 0 0 6px rgba(245,165,36,0);}}
  .brand-text .name{font-family:'Space Grotesk',sans-serif;font-size:14.5px;font-weight:600;color:var(--text);}
  .brand-text .role{font-size:11px;color:var(--text-faint);}
  .nav-group{margin-bottom:18px;}
  .nav-group-label{font-size:10.5px;font-weight:600;letter-spacing:0.06em;color:var(--text-faint);padding:0 10px;margin-bottom:6px;text-transform:uppercase;opacity:0;animation:fadeUp .5s var(--ease) forwards;}
  .nav-item{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:var(--radius-s);color:var(--text-dim);font-size:13px;font-weight:500;margin-bottom:2px;position:relative;transition:background .22s var(--ease),color .22s var(--ease),padding-left .22s var(--ease);opacity:0;animation:fadeUp .5s var(--ease) forwards;}
  .nav-item svg{flex-shrink:0;opacity:0.75;transition:opacity .2s,transform .3s var(--ease);}
  .nav-item:hover{background:var(--panel-raised);color:var(--text);padding-left:14px;}
  .nav-item:hover svg{transform:translateX(1px) scale(1.05);}
  .nav-item.active{background:var(--amber-dim);color:var(--amber);}
  .nav-item.active svg{opacity:1;}
  .nav-item.active::before{content:'';position:absolute;left:-14px;top:50%;transform:translateY(-50%);width:3px;height:16px;border-radius:2px;background:var(--amber);animation:barIn .28s var(--ease);}
  @keyframes barIn{from{height:0;opacity:0;}to{height:16px;opacity:1;}}
  @keyframes fadeUp{from{opacity:0;transform:translateY(6px);}to{opacity:1;transform:translateY(0);}}

  .nav-group:nth-child(1) .nav-group-label{animation-delay:.55s}
  .nav-group:nth-child(1) .nav-item{animation-delay:.60s}
  .nav-group:nth-child(2) .nav-group-label{animation-delay:.68s}
  .nav-group:nth-child(2) .nav-item:nth-child(2){animation-delay:.72s}
  .nav-group:nth-child(2) .nav-item:nth-child(3){animation-delay:.77s}
  .nav-group:nth-child(2) .nav-item:nth-child(4){animation-delay:.82s}
  .nav-group:nth-child(3) .nav-group-label{animation-delay:.88s}
  .nav-group:nth-child(3) .nav-item:nth-child(2){animation-delay:.92s}
  .nav-group:nth-child(3) .nav-item:nth-child(3){animation-delay:.97s}
  .nav-group:nth-child(3) .nav-item:nth-child(4){animation-delay:1.02s}
  .nav-group:nth-child(4) .nav-group-label{animation-delay:1.08s}
  .nav-group:nth-child(4) .nav-item:nth-child(2){animation-delay:1.12s}
  .nav-group:nth-child(4) .nav-item:nth-child(3){animation-delay:1.16s}

  @media(max-width:1000px){
    .sidebar{width:100%;height:auto;position:relative;}
  }
</style>