<template>
    <AdminLayout>
        <div v-if="loading" class="max-w-4xl mx-auto p-8 text-center text-gray-500">Loading...</div>
        <div v-else-if="!paket" class="max-w-4xl mx-auto p-8 text-center text-gray-500">Data paket tidak ditemukan.</div>
        <div v-else class="max-w-4xl mx-auto">
            <button @click="$inertia.visit('/admin/paket')" class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Kembali ke Daftar Paket
            </button>

            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Data Paket</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama Paket</p>
                            <p class="font-medium">{{ paket.nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tahun Ajaran</p>
                            <p class="font-medium">{{ paket.tahunajaran?.tahunajaran || '-' }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Kompetensi</CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="!paket.kompetensi || paket.kompetensi.length === 0" class="text-center text-gray-500 py-4">
                        Belum ada data kompetensi.
                    </div>
                    <Table v-else>
                        <TableHeader>
                            <TableRow>
                                <TableHead>No</TableHead>
                                <TableHead>Nama Kompetensi</TableHead>
                                <TableHead>Deskripsi</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(komp, idx) in paket.kompetensi" :key="komp.id">
                                <TableCell>{{ idx + 1 }}</TableCell>
                                <TableCell class="font-medium">{{ komp.nama }}</TableCell>
                                <TableCell>{{ komp.deskripsi || '-' }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
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
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useApi } from '@/composables/useApi';

const page = usePage();
const paketId = page.props.paketId || page.url.split('/').pop();
const { loading, get } = useApi();
const paket = ref(null);

onMounted(async () => {
    try {
        const data = await get(`/paket/${paketId}`);
        paket.value = data;
    } catch (e) { console.error(e); }
});
</script>
