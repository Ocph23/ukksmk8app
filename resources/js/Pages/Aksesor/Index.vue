<template>
    <AdminLayout>
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Asesor</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola data asesor</p>
                </div>
                <Button @click="openDialog()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Asesor
                </Button>
            </div>

            <Card>
                <CardContent class="p-0">
                    <div v-if="asesor.length === 0" class="p-8 text-center text-gray-500">
                        Belum ada data asesor.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No</TableHead>
                                    <TableHead>Nama</TableHead>
                                    <TableHead>Instansi</TableHead>
                                    <TableHead>JK</TableHead>
                                    <TableHead>Jenis</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(item, index) in asesor" :key="item.id">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell class="font-medium">{{ item.nama }}</TableCell>
                                    <TableCell>{{ item.instansi || '-' }}</TableCell>
                                    <TableCell>{{ item.jk || '-' }}</TableCell>
                                    <TableCell>
                                        <Badge :variant="item.jenis === 'Eksternal' ? 'default' : 'secondary'">
                                            {{ item.jenis || '-' }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button v-if="item.logo" variant="outline" size="sm"
                                                @click="viewLogo(item)">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Logo
                                            </Button>
                                            <Button variant="outline" size="sm" @click="openDialog(item)">Edit</Button>
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
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>{{ isEdit ? 'Edit Asesor' : 'Tambah Asesor' }}</DialogTitle>
                    <DialogDescription>{{ isEdit ? 'Perbarui data asesor' : 'Isi data asesor baru' }}
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="save" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="nama">Nama</Label>
                        <Input id="nama" v-model="form.nama" placeholder="Nama asesor" />
                    </div>
                    <div class="space-y-2">
                        <Label for="instansi">Instansi</Label>
                        <Input id="instansi" v-model="form.instansi" placeholder="Nama instansi" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-2">
                            <Label for="jk">Jenis Kelamin</Label>
                            <AppSelect id="jk" v-model="form.jk">
                                <option value="Pria">Pria</option>
                                <option value="Wanita">Wanita</option>
                            </AppSelect>
                        </div>
                        <div class="space-y-2">
                            <Label for="jenis">Jenis Asesor</Label>
                            <AppSelect id="jenis" v-model="form.jenis">
                                <option value="Internal">Internal</option>
                                <option value="Eksternal">Eksternal</option>
                            </AppSelect>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <Label for="logo">Logo</Label>
                        <input id="logo" type="file" accept="image/*" @change="handleLogoUpload"
                            class="h-10 w-full rounded-md border border-input bg-background px-3 text-sm file:mr-2 file:h-6 file:text-sm file:font-medium file:border-0 file:bg-transparent file:text-foreground" />
                        <div v-if="form.dataLogo" class="mt-2">
                            <p class="text-xs text-green-600">✓ Logo dipilih</p>
                            <img v-if="logoPreview" :src="logoPreview" alt="Preview"
                                class="w-16 h-16 rounded-md object-cover mt-1" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <Label for="catatan">Catatan</Label>
                        <Input id="catatan" v-model="form.catatan" placeholder="Catatan (opsional)" />
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
                    <DialogDescription>Apakah Anda yakin ingin menghapus asesor "{{ deleteItem?.nama }}"?
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="deleteDialogOpen = false">Batal</Button>
                    <Button variant="destructive" :disabled="deleting" @click="executeDelete">{{ deleting ?
                        'Menghapus...' : 'Hapus' }}</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Logo Preview Dialog -->
        <Dialog :open="logoDialogOpen" @update:open="logoDialogOpen = $event">
            <DialogContent class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Logo - {{ logoItem?.nama }}</DialogTitle>
                    <DialogDescription>{{ logoItem?.instansi }}</DialogDescription>
                </DialogHeader>
                <div class="flex justify-center py-4">
                    <img :src="getLogoUrl(logoItem?.logo)" alt="Logo"
                        class="max-w-full max-h-64 rounded-lg object-contain" />
                </div>
                <DialogFooter>
                    <Button @click="logoDialogOpen = false">Tutup</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AdminLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppSelect from '@/Components/ui/AppSelect.vue';
import { Badge } from '@/components/ui/badge';
import { success } from '@/composables/useCustomToast';

const page = usePage();
const asesor = ref(page.props.asesor || []);

const dialogOpen = ref(false);
const isEdit = ref(false);
const saving = ref(false);
const deleteDialogOpen = ref(false);
const deleteItem = ref(null);
const deleting = ref(false);
const logoDialogOpen = ref(false);
const logoItem = ref(null);
const form = ref({ id: null, nama: '', instansi: '', jk: 'Pria', jenis: 'Internal', dataLogo: '', catatan: '' });
const logoPreview = ref('');

// Watch for flash messages
watch(() => page.props.flash?.success, (msg) => {
    if (msg) success('Berhasil', msg);
});

function openDialog(item = null) {
    if (item) {
        isEdit.value = true;
        form.value = {
            id: item.id, nama: item.nama, instansi: item.instansi || '',
            jk: item.jk || 'Pria', jenis: item.jenis || 'Internal',
            dataLogo: '', catatan: item.catatan || '',
        };
        logoPreview.value = item.logo ? `/instansi/${item.logo}` : '';
    } else {
        isEdit.value = false;
        form.value = { id: null, nama: '', instansi: '', jk: 'Pria', jenis: 'Internal', dataLogo: '', catatan: '' };
        logoPreview.value = '';
    }
    dialogOpen.value = true;
}

function handleLogoUpload(event) {
    const file = event.target.files?.[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        form.value.dataLogo = e.target?.result?.split(',')[1] || '';
        logoPreview.value = e.target?.result || '';
    };
    reader.readAsDataURL(file);
}

function viewLogo(item) {
    logoItem.value = item;
    logoDialogOpen.value = true;
}

function getLogoUrl(logoFilename) {
    if (!logoFilename) return '';
    if (logoFilename.startsWith('data:')) return logoFilename;
    return `/storage/instansi/${logoFilename}`;
}

function save() {
    saving.value = true;

    if (isEdit.value) {
        router.put(`/admin/aksesor/${form.value.id}`, form.value, {
            preserveScroll: true,
            onSuccess: () => {
                dialogOpen.value = false;
            },
            onFinish: () => {
                saving.value = false;
            }
        });
    } else {
        router.post('/admin/aksesor', form.value, {
            preserveScroll: true,
            onSuccess: () => {
                dialogOpen.value = false;
            },
            onFinish: () => {
                saving.value = false;
            }
        });
    }
}

function confirmDelete(item) { deleteItem.value = item; deleteDialogOpen.value = true; }

function executeDelete() {
    if (!deleteItem.value) return;
    deleting.value = true;
    router.delete(`/admin/aksesor/${deleteItem.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            deleteDialogOpen.value = false;
        },
        onFinish: () => {
            deleting.value = false;
        }
    });
}
</script>
