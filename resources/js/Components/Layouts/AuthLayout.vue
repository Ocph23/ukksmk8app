<template>
    <div class="auth-root">
        <!-- Abstract light background -->
        <div class="bg-layer">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
            <div class="orb orb-4"></div>
            <div class="grid-overlay"></div>
        </div>

        <div class="auth-center">
            <slot />
        </div>

        <CustomToast />
    </div>
</template>

<script setup>
import CustomToast from '@/components/ui/CustomToast.vue';
</script>

<style scoped>
.auth-root {
    min-height: 100vh;
    background: #f0f4ff;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

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

.auth-center {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 420px;
    padding: 20px;
}
</style>
