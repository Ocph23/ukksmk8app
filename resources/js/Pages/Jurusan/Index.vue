<template>
    <AdminLayout>
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Jurusan</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola data jurusan</p>
                </div>
                <Button @click="openDialog()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Jurusan
                </Button>
            </div>

            <!-- Table -->
            <Card>
                <CardContent class="p-0">
                    <div v-if="loading" class="p-8 text-center text-gray-500">Loading...</div>
                    <div v-else-if="items.length === 0" class="p-8 text-center text-gray-500">
                        Belum ada data jurusan.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No</TableHead>
                                    <TableHead>Kode</TableHead>
                                    <TableHead>Nama</TableHead>
                                    <TableHead>Deskripsi</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(item, index) in items" :key="item.id">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell class="font-mono text-sm">{{ item.kode }}</TableCell>
                                    <TableCell class="font-medium">{{ item.nama }}</TableCell>
                                    <TableCell class="max-w-xs truncate">{{ item.deskripsi }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button variant="outline" size="sm" @click="openDialog(item)">
                                                Edit
                                            </Button>
                                            <Button variant="destructive" size="sm" @click="confirmDelete(item)">
                                                Hapus
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Add/Edit Dialog -->
        <Dialog :open="dialogOpen" @update:open="dialogOpen = $event">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>{{ isEdit ? 'Edit Jurusan' : 'Tambah Jurusan' }}</DialogTitle>
                    <DialogDescription>
                        {{ isEdit ? 'Perbarui data jurusan' : 'Isi data jurusan baru' }}
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="save" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="kode">Kode</Label>
                        <Input id="kode" v-model="form.kode" placeholder="Contoh: RPL" />
                        <p v-if="errors.kode" class="text-red-500 text-xs">{{ errors.kode }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="nama">Nama</Label>
                        <Input id="nama" v-model="form.nama" placeholder="Contoh: Rekayasa Perangkat Lunak" />
                        <p v-if="errors.nama" class="text-red-500 text-xs">{{ errors.nama }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="deskripsi">Deskripsi</Label>
                        <Input id="deskripsi" v-model="form.deskripsi" placeholder="Deskripsi jurusan" />
                        <p v-if="errors.deskripsi" class="text-red-500 text-xs">{{ errors.deskripsi }}</p>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="dialogOpen = false">Batal</Button>
                        <Button type="submit" :disabled="saving">
                            {{ saving ? 'Menyimpan...' : 'Simpan' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <Dialog :open="deleteDialogOpen" @update:open="deleteDialogOpen = $event">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Konfirmasi Hapus</DialogTitle>
                    <DialogDescription>
                        Apakah Anda yakin ingin menghapus jurusan "{{ deleteItem?.nama }}"?
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="deleteDialogOpen = false">Batal</Button>
                    <Button variant="destructive" :disabled="deleting" @click="executeDelete">
                        {{ deleting ? 'Menghapus...' : 'Hapus' }}
                    </Button>
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
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useApi } from '@/composables/useApi';

const { loading, get, post, put, del } = useApi();

const items = ref([]);
const dialogOpen = ref(false);
const isEdit = ref(false);
const saving = ref(false);
const errors = ref({});

const form = ref({
    id: null,
    kode: '',
    nama: '',
    deskripsi: '',
});

// Delete state
const deleteDialogOpen = ref(false);
const deleteItem = ref(null);
const deleting = ref(false);

async function fetchItems() {
    try {
        const data = await get('/jurusan');
        items.value = Array.isArray(data) ? data : [];
    } catch (e) {
        console.error('Failed to fetch jurusan:', e);
    }
}

function openDialog(item = null) {
    if (item) {
        isEdit.value = true;
        form.value = { id: item.id, kode: item.kode, nama: item.nama, deskripsi: item.deskripsi };
    } else {
        isEdit.value = false;
        form.value = { id: null, kode: '', nama: '', deskripsi: '' };
    }
    errors.value = {};
    dialogOpen.value = true;
}

async function save() {
    saving.value = true;
    errors.value = {};
    try {
        if (isEdit.value) {
            await put(`/jurusan/${form.value.id}`, form.value);
        } else {
            await post('/jurusan', form.value);
        }
        dialogOpen.value = false;
        await fetchItems();
    } catch (e) {
        const msg = e.response?.data?.message;
        if (msg) {
            errors.value = { general: msg };
        } else {
            errors.value = { general: 'Gagal menyimpan data' };
        }
    } finally {
        saving.value = false;
    }
}

function confirmDelete(item) {
    deleteItem.value = item;
    deleteDialogOpen.value = true;
}

async function executeDelete() {
    if (!deleteItem.value) return;
    deleting.value = true;
    try {
        await del(`/jurusan/${deleteItem.value.id}`);
        deleteDialogOpen.value = false;
        await fetchItems();
    } catch (e) {
        console.error('Failed to delete:', e);
    } finally {
        deleting.value = false;
    }
}

onMounted(fetchItems);
</script>
