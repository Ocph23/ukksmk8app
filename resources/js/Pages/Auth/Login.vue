<template>
    <AuthLayout>
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-wrap">
                <img src="/assets/images/smk8logo.jpeg" alt="Logo" class="logo-img" />
                <div class="logo-ring"></div>
            </div>

            <!-- Header -->
            <div class="card-header">
                <span class="badge">APLIKASI UKK</span>
                <h1 class="card-title">SMK 8 TIK JAYAPURA</h1>
                <p class="card-desc">Masuk untuk melanjutkan</p>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="login-form">
                <div class="field">
                    <div class="input-wrap" :class="{ error: form.errors.username }">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        <input
                            id="username"
                            v-model="form.username"
                            type="email"
                            placeholder="Email / Username"
                            autocomplete="email"
                            autofocus
                            class="login-input"
                        />
                    </div>
                    <p v-if="form.errors.username" class="field-error">{{ form.errors.username }}</p>
                </div>

                <div class="field">
                    <div class="input-wrap" :class="{ error: form.errors.password }">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input
                            id="password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            placeholder="Password"
                            autocomplete="current-password"
                            class="login-input"
                        />
                        <button type="button" class="eye-btn" @click="showPassword = !showPassword" tabindex="-1">
                            <svg v-if="!showPassword" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg v-else fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <p v-if="form.errors.password" class="field-error">{{ form.errors.password }}</p>
                </div>

                <div class="form-row">
                    <label class="remember-label">
                        <input type="checkbox" v-model="form.remember" class="remember-check" />
                        <span>Tetap masuk</span>
                    </label>
                </div>

                <button type="submit" class="submit-btn" :disabled="form.processing">
                    <span v-if="form.processing" class="spinner"></span>
                    <span>{{ form.processing ? 'Memproses...' : 'MASUK' }}</span>
                    <svg v-if="!form.processing" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </button>
            </form>

            <!-- Footer -->
            <p class="card-footer">
                Belum punya akun?
                <a href="/auth/register" class="register-link">Daftar</a>
            </p>
        </div>
    </AuthLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { errorToast } from '@/composables/useCustomToast';
import AuthLayout from '@/Components/Layouts/AuthLayout.vue';

const showPassword = ref(false);

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/auth/login', {
        onError: (errors) => {
            const message = errors.email || errors.username || errors.password || 'Login gagal. Periksa kembali data Anda.';
            errorToast('Login Gagal', message);
        },
    });
}
</script>

<style scoped>
/* ── Card ── */
.login-card {
    background: rgba(255, 255, 255, 0.65);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255, 255, 255, 0.9);
    border-radius: 28px;
    padding: 44px 40px;
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
    to { transform: rotate(360deg); }
}

/* ── Header ── */
.card-header {
    margin-bottom: 28px;
}

.badge {
    display: inline-block;
    padding: 3px 12px;
    background: linear-gradient(135deg, rgba(165, 192, 255, 0.4), rgba(196, 176, 255, 0.4));
    border: 1px solid rgba(165, 192, 255, 0.6);
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    color: #4a6cf7;
    text-transform: uppercase;
    margin-bottom: 10px;
}

.card-title {
    font-size: 20px;
    font-weight: 800;
    background: linear-gradient(135deg, #2d4fd6 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0 0 4px;
    letter-spacing: -0.01em;
}

.card-desc {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}

/* ── Form ── */
.login-form {
    display: flex;
    flex-direction: column;
    gap: 14px;
    text-align: left;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.input-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255, 255, 255, 0.7);
    border: 1.5px solid rgba(165, 192, 255, 0.5);
    border-radius: 12px;
    padding: 0 14px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.input-wrap:focus-within {
    border-color: #4a6cf7;
    box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.12);
    background: rgba(255, 255, 255, 0.9);
}

.input-wrap.error {
    border-color: #ef4444;
}

.input-icon {
    width: 18px;
    height: 18px;
    color: #94a3b8;
    flex-shrink: 0;
}

.login-input {
    flex: 1;
    height: 48px;
    background: transparent;
    border: none;
    outline: none;
    font-size: 14px;
    color: #1e293b;
}

.login-input::placeholder {
    color: #94a3b8;
}

.eye-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: #94a3b8;
    padding: 0;
    display: flex;
    align-items: center;
    transition: color 0.2s;
}

.eye-btn:hover { color: #4a6cf7; }

.field-error {
    font-size: 11px;
    color: #ef4444;
    margin: 0;
    padding-left: 4px;
}

/* ── Remember ── */
.form-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.remember-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    color: #64748b;
    cursor: pointer;
}

.remember-check {
    width: 15px;
    height: 15px;
    accent-color: #4a6cf7;
    cursor: pointer;
}

/* ── Submit ── */
.submit-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    height: 50px;
    background: linear-gradient(135deg, #4a6cf7 0%, #7c3aed 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.05em;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(74, 108, 247, 0.4);
    transition: all 0.2s;
    margin-top: 4px;
}

.submit-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 6px 24px rgba(74, 108, 247, 0.5);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}

/* ── Footer ── */
.card-footer {
    margin-top: 24px;
    font-size: 13px;
    color: #64748b;
}

.register-link {
    color: #4a6cf7;
    font-weight: 600;
    text-decoration: none;
    margin-left: 4px;
}

.register-link:hover {
    text-decoration: underline;
}
</style>
