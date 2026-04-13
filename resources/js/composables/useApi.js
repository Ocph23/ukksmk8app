import { ref } from 'vue';
import axios from 'axios';

export function useApi(baseUrl = '/api') {
    const loading = ref(false);
    const error = ref(null);

    async function request(method, endpoint, data = null) {
        loading.value = true;
        error.value = null;
        try {
            const response = await axios({
                method,
                url: `${baseUrl}${endpoint}`,
                data,
            });
            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || err.message || 'Terjadi kesalahan';
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function get(endpoint) {
        return request('get', endpoint);
    }

    async function post(endpoint, data) {
        return request('post', endpoint, data);
    }

    async function put(endpoint, data) {
        return request('put', endpoint, data);
    }

    async function del(endpoint) {
        return request('delete', endpoint);
    }

    return { loading, error, get, post, put, del };
}
