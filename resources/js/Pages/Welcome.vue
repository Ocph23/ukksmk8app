<template>
    <div class="min-h-screen bg-gray-100 ">
        <div class="flex justify-end p-5">
            <a href="/auth/login"
                class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Go to Login
            </a>

        </div>
        <div class="min-h-[90vh] bg-gray-100 flex items-center justify-center text-center">
            <div class="text-center py-12">
                <img src="/assets/images/smk8logo.jpeg" alt="SMK 8" class="w-20 h-20 mx-auto mb-6 " />
                <h1 class="text-3xl font-bold text-gray-900">SISTEM INFORMASI</h1>
                <h2 class="text-2xl font-semibold text-gray-700 mt-2">UJI KOMPETENSI KEAHLIAN</h2>
                <p class="text-lg text-gray-500 mt-8">SMK NEGERI 8 TEKNOLOGI INFORMASI DAN KOMUNIKASI</p>
                <p class="text-base text-gray-500 mt-1">JAYAPURA</p>
                <p class="text-sm text-gray-400 mt-16">2024 - {{ tahun }}</p>
                <p class="text-sm text-gray-400 ">@Ocph23</p>
            </div>
        </div>
    </div>
</template>


<script setup>
import { Head, Link } from '@inertiajs/vue3';

const tahun = new Date().getFullYear();

</script>
