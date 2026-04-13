<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Top Navbar -->
        <nav class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 right-0 z-30">
            <div class="flex items-center justify-between h-16 px-4">
                <div class="flex items-center gap-3">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-md text-gray-600 hover:bg-gray-100 lg:hidden"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <a href="/admin" class="flex items-center gap-2">
                        <img src="/assets/images/smk8logo.jpeg" alt="Logo" class="w-10 h-10 rounded" />
                        <span class="text-lg font-semibold text-gray-800 hidden sm:block">UKK App</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative group">
                        <button class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition">
                            <img src="/assets/images/faces/face1.jpg" alt="Profile" class="w-8 h-8 rounded-full" />
                            <span class="text-sm font-medium text-gray-700 hidden md:block">{{ auth?.user?.name ?? 'Admin' }}</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <div class="p-3 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-800">{{ auth?.user?.name ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                            <Link
                                href="/auth/logout"
                                method="get"
                                as="button"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition"
                            >
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Sign Out
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar Overlay (mobile) -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"
            @click="sidebarOpen = false"
        />

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed top-0 left-0 z-50 h-screen w-64 bg-white shadow-lg transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:z-auto lg:shadow-none lg:block',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 lg:hidden">
                    <span class="text-lg font-semibold text-gray-800">Menu</span>
                    <button @click="sidebarOpen = false" class="p-2 rounded-md text-gray-600 hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Sidebar Profile -->
                <div class="hidden lg:block p-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <img src="/assets/images/faces/face1.jpg" alt="Profile" class="w-10 h-10 rounded-full" />
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ auth?.user?.name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="flex-1 overflow-y-auto p-3">
                    <ul class="space-y-1">
                        <li v-for="item in menuItems" :key="item.href">
                            <Link
                                :href="item.href"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition"
                                :class="[
                                    isActive(item.href)
                                        ? 'bg-blue-50 text-blue-700 font-medium'
                                        : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
                                ]"
                            >
                                <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
                                {{ item.label }}
                            </Link>
                        </li>
                    </ul>
                </nav>

                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-gray-200">
                    <p class="text-xs text-gray-400 text-center">&copy; 2024 UKK App</p>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:ml-0">
            <main class="pt-16 min-h-screen">
                <div class="p-4 lg:p-6">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, markRaw } from 'vue';

const sidebarOpen = ref(false);

const page = usePage();
const auth = computed(() => page.props.auth);

// SVG icon components (inline for simplicity)
const icons = {
    Home: { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>' },
    Contacts: { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>' },
    Calendar: { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>' },
    Users: { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" /></svg>' },
    Package: { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>' },
    ClipboardList: { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>' },
    GraduationCap: { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>' },
    ChartBar: { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>' },
};

const menuItems = [
    { label: 'Dashboard', href: '/admin', icon: markRaw(icons.Home) },
    { label: 'Jurusan', href: '/admin/jurusan', icon: markRaw(icons.Contacts) },
    { label: 'Tahun Ajaran', href: '/admin/tahunajaran', icon: markRaw(icons.Calendar) },
    { label: 'Asesor', href: '/admin/aksesor', icon: markRaw(icons.Users) },
    { label: 'Paket', href: '/admin/paket', icon: markRaw(icons.Package) },
    { label: 'Siswa', href: '/admin/siswa', icon: markRaw(icons.ClipboardList) },
    { label: 'Laporan Kelulusan', href: '/admin/lkelulusan', icon: markRaw(icons.GraduationCap) },
    { label: 'Laporan Asesor', href: '/admin/laksesor', icon: markRaw(icons.ChartBar) },
];

function isActive(href) {
    return page.url === href;
}
</script>
