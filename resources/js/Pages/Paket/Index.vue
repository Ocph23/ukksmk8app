<template>
    <AdminLayout>
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Paket</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola data paket uji kompetensi </p>
                </div>
                <Button @click="openDialog()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Paket
                </Button>
            </div>


            <Card>
                <CardContent class="p-0">
                    <div v-if="filteredItems.length === 0" class="p-8 text-center text-gray-500">
                        Belum ada data paket.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No</TableHead>
                                    <TableHead>Kode</TableHead>
                                    <TableHead>Judul Tugas</TableHead>
                                    <TableHead>Jurusan</TableHead>
                                    <TableHead>Alokasi Waktu</TableHead>
                                    <TableHead>Asesor Internal</TableHead>
                                    <TableHead>Asesor Eksternal</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(item, index) in filteredItems" :key="item.id">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell class="font-mono text-sm">{{ item.kode }}</TableCell>
                                    <TableCell class="font-medium max-w-xs truncate">{{ item.judultugas }}</TableCell>
                                    <TableCell>{{ item.jurusan?.nama || '-' }}</TableCell>
                                    <TableCell>{{ item.alokasiwaktu || '-' }}</TableCell>
                                    <TableCell>{{ item.internal?.nama || '-' }}</TableCell>
                                    <TableCell>{{ item.eksternal?.nama || '-' }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button variant="outline" size="sm" @click="openDialog(item)">Edit</Button>
                                            <Button variant="default" size="sm"
                                                @click="$inertia.visit(`/admin/paket/${item.id}`)">Detail</Button>
                                            <Button variant="destructive" size="sm"
                                                @click="confirmDelete(item)">Hapus</Button>
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
            <DialogContent class="sm:max-w-lg max-h-[90vh] overflow-y-auto">
                <DialogHeader>
                    <DialogTitle>{{ isEdit ? 'Edit Paket' : 'Tambah Paket' }}</DialogTitle>
                    <DialogDescription>{{ isEdit ? 'Perbarui data paket' : 'Isi data paket baru' }}</DialogDescription>
                </DialogHeader>
                <form @submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-2">
                            <Label for="kode">Kode Paket</Label>
                            <Input id="kode" v-model="form.kode" placeholder="Contoh: UKK-2024-001" />
                        </div>
                        <div class="space-y-2">
                            <Label for="bentukpenugasan">Bentuk Penugasan</Label>
                            <Input id="bentukpenugasan" v-model="form.bentukpenugasan"
                                placeholder="Contoh: Proyek, Praktik, dll" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <Label for="judultugas">Judul Tugas</Label>
                        <Input id="judultugas" v-model="form.judultugas" placeholder="Judul tugas UKK" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-2">
                            <Label for="alokasiwaktu">Alokasi Waktu</Label>
                            <Input id="alokasiwaktu" v-model="form.alokasiwaktu" placeholder="Contoh: 3 hari, 12 jam" />
                        </div>
                        <div class="space-y-2">
                            <Label for="basisnilai">Basis Nilai</Label>
                            <AppSelect id="basisnilai" v-model="form.basisnilai">
                                <option value="true">Ya</option>
                                <option value="false">Tidak</option>
                            </AppSelect>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-2">
                            <Label for="jurusan_id">Jurusan</Label>
                            <AppSelect id="jurusan_id" v-model="form.jurusan_id">
                                <option value="">Pilih</option>
                                <option v-for="j in jurusanList" :key="j.id" :value="j.id">{{ j.nama }}</option>
                            </AppSelect>
                        </div>

                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-2">
                            <Label for="aksesorinternal">Asesor Internal</Label>
                            <AppSelect id="aksesorinternal" v-model="form.aksesorinternal">
                                <option value="">Pilih</option>
                                <option v-for="a in asesorsInternal" :key="a.id" :value="a.id">{{ a.nama }}</option>
                            </AppSelect>
                        </div>
                        <div class="space-y-2">
                            <Label for="aksesoreksternal">Asesor Eksternal</Label>
                            <AppSelect id="aksesoreksternal" v-model="form.aksesoreksternal">
                                <option value="">Pilih</option>
                                <option v-for="a in asesorsEksternal" :key="a.id" :value="a.id">{{ a.nama }}</option>
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

        <Dialog :open="deleteDialogOpen" @update:open="deleteDialogOpen = $event">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Konfirmasi Hapus</DialogTitle>
                    <DialogDescription>Apakah Anda yakin ingin menghapus paket "{{ deleteItem?.kode }}"?
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="deleteDialogOpen = false">Batal</Button>
                    <Button variant="destructive" :disabled="deleting" @click="executeDelete">{{ deleting ?
                        'Menghapus...' : 'Hapus' }}</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { success, httpError } from '@/composables/useCustomToast';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppSelect from '@/Components/ui/AppSelect.vue';
import { useForm, router } from '@inertiajs/vue3';


const props = defineProps({
    pakets: Array,
    jurusan: Array,
    tahunajaran: Array,
    assesors: Array,
    flash: Object,
});

const filterTahun = ref('');
const tahunAjaranList = ref([]);
const jurusanList = ref([]);
const asesorsInternal = ref([]);
const asesorsEksternal = ref([]);
const dialogOpen = ref(false);
const isEdit = ref(false);
const deleteDialogOpen = ref(false);
const deleteItem = ref(null);

const form = useForm({
    id: null,
    kode: '',
    basisnilai: 'true',
    alokasiwaktu: '',
    bentukpenugasan: '',
    judultugas: '',
    jurusan_id: '',
    tahunajaran_id: '',
    aksesorinternal: '',
    aksesoreksternal: '',
});

const filteredItems = computed(() => {
    let result = props.pakets || [];
    if (filterTahun.value) result = result.filter(i => i.tahunajaran_id == filterTahun.value);
    return result;
});

async function fetchItems() {
    try {

        tahunAjaranList.value = Array.isArray(props.tahunajaran) ? props.tahunajaran : [];
        jurusanList.value = props.jurusan || (Array.isArray(jurusan) ? jurusan : []);
        asesorsInternal.value = Array.isArray(props.assesors) ? props.assesors.filter(a => a.jenis === 'Internal') : [];
        asesorsEksternal.value = Array.isArray(props.assesors) ? props.assesors.filter(a => a.jenis === 'Eksternal') : [];
    } catch (e) { console.error(e); }
}

function openDialog(item = null) {
    if (item) {
        isEdit.value = true;
        form.id = item.id;
        form.kode = item.kode || '';
        form.basisnilai = item.basisnilai ? 'true' : 'false';
        form.alokasiwaktu = item.alokasiwaktu || '';
        form.bentukpenugasan = item.bentukpenugasan || '';
        form.judultugas = item.judultugas || '';
        form.jurusan_id = item.jurusan_id || '';
        form.tahunajaran_id = item.tahunajaran_id || '';
        form.aksesorinternal = item.aksesorinternal || '';
        form.aksesoreksternal = item.aksesoreksternal || '';
    } else {
        isEdit.value = false;
        form.reset();
        form.basisnilai = 'true';
    }
    dialogOpen.value = true;
}

async function save() {
    const payload = {
        ...form,
        basisnilai: form.basisnilai === 'true',
    };

    if (isEdit.value) {
        router.put(`/admin/paket/${form.id}`, payload, {
            onSuccess: () => {
                success('Berhasil', 'Data paket berhasil diperbarui');
                dialogOpen.value = false;
            },
            onError: (errors) => {
                httpError({ message: Object.values(errors).join(', ') }, 'Gagal menyimpan data paket');
            }
        });
    } else {
        router.post('/admin/paket', payload, {
            onSuccess: () => {
                success('Berhasil', 'Data paket berhasil ditambahkan');
                dialogOpen.value = false;
            },
            onError: (errors) => {
                httpError({ message: Object.values(errors).join(', ') }, 'Gagal menyimpan data paket');
            }
        });
    }
}

function confirmDelete(item) { deleteItem.value = item; deleteDialogOpen.value = true; }

async function executeDelete() {
    if (!deleteItem.value) return;
    router.delete(`/admin/paket/${deleteItem.value.id}`, {
        onSuccess: () => {
            success('Berhasil', 'Data paket berhasil dihapus');
            deleteDialogOpen.value = false;
        },
        onError: () => {
            httpError({ message: 'Gagal menghapus data paket' }, 'Gagal menghapus data paket');
        }
    });
}

onMounted(() => {
    tahunAjaranList.value = Array.isArray(props.tahunajaran) ? props.tahunajaran : [];
    jurusanList.value = props.jurusan || [];
    asesorsInternal.value = Array.isArray(props.assesors) ? props.assesors.filter(a => a.jenis === 'Internal') : [];
    asesorsEksternal.value = Array.isArray(props.assesors) ? props.assesors.filter(a => a.jenis === 'Eksternal') : [];

    if (props.flash?.success) {
        success('Berhasil', props.flash.success);
    }
    if (props.flash?.error) {
        httpError({ message: props.flash.error }, 'Error');
    }
});
</script>
