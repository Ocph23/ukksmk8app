<template>
    <AuthLayout>
        <Card class="border-0 shadow-lg">
            <CardHeader class="text-center">
                <div class="flex justify-center mb-4">
                    <img src="/assets/images/smk8logo.jpeg" alt="Logo" class="w-16 h-16 rounded-full" />
                </div>
                <CardTitle class="text-xl font-bold text-gray-900">Register Account</CardTitle>
                <CardDescription class="text-sm text-gray-500 mt-1">Buat akun administrator baru</CardDescription>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <Label for="name">Nama Lengkap</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Nama Lengkap"
                            class="mt-1"
                            :class="{ 'border-red-500': form.errors.name }"
                            autofocus
                        />
                        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="Email"
                            class="mt-1"
                            :class="{ 'border-red-500': form.errors.email }"
                            autocomplete="email"
                        />
                        <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <Label for="password">Password</Label>
                        <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            placeholder="Password (min. 6 karakter)"
                            class="mt-1"
                            :class="{ 'border-red-500': form.errors.password }"
                            autocomplete="new-password"
                        />
                        <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
                    </div>
                    <div>
                        <Label for="password_confirmation">Konfirmasi Password</Label>
                        <Input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="Ulangi Password"
                            class="mt-1"
                            :class="{ 'border-red-500': form.errors.password_confirmation }"
                            autocomplete="new-password"
                        />
                        <p v-if="form.errors.password_confirmation" class="text-red-500 text-xs mt-1">{{ form.errors.password_confirmation }}</p>
                    </div>
                    <Button
                        type="submit"
                        class="w-full h-12 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing">Registering...</span>
                        <span v-else>REGISTER</span>
                    </Button>
                </form>

                <!-- General Error -->
                <div v-if="form.errors.general" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-700 text-sm">{{ form.errors.general }}</p>
                </div>
            </CardContent>
            <CardFooter class="flex justify-center pb-6">
                <p class="text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="/auth/login" class="text-blue-600 hover:underline font-medium">Login</a>
                </p>
            </CardFooter>
        </Card>
    </AuthLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Components/Layouts/AuthLayout.vue';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/auth/register', {
        onError: (errors) => {
            console.error('Registration error:', errors);
        },
    });
}
</script>
