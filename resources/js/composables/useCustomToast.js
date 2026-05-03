import { ref } from 'vue';

// Global toast state - shared across all components
const toasts = ref([]);
let toastId = 0;

function showToast(type, title, description = '', duration = 5000) {
    const id = ++toastId;
    const progress = ref(100);
    const toast = { id, type, title, description, duration, progress };
    toasts.value.push(toast);

    if (duration > 0) {
        const interval = setInterval(() => {
            if (toast.progress.value > 0) {
                toast.progress.value -= 100 / (duration / 100);
            } else {
                clearInterval(interval);
            }
        }, 100);

        setTimeout(() => {
            removeToast(id);
            clearInterval(interval);
        }, duration);
    }

    return id;
}

export function removeToast(id) {
    const index = toasts.value.findIndex(t => t.id === id);
    if (index > -1) {
        toasts.value.splice(index, 1);
    }
}

export function success(title, description) {
    return showToast('success', title, description);
}

export function errorToast(title, description) {
    return showToast('error', title, description);
}

export function info(title, description) {
    return showToast('info', title, description);
}

export function warning(title, description) {
    return showToast('warning', title, description);
}

export function httpError(err, fallbackMessage = 'Terjadi kesalahan') {
    const response = err?.response?.data;
    const errors = response?.errors;
    const message = response?.message || fallbackMessage;

    if (errors && typeof errors === 'object') {
        const errorMessages = Object.values(errors)
            .flat()
            .filter(msg => typeof msg === 'string')
            .join('\n');
        return errorToast('Validasi Gagal', errorMessages || message);
    } else {
        return errorToast('Error', String(message));
    }
}

// Export composable
export function useToast() {
    return {
        success,
        error: errorToast,
        info,
        warning,
        httpError,
        toasts,
        removeToast,
    };
}
