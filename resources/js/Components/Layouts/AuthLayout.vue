<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="w-full max-w-md">
            <slot />
        </div>
        <CustomToast />
    </div>
</template>

<script setup>
import CustomToast from '@/components/ui/CustomToast.vue';
</script>
