<?php
$hostname = getenv('HOSTNAME') ?: ($_SERVER['HOSTNAME'] ?? 'unknown-host');
$time = date('Y-m-d H:i:s');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Pavan Dath — Gamer Profile</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;500;700&family=Orbitron:wght@600;800&display=swap');

:root {
    --card: rgba(12,14,18,0.46);
    --muted: #9fb0c7;
    --soft-teal: rgba(0,210,180,0.85);
    --soft-teal-halo: rgba(0,210,180,0.25);
    --border-light: rgba(255,255,255,0.05);
}

/* === PAGE BACKGROUND === */
html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
        linear-gradient(180deg, rgba(0,0,0,0.08), rgba(0,0,0,0.18)),
        url("https://4kwallpapers.com/images/walls/thumbs_3t/15243.jpg")
        center/cover fixed no-repeat;
    font-family: 'Inter', sans-serif;
    color: #eaf8ff;
}

/* === CARD === */
.card {
    width: min(93%, 900px);
    background: var(--card);
    padding: 32px 30px;
    border-radius: 18px;
    box-shadow: 0 14px 60px rgba(0,0,0,0.65);
    border: 1px solid var(--border-light);
    backdrop-filter: blur(10px) saturate(1.2);
    display: flex;
    flex-direction: column;
    align-items: center;
    animation: fadeUp .8s ease forwards;
    transform: translateY(18px);
    opacity: 0;
}
@keyframes fadeUp {
    to { transform: translateY(0); opacity: 1; }
}

/* === PROFILE CIRCLE — SOFT NEON EDGE === */
.profile-circle {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    position: relative;
    border: 4px solid rgba(255,255,255,0.06);
    box-shadow:
        0 0 20px var(--soft-teal-halo),
        0 0 40px var(--soft-teal),
        0 8px 25px rgba(0,0,0,0.6);
    animation: picFade .8s ease .15s forwards;
    opacity: 0;
}
@keyframes picFade {
    to { opacity: 1; transform: scale(1); }
}
.profile-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* === BADGES === */
.pill {
    background: rgba(255,255,255,0.06);
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
    margin: 6px;
    color: var(--muted);
}

/* === NAME === */
.name {
    font-family: 'Orbitron', sans-serif;
    font-size: 42px;
    margin-top: 20px;
    margin-bottom: 6px;
    font-weight: 800;
    text-shadow: 0 6px 22px rgba(0,0,0,0.55);
}

/* === TAGLINE === */
.tagline {
    font-size: 16px;
    color: var(--muted);
    margin-bottom: 18px;
}

/* === HOST & TIME INFO === */
.meta-box {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    justify-content: center;
    margin-bottom: 22px;
}
.meta-pill {
    background: rgba(255,255,255,0.05);
    padding: 10px 18px;
    border-radius: 999px;
    font-size: 13px;
    border: 1px solid rgba(255,255,255,0.07);
}

/* === YOUTUBE BADGE === */
.yt-badge {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: rgba(0,0,0,0.35);
    padding: 12px 18px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.06);
    text-decoration: none;
    color: #fff;
    margin-bottom: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    transition: 0.2s ease;
}
.yt-badge:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.55);
}
.yt-icon {
    width: 26px;
    height: 18px;
}

/* === FOOTER === */
.footer-text {
    font-size: 13px;
    color: var(--muted);
}

</style>
</head>
<body>

<div class="card">

    <!-- PROFILE -->
    <div class="profile-circle">
        <img src="https://images.steamusercontent.com/ugc/2058741034012529959/08286FCE516F450F0535590A2831ED629687B2DD/">
    </div>

    <!-- LEVEL BADGES -->
    <div>
        <span class="pill">LEVEL 99</span>
        <span class="pill">SLAYER</span>
    </div>

    <!-- NAME -->
    <div class="name">Pavan Dath</div>

    <!-- TAGLINE -->
    <div class="tagline">Hardcore Gamer • Streamer • Content Creator</div>

    <!-- HOST + TIME -->
    <div class="meta-box">
        <div class="meta-pill">HOST: <strong><?= $hostname ?></strong></div>
        <div class="meta-pill">TIME: <strong><?= $time ?></strong></div>
    </div>

    <!-- YOUTUBE BADGE -->
    <a class="yt-badge" href="#" target="_blank">
        <svg class="yt-icon" viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
            <rect width="24" height="16" rx="3" fill="#ff0000"/>
            <path d="M9 4.75L15 8L9 11.25V4.75Z" fill="#fff"/>
        </svg>
        <div>
            <div style="font-weight:700;">Dazap Ace</div>
            <div style="font-size:11px;opacity:0.85;">YouTube Channel</div>
        </div>
    </a>

    <div class="footer-text">Follow for streams & highlights</div>
</div>

</body>
</html>
