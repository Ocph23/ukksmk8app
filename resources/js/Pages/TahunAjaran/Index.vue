<template>
    <AdminLayout>
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tahun Ajaran</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola data tahun ajaran</p>
                </div>
                <Button @click="openDialog()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Tahun Ajaran
                </Button>
            </div>

            <Card>
                <CardContent class="p-0">
                    <div v-if="loading" class="p-8 text-center text-gray-500">Loading...</div>
                    <div v-else-if="items.length === 0" class="p-8 text-center text-gray-500">
                        Belum ada data tahun ajaran.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No</TableHead>
                                    <TableHead>Tahun Ajaran</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(item, index) in items" :key="item.id">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell class="font-medium">{{ item.tahunajaran }}</TableCell>
                                    <TableCell>
                                        <Badge :variant="item.status === 'aktif' ? 'default' : 'secondary'">
                                            {{ item.status || 'tidak aktif' }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button variant="outline" size="sm" @click="openDialog(item)">Edit</Button>
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
                    <DialogTitle>{{ isEdit ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran' }}</DialogTitle>
                    <DialogDescription>{{ isEdit ? 'Perbarui data tahun ajaran' : 'Isi data tahun ajaran baru' }}</DialogDescription>
                </DialogHeader>
                <form @submit.prevent="save" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="tahunajaran">Tahun Ajaran</Label>
                        <Input id="tahunajaran" v-model="form.tahunajaran" placeholder="Contoh: 2024/2025" />
                    </div>
                    <div class="space-y-2">
                        <Label for="status">Status</Label>
                        <select id="status" v-model="form.status" class="w-full h-10 rounded-md border border-input bg-background px-3">
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
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
                    <DialogDescription>Apakah Anda yakin ingin menghapus tahun ajaran "{{ deleteItem?.tahunajaran }}"?</DialogDescription>
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
import { ref, onMounted } from 'vue';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useApi } from '@/composables/useApi';

const { loading, get, post, put, del } = useApi();
const items = ref([]);
const dialogOpen = ref(false);
const isEdit = ref(false);
const saving = ref(false);
const deleteDialogOpen = ref(false);
const deleteItem = ref(null);
const deleting = ref(false);

const form = ref({ id: null, tahunajaran: '', status: 'aktif' });

async function fetchItems() {
    try {
        const data = await get('/tahunajaran');
        items.value = Array.isArray(data) ? data : [];
    } catch (e) { console.error(e); }
}

function openDialog(item = null) {
    if (item) {
        isEdit.value = true;
        form.value = { id: item.id, tahunajaran: item.tahunajaran, status: item.status || 'aktif' };
    } else {
        isEdit.value = false;
        form.value = { id: null, tahunajaran: '', status: 'aktif' };
    }
    dialogOpen.value = true;
}

async function save() {
    saving.value = true;
    try {
        if (isEdit.value) await put(`/tahunajaran/${form.value.id}`, form.value);
        else await post('/tahunajaran', form.value);
        dialogOpen.value = false;
        await fetchItems();
    } catch (e) { console.error(e); } finally { saving.value = false; }
}

function confirmDelete(item) { deleteItem.value = item; deleteDialogOpen.value = true; }

async function executeDelete() {
    if (!deleteItem.value) return;
    deleting.value = true;
    try {
        await del(`/tahunajaran/${deleteItem.value.id}`);
        deleteDialogOpen.value = false;
        await fetchItems();
    } catch (e) { console.error(e); } finally { deleting.value = false; }
}

onMounted(fetchItems);
</script>
