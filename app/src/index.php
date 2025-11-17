<?php
// Responsive gaming profile page with entrance animation, tagline, and YouTube badge.
// Save as profile.php and open in a PHP-enabled server.

$hostname = getenv('HOSTNAME') ?: ($_SERVER['HOSTNAME'] ?? 'unknown-host');
$time = date('Y-m-d H:i:s');
echo <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Pavan Dath — Gamer Profile</title>

<style>
  /* Font */
  @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Inter:wght@300;500;700&display=swap');

  :root{
    --neon: #00ffd5;
    --accent: #ff2d95;
    --glass: rgba(10,10,20,0.45);
  }

  html,body{
    height:100%;
    margin:0;
    font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    background:
      linear-gradient(180deg, rgba(0,0,0,0.55), rgba(0,0,0,0.75)),
      url("https://images.steamusercontent.com/ugc/2058741034012526512/379E6434B473E7BE31C50525EB946D4212A8C8B3/")
      center/cover fixed no-repeat;
    color: #eaffff;
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    padding: 28px;
  }

  /* subtle animated scanlines overlay */
  .scan-overlay {
    position: fixed;
    inset: 0;
    pointer-events: none;
    background-image: linear-gradient(transparent 92%, rgba(255,255,255,0.03) 100%);
    background-size: 100% 3px;
    animation: scan 6s linear infinite;
    opacity: 0.35;
    mix-blend-mode: overlay;
  }
  @keyframes scan { 0%{ transform:translateY(0) } 100%{ transform:translateY(6px) } }

  /* Main card */
  .card {
    width: min(96%, 980px);
    max-width: 980px;
    background: linear-gradient(180deg, rgba(8,8,12,0.48), rgba(12,12,20,0.34));
    border-radius: 20px;
    padding: clamp(18px, 3.2vw, 36px);
    box-shadow: 0 12px 60px rgba(0,0,0,0.6);
    border: 1px solid rgba(255,255,255,0.05);
    display:flex;
    gap: clamp(18px, 3vw, 28px);
    align-items:center;
    justify-content:flex-start;
    backdrop-filter: blur(8px) saturate(1.05);
    transform: translateY(20px) scale(0.98);
    opacity: 0;
    animation: entrance 800ms cubic-bezier(.2,.9,.2,1) 1 forwards;
  }

  /* entrance animation for the whole card */
  @keyframes entrance {
    from { transform: translateY(20px) scale(0.98); opacity:0; }
    to   { transform: translateY(0px) scale(1); opacity:1; }
  }

  /* Left area: profile */
  .left {
    width: clamp(120px, 28vw, 260px);
    min-width: 120px;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:14px;
  }

  /* Clean circular frame with neon rim */
  .profile-circle {
    width: clamp(120px, 28vw, 230px);
    height: clamp(120px, 28vw, 230px);
    border-radius:50%;
    overflow:hidden;
    display:inline-block;
    border: 6px solid rgba(0,255,213,0.9);
    box-shadow:
      0 8px 30px rgba(0,0,0,0.6),
      0 0 22px rgba(0,255,213,0.18),
      0 0 48px rgba(0,255,213,0.08);
    transition: transform .45s cubic-bezier(.2,.9,.2,1), box-shadow .45s ease;
    transform-origin:center;
    /* small pop-in for profile */
    animation: picEntrance 700ms cubic-bezier(.2,.9,.2,1) .12s both;
  }
  .profile-circle:hover { transform: scale(1.04); box-shadow: 0 16px 60px rgba(0,0,0,0.68), 0 0 36px rgba(0,255,213,0.24); }

  @keyframes picEntrance {
    from { transform: scale(0.88) rotate(-2deg); opacity:0; filter: blur(2px); }
    to   { transform: scale(1) rotate(0deg); opacity:1; filter: blur(0); }
  }

  .profile-circle img {
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
  }

  /* Center area: name + tagline + meta */
  .center {
    flex:1;
    display:flex;
    flex-direction:column;
    gap:6px;
    align-items:flex-start;
    min-width:0;
  }

  .name {
    font-family: 'Orbitron', sans-serif;
    font-size: clamp(28px, 5.5vw, 56px);
    line-height:0.95;
    margin:0;
    font-weight:900;
    background: linear-gradient(90deg, var(--neon), #78ff66 40%, var(--accent) 80%);
    -webkit-background-clip:text;
    background-clip:text;
    color: transparent;
    text-shadow: 0 4px 18px rgba(0,0,0,0.6);
    transform: translateY(6px);
    opacity:0;
    animation: textEntrance 700ms cubic-bezier(.2,.9,.2,1) .28s both;
  }

  @keyframes textEntrance {
    from { transform: translateY(6px); opacity:0; filter:blur(2px); }
    to { transform: translateY(0px); opacity:1; filter:blur(0); }
  }

  .tagline {
    font-size: clamp(12px, 2vw, 15px);
    color: #dffcff;
    opacity:0.95;
    margin-top:6px;
    transform: translateY(8px);
    opacity:0;
    animation: subEntrance 700ms cubic-bezier(.2,.9,.2,1) .36s both;
  }
  @keyframes subEntrance {
    from { transform: translateY(8px); opacity:0; }
    to { transform: translateY(0); opacity:1; }
  }

  .meta {
    margin-top:10px;
    font-size: 13px;
    color: rgba(230,247,255,0.75);
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    align-items:center;
  }

  .pill {
    background: linear-gradient(90deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
    border-radius: 999px;
    padding:8px 12px;
    font-weight:700;
    font-size:12px;
    border:1px solid rgba(255,255,255,0.04);
    box-shadow: 0 6px 18px rgba(0,0,0,0.5);
  }

  /* Right area: social / CTA */
  .right {
    display:flex;
    flex-direction:column;
    align-items:flex-end;
    gap:12px;
    min-width:120px;
  }

  .yt-badge {
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding:8px 12px;
    background: linear-gradient(90deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
    border-radius: 12px;
    border:1px solid rgba(255,255,255,0.04);
    box-shadow: 0 8px 30px rgba(0,0,0,0.45);
    cursor:pointer;
    text-decoration:none;
    color: #fff;
    transition: transform .16s ease, box-shadow .16s ease;
    transform: translateY(6px);
    opacity:0;
    animation: ytEntrance 700ms cubic-bezier(.2,.9,.2,1) .44s both;
  }
  .yt-badge:hover { transform: translateY(0px) scale(1.03); box-shadow: 0 18px 48px rgba(0,0,0,0.6); }

  @keyframes ytEntrance {
    from { transform: translateY(6px); opacity:0; }
    to { transform: translateY(0); opacity:1; }
  }

  /* YouTube icon (red play) as inline svg styling */
  .yt-icon {
    width:28px; height:20px;
    display:inline-block;
    flex:0 0 auto;
    filter: drop-shadow(0 6px 18px rgba(0,0,0,0.55));
  }
  .yt-text {
    font-weight:700;
    letter-spacing:0.2px;
    font-size:14px;
    font-family: 'Inter', sans-serif;
  }

  /* Responsive adjustments */
  @media (max-width:900px){
    .card { flex-direction: column; align-items:center; text-align:center; padding:24px; gap:18px; }
    .center { align-items:center; }
    .right { align-items:center; width:100%; }
    .meta { justify-content:center; }
  }

</style>
</head>
<body>
  <div class="scan-overlay" aria-hidden="true"></div>

  <div class="card" role="region" aria-label="Pavan Dath gaming profile">
    <!-- LEFT: Circular profile -->
    <div class="left" aria-hidden="false">
      <div class="profile-circle" title="Pavan Dath">
        <img src="https://images.steamusercontent.com/ugc/2058741034012529959/08286FCE516F450F0535590A2831ED629687B2DD/" alt="Profile image of Pavan Dath" />
      </div>

      <div style="display:flex; gap:8px; margin-top:6px;">
        <div class="pill" style="font-size:12px;">LEVEL 99</div>
        <div class="pill" style="font-size:12px;">SLAYER</div>
      </div>
    </div>

    <!-- CENTER: Name + tagline + meta -->
    <div class="center">
      <h1 class="name">Pavan Dath</h1>
      <div class="tagline">Hardcore Gamer • Streamer • Content Creator</div>

      <div class="meta" aria-hidden="false">
        <div class="pill">HOST: <strong style="margin-left:6px;">{$hostname}</strong></div>
        <div class="pill">TIME: <strong style="margin-left:6px;">{$time}</strong></div>
      </div>
    </div>

    <!-- RIGHT: YouTube badge -->
    <div class="right" aria-hidden="false">
      <!-- Badge links to YouTube (replace # with your channel link if you want) -->
      <a class="yt-badge" href="#" target="_blank" rel="noopener noreferrer" aria-label="Visit Dazap Ace on YouTube">
        <!-- Inline SVG YouTube play icon -->
        <svg class="yt-icon" viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true">
          <rect width="24" height="16" rx="3" fill="#FF0000"></rect>
          <path d="M9 4.75L15 8L9 11.25V4.75Z" fill="#fff"/>
        </svg>
        <div style="display:flex;flex-direction:column;align-items:flex-start;line-height:1;">
          <div class="yt-text">Dazap Ace</div>
          <div style="font-size:11px; opacity:0.85; margin-top:2px;">YouTube Channel</div>
        </div>
      </a>

      <div style="font-size:12px; color:rgba(230,247,255,0.7); opacity:0.95;">Follow for streams & highlights</div>
    </div>
  </div>

</body>
</html>
HTML;
?>
