<template>
    <AdminLayout>
        <div class="max-w-6xl mx-auto">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Penilaian</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola data penilaian uji kompetensi</p>
            </div>

            <Card>
                <CardContent class="p-0">
                    <div v-if="loading" class="p-8 text-center text-gray-500">Loading...</div>
                    <div v-else-if="items.length === 0" class="p-8 text-center text-gray-500">
                        Belum ada data penilaian.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No</TableHead>
                                    <TableHead>Siswa</TableHead>
                                    <TableHead>Paket</TableHead>
                                    <TableHead>Mulai</TableHead>
                                    <TableHead>Selesai</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(item, index) in items" :key="item.id">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell class="font-medium">{{ item.siswa?.nama || '-' }}</TableCell>
                                    <TableCell>{{ item.paket?.nama || '-' }}</TableCell>
                                    <TableCell>{{ item.mulai ? formatDate(item.mulai) : '-' }}</TableCell>
                                    <TableCell>{{ item.selesai ? formatDate(item.selesai) : '-' }}</TableCell>
                                    <TableCell class="text-right">
                                        <Button variant="outline" size="sm" @click="openDialog(item)">Detail</Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Detail/Edit Dialog -->
        <Dialog :open="dialogOpen" @update:open="dialogOpen = $event">
            <DialogContent class="sm:max-w-2xl max-h-[90vh] overflow-y-auto">
                <DialogHeader>
                    <DialogTitle>Detail Penilaian</DialogTitle>
                </DialogHeader>
                <div v-if="selected" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Siswa</p>
                            <p class="font-medium">{{ selected.siswa?.nama || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Paket</p>
                            <p class="font-medium">{{ selected.paket?.nama || '-' }}</p>
                        </div>
                    </div>

                    <!-- Detail Penilaian Items -->
                    <div v-if="selected.detail && selected.detail.length > 0">
                        <h3 class="font-semibold mb-2">Detail Kompetensi</h3>
                        <div v-for="(detail, idx) in selected.detail" :key="detail.id" class="border rounded-lg p-3 mb-2">
                            <div class="flex items-center justify-between">
                                <span class="font-medium">{{ detail.kompetensi?.nama || `Kompetensi ${idx + 1}` }}</span>
                                <Badge :variant="detail.kompeten ? 'default' : 'destructive'">
                                    {{ detail.kompeten ? 'Kompeten' : 'Belum Kompeten' }}
                                </Badge>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Nilai: {{ detail.nilai || 0 }}</p>
                        </div>
                    </div>
                </div>
                <DialogFooter>
                    <Button @click="dialogOpen = false">Tutup</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useApi } from '@/composables/useApi';

const { loading, get } = useApi();
const items = ref([]);
const dialogOpen = ref(false);
const selected = ref(null);

function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

async function fetchItems() {
    try {
        const data = await get('/penilaian');
        items.value = Array.isArray(data) ? data : [];
    } catch (e) { console.error(e); }
}

async function openDialog(item) {
    try {
        const detail = await get(`/penilaian/${item.id}`);
        selected.value = detail;
        dialogOpen.value = true;
    } catch (e) { console.error(e); }
}

onMounted(fetchItems);
</script>
