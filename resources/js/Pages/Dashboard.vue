<template>
    <AdminLayout>
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Selamat datang di Sistem Informasi UKK</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Siswa</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.siswa ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jurusan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.jurusan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Paket</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.paket ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Asesor</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.aksesor ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Tahun Ajaran Info -->
            <div v-if="activeTa" class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-700">
                    Tahun Ajaran Aktif: <span class="font-semibold">{{ activeTa.nama }}</span>
                    <span v-if="activeTa.kepala_sekolah"> — Kepala Sekolah: {{ activeTa.kepala_sekolah }}</span>
                </p>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { useApi } from '@/composables/useApi';

const { get } = useApi();
const stats = ref({ siswa: null, jurusan: null, paket: null, aksesor: null });
const activeTa = ref(null);

onMounted(async () => {
    try {
        const [siswa, jurusan, paket, aksesor, ta] = await Promise.all([
            get('/siswa'),
            get('/jurusan'),
            get('/paket'),
            get('/aksesor'),
            get('/tahunajaran/aktif'),
        ]);
        stats.value = {
            siswa: Array.isArray(siswa) ? siswa.length : 0,
            jurusan: Array.isArray(jurusan) ? jurusan.length : 0,
            paket: Array.isArray(paket) ? paket.length : 0,
            aksesor: Array.isArray(aksesor) ? aksesor.length : 0,
        };
        activeTa.value = ta || null;
    } catch (e) {
        console.error(e);
    }
});
</script>
