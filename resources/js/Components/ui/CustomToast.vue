<script setup>
import { useToast } from '@/composables/useCustomToast';

const { toasts, removeToast } = useToast();
</script>

<template>
    <!-- Toast container -->
    <div class="fixed top-4 right-4 z-[999999] flex flex-col gap-2 max-w-sm" style="pointer-events: none;">
        <TransitionGroup name="toast">
            <div v-for="t in toasts" :key="t.id"
                class="pointer-events-auto rounded-lg shadow-lg border p-4 bg-white animate-slide-in" :class="{
                    'border-green-500 bg-green-50': t.type === 'success',
                    'border-red-500 bg-red-50': t.type === 'error',
                    'border-blue-500 bg-blue-50': t.type === 'info',
                    'border-yellow-500 bg-yellow-50': t.type === 'warning',
                }" role="alert">
                <div class="flex items-start gap-3">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <svg v-if="t.type === 'success'" class="w-5 h-5 text-green-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg v-else-if="t.type === 'error'" class="w-5 h-5 text-red-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <svg v-else-if="t.type === 'info'" class="w-5 h-5 text-blue-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg v-else-if="t.type === 'warning'" class="w-5 h-5 text-yellow-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ t.title }}</p>
                        <p v-if="t.description" class="mt-1 text-sm text-gray-600 whitespace-pre-line">{{ t.description
                            }}</p>
                    </div>

                    <!-- Close button -->
                    <button @click="removeToast(t.id)" class="flex-shrink-0 text-gray-400 hover:text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Progress bar -->
                <div class="mt-2 h-1 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-100 ease-linear" :class="{
                        'bg-green-500': t.type === 'success',
                        'bg-red-500': t.type === 'error',
                        'bg-blue-500': t.type === 'info',
                        'bg-yellow-500': t.type === 'warning',
                    }" :style="{ width: t.progress + '%' }" />
                </div>
            </div>
        </TransitionGroup>
    </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(100%);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(100%);
}

@keyframes slide-in {
    from {
        opacity: 0;
        transform: translateX(100%);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}
</style>
