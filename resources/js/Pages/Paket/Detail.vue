<template>
    <AdminLayout>
        <div v-if="loading" class="max-w-6xl mx-auto p-8 text-center text-gray-500">Loading...</div>
        <div v-else-if="!paket" class="max-w-6xl mx-auto p-8 text-center text-gray-500">Data paket tidak ditemukan.</div>
        <div v-else class="max-w-6xl mx-auto">
            <!-- Back button -->
            <Button variant="ghost" @click="$inertia.visit('/admin/paket')" class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 mb-6 -ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Kembali ke Daftar Paket
            </Button>

            <!-- Paket Info Card -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Informasi Paket</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Kode Paket</p>
                            <p class="font-medium">{{ paket.kode }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Judul Tugas</p>
                            <p class="font-medium">{{ paket.judultugas }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jurusan</p>
                            <p class="font-medium">{{ paket.jurusan?.nama || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tahun Ajaran</p>
                            <p class="font-medium">{{ paket.tahunajaran?.nama || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Bentuk Penugasan</p>
                            <p class="font-medium">{{ paket.bentukpenugasan || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Alokasi Waktu</p>
                            <p class="font-medium">{{ paket.alokasiwaktu }} jam</p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Kompetensi Management Card -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Daftar Kompetensi</CardTitle>
                            <p class="text-sm text-gray-500 mt-1">{{ kompetensis.length }} kompetensi terdaftar</p>
                        </div>
                        <div class="flex gap-2">
                            <Button variant="outline" @click="openBulkDialog" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Import Multiple
                            </Button>
                            <Button @click="openCreateDialog" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Kompetensi
                            </Button>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <!-- Empty State -->
                    <div v-if="kompetensis.length === 0" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada kompetensi</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai tambahkan kompetensi untuk paket ini.</p>
                        <div class="mt-6">
                            <Button @click="openCreateDialog" class="flex items-center gap-2 mx-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Kompetensi Pertama
                            </Button>
                        </div>
                    </div>

                    <!-- Kompetensi Table -->
                    <div v-else>
                        <div class="space-y-3">
                            <div
                                v-for="(item, index) in kompetensis"
                                :key="item.id"
                                class="border rounded-lg p-4 hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <code class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-sm font-medium">{{ item.kode }}</code>
                                            <span class="text-xs text-gray-500">#{{ index + 1 }}</span>
                                        </div>
                                        <p class="text-gray-700">{{ item.elemen }}</p>
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <Button variant="outline" size="sm" @click="openEditDialog(item)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </Button>
                                        <Button variant="destructive" size="sm" @click="deleteKompetensi(item)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Create/Edit Dialog -->
        <Dialog :open="dialogOpen" @update:open="dialogOpen = $event">
            <DialogContent class="sm:max-w-xl">
                <DialogHeader>
                    <DialogTitle>{{ isEditing ? 'Edit Kompetensi' : 'Tambah Kompetensi' }}</DialogTitle>
                </DialogHeader>

                <div class="space-y-4">
                    <!-- Kode -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">
                            Kode Kompetensi <span class="text-red-500">*</span>
                        </label>
                        <Input
                            v-model="formData.kode"
                            placeholder="Contoh: KM-001"
                            required
                        />
                        <p class="text-xs text-gray-500 mt-1">Gunakan format yang konsisten untuk semua kompetensi</p>
                    </div>

                    <!-- Elemen -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">
                            Elemen Kompetensi <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            v-model="formData.elemen"
                            placeholder="Deskripsi elemen kompetensi..."
                            rows="5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            required
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">Jelaskan secara detail apa yang harus dikuasai siswa</p>
                    </div>

                    <!-- Validation Errors -->
                    <div v-if="errors.length > 0" class="p-3 bg-red-50 border border-red-200 rounded-md">
                        <ul class="text-sm text-red-600 space-y-1">
                            <li v-for="(error, idx) in errors" :key="idx">• {{ error }}</li>
                        </ul>
                    </div>
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="dialogOpen = false">Batal</Button>
                    <Button @click="saveKompetensi" :disabled="saving">
                        <svg v-if="saving" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ saving ? 'Menyimpan...' : 'Simpan' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <Dialog :open="deleteDialogOpen" @update:open="deleteDialogOpen = $event">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Konfirmasi Hapus
                    </DialogTitle>
                </DialogHeader>

                <div class="py-4">
                    <p class="text-gray-700">Apakah Anda yakin ingin menghapus kompetensi ini?</p>
                    <div v-if="selectedItem" class="mt-3 p-3 bg-gray-50 rounded-md">
                        <div class="flex items-center gap-2 mb-1">
                            <code class="px-2 py-1 bg-gray-200 rounded text-sm">{{ selectedItem.kode }}</code>
                        </div>
                        <p class="text-sm text-gray-600">{{ selectedItem.elemen }}</p>
                    </div>
                    <p class="text-sm text-red-600 mt-3">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="deleteDialogOpen = false">Batal</Button>
                    <Button variant="destructive" @click="confirmDelete" :disabled="deleting">
                        <svg v-if="deleting" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ deleting ? 'Menghapus...' : 'Hapus' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Bulk Import Dialog -->
        <Dialog :open="bulkDialogOpen" @update:open="bulkDialogOpen = $event">
            <DialogContent class="sm:max-w-2xl">
                <DialogHeader>
                    <DialogTitle>Import Multiple Kompetensi</DialogTitle>
                </DialogHeader>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">
                            Daftar Kompetensi (satu per baris)
                        </label>
                        <textarea
                            v-model="bulkData"
                            placeholder="KM-001|Elemen kompetensi pertama&#10;KM-002|Elemen kompetensi kedua&#10;KM-003|Elemen kompetensi ketiga"
                            rows="10"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm resize-none"
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Format: <code class="px-1 bg-gray-100 rounded">KODE|ELEMEN KOMPETENSI</code> (satu kompetensi per baris)
                        </p>
                    </div>

                    <!-- Validation Errors -->
                    <div v-if="errors.length > 0" class="p-3 bg-red-50 border border-red-200 rounded-md">
                        <ul class="text-sm text-red-600 space-y-1">
                            <li v-for="(error, idx) in errors" :key="idx">• {{ error }}</li>
                        </ul>
                    </div>
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="bulkDialogOpen = false">Batal</Button>
                    <Button @click="saveBulkKompetensi" :disabled="saving">
                        <svg v-if="saving" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ saving ? 'Mengimport...' : 'Import Semua' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useApi } from '@/composables/useApi';

const page = usePage();
const paketId = page.props.paketId;
const { loading, get, post, put, del } = useApi();
const paket = ref(null);
const kompetensis = ref([]);

// Dialog state
const dialogOpen = ref(false);
const deleteDialogOpen = ref(false);
const bulkDialogOpen = ref(false);
const isEditing = ref(false);
const selectedItem = ref(null);
const saving = ref(false);
const deleting = ref(false);
const errors = ref([]);
const bulkData = ref('');

const formData = ref({
    kode: '',
    elemen: '',
    paket_id: ''
});

// Fetch paket detail with kompetensis
async function fetchPaket() {
    try {
        const data = await get(`/paket/${paketId}`);
        paket.value = data;
        kompetensis.value = data.kompetensis || [];
    } catch (e) {
        console.error(e);
        alert('Gagal memuat data paket');
    }
}

// Open create dialog
function openCreateDialog() {
    isEditing.value = false;
    formData.value = {
        kode: '',
        elemen: '',
        paket_id: paketId
    };
    errors.value = [];
    dialogOpen.value = true;
}

// Open edit dialog
function openEditDialog(item) {
    isEditing.value = true;
    selectedItem.value = item;
    formData.value = {
        kode: item.kode,
        elemen: item.elemen,
        paket_id: paketId
    };
    errors.value = [];
    dialogOpen.value = true;
}

// Open bulk import dialog
function openBulkDialog() {
    bulkData.value = '';
    errors.value = [];
    bulkDialogOpen.value = true;
}

// Save kompetensi (create or update)
async function saveKompetensi() {
    // Validation
    errors.value = [];
    if (!formData.value.kode) errors.value.push('Kode harus diisi');
    if (!formData.value.elemen) errors.value.push('Elemen kompetensi harus diisi');

    if (errors.value.length > 0) return;

    saving.value = true;
    try {
        if (isEditing.value) {
            await put(`/kompetensi/${selectedItem.value.id}`, formData.value);
            alert('Kompetensi berhasil diupdate');
        } else {
            await post('/kompetensi', formData.value);
            alert('Kompetensi berhasil ditambahkan');
        }

        dialogOpen.value = false;
        await fetchPaket();
    } catch (e) {
        console.error(e);
        alert(e.response?.data?.message || 'Gagal menyimpan kompetensi');
    } finally {
        saving.value = false;
    }
}

// Save bulk kompetensi
async function saveBulkKompetensi() {
    // Parse bulk data
    errors.value = [];
    const lines = bulkData.value.trim().split('\n').filter(line => line.trim());

    if (lines.length === 0) {
        errors.value.push('Data tidak boleh kosong');
        return;
    }

    const kompetensiList = [];
    for (let i = 0; i < lines.length; i++) {
        const line = lines[i].trim();
        const parts = line.split('|');

        if (parts.length < 2) {
            errors.value.push(`Baris ${i + 1}: Format tidak valid (gunakan format: KODE|ELEMEN)`);
            continue;
        }

        const [kode, ...elemenParts] = parts;
        const elemen = elemenParts.join('|').trim();

        if (!kode.trim()) {
            errors.value.push(`Baris ${i + 1}: Kode tidak boleh kosong`);
            continue;
        }

        if (!elemen.trim()) {
            errors.value.push(`Baris ${i + 1}: Elemen kompetensi tidak boleh kosong`);
            continue;
        }

        kompetensiList.push({
            kode: kode.trim(),
            elemen: elemen.trim()
        });
    }

    if (errors.value.length > 0) return;
    if (kompetensiList.length === 0) {
        errors.value.push('Tidak ada data valid untuk diimport');
        return;
    }

    saving.value = true;
    try {
        await post('/kompetensi/bulk', {
            paket_id: paketId,
            kompetensi: kompetensiList
        });

        alert(`${kompetensiList.length} kompetensi berhasil diimport`);
        bulkDialogOpen.value = false;
        await fetchPaket();
    } catch (e) {
        console.error(e);
        alert(e.response?.data?.message || 'Gagal mengimport kompetensi');
    } finally {
        saving.value = false;
    }
}

// Delete kompetensi
function deleteKompetensi(item) {
    selectedItem.value = item;
    deleteDialogOpen.value = true;
}

// Confirm delete
async function confirmDelete() {
    deleting.value = true;
    try {
        await del(`/kompetensi/${selectedItem.value.id}`);
        alert('Kompetensi berhasil dihapus');
        deleteDialogOpen.value = false;
        await fetchPaket();
    } catch (e) {
        console.error(e);
        alert(e.response?.data?.message || 'Gagal menghapus kompetensi');
    } finally {
        deleting.value = false;
    }
}

onMounted(fetchPaket);
</script>
