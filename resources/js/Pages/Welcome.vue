<template>
    <div class="welcome-root">
        <!-- Abstract light background -->
        <div class="bg-layer">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
            <div class="orb orb-4"></div>
            <div class="grid-overlay"></div>
        </div>

        <!-- Login button -->
        <div class="top-bar">
            <a href="/auth/login" class="login-btn">
                Masuk
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <!-- Main content -->
        <main class="hero">
            <div class="hero-card">
                <!-- Logo -->
                <div class="logo-wrap">
                    <img src="/assets/images/smk8logo.jpeg" alt="SMK 8" class="logo-img" />
                    <div class="logo-ring"></div>
                </div>

                <!-- Badge -->
                <span class="badge">Sistem Informasi Resmi</span>

                <!-- Title -->
                <h1 class="title">
                    <span class="title-main">UJI KOMPETENSI</span>
                    <span class="title-sub">KEAHLIAN</span>
                </h1>

                <!-- Divider -->
                <div class="divider">
                    <span class="divider-dot"></span>
                    <span class="divider-line"></span>
                    <span class="divider-dot"></span>
                </div>

                <!-- School name -->
                <p class="school-name">SMK NEGERI 8</p>
                <p class="school-sub">Teknologi Informasi dan Komunikasi · Jayapura</p>

                <!-- CTA -->
                <a href="/auth/login" class="cta-btn">
                    Mulai Sekarang
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>

                <!-- Footer note -->
                <p class="footer-note">© 2024 – {{ tahun }} &nbsp;·&nbsp; @Ocph23</p>
            </div>
        </main>
    </div>
</template>

<script setup>
const tahun = new Date().getFullYear();
</script>

<style scoped>
/* ── Root ── */
.welcome-root {
    min-height: 100vh;
    background: #f0f4ff;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

/* ── Abstract background ── */
.bg-layer {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
}

.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.55;
}

.orb-1 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, #c7d9ff 0%, #a5c0ff 60%, transparent 100%);
    top: -180px; left: -160px;
    animation: drift 14s ease-in-out infinite alternate;
}

.orb-2 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, #e0d4ff 0%, #c4b0ff 60%, transparent 100%);
    top: 10%; right: -140px;
    animation: drift 18s ease-in-out infinite alternate-reverse;
}

.orb-3 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, #b8f0e8 0%, #8de8d8 60%, transparent 100%);
    bottom: -100px; left: 20%;
    animation: drift 16s ease-in-out infinite alternate;
}

.orb-4 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, #ffd6e8 0%, #ffb3d1 60%, transparent 100%);
    bottom: 15%; right: 10%;
    animation: drift 20s ease-in-out infinite alternate-reverse;
}

@keyframes drift {
    from { transform: translate(0, 0) scale(1); }
    to   { transform: translate(30px, 20px) scale(1.08); }
}

.grid-overlay {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(99, 130, 255, 0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(99, 130, 255, 0.06) 1px, transparent 1px);
    background-size: 48px 48px;
}

/* ── Top bar ── */
.top-bar {
    position: relative;
    z-index: 10;
    display: flex;
    justify-content: flex-end;
    padding: 20px 28px;
}

.login-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 20px;
    border: 1.5px solid rgba(99, 130, 255, 0.4);
    border-radius: 999px;
    font-size: 14px;
    font-weight: 500;
    color: #4a6cf7;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(8px);
    text-decoration: none;
    transition: all 0.2s;
}

.login-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: #4a6cf7;
    box-shadow: 0 4px 16px rgba(74, 108, 247, 0.2);
}

/* ── Hero ── */
.hero {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    z-index: 10;
}

.hero-card {
    background: rgba(255, 255, 255, 0.65);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255, 255, 255, 0.9);
    border-radius: 28px;
    padding: 52px 48px;
    max-width: 480px;
    width: 100%;
    text-align: center;
    box-shadow:
        0 8px 32px rgba(74, 108, 247, 0.1),
        0 2px 8px rgba(0, 0, 0, 0.06),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
}

/* ── Logo ── */
.logo-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 20px;
}

.logo-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    position: relative;
    z-index: 1;
    box-shadow: 0 4px 20px rgba(74, 108, 247, 0.25);
}

.logo-ring {
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    border: 2px solid transparent;
    background: linear-gradient(135deg, #a5c0ff, #c4b0ff, #8de8d8) border-box;
    -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: destination-out;
    mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
    mask-composite: exclude;
    animation: spin 8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ── Badge ── */
.badge {
    display: inline-block;
    padding: 4px 14px;
    background: linear-gradient(135deg, rgba(165, 192, 255, 0.4), rgba(196, 176, 255, 0.4));
    border: 1px solid rgba(165, 192, 255, 0.6);
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.06em;
    color: #4a6cf7;
    text-transform: uppercase;
    margin-bottom: 20px;
}

/* ── Title ── */
.title {
    display: flex;
    flex-direction: column;
    gap: 2px;
    margin-bottom: 20px;
}

.title-main {
    font-size: 32px;
    font-weight: 800;
    letter-spacing: -0.02em;
    background: linear-gradient(135deg, #2d4fd6 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.1;
}

.title-sub {
    font-size: 22px;
    font-weight: 700;
    color: #64748b;
    letter-spacing: 0.04em;
}

/* ── Divider ── */
.divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 20px;
}

.divider-line {
    width: 80px;
    height: 1.5px;
    background: linear-gradient(90deg, transparent, #a5c0ff, transparent);
}

.divider-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #a5c0ff;
}

/* ── School ── */
.school-name {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 4px;
    letter-spacing: 0.02em;
}

.school-sub {
    font-size: 13px;
    color: #64748b;
    margin: 0 0 32px;
}

/* ── CTA ── */
.cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 32px;
    background: linear-gradient(135deg, #4a6cf7 0%, #7c3aed 100%);
    color: white;
    border-radius: 999px;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 4px 20px rgba(74, 108, 247, 0.4);
    transition: all 0.2s;
    margin-bottom: 28px;
}

.cta-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(74, 108, 247, 0.5);
}

/* ── Footer ── */
.footer-note {
    font-size: 12px;
    color: #94a3b8;
    margin: 0;
}

/* ── Responsive ── */
@media (max-width: 480px) {
    .hero-card {
        padding: 36px 24px;
    }
    .title-main { font-size: 26px; }
    .title-sub  { font-size: 18px; }
}
</style>
