<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
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
                <CardContent class="px-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <Label class="text-xs text-gray-500 mb-1">Jurusan</Label>
                            <AppSelect v-model="filterJurusan" @update:model-value="applyFilter('jurusan_id', $event)">
                                <option value="">Semua</option>
                                <option v-for="j in $page.props.jurusanList" :key="j.id" :value="j.id">{{ j.nama }}
                                </option>
                            </AppSelect>
                        </div>
                        <div class="flex-1">
                            <Label class="text-xs text-gray-500 mb-1">Cari NIS / Nama</Label>
                            <Input v-model="search" placeholder="Ketik untuk mencari..."
                                @keyup.enter="applyFilter('search', search)" />
                        </div>
                        <div class="flex items-center">
                            <Button variant="outline" @click="resetFilters">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Table -->
            <Card>
                <CardContent class="p-0">
                    <div v-if="$page.props.siswa.data.length === 0" class="p-8 text-center text-gray-500">
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
                                    <TableHead>Paket</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(item, index) in $page.props.siswa.data" :key="item.id">
                                    <TableCell>{{ ($page.props.siswa.current_page - 1) * $page.props.siswa.per_page +
                                        index + 1 }}</TableCell>
                                    <TableCell class="font-mono text-sm">{{ item.nis }}</TableCell>
                                    <TableCell class="font-medium">{{ item.nama }}</TableCell>
                                    <TableCell>{{ item.jk === 'Pria' ? 'L' : 'P' }}</TableCell>
                                    <TableCell>{{ item.jurusan?.nama || '-' }}</TableCell>
                                    <TableCell>{{ item.tahunajaran?.nama || '-' }}</TableCell>
                                    <TableCell>{{ item.paket?.kode || '-' }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button variant="outline" size="sm" @click="openDialog(item)">Edit</Button>
                                            <Button variant="default" size="sm"
                                                @click="$inertia.visit(`/admin/siswa/${item.id}`)">Detail</Button>
                                            <Button variant="destructive" size="sm"
                                                @click="confirmDelete(item)">Hapus</Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="$page.props.siswa.last_page > 1"
                        class="flex items-center justify-between border-t px-4 py-3">
                        <div class="text-sm text-gray-500">
                            Menampilkan {{ $page.props.siswa.from }} - {{ $page.props.siswa.to }} dari
                            {{ $page.props.siswa.total }} data
                        </div>
                        <div class="flex items-center gap-1">
                            <Button variant="outline" size="sm" :disabled="$page.props.siswa.current_page === 1"
                                @click="goToPage($page.props.siswa.current_page - 1)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </Button>
                            <template v-for="page in visiblePages" :key="page">
                                <Button v-if="page === '...'" variant="outline" size="sm" disabled>...</Button>
                                <Button v-else variant="outline" size="sm"
                                    :class="{ 'bg-blue-50 text-blue-700 border-blue-300': page === $page.props.siswa.current_page }"
                                    @click="goToPage(page)">
                                    {{ page }}
                                </Button>
                            </template>
                            <Button variant="outline" size="sm"
                                :disabled="$page.props.siswa.current_page === $page.props.siswa.last_page"
                                @click="goToPage($page.props.siswa.current_page + 1)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </Button>
                        </div>
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
                            <AppSelect id="jk" v-model="form.jk">
                                <option value="Pria">Laki-laki</option>
                                <option value="Wanita">Perempuan</option>
                            </AppSelect>
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
                        <textarea id="alamat" v-model="form.alamat" placeholder="Alamat lengkap siswa" rows="3"
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" />
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="space-y-2">
                            <Label for="jurusan_id">Jurusan</Label>
                            <AppSelect id="jurusan_id" v-model="form.jurusan_id">
                                <option value="">Pilih</option>
                                <option v-for="j in $page.props.jurusanList" :key="j.id" :value="j.id">{{ j.nama }}
                                </option>
                            </AppSelect>
                        </div>

                        <div class="space-y-2">
                            <Label for="paket_id">Paket</Label>

                            <AppSelect id="paket_id" v-model="form.paket_id">
                                <option value="">Pilih</option>
                                <option v-for="p in filteredPaketList" :key="p.id" :value="p.id">{{ p.kode }} - {{
                                    p.judultugas
                                    }}</option>
                            </AppSelect>
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
                    <DialogDescription>Apakah Anda yakin ingin menghapus siswa "{{ deleteItem?.nama }}"?
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="deleteDialogOpen = false">Batal</Button>
                    <Button variant="destructive" :disabled="deleting" @click="executeDelete">{{ deleting ?
                        'Menghapus...' :
                        'Hapus' }}</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppSelect from '@/Components/ui/AppSelect.vue';
import { success, httpError } from '@/composables/useCustomToast';

const page = usePage();
const activeTahunAjaran = computed(() => page.props.activeTahunAjaran);

const dialogOpen = ref(false);
const isEdit = ref(false);
const saving = ref(false);
const deleteDialogOpen = ref(false);
const deleteItem = ref(null);
const deleting = ref(false);

// Filters - read from URL query params
const urlParams = new URLSearchParams(window.location.search);
const filterTa = ref(urlParams.get('tahunajaran_id') ? parseInt(urlParams.get('tahunajaran_id')) : (page.props.filters.tahunajaran_id || ''));
const filterJurusan = ref(urlParams.get('jurusan_id') || page.props.filters.jurusan_id || '');
const search = ref(urlParams.get('search') || page.props.filters.search || '');

const form = ref({
    id: null, nis: '', nama: '', jk: 'Pria', tempatlahir: '', tanggallahir: '',
    alamat: '', jurusan_id: '', tahunajaran_id: '', paket_id: '', tahunajaran_id: activeTahunAjaran.id || '',
});
console.log(page.props.paketList);

const filteredPaketList = computed(() => {
    if (!form.value.tahunajaran_id) return page.props.paketList || [];
    return (page.props.paketList || []).filter(p => p.tahunajaran_id == form.value.tahunajaran_id);
});

// Pagination
const visiblePages = computed(() => {
    const current = page.props.siswa.current_page;
    const last = page.props.siswa.last_page;
    if (last <= 5) return Array.from({ length: last }, (_, i) => i + 1);

    const pages = [];
    if (current <= 3) {
        pages.push(1, 2, 3, 4, '...', last);
    } else if (current >= last - 2) {
        pages.push(1, '...', last - 3, last - 2, last - 1, last);
    } else {
        pages.push(1, '...', current - 1, current, current + 1, '...', last);
    }
    return pages;
});

function goToPage(pageNum) {
    router.get('/admin/siswa', {
        ...page.props.filters,
        page: pageNum,
    }, { preserveState: true, preserveScroll: true });
}

function applyFilter(key, value) {
    const params = { ...page.props.filters };
    params[key] = value;
    params.page = 1;

    // Remove empty filters
    Object.keys(params).forEach(k => {
        if (params[k] === '' || params[k] === null) delete params[k];
    });

    router.get('/admin/siswa', params, { preserveState: true, preserveScroll: true });
}

function resetFilters() {
    router.get('/admin/siswa', { page: 1 }, { preserveState: true, preserveScroll: true });
}

function onTaChange() {
    form.value.paket_id = '';
}

async function save() {
    saving.value = true;
    try {
        const url = isEdit.value ? `/api/siswa/${form.value.id}` : '/api/siswa';
        const method = isEdit.value ? 'put' : 'post';
        await axios({ method, url, data: form.value });

        success('Berhasil', isEdit.value ? 'Data siswa berhasil diperbarui' : 'Data siswa berhasil ditambahkan');
        dialogOpen.value = false;
        router.reload({ only: ['siswa', 'paketList'] });
    } catch (e) {
        httpError(e, 'Gagal menyimpan data siswa');
    } finally {
        saving.value = false;
    }
}

function openDialog(item = null) {
    if (item) {
        isEdit.value = true;
        form.value = {
            id: item.id, nis: item.nis, nama: item.nama, jk: item.jk || 'Pria',
            tempatlahir: item.tempatlahir || '', tanggallahir: item.tanggallahir || '',
            alamat: item.alamat || '', jurusan_id: item.jurusan_id || '',
            tahunajaran_id: item.tahunajaran_id || '', paket_id: item.paket_id || '',
        };
    } else {
        isEdit.value = false;
        // Default to active tahun ajaran or current filter
        const defaultTa = filterTa.value || activeTahunAjaran.value?.id || '';
        form.value = { id: null, nis: '', nama: '', jk: 'Pria', tempatlahir: '', tanggallahir: '', alamat: '', jurusan_id: '', tahunajaran_id: defaultTa, paket_id: '' };
    }
    dialogOpen.value = true;
}

function confirmDelete(item) { deleteItem.value = item; deleteDialogOpen.value = true; }
async function executeDelete() {
    if (!deleteItem.value) return;
    deleting.value = true;
    try {
        await axios.delete(`/api/siswa/${deleteItem.value.id}`);
        success('Berhasil', 'Data siswa berhasil dihapus');
        deleteDialogOpen.value = false;
        router.reload({ only: ['siswa'] });
    } catch (e) {
        httpError(e, 'Gagal menghapus data siswa');
    } finally {
        deleting.value = false;
    }
}
</script>
