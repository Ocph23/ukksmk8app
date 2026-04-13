<template>
    <AdminLayout>
        <div v-if="loading" class="max-w-4xl mx-auto p-8 text-center text-gray-500">Loading...</div>
        <div v-else-if="!siswa" class="max-w-4xl mx-auto p-8 text-center text-gray-500">Data siswa tidak ditemukan.</div>
        <div v-else class="max-w-4xl mx-auto">
            <!-- Back button -->
            <button @click="$inertia.visit('/admin/siswa')" class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Kembali ke Daftar Siswa
            </button>

            <!-- Profile Card -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Data Siswa</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">NIS</p>
                            <p class="font-medium">{{ siswa.nis }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
                            <p class="font-medium">{{ siswa.nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenis Kelamin</p>
                            <p class="font-medium">{{ siswa.jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tempat, Tanggal Lahir</p>
                            <p class="font-medium">{{ siswa.tempatlahir }}, {{ siswa.tanggallahir }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Alamat</p>
                            <p class="font-medium">{{ siswa.alamat }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jurusan</p>
                            <p class="font-medium">{{ siswa.jurusan?.nama || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tahun Ajaran</p>
                            <p class="font-medium">{{ siswa.tahunajaran?.tahunajaran || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Paket</p>
                            <p class="font-medium">{{ siswa.paket?.nama || '-' }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Penilaian Section -->
            <Card>
                <CardHeader>
                    <CardTitle>Penilaian</CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="!siswa.penilaian || siswa.penilaian.length === 0" class="text-center text-gray-500 py-4">
                        Belum ada data penilaian.
                    </div>
                    <div v-else class="space-y-4">
                        <div v-for="(penilaian, pIdx) in siswa.penilaian" :key="pIdx" class="border rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold">{{ penilaian.kompetensi?.nama || 'Kompetensi' }}</h3>
                                <Badge :variant="penilaian.status === 'lulus' ? 'default' : 'destructive'">
                                    {{ penilaian.status || 'belum dinilai' }}
                                </Badge>
                            </div>
                            <p class="text-sm text-gray-500">Asesor: {{ penilaian.asesor?.nama || '-' }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { useApi } from '@/composables/useApi';

const page = usePage();
const siswaId = page.props.siswaId || page.url.split('/').pop();
const { loading, get } = useApi();
const siswa = ref(null);

onMounted(async () => {
    try {
        const data = await get(`/siswa/${siswaId}`);
        siswa.value = data;
    } catch (e) { console.error(e); }
});
</script>
