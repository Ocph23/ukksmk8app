<template>
    <AuthLayout>
        <Card class="border-0 shadow-lg">
            <CardHeader class="text-center">
                <div class="flex justify-center mb-4">
                    <img src="/assets/images/smk8logo.jpeg" alt="Logo" class="w-16 h-16 rounded-full" />
                </div>
                <CardTitle class="text-xl font-bold text-gray-900">SMK 8 TIK JAYAPURA</CardTitle>
                <CardDescription class="text-base">APLIKASI UKK</CardDescription>
                <CardDescription class="text-sm text-gray-500 mt-1">Login untuk melanjutkan.</CardDescription>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <Label for="username" class="sr-only">Username</Label>
                        <Input
                            id="username"
                            v-model="form.username"
                            type="email"
                            placeholder="Username"
                            class="h-12"
                            :class="{ 'border-red-500': form.errors.username }"
                            autocomplete="email"
                            autofocus
                        />
                        <p v-if="form.errors.username" class="text-red-500 text-xs mt-1">{{ form.errors.username }}</p>
                    </div>
                    <div>
                        <Label for="password" class="sr-only">Password</Label>
                        <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            placeholder="Password"
                            class="h-12"
                            :class="{ 'border-red-500': form.errors.password }"
                            autocomplete="current-password"
                        />
                        <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <Checkbox id="remember" v-model:checked="form.remember" />
                            <span>Keep me signed in</span>
                        </label>
                        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    </div>
                    <Button
                        type="submit"
                        class="w-full h-12 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing">Signing in...</span>
                        <span v-else>SIGN IN</span>
                    </Button>
                </form>

                <!-- Error Alert -->
                <div v-if="form.errors.email" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-700 text-sm">{{ form.errors.email }}</p>
                </div>
            </CardContent>
            <CardFooter class="flex justify-center pb-6">
                <p class="text-sm text-gray-500">
                    Belum punya akun?
                    <a href="/auth/register" class="text-blue-600 hover:underline font-medium">Daftar</a>
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
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/auth/login', {
        onError: (errors) => {
            console.error('Login error:', errors);
        },
    });
}
</script>
