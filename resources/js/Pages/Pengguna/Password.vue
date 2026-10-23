<template>
    <AdminLayout>
        <div class="max-w-lg mx-auto">
            <h1 class="text-2xl font-bold text-gray-900">Ubah Password</h1>
            <p class="text-sm text-gray-500 mt-1 mb-6">Gunakan password lama untuk membuat password baru.</p>

            <Card>
                <CardContent class="p-6">
                    <form class="space-y-4" @submit.prevent="submit">
                        <div class="space-y-2">
                            <Label>Password Lama</Label>
                            <Input v-model="form.current_password" type="password" required />
                            <p v-if="errors.current_password" class="text-sm text-red-600">{{ errors.current_password }}</p>
                        </div>
                        <div class="space-y-2">
                            <Label>Password Baru</Label>
                            <Input v-model="form.password" type="password" required minlength="6" />
                            <p v-if="errors.password" class="text-sm text-red-600">{{ errors.password }}</p>
                        </div>
                        <div class="space-y-2">
                            <Label>Konfirmasi Password Baru</Label>
                            <Input v-model="form.password_confirmation" type="password" required minlength="6" />
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <Button type="button" variant="outline" @click="router.visit('/admin')">Batal</Button>
                            <Button type="submit" :disabled="saving">{{ saving ? 'Menyimpan...' : 'Simpan Password' }}</Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const page = usePage();
const saving = ref(false);
const form = ref({ current_password: '', password: '', password_confirmation: '' });
const errors = computed(() => page.props.errors || {});

function submit() {
    saving.value = true;
    router.put('/admin/password', form.value, {
        preserveScroll: true,
        onSuccess: () => { form.value = { current_password: '', password: '', password_confirmation: '' }; },
        onFinish: () => { saving.value = false; },
    });
}
</script>
