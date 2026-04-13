<template>
    <AdminLayout>
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Paket</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola data paket uji kompetensi</p>
                </div>
                <Button @click="openDialog()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Paket
                </Button>
            </div>

            <!-- Filter -->
            <Card class="mb-4">
                <CardContent class="p-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <Label class="text-xs text-gray-500 mb-1">Tahun Ajaran</Label>
                            <select v-model="filterTahun" @change="fetchItems" class="w-full h-10 rounded-md border border-input bg-background px-3 text-sm">
                                <option value="">Semua</option>
                                <option v-for="ta in tahunAjaranList" :key="ta.id" :value="ta.id">{{ ta.tahunajaran }}</option>
                            </select>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="p-0">
                    <div v-if="loading" class="p-8 text-center text-gray-500">Loading...</div>
                    <div v-else-if="filteredItems.length === 0" class="p-8 text-center text-gray-500">
                        Belum ada data paket.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No</TableHead>
                                    <TableHead>Nama</TableHead>
                                    <TableHead>Tahun Ajaran</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(item, index) in filteredItems" :key="item.id">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell class="font-medium">{{ item.nama }}</TableCell>
                                    <TableCell>{{ item.tahunajaran?.tahunajaran || '-' }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button variant="outline" size="sm" @click="openDialog(item)">Edit</Button>
                                            <Button variant="default" size="sm" @click="$inertia.visit(`/admin/paket/${item.id}`)">Detail</Button>
                                            <Button variant="destructive" size="sm" @click="confirmDelete(item)">Hapus</Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>

        <Dialog :open="dialogOpen" @update:open="dialogOpen = $event">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>{{ isEdit ? 'Edit Paket' : 'Tambah Paket' }}</DialogTitle>
                    <DialogDescription>{{ isEdit ? 'Perbarui data paket' : 'Isi data paket baru' }}</DialogDescription>
                </DialogHeader>
                <form @submit.prevent="save" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="nama">Nama Paket</Label>
                        <Input id="nama" v-model="form.nama" placeholder="Nama paket uji kompetensi" />
                    </div>
                    <div class="space-y-2">
                        <Label for="tahunajaran_id">Tahun Ajaran</Label>
                        <select id="tahunajaran_id" v-model="form.tahunajaran_id" class="w-full h-10 rounded-md border border-input bg-background px-3 text-sm">
                            <option value="">Pilih</option>
                            <option v-for="ta in tahunAjaranList" :key="ta.id" :value="ta.id">{{ ta.tahunajaran }}</option>
                        </select>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="dialogOpen = false">Batal</Button>
                        <Button type="submit" :disabled="saving">{{ saving ? 'Menyimpan...' : 'Simpan' }}</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <Dialog :open="deleteDialogOpen" @update:open="deleteDialogOpen = $event">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Konfirmasi Hapus</DialogTitle>
                    <DialogDescription>Apakah Anda yakin ingin menghapus paket "{{ deleteItem?.nama }}"?</DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="deleteDialogOpen = false">Batal</Button>
                    <Button variant="destructive" :disabled="deleting" @click="executeDelete">{{ deleting ? 'Menghapus...' : 'Hapus' }}</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useApi } from '@/composables/useApi';

const { loading, get, post, put, del } = useApi();
const items = ref([]);
const filterTahun = ref('');
const tahunAjaranList = ref([]);
const dialogOpen = ref(false);
const isEdit = ref(false);
const saving = ref(false);
const deleteDialogOpen = ref(false);
const deleteItem = ref(null);
const deleting = ref(false);
const form = ref({ id: null, nama: '', tahunajaran_id: '' });

const filteredItems = computed(() => {
    let result = items.value;
    if (filterTahun.value) result = result.filter(i => i.tahunajaran_id == filterTahun.value);
    return result;
});

async function fetchItems() {
    try {
        const [paket, ta] = await Promise.all([get('/paket'), get('/tahunajaran')]);
        items.value = Array.isArray(paket) ? paket : [];
        tahunAjaranList.value = Array.isArray(ta) ? ta : [];
    } catch (e) { console.error(e); }
}

function openDialog(item = null) {
    if (item) { isEdit.value = true; form.value = { id: item.id, nama: item.nama, tahunajaran_id: item.tahunajaran_id || '' }; }
    else { isEdit.value = false; form.value = { id: null, nama: '', tahunajaran_id: '' }; }
    dialogOpen.value = true;
}

async function save() {
    saving.value = true;
    try {
        if (isEdit.value) await put(`/paket/${form.value.id}`, form.value);
        else await post('/paket', form.value);
        dialogOpen.value = false;
        await fetchItems();
    } catch (e) { console.error(e); } finally { saving.value = false; }
}

function confirmDelete(item) { deleteItem.value = item; deleteDialogOpen.value = true; }
async function executeDelete() {
    if (!deleteItem.value) return;
    deleting.value = true;
    try { await del(`/paket/${deleteItem.value.id}`); deleteDialogOpen.value = false; await fetchItems(); }
    catch (e) { console.error(e); } finally { deleting.value = false; }
}

onMounted(fetchItems);
</script>
