<template>
    <AdminLayout>
        <div v-if="loading" class="max-w-6xl mx-auto p-8 text-center text-gray-500">Loading...</div>
        <div v-else class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Manajemen Kompetensi</h1>
                        <p class="text-sm text-gray-500 mt-1">Kelola data kompetensi untuk paket uji kompetensi</p>
                    </div>
                    <Button @click="openCreateDialog" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Kompetensi
                    </Button>
                </div>
            </div>

            <!-- Filter Section -->
            <Card class="mb-6">
                <CardContent class="pt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1 block">Pilih Paket</label>
                            <select 
                                v-model="filterPaketId" 
                                @change="fetchKompetensi"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Semua Paket</option>
                                <option v-for="paket in paketList" :key="paket.id" :value="paket.id">
                                    {{ paket.kode }} - {{ paket.judultugas }}
                                </option>
                            </select>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Kompetensi List -->
            <Card>
                <CardContent class="p-0">
                    <div v-if="items.length === 0" class="p-8 text-center text-gray-500">
                        Belum ada data kompetensi.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-16">No</TableHead>
                                    <TableHead>Kode</TableHead>
                                    <TableHead class="w-1/3">Elemen Kompetensi</TableHead>
                                    <TableHead>Paket</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(item, index) in items" :key="item.id">
                                    <TableCell class="font-medium">{{ index + 1 }}</TableCell>
                                    <TableCell>
                                        <code class="px-2 py-1 bg-gray-100 rounded text-sm">{{ item.kode }}</code>
                                    </TableCell>
                                    <TableCell>{{ item.elemen }}</TableCell>
                                    <TableCell>
                                        <div class="text-sm">
                                            <p class="font-medium">{{ item.paket?.kode }}</p>
                                            <p class="text-gray-500 text-xs">{{ item.paket?.judultugas }}</p>
                                        </div>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex gap-2 justify-end">
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
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
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
                    </div>

                    <!-- Elemen -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">
                            Elemen Kompetensi <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            v-model="formData.elemen" 
                            placeholder="Deskripsi elemen kompetensi..."
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            required
                        ></textarea>
                    </div>

                    <!-- Paket -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">
                            Paket <span class="text-red-500">*</span>
                        </label>
                        <select 
                            v-model="formData.paket_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >
                            <option value="">Pilih Paket</option>
                            <option v-for="paket in paketList" :key="paket.id" :value="paket.id">
                                {{ paket.kode }} - {{ paket.judultugas }}
                            </option>
                        </select>
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
                        <p class="font-medium">{{ selectedItem.kode }}</p>
                        <p class="text-sm text-gray-600">{{ selectedItem.elemen }}</p>
                    </div>
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
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useApi } from '@/composables/useApi';

const { loading, get, post, put, del } = useApi();
const items = ref([]);
const paketList = ref([]);
const filterPaketId = ref('');

// Dialog state
const dialogOpen = ref(false);
const deleteDialogOpen = ref(false);
const isEditing = ref(false);
const selectedItem = ref(null);
const saving = ref(false);
const deleting = ref(false);
const errors = ref([]);

const formData = ref({
    kode: '',
    elemen: '',
    paket_id: ''
});

// Fetch kompetensi list
async function fetchKompetensi() {
    try {
        let url = '/kompetensi';
        if (filterPaketId.value) {
            url = `/kompetensi/paket/${filterPaketId.value}`;
        }
        const data = await get(url);
        items.value = Array.isArray(data) ? data : [];
    } catch (e) {
        console.error(e);
        alert('Gagal memuat data kompetensi');
    }
}

// Fetch paket list
async function fetchPaketList() {
    try {
        const data = await get('/paket');
        paketList.value = Array.isArray(data) ? data : [];
    } catch (e) {
        console.error(e);
    }
}

// Open create dialog
function openCreateDialog() {
    isEditing.value = false;
    formData.value = {
        kode: '',
        elemen: '',
        paket_id: filterPaketId.value || ''
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
        paket_id: item.paket_id
    };
    errors.value = [];
    dialogOpen.value = true;
}

// Save kompetensi (create or update)
async function saveKompetensi() {
    // Validation
    errors.value = [];
    if (!formData.value.kode) errors.value.push('Kode harus diisi');
    if (!formData.value.elemen) errors.value.push('Elemen kompetensi harus diisi');
    if (!formData.value.paket_id) errors.value.push('Paket harus dipilih');

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
        await fetchKompetensi();
    } catch (e) {
        console.error(e);
        alert(e.response?.data?.message || 'Gagal menyimpan kompetensi');
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
        await fetchKompetensi();
    } catch (e) {
        console.error(e);
        alert(e.response?.data?.message || 'Gagal menghapus kompetensi');
    } finally {
        deleting.value = false;
    }
}

onMounted(async () => {
    await fetchPaketList();
    await fetchKompetensi();
});
</script>
