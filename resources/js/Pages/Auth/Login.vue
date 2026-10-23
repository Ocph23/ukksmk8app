<template>
    <AuthLayout>
        <Card class="border-white/80 bg-white/80 shadow-xl backdrop-blur-xl p-10 ">
            <CardHeader class="text-center">
                <img src="/assets/images/smk8logo.jpeg" alt="Logo SMK 8"
                    class="mx-auto mb-4 size-16 rounded-full object-cover" />
                <CardTitle class="text-xl">SMK 8 TIK JAYAPURA</CardTitle>
                <CardDescription>Masuk untuk melanjutkan</CardDescription>
            </CardHeader>
            <CardContent>
                <form class="space-y-4" @submit.prevent="submit">
                    <div class="space-y-2">
                        <Label for="username">Email</Label>
                        <Input id="username" v-model="form.username" type="email" placeholder="nama@contoh.com"
                            autocomplete="email" autofocus :aria-invalid="!!form.errors.username" />
                        <p v-if="form.errors.username" class="text-sm text-destructive">{{ form.errors.username }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label for="password">Password</Label>
                        <div class="relative">
                            <Input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'"
                                placeholder="Password" autocomplete="current-password" class="pr-10"
                                :aria-invalid="!!form.errors.password" />
                            <Button type="button" variant="ghost" size="icon"
                                class="absolute right-1 top-1/2 -translate-y-1/2" aria-label="Tampilkan password"
                                @click="showPassword = !showPassword">
                                <EyeOff v-if="showPassword" class="size-4" />
                                <Eye v-else class="size-4" />
                            </Button>
                        </div>
                        <p v-if="form.errors.password" class="text-sm text-destructive">{{ form.errors.password }}</p>
                    </div>

                    <Label for="remember" class="flex items-center gap-2 text-sm text-muted-foreground">
                        <Checkbox id="remember" v-model:checked="form.remember" />
                        <span>Tetap masuk</span>
                    </Label>

                    <Button type="submit" class="w-full" :disabled="form.processing">
                        {{ form.processing ? 'Memproses...' : 'Masuk' }}
                    </Button>
                </form>
            </CardContent>
        </Card>
    </AuthLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Eye, EyeOff } from 'lucide-vue-next';
import AuthLayout from '@/Components/Layouts/AuthLayout.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { errorToast } from '@/composables/useCustomToast';

const showPassword = ref(false);
const form = useForm({ username: '', password: '', remember: false });

function submit() {
    form.post('/auth/login', {
        onError: (errors) => {
            const message = errors.email || errors.username || errors.password || 'Login gagal. Periksa kembali data Anda.';
            errorToast('Login Gagal', message);
        },
    });
}
</script>
