<template>
    <AdminLayout>
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pengguna</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola akun admin dan pengguna per jurusan</p>
                </div>
                <Button @click="openDialog()">Tambah User</Button>
            </div>

            <Card>
                <CardContent class="p-0 overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>No</TableHead>
                                <TableHead>Nama</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Role</TableHead>
                                <TableHead>Jurusan</TableHead>
                                <TableHead class="text-right">Aksi</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(item, index) in users" :key="item.id">
                                <TableCell>{{ index + 1 }}</TableCell>
                                <TableCell class="font-medium">{{ item.name }}</TableCell>
                                <TableCell>{{ item.email }}</TableCell>
                                <TableCell>{{ item.role === 'jurusan' ? 'User Jurusan' : 'Admin' }}</TableCell>
                                <TableCell>{{ item.jurusan?.nama || '-' }}</TableCell>
                                <TableCell class="text-right space-x-2">
                                    <Button variant="outline" size="sm" @click="openDialog(item)">Edit</Button>
                                    <Button variant="outline" size="sm" @click="openResetDialog(item)">Reset Password</Button>
                                    <Button variant="destructive" size="sm" @click="remove(item)">Hapus</Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>

        <Dialog :open="dialogOpen" @update:open="dialogOpen = $event">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>{{ form.id ? 'Edit User' : 'Tambah User' }}</DialogTitle>
                    <DialogDescription>Atur akun dan jurusan yang dapat dikelola user.</DialogDescription>
                </DialogHeader>
                <form class="space-y-4" @submit.prevent="save">
                    <div class="space-y-2"><Label>Nama</Label><Input v-model="form.name" required /></div>
                    <div class="space-y-2"><Label>Email</Label><Input v-model="form.email" type="email" required /></div>
                    <div class="space-y-2">
                        <Label>Password {{ form.id ? '(kosongkan jika tidak diubah)' : '' }}</Label>
                        <Input v-model="form.password" type="password" :required="!form.id" />
                    </div>
                    <div class="space-y-2">
                        <Label>Role</Label>
                        <NativeSelect v-model="form.role">
                            <option value="jurusan">User Jurusan</option>
                            <option value="admin">Admin</option>
                        </NativeSelect>
                    </div>
                    <div v-if="form.role === 'jurusan'" class="space-y-2">
                        <Label>Jurusan</Label>
                        <NativeSelect v-model="form.jurusan_id" required>
                            <option value="">Pilih jurusan</option>
                            <option v-for="jurusan in jurusanList" :key="jurusan.id" :value="jurusan.id">{{ jurusan.nama }}</option>
                        </NativeSelect>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="dialogOpen = false">Batal</Button>
                        <Button type="submit" :disabled="saving">{{ saving ? 'Menyimpan...' : 'Simpan' }}</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <Dialog :open="resetDialogOpen" @update:open="resetDialogOpen = $event">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Reset Password</DialogTitle>
                    <DialogDescription>Atur password baru untuk {{ resetUser?.name }}.</DialogDescription>
                </DialogHeader>
                <form class="space-y-4" @submit.prevent="resetPassword">
                    <div class="space-y-2"><Label>Password Baru</Label><Input v-model="resetForm.password" type="password" required minlength="6" /></div>
                    <div class="space-y-2"><Label>Konfirmasi Password</Label><Input v-model="resetForm.password_confirmation" type="password" required minlength="6" /></div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="resetDialogOpen = false">Batal</Button>
                        <Button type="submit" :disabled="resetting">{{ resetting ? 'Mereset...' : 'Reset Password' }}</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { NativeSelect } from '@/components/ui/native-select';

const props = defineProps({ users: Array, jurusanList: Array });
const users = props.users || [];
const jurusanList = props.jurusanList || [];
const dialogOpen = ref(false);
const saving = ref(false);
const resetting = ref(false);
const resetDialogOpen = ref(false);
const resetUser = ref(null);
const resetForm = ref({ password: '', password_confirmation: '' });
const form = ref({ id: null, name: '', email: '', password: '', role: 'jurusan', jurusan_id: '' });

function openDialog(item = null) {
    form.value = item
        ? { id: item.id, name: item.name, email: item.email, password: '', role: item.role, jurusan_id: item.jurusan_id || '' }
        : { id: null, name: '', email: '', password: '', role: 'jurusan', jurusan_id: '' };
    dialogOpen.value = true;
}

function save() {
    saving.value = true;
    const options = { preserveScroll: true, onSuccess: () => { dialogOpen.value = false; }, onFinish: () => { saving.value = false; } };
    form.value.id ? router.put(`/admin/pengguna/${form.value.id}`, form.value, options) : router.post('/admin/pengguna', form.value, options);
}

function openResetDialog(item) {
    resetUser.value = item;
    resetForm.value = { password: '', password_confirmation: '' };
    resetDialogOpen.value = true;
}

function resetPassword() {
    resetting.value = true;
    router.put(`/admin/pengguna/${resetUser.value.id}/reset-password`, resetForm.value, {
        preserveScroll: true,
        onSuccess: () => { resetDialogOpen.value = false; },
        onFinish: () => { resetting.value = false; },
    });
}

function remove(item) {
    if (confirm(`Hapus user ${item.name}?`)) router.delete(`/admin/pengguna/${item.id}`, { preserveScroll: true });
}
</script>
