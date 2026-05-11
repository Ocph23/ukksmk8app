<template>
    <div class="verify-page">
        <!-- Background blobs -->
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        <div class="verify-container">
            <div class="logo-wrap">
                <img src="/assets/images/smk8logo.jpeg" alt="Logo" class="logo-img" />
                <div class="logo-ring"></div>
            </div>
            <!-- Header -->
            <div class="verify-header">
                <span class="badge">VERIFIKASI SERTIFIKAT</span>
                <h1 class="page-title">SMK 8 TIK JAYAPURA</h1>
                <p v-if="!result" class="page-desc">Masukkan nomor seri atau nomor sertifikat untuk memverifikasi keasliannya</p>
            </div>

            <!-- Search Card -->
            <div class="search-card" v-if="!result">
                <div class="input-group">
                    <div class="input-wrap" :class="{ error: errorMsg }">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <input v-model="nomorInput" type="text" placeholder="Contoh: 123/SKT/2024 atau nomor seri..."
                            class="verify-input" @keyup.enter="verifikasi" autofocus />
                        <button v-if="nomorInput" @click="nomorInput = ''; errorMsg = ''" class="clear-btn"
                            type="button">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <p v-if="errorMsg" class="error-msg">{{ errorMsg }}</p>
                </div>

                <button @click="verifikasi" :disabled="loading || !nomorInput.trim()" class="verify-btn">
                    <span v-if="loading" class="spinner"></span>
                    <svg v-else fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {{ loading ? 'Memverifikasi...' : 'Verifikasi' }}
                </button>
            </div>

            <!-- Result: Valid -->
            <div v-if="result" class="result-card valid">
                <!-- Verified Icon -->
                <div class="verified-icon-wrap">
                    <div class="verified-ring ring-outer"></div>
                    <div class="verified-ring ring-inner"></div>
                    <div class="verified-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="52" height="52">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>

                <div class="verified-label">SERTIFIKAT TERVERIFIKASI</div>
                <p class="verified-sub">Sertifikat ini valid dan terdaftar dalam sistem</p>

                <!-- Detail Info -->
                <div class="cert-details">
                    <div class="detail-row">
                        <span class="detail-label">Nama Siswa</span>
                        <span class="detail-value">{{ result.siswa?.nama ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">NIS</span>
                        <span class="detail-value">{{ result.siswa?.nis ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Jurusan</span>
                        <span class="detail-value">{{ result.siswa?.jurusan?.nama ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tahun Ajaran</span>
                        <span class="detail-value">{{ result.siswa?.tahunajaran?.nama ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nomor Sertifikat</span>
                        <span class="detail-value mono">{{ result.nomor }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nomor Seri</span>
                        <span class="detail-value mono">{{ result.nomorseri }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Instansi</span>
                        <span class="detail-value">{{ result.instansi }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tanggal Penetapan</span>
                        <span class="detail-value">{{ formatTanggal(result.tanggalpenetapan) }}</span>
                    </div>
                </div>

                <button @click="reset" class="reset-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Verifikasi Sertifikat Lain
                </button>
            </div>

            <!-- Result: Not Found -->
            <div v-if="notFound" class="result-card invalid">
                <div class="invalid-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="56" height="56">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="invalid-label">SERTIFIKAT TIDAK DITEMUKAN</div>
                <p class="invalid-sub">Nomor yang Anda masukkan tidak terdaftar dalam sistem kami. Pastikan nomor yang
                    dimasukkan sudah benar.</p>
                <button @click="reset" class="reset-btn outline">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Coba Lagi
                </button>
            </div>

            <p class="footer-note">
                <a href="/auth/login" class="footer-link">← Masuk ke sistem</a>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const nomorInput = ref('');
const loading = ref(false);
const errorMsg = ref('');
const result = ref(null);
const notFound = ref(false);

async function verifikasi() {
    const nomor = nomorInput.value.trim();
    if (!nomor) return;

    loading.value = true;
    errorMsg.value = '';
    result.value = null;
    notFound.value = false;

    try {
        const res = await axios.get('/api/sertifikat/verifikasi', { params: { nomor } });
        result.value = res.data;
    } catch (err) {
        if (err.response?.status === 404) {
            notFound.value = true;
        } else {
            errorMsg.value = err.response?.data?.message || 'Terjadi kesalahan, coba lagi.';
        }
    } finally {
        loading.value = false;
    }
}

function reset() {
    result.value = null;
    notFound.value = false;
    nomorInput.value = '';
    errorMsg.value = '';
}

function formatTanggal(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}
</script>

<style scoped>
.verify-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 50%, #ecfdf5 100%);
    padding: 24px 16px;
    position: relative;
    overflow: hidden;
}

/* Background blobs */
.blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.35;
    pointer-events: none;
}

.blob-1 {
    width: 400px;
    height: 400px;
    background: #a5c0ff;
    top: -100px;
    left: -100px;
}

.blob-2 {
    width: 350px;
    height: 350px;
    background: #c4b0ff;
    bottom: -80px;
    right: -80px;
}

.blob-3 {
    width: 250px;
    height: 250px;
    background: #6ee7b7;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.verify-container {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 520px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 24px;
}

/* Header */
.verify-header {
    position: relative;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

/* ── Logo ── */
.logo-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 20px;
}

.logo-img {
    width: 72px;
    height: 72px;
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
    to {
        transform: rotate(360deg);
    }
}

.badge {
    display: inline-block;
    padding: 3px 14px;
    background: linear-gradient(135deg, rgba(165, 192, 255, 0.4), rgba(196, 176, 255, 0.4));
    border: 1px solid rgba(165, 192, 255, 0.6);
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    color: #4a6cf7;
    text-transform: uppercase;
    margin-top: 16px;
}

.page-title {
    font-size: 22px;
    font-weight: 800;
    background: linear-gradient(135deg, #2d4fd6 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
}

.page-desc {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    max-width: 380px;
    line-height: 1.5;
}

/* Search Card */
.search-card {
    width: 100%;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255, 255, 255, 0.9);
    border-radius: 24px;
    padding: 32px 28px;
    box-shadow: 0 8px 32px rgba(74, 108, 247, 0.1), 0 2px 8px rgba(0, 0, 0, 0.06);
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.input-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255, 255, 255, 0.8);
    border: 1.5px solid rgba(165, 192, 255, 0.5);
    border-radius: 14px;
    padding: 0 14px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.input-wrap:focus-within {
    border-color: #4a6cf7;
    box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.12);
}

.input-wrap.error {
    border-color: #ef4444;
}

.input-icon {
    width: 20px;
    height: 20px;
    color: #94a3b8;
    flex-shrink: 0;
}

.verify-input {
    flex: 1;
    height: 52px;
    background: transparent;
    border: none;
    outline: none;
    font-size: 14px;
    color: #1e293b;
}

.verify-input::placeholder {
    color: #94a3b8;
}

.clear-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: #94a3b8;
    padding: 4px;
    display: flex;
    align-items: center;
    border-radius: 6px;
    transition: color 0.2s, background 0.2s;
}

.clear-btn:hover {
    color: #64748b;
    background: rgba(0, 0, 0, 0.05);
}

.error-msg {
    font-size: 12px;
    color: #ef4444;
    padding-left: 4px;
}

.verify-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    height: 52px;
    background: linear-gradient(135deg, #4a6cf7 0%, #7c3aed 100%);
    color: white;
    border: none;
    border-radius: 14px;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 0.03em;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(74, 108, 247, 0.4);
    transition: all 0.2s;
}

.verify-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 6px 24px rgba(74, 108, 247, 0.5);
}

.verify-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}

/* Result Cards */
.result-card {
    width: 100%;
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border-radius: 24px;
    padding: 36px 28px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    text-align: center;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.result-card.valid {
    background: rgba(240, 253, 244, 0.85);
    border: 1.5px solid rgba(134, 239, 172, 0.6);
}

.result-card.invalid {
    background: rgba(254, 242, 242, 0.85);
    border: 1.5px solid rgba(252, 165, 165, 0.6);
}

/* Verified Icon */
.verified-icon-wrap {
    position: relative;
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}

.verified-ring {
    position: absolute;
    border-radius: 50%;
    border: 2px solid rgba(34, 197, 94, 0.3);
    animation: pulse-ring 2s ease-out infinite;
}

.ring-outer {
    width: 120px;
    height: 120px;
    animation-delay: 0s;
}

.ring-inner {
    width: 96px;
    height: 96px;
    animation-delay: 0.4s;
}

@keyframes pulse-ring {
    0% {
        transform: scale(0.9);
        opacity: 0.8;
    }

    70% {
        transform: scale(1.05);
        opacity: 0;
    }

    100% {
        transform: scale(1.05);
        opacity: 0;
    }
}

.verified-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 8px 32px rgba(34, 197, 94, 0.4);
    animation: pop-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes pop-in {
    0% {
        transform: scale(0);
        opacity: 0;
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.verified-label {
    font-size: 16px;
    font-weight: 800;
    color: #15803d;
    letter-spacing: 0.05em;
}

.verified-sub {
    font-size: 13px;
    color: #4ade80;
    margin: 0;
}

/* Cert Details */
.cert-details {
    width: 100%;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 16px;
    padding: 20px;
    margin-top: 8px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    text-align: left;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.detail-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.detail-label {
    font-size: 12px;
    color: #64748b;
    flex-shrink: 0;
    min-width: 130px;
}

.detail-value {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    text-align: right;
}

.detail-value.mono {
    font-family: monospace;
    font-size: 12px;
}

/* Invalid Icon */
.invalid-icon-wrap {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 8px 32px rgba(239, 68, 68, 0.4);
    margin-bottom: 8px;
    animation: pop-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.invalid-label {
    font-size: 16px;
    font-weight: 800;
    color: #dc2626;
    letter-spacing: 0.05em;
}

.invalid-sub {
    font-size: 13px;
    color: #f87171;
    margin: 0;
    max-width: 360px;
    line-height: 1.5;
}

/* Reset Button */
.reset-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    background: linear-gradient(135deg, #4a6cf7 0%, #7c3aed 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 8px;
    box-shadow: 0 4px 16px rgba(74, 108, 247, 0.3);
    transition: all 0.2s;
}

.reset-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(74, 108, 247, 0.4);
}

.reset-btn.outline {
    background: transparent;
    border: 1.5px solid #ef4444;
    color: #ef4444;
    box-shadow: none;
}

.reset-btn.outline:hover {
    background: rgba(239, 68, 68, 0.06);
    box-shadow: none;
}

/* Footer */
.footer-note {
    font-size: 13px;
    color: #94a3b8;
}

.footer-link {
    color: #4a6cf7;
    font-weight: 600;
    text-decoration: none;
}

.footer-link:hover {
    text-decoration: underline;
}
</style>
