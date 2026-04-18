<template>
    <div class="min-h-screen transition-colors">

        <!-- Top Navbar -->
        <nav
            class="shadow-sm border-b border-gray-200 dark:border-gray-700 fixed top-0 left-0 right-0 z-30 transition-colors">
            <div class="flex items-center justify-between h-16 px-4">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true"
                        class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <a href="/admin" class="flex items-center gap-2">
                        <img src="/assets/images/smk8logo.jpeg" alt="Logo" class="w-10 h-10 rounded" />
                        <span class="text-lg font-semibold text-gray-800 dark:text-gray-100 hidden sm:block">SISTEM
                            INFORMASI UKK</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Theme Toggle -->
                    <ThemeToggle />

                    <!-- Profile Dropdown -->
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button
                                class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <Avatar>
                                    <AvatarImage src="/assets/images/faces/face1.jpg" alt="Profile" />
                                    <AvatarFallback>{{ auth?.user?.name?.charAt(0) ?? 'A' }}</AvatarFallback>
                                </Avatar>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200 hidden md:block">{{
                                    auth?.user?.name ?? 'Admin' }}</span>
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="w-56" align="end">
                            <DropdownMenuLabel>
                                <div>
                                    <p class="text-sm font-medium">{{ auth?.user?.name ?? 'Admin' }}</p>
                                    <p class="text-xs text-muted-foreground">Administrator</p>
                                </div>
                            </DropdownMenuLabel>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem as-child>
                                <Link href="/auth/logout" method="get" as="button"
                                    class="w-full cursor-pointer text-red-600 focus:text-red-600">
                                    <LogOut class="mr-2 h-4 w-4" />
                                    Sign Out
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </nav>

        <!-- Sidebar for Desktop -->
        <aside
            class="hidden lg:block fixed top-16 left-0 z-40 h-[calc(100vh-4rem)] w-64 border-r border-gray-200 dark:border-gray-700">
            <div class="flex flex-col h-full">
                <!-- Sidebar Profile -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <Avatar>
                            <AvatarImage src="/assets/images/faces/face1.jpg" alt="Profile" />
                            <AvatarFallback>{{ auth?.user?.name?.charAt(0) ?? 'A' }}</AvatarFallback>
                        </Avatar>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">{{
                                auth?.user?.name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                        </div>
                    </div>
                    <!-- Active Tahun Ajaran Selector (Filter Only) -->
                    <div class="mt-3">
                        <label class="text-xs block mb-1">Filter Tahun Ajaran</label>
                        <select v-model="selectedTa" @change="onFilterChange"
                            class="w-full h-8 text-xs rounded-md border border-input  px-2 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-1 outline-none text-gray-700 dark:text-gray-200">
                            <option :value="null">Semua</option>
                            <option v-for="ta in tahunAjaranList" :key="ta.id" :value="ta.id">
                                {{ ta.nama }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="flex-1 overflow-y-auto p-3">
                    <ul class="space-y-1">
                        <li v-for="item in menuItems" :key="item.label">
                            <Link :href="item.href"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition" :class="[
                                    isActive(item.href)
                                        ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 font-medium'
                                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100'
                                ]">
                                <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
                                {{ item.label }}
                            </Link>
                        </li>
                    </ul>
                </nav>

                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-400 dark:text-gray-500 text-center">&copy; 2024 UKK App</p>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar using shadcn Sheet -->
        <Sheet v-model:open="sidebarOpen">
            <SheetContent side="left" class="w-64 p-0">
                <div class="flex flex-col h-full">
                    <!-- Sidebar Profile -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <Avatar>
                                <AvatarImage src="/assets/images/faces/face1.jpg" alt="Profile" />
                                <AvatarFallback>{{ auth?.user?.name?.charAt(0) ?? 'A' }}</AvatarFallback>
                            </Avatar>
                            <div class="min-w-0">
                                <SheetTitle class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">{{
                                    auth?.user?.name ?? 'Admin' }}</SheetTitle>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                            </div>
                        </div>
                        <!-- Active Tahun Ajaran Selector (Filter Only) -->
                        <div class="mt-3">
                            <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Filter Tahun
                                Ajaran</label>
                            <select v-model="selectedTa" @change="onFilterChange"
                                class="w-full h-8 text-xs rounded-md border border-input bg-background dark:bg-gray-700 px-2 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-1 outline-none text-gray-700 dark:text-gray-200">
                                <option :value="null">Semua</option>
                                <option v-for="ta in tahunAjaranList" :key="ta.id" :value="ta.id">
                                    {{ ta.nama }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="flex-1 overflow-y-auto p-3">
                        <ul class="space-y-1">
                            <li v-for="item in menuItems" :key="item.label">
                                <SheetClose as-child>
                                    <Link :href="item.href"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition"
                                        :class="[
                                            isActive(item.href)
                                                ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 font-medium'
                                                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100'
                                        ]">
                                        <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
                                        {{ item.label }}
                                    </Link>
                                </SheetClose>
                            </li>
                        </ul>
                    </nav>

                    <!-- Sidebar Footer -->
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-400 dark:text-gray-500 text-center">&copy; 2024 UKK App</p>
                    </div>
                </div>
            </SheetContent>
        </Sheet>

        <!-- Main Content -->
        <div class="lg:ml-64">
            <main class="pt-16 min-h-screen">
                <div class="p-4 lg:p-6">
                    <slot />
                </div>
            </main>
        </div>
        <CustomToast />
    </div>
</template>

<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref, markRaw, watch } from 'vue';
import CustomToast from '@/components/ui/CustomToast.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import { LogOut, Home, School, CalendarDays, Users, Package, ClipboardList, GraduationCap, BarChart3 } from 'lucide-vue-next';

// shadcn imports
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetTitle,
} from '@/components/ui/sheet';

const sidebarOpen = ref(false);

const page = usePage();
const auth = computed(() => page.props.auth);
const activeTahunAjaran = computed(() => page.props.activeTahunAjaran);
const tahunAjaranList = computed(() => page.props.tahunAjaranList || []);

// Get current URL query params reactively
const getQueryParam = (key) => {
    const url = new URL(window.location.href);
    const val = url.searchParams.get(key);
    return val ? parseInt(val) : null;
};

const selectedTa = ref(getQueryParam('tahunajaran_id') ?? activeTahunAjaran.value?.id ?? null);

// Watch for URL changes (when navigating via Inertia)
watch(() => page.url, () => {
    selectedTa.value = getQueryParam('tahunajaran_id') ?? activeTahunAjaran.value?.id ?? null;
});

function onFilterChange() {
    const currentUrl = new URL(window.location.href);
    if (selectedTa.value) {
        currentUrl.searchParams.set('tahunajaran_id', selectedTa.value);
    } else {
        currentUrl.searchParams.delete('tahunajaran_id');
    }
    router.get(currentUrl.pathname + currentUrl.search, {}, { preserveState: true, preserveScroll: true });
}

const menuItems = computed(() => {
    const taParam = selectedTa.value ? `?tahunajaran_id=${selectedTa.value}` : '';
    return [
        { label: 'Dashboard', href: '/admin', icon: markRaw(Home) },
        { label: 'Jurusan', href: '/admin/jurusan', icon: markRaw(School) },
        { label: 'Tahun Ajaran', href: '/admin/tahunajaran', icon: markRaw(CalendarDays) },
        { label: 'Asesor', href: '/admin/aksesor', icon: markRaw(Users) },
        { label: 'Paket', href: `/admin/paket${taParam}`, icon: markRaw(Package) },
        { label: 'Siswa', href: `/admin/siswa${taParam}`, icon: markRaw(ClipboardList) },
        { label: 'Laporan Kelulusan', href: `/admin/lkelulusan${taParam}`, icon: markRaw(GraduationCap) },
        { label: 'Laporan Asesor', href: `/admin/laksesor${taParam}`, icon: markRaw(BarChart3) },
    ];
});

function isActive(href) {
    // Strip query params for active state matching
    const hrefPath = href.split('?')[0];
    return page.url === hrefPath || page.url.startsWith(hrefPath + '?');
}
</script>
