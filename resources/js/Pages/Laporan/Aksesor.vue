<template>
    <AdminLayout>
        <div class="max-w-6xl mx-auto">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Laporan Asesor</h1>
                <p class="text-sm text-gray-500 mt-1">Laporan asesor internal dan eksternal per tahun ajaran</p>
            </div>

            <!-- Filter -->
            <Card class="mb-4">
                <CardContent class="p-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <Label class="text-xs text-gray-500 mb-1">Tahun Ajaran</Label>
                            <AppSelect v-model="filterTa">
                                <option value="">Pilih</option>
                                <option v-for="ta in tahunAjaranList" :key="ta.id" :value="ta.id">{{ ta.tahun }}</option>
                            </AppSelect>
                        </div>
                        <div class="flex items-end">
                            <Button @click="fetchReport" :disabled="!filterTa || loading">Tampilkan</Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Report -->
            <Card v-if="report.length > 0">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Hasil Laporan</CardTitle>
                            <p class="text-sm text-gray-500 mt-1">{{ report.length }} asesor terdaftar</p>
                        </div>
                        <Button variant="outline" size="sm" @click="printReport">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak
                        </Button>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>No</TableHead>
                                <TableHead>Nama</TableHead>
                                <TableHead>L/P</TableHead>
                                <TableHead>Instansi</TableHead>
                                <TableHead>Jenis</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(item, index) in report" :key="index">
                                <TableCell>{{ index + 1 }}</TableCell>
                                <TableCell class="font-medium">{{ item.nama }}</TableCell>
                                <TableCell>{{ item.jk }}</TableCell>
                                <TableCell>{{ item.instansi || '-' }}</TableCell>
                                <TableCell>
                                    <Badge :variant="item.jenis === 'internal' ? 'default' : 'secondary'">
                                        {{ item.jenis }}
                                    </Badge>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>

        <!-- Print Section -->
        <div id="laporan-aksesor-print">
            <div class="print-header">
                <h2>LAPORAN ASESOR UJI KOMPETENSI KEJURUAN</h2>
                <h3>SMK NEGERI 8 TEKNOLOGI INFORMASI DAN KOMUNIKASI KOTA JAYAPURA</h3>
                <p>Tahun Ajaran: {{ selectedTaNama }}</p>
                <hr>
            </div>
            <table class="print-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>L/P</th>
                        <th>Instansi</th>
                        <th>Jenis</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in report" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ item.nama }}</td>
                        <td>{{ item.jk }}</td>
                        <td>{{ item.instansi || '-' }}</td>
                        <td>{{ item.jenis }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"><strong>Total Asesor</strong></td>
                        <td><strong>{{ report.length }}</strong></td>
                    </tr>
                </tfoot>
            </table>
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
const report = ref([]);
const tahunAjaranList = ref([]);

const selectedTaNama = computed(() => tahunAjaranList.value.find(t => t.id == filterTa.value)?.tahunajaran || '');

async function fetchReport() {
    if (!filterTa.value) return;
    try {
        const data = await get(`/laporan/${filterTa.value}`);
        report.value = Array.isArray(data) ? data : [];
    } catch (e) { console.error(e); }
}

function printReport() { window.print(); }

onMounted(async () => {
    await fetchActiveTahunAjaran();
    if (activeTahunAjaranId.value) filterTa.value = activeTahunAjaranId.value;
    try {
        const ta = await get('/tahunajaran');
        tahunAjaranList.value = Array.isArray(ta) ? ta : [];
    } catch (e) { console.error(e); }
});
</script>

<style>
#laporan-aksesor-print {
    display: none;
}

@media print {
    @page { margin: 20mm; }

    body * { visibility: hidden !important; }

    #laporan-aksesor-print,
    #laporan-aksesor-print * { visibility: visible !important; }

    #laporan-aksesor-print {
        display: block !important;
        position: fixed;
        top: 0; left: 0;
        width: 100%;
        background: white;
        z-index: 99999;
    }

    .print-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .print-header h2 {
        font-size: 16px;
        font-weight: bold;
        margin: 0;
    }

    .print-header h3 {
        font-size: 13px;
        margin: 4px 0;
    }

    .print-header p {
        font-size: 12px;
        margin: 6px 0;
    }

    .print-header hr {
        border-top: 2px solid #000;
        margin: 10px 0;
    }

    .print-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .print-table th,
    .print-table td {
        border: 1px solid #333;
        padding: 6px 8px;
        text-align: left;
    }

    .print-table th {
        background-color: #f0f0f0;
        font-weight: bold;
        text-align: center;
    }

    .print-table tfoot td {
        font-weight: bold;
        background-color: #f9f9f9;
    }
}
</style>
