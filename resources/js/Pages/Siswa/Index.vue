<template>
    <AdminLayout>
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Siswa</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola data siswa</p>
                </div>
                <Button @click="openDialog()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Siswa
                </Button>
            </div>

            <!-- Filters -->
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
                        <div class="flex-1">
                            <Label class="text-xs text-gray-500 mb-1">Cari NIS / Nama</Label>
                            <Input v-model="search" placeholder="Ketik untuk mencari..." @input="filterItems" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Table -->
            <Card>
                <CardContent class="p-0">
                    <div v-if="loading" class="p-8 text-center text-gray-500">Loading...</div>
                    <div v-else-if="filteredItems.length === 0" class="p-8 text-center text-gray-500">
                        Belum ada data siswa.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No</TableHead>
                                    <TableHead>NIS</TableHead>
                                    <TableHead>Nama</TableHead>
                                    <TableHead>L/P</TableHead>
                                    <TableHead>Jurusan</TableHead>
                                    <TableHead>Tahun Ajaran</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(item, index) in filteredItems" :key="item.id">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell class="font-mono text-sm">{{ item.nis }}</TableCell>
                                    <TableCell class="font-medium">{{ item.nama }}</TableCell>
                                    <TableCell>{{ item.jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</TableCell>
                                    <TableCell>{{ item.jurusan?.nama || '-' }}</TableCell>
                                    <TableCell>{{ item.tahunajaran?.tahunajaran || '-' }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button variant="outline" size="sm" @click="openDialog(item)">Edit</Button>
                                            <Button variant="default" size="sm" @click="$inertia.visit(`/admin/siswa/${item.id}`)">Detail</Button>
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

        <!-- Add/Edit Dialog -->
        <Dialog :open="dialogOpen" @update:open="dialogOpen = $event">
            <DialogContent class="sm:max-w-lg max-h-[90vh] overflow-y-auto">
                <DialogHeader>
                    <DialogTitle>{{ isEdit ? 'Edit Siswa' : 'Tambah Siswa' }}</DialogTitle>
                    <DialogDescription>{{ isEdit ? 'Perbarui data siswa' : 'Isi data siswa baru' }}</DialogDescription>
                </DialogHeader>
                <form @submit.prevent="save" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-2">
                            <Label for="nis">NIS</Label>
                            <Input id="nis" v-model="form.nis" placeholder="NIS" />
                        </div>
                        <div class="space-y-2">
                            <Label for="nama">Nama</Label>
                            <Input id="nama" v-model="form.nama" placeholder="Nama lengkap" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-2">
                            <Label for="jk">Jenis Kelamin</Label>
                            <select id="jk" v-model="form.jk" class="w-full h-10 rounded-md border border-input bg-background px-3 text-sm">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label for="tempatlahir">Tempat Lahir</Label>
                            <Input id="tempatlahir" v-model="form.tempatlahir" placeholder="Tempat lahir" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <Label for="tanggallahir">Tanggal Lahir</Label>
                        <Input id="tanggallahir" v-model="form.tanggallahir" type="date" />
                    </div>
                    <div class="space-y-2">
                        <Label for="alamat">Alamat</Label>
                        <Input id="alamat" v-model="form.alamat" placeholder="Alamat" />
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="space-y-2">
                            <Label for="jurusan_id">Jurusan</Label>
                            <select id="jurusan_id" v-model="form.jurusan_id" class="w-full h-10 rounded-md border border-input bg-background px-3 text-sm">
                                <option value="">Pilih</option>
                                <option v-for="j in jurusanList" :key="j.id" :value="j.id">{{ j.nama }}</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label for="tahunajaran_id">Tahun Ajaran</Label>
                            <select id="tahunajaran_id" v-model="form.tahunajaran_id" class="w-full h-10 rounded-md border border-input bg-background px-3 text-sm">
                                <option value="">Pilih</option>
                                <option v-for="ta in tahunAjaranList" :key="ta.id" :value="ta.id">{{ ta.tahunajaran }}</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label for="paket_id">Paket</Label>
                            <select id="paket_id" v-model="form.paket_id" class="w-full h-10 rounded-md border border-input bg-background px-3 text-sm">
                                <option value="">Pilih</option>
                                <option v-for="p in paketList" :key="p.id" :value="p.id">{{ p.nama }}</option>
                            </select>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="dialogOpen = false">Batal</Button>
                        <Button type="submit" :disabled="saving">{{ saving ? 'Menyimpan...' : 'Simpan' }}</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Delete Dialog -->
        <Dialog :open="deleteDialogOpen" @update:open="deleteDialogOpen = $event">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Konfirmasi Hapus</DialogTitle>
                    <DialogDescription>Apakah Anda yakin ingin menghapus siswa "{{ deleteItem?.nama }}"?</DialogDescription>
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
const search = ref('');
const filterTahun = ref('');
const dialogOpen = ref(false);
const isEdit = ref(false);
const saving = ref(false);
const deleteDialogOpen = ref(false);
const deleteItem = ref(null);
const deleting = ref(false);

// Reference data
const jurusanList = ref([]);
const tahunAjaranList = ref([]);
const paketList = ref([]);

const form = ref({
    id: null, nis: '', nama: '', jk: 'L', tempatlahir: '', tanggallahir: '',
    alamat: '', jurusan_id: '', tahunajaran_id: '', paket_id: '',
});

const filteredItems = computed(() => {
    let result = items.value;
    if (filterTahun.value) {
        result = result.filter(i => i.tahunajaran_id == filterTahun.value);
    }
    if (search.value) {
        const q = search.value.toLowerCase();
        result = result.filter(i => i.nis.toLowerCase().includes(q) || i.nama.toLowerCase().includes(q));
    }
    return result;
});

async function fetchItems() {
    try {
        const [siswa, jurusan, ta, paket] = await Promise.all([
            get('/siswa'),
            get('/jurusan'),
            get('/tahunajaran'),
            get('/paket'),
        ]);
        items.value = Array.isArray(siswa) ? siswa : [];
        jurusanList.value = Array.isArray(jurusan) ? jurusan : [];
        tahunAjaranList.value = Array.isArray(ta) ? ta : [];
        paketList.value = Array.isArray(paket) ? paket : [];
    } catch (e) { console.error(e); }
}

function filterItems() { /* computed handles this */ }

function openDialog(item = null) {
    if (item) {
        isEdit.value = true;
        form.value = {
            id: item.id, nis: item.nis, nama: item.nama, jk: item.jk || 'L',
            tempatlahir: item.tempatlahir || '', tanggallahir: item.tanggallahir || '',
            alamat: item.alamat || '', jurusan_id: item.jurusan_id || '',
            tahunajaran_id: item.tahunajaran_id || '', paket_id: item.paket_id || '',
        };
    } else {
        isEdit.value = false;
        form.value = { id: null, nis: '', nama: '', jk: 'L', tempatlahir: '', tanggallahir: '', alamat: '', jurusan_id: '', tahunajaran_id: '', paket_id: '' };
    }
    dialogOpen.value = true;
}

async function save() {
    saving.value = true;
    try {
        if (isEdit.value) await put(`/siswa/${form.value.id}`, form.value);
        else await post('/siswa', form.value);
        dialogOpen.value = false;
        await fetchItems();
    } catch (e) { console.error(e); } finally { saving.value = false; }
}

function confirmDelete(item) { deleteItem.value = item; deleteDialogOpen.value = true; }
async function executeDelete() {
    if (!deleteItem.value) return;
    deleting.value = true;
    try { await del(`/siswa/${deleteItem.value.id}`); deleteDialogOpen.value = false; await fetchItems(); }
    catch (e) { console.error(e); } finally { deleting.value = false; }
}

onMounted(fetchItems);
</script>
