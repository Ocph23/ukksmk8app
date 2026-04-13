<template>
    <AdminLayout>
        <div class="max-w-6xl mx-auto">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Laporan Kelulusan</h1>
                <p class="text-sm text-gray-500 mt-1">Laporan kelulusan siswa per tahun ajaran dan jurusan</p>
            </div>

            <!-- Filters -->
            <Card class="mb-4">
                <CardContent class="p-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <Label class="text-xs text-gray-500 mb-1">Tahun Ajaran</Label>
                            <AppSelect v-model="filterTa">
                                <option value="">Pilih</option>
                                <option v-for="ta in tahunAjaranList" :key="ta.id" :value="ta.id">{{ ta.tahunajaran }}</option>
                            </AppSelect>
                        </div>
                        <div class="flex-1">
                            <Label class="text-xs text-gray-500 mb-1">Jurusan</Label>
                            <AppSelect v-model="filterJurusan">
                                <option value="">Pilih</option>
                                <option v-for="j in jurusanList" :key="j.id" :value="j.id">{{ j.nama }}</option>
                            </AppSelect>
                        </div>
                        <div class="flex items-end">
                            <Button @click="fetchReport" :disabled="!filterTa || !filterJurusan || loading">
                                Tampilkan
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Report -->
            <Card v-if="report.length > 0">
                <CardHeader>
                    <CardTitle>Hasil Laporan</CardTitle>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500">{{ lulusCount }} / {{ report.length }} siswa lulus</p>
                        <Button variant="outline" size="sm" @click="printReport">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                            Cetak
                        </Button>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>NIS</TableHead>
                                <TableHead>Nama</TableHead>
                                <TableHead>L/P</TableHead>
                                <TableHead>Paket</TableHead>
                                <TableHead class="text-center">Status</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(item, index) in report" :key="index">
                                <TableCell class="font-mono text-sm">{{ item.nis }}</TableCell>
                                <TableCell class="font-medium">{{ item.nama }}</TableCell>
                                <TableCell>{{ item.jk }}</TableCell>
                                <TableCell>{{ item.paket }}</TableCell>
                                <TableCell class="text-center">
                                    <Badge :variant="item.status === 'Lulus' ? 'default' : 'secondary'">
                                        {{ item.status || 'Belum Lulus' }}
                                    </Badge>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Label } from '@/components/ui/label';
import AppSelect from '@/Components/ui/AppSelect.vue';
import { useApi } from '@/composables/useApi';

const { loading, get, activeTahunAjaranId, fetchActiveTahunAjaran } = useApi();
const filterTa = ref('');
const filterJurusan = ref('');
const report = ref([]);
const tahunAjaranList = ref([]);
const jurusanList = ref([]);

const lulusCount = computed(() => report.value.filter(r => r.status === 'Lulus').length);

async function fetchReport() {
    if (!filterTa.value || !filterJurusan.value) return;
    try {
        const data = await get(`/laporan/${filterTa.value}/${filterJurusan.value}`);
        report.value = Array.isArray(data) ? data : [];
    } catch (e) { console.error(e); }
}

function printReport() { window.print(); }

onMounted(async () => {
    await fetchActiveTahunAjaran();
    if (activeTahunAjaranId.value) {
        filterTa.value = activeTahunAjaranId.value;
    }
    try {
        const [ta, jurusan] = await Promise.all([get('/tahunajaran'), get('/jurusan')]);
        tahunAjaranList.value = Array.isArray(ta) ? ta : [];
        jurusanList.value = Array.isArray(jurusan) ? jurusan : [];
    } catch (e) { console.error(e); }
});
</script>
