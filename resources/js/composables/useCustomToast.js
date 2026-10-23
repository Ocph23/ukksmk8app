import { toast } from 'vue-sonner';

let lastErrorKey = '';
let lastErrorAt = 0;

function showToast(type, title, description = '', duration = 5000) {
    if (type === 'error') {
        const key = `${title}:${description}`;
        const now = Date.now();
        if (key === lastErrorKey && now - lastErrorAt < 800) return;
        lastErrorKey = key;
        lastErrorAt = now;
    }
    return toast[type](title, { description, duration });
}

export function removeToast(id) {
    toast.dismiss(id);
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
    const message = response?.message || err?.message || fallbackMessage;

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
        removeToast,
    };
}
