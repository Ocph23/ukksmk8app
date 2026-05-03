<template>
    <AdminLayout>
        <div v-if="loading" class="max-w-6xl mx-auto p-8 text-center text-gray-500">Loading...</div>
        <div v-else-if="!siswa" class="max-w-6xl mx-auto p-8 text-center text-gray-500">Data siswa tidak ditemukan.
        </div>
        <div v-else class="max-w-6xl mx-auto">
            <!-- Back button -->
            <Button variant="ghost" @click="$inertia.visit('/admin/siswa')"
                class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 mb-6 -ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Siswa
            </Button>

            <!-- Profile Card -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Data Siswa</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">NIS</p>
                            <p class="font-medium">{{ siswa.nis }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
                            <p class="font-medium">{{ siswa.nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenis Kelamin</p>
                            <p class="font-medium">{{ siswa.jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tempat, Tanggal Lahir</p>
                            <p class="font-medium">{{ siswa.tempatlahir }}, {{ formatTanggal(siswa.tanggallahir) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Alamat</p>
                            <p class="font-medium">{{ siswa.alamat }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jurusan</p>
                            <p class="font-medium">{{ siswa.jurusan?.nama || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tahun Ajaran</p>
                            <p class="font-medium">{{ siswa.tahunajaran?.nama || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Paket</p>
                            <p class="font-medium">{{ siswa.paket?.kode || '-' }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Penilaian Input Section -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <CardTitle>Input Penilaian Uji Kompetensi</CardTitle>
                        <Button v-if="penilaian && penilaian.penilaian && penilaian.penilaian.length > 0"
                            @click="savePenilaian" :disabled="saving" class="flex items-center gap-2">
                            <svg v-if="saving" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            {{ saving ? 'Menyimpan...' : 'Simpan Penilaian' }}
                        </Button>
                    </div>
                </CardHeader>
                <CardContent>
                    <!-- Loading State -->
                    <div v-if="penilaianLoading" class="text-center py-8 text-gray-500">
                        Memuat data penilaian...
                    </div>

                    <!-- Empty State -->
                    <div v-else-if="!penilaian || !penilaian.penilaian || penilaian.penilaian.length === 0"
                        class="text-center py-8">
                        <p class="text-gray-500 mb-4">Belum ada data penilaian untuk siswa ini.</p>
                        <Button @click="fetchPenilaian" :disabled="creating">
                            <svg v-if="creating" class="animate-spin -ml-1 mr-2 h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            {{ creating ? 'Memuat...' : 'Muat Data Penilaian' }}
                        </Button>
                    </div>

                    <!-- Penilaian Form -->
                    <div v-else>
                        <!-- Info Section -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-500">Total Kompetensi</p>
                                <p class="text-2xl font-bold text-primary mt-1">
                                    {{ penilaian.penilaian?.length || 0 }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Kompeten</p>
                                <p class="text-2xl font-bold text-primary mt-1">
                                    {{ kompetenCount }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Rata-rata Nilai</p>
                                <p class="text-2xl font-bold text-primary mt-1">
                                    {{ averageNilai }}
                                </p>
                            </div>
                        </div>

                        <!-- Kompetensi List -->
                        <div class="space-y-4">
                            <div v-for="(item, index) in penilaian.penilaian" :key="item.id"
                                class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-lg">{{ item.kompetensi?.elemen || 'Kompetensi ' +
                                            (index + 1) }}</h3>
                                        <p v-if="item.kompetensi?.kode" class="text-sm text-gray-500">Kode: <code
                                                class="px-2 py-1 bg-gray-100 rounded">{{ item.kompetensi.kode }}</code>
                                        </p>
                                    </div>
                                    <Badge :variant="item.kompeten ? 'default' : 'destructive'" class="ml-2">
                                        {{ item.kompeten ? 'Kompeten' : 'Belum Kompeten' }}
                                    </Badge>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nilai Input -->
                                    <div>
                                        <Label :for="`nilai-${index}`" class="text-sm font-medium text-gray-700">
                                            Nilai (0-100)
                                        </Label>
                                        <Input :id="`nilai-${index}`" v-model.number="item.nilai" type="number" min="0"
                                            max="100" placeholder="Masukkan nilai" class="mt-1"
                                            @input="updateKompeten(item)" />
                                    </div>

                                    <!-- Status Kompeten Toggle -->
                                    <div>
                                        <Label class="text-sm font-medium text-gray-700">Status</Label>
                                        <div class="flex items-center gap-2 mt-2">
                                            <input type="checkbox" :checked="item.kompeten" disabled
                                                @change="item.kompeten = $event.target.checked"
                                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" />
                                            <span class="text-sm"
                                                :class="item.kompeten ? 'text-green-600' : 'text-red-600'">
                                                {{ item.kompeten ? 'Kompeten' : 'Belum Kompeten' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all"
                                            :class="item.kompeten ? 'bg-green-500' : 'bg-red-500'"
                                            :style="{ width: `${item.nilai || 0}%` }"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-semibold text-blue-900 mb-2">Ringkasan Penilaian</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-blue-700">Total Kompetensi</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ penilaian.penilaian?.length || 0 }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-blue-700">Kompeten</p>
                                    <p class="text-2xl font-bold text-green-600">{{ kompetenCount }}</p>
                                </div>
                            </div>
                            <div v-if="penilaian.penilaian?.length > 0" class="mt-3 pt-3 border-t border-blue-200">
                                <p class="text-sm text-blue-700">
                                    Status Akhir:
                                    <span class="font-bold" :class="allKompeten ? 'text-green-600' : 'text-red-600'">
                                        {{ allKompeten ? 'SEMUA KOMPETEN - LULUS' : 'BELUM SEMUA KOMPETEN' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <!-- Sertifikat Section -->
            <Card class="mt-6">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <CardTitle>Sertifikat</CardTitle>
                        <div class="flex gap-2">
                            <Button @click="printCertificate" class="flex items-center gap-2"
                                :disabled="!canPrintCertificate">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak Sertifikat
                            </Button>
                            <Button @click="showSertifikatForm = true" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Sertifikat
                            </Button>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <!-- Sertifikat Form -->
                    <div v-if="showSertifikatForm" class="mb-6 p-4 border rounded-lg bg-gray-50">
                        <h4 class="font-semibold mb-4">
                            {{ editingSertifikat ? 'Edit Sertifikat' : 'Tambah Sertifikat Baru' }}
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <Label for="sertifikat-instansi">Lembaga / Instansi</Label>
                                <Input id="sertifikat-instansi" v-model="sertifikatForm.instansi" type="text"
                                    placeholder="Masukkan nama lembaga atau instansi" class="mt-1" />
                            </div>
                            <div>
                                <Label for="sertifikat-ketuapenguji">Ketua Penguji</Label>
                                <Input id="sertifikat-ketuapenguji" v-model="sertifikatForm.ketuapenguji" type="text"
                                    placeholder="Masukkan Nama Ketua Penguji" class="mt-1" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <Label for="sertifikat-nomor">Nomor Seri Sertifikat</Label>
                                    <Input id="sertifikat-nomor" v-model="sertifikatForm.nomorseri" type="text"
                                        placeholder="Masukkan nomor seri sertifikat" class="mt-1" />
                                </div>
                                <div>
                                    <Label for="sertifikat-nomor">Nomor Sertifikat</Label>
                                    <Input id="sertifikat-nomor" v-model="sertifikatForm.nomor" type="text"
                                        placeholder="Masukkan nomor sertifikat" class="mt-1" />
                                </div>

                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <Label for="sertifikat-tanggal">Tanggal Penetapan</Label>
                                    <Input id="sertifikat-tanggal" v-model="sertifikatForm.tanggalpenetapan" type="date"
                                        class="mt-1" />
                                </div>
                                <div>
                                    <Label for="sertifikat-tanggalCetak">Tanggal Cetak Sertifikat</Label>
                                    <Input id="sertifikat-tanggalCetak" v-model="sertifikatForm.tanggalcetak"
                                        type="date" placeholder="Masukkan tanggal cetak sertifikat" class="mt-1" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <Label for="sertifikat-tanggalAmbil">Tanggal Ambil</Label>
                                    <Input id="sertifikat-tanggalAmbil" v-model="sertifikatForm.tanggalambil"
                                        type="date" class="mt-1" />
                                </div>
                                <div>
                                    <Label for="sertifikat-diambiloleh">Diambil Oleh</Label>
                                    <Input id="sertifikat-diambiloleh" v-model="sertifikatForm.diambiloleh" type="text"
                                        placeholder="Masukkan nama orang yang mengambil sertifikat" class="mt-1" />
                                </div>
                            </div>
                            <div class="flex gap-2 justify-end">
                                <Button variant="outline" @click="cancelSertifikatForm">Batal</Button>
                                <Button @click="saveSertifikat" :disabled="savingSertifikat">
                                    <svg v-if="savingSertifikat" class="animate-spin -ml-1 mr-2 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    {{ savingSertifikat ? 'Menyimpan...' : 'Simpan' }}
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- Sertifikat List -->
                    <div v-if="sertifikatLoading" class="text-center py-8 text-gray-500">
                        Memuat data sertifikat...
                    </div>
                    <div v-else-if="!sertifikat || sertifikat.length === 0" class="text-center py-8">
                        <p class="text-gray-500">Belum ada sertifikat untuk siswa ini.</p>
                    </div>
                    <div v-else class="space-y-4">
                        <div v-for="(cert, index) in sertifikat" :key="cert.id"
                            class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg">{{ cert.instansi || 'Sertifikat' }}</h3>
                                    <p v-if="cert.nomor" class="text-sm text-gray-500">Nomor: <code
                                            class="px-2 py-1 bg-gray-100 rounded">{{ cert.nomor }}</code></p>
                                    <p v-if="cert.tanggalpenetapan" class="text-sm text-gray-500 mt-1">Tanggal
                                        Penetapan: {{
                                            formatTanggal(cert.tanggalpenetapan) }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <Button variant="outline" size="sm" @click="editSertifikat(cert)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </Button>
                                    <Button variant="destructive" size="sm" @click="deleteSertifikat(cert.id)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
    <CertificatePrint v-if="siswa" :siswa="siswa" :penilaian="penilaian?.penilaian || []" :sertifikat-nomor="sertifikatNomor"
        :tahun-penetapan="tahunPenetapan" :average-nilai="String(averageNilai)"
        :format-tanggal="formatTanggal" />
</template>


<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Components/Layouts/AdminLayout.vue';
import CertificatePrint from '@/Components/CertificatePrint.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useApi } from '@/composables/useApi';
import { success, errorToast, httpError } from '@/composables/useCustomToast';

const page = usePage();
const siswaId = page.props.siswaId || page.url.split('/').pop();
const { loading, get, put, post, del } = useApi();
const siswa = ref(null);
const penilaian = ref(null);
const penilaianLoading = ref(false);
const saving = ref(false);
const creating = ref(false);

// Sertifikat state
const sertifikat = ref([]);
const sertifikatLoading = ref(false);
const showSertifikatForm = ref(false);
const editingSertifikat = ref(null);
const savingSertifikat = ref(false);
const sertifikatForm = ref({
    instansi: '',
    ketuapenguji: '',
    nomorseri: '',
    nomor: '',
    tanggalpenetapan: '',
    tanggalcetak: '',
    tanggalambil: '',
    diambiloleh: '',
});

// Computed properties
const kompetenCount = computed(() => {
    if (!penilaian.value?.penilaian) return 0;
    return penilaian.value.penilaian.filter(d => d.kompeten).length;
});

const allKompeten = computed(() => {
    if (!penilaian.value?.penilaian || penilaian.value.penilaian.length === 0) return false;
    return penilaian.value.penilaian.every(d => d.kompeten);
});

const averageNilai = computed(() => {
    if (!penilaian.value?.penilaian || penilaian.value.penilaian.length === 0) return 0;
    const total = penilaian.value.penilaian.reduce((sum, d) => sum + (d.nilai || 0), 0);
    return Math.round(total / penilaian.value.penilaian.length);
});

// Certificate computed properties
const canPrintCertificate = computed(() => {
    return siswa.value
        && siswa.value.sertifikat
        && penilaian.value?.penilaian
        && penilaian.value.penilaian.length > 0;
});

const sertifikatNomor = computed(() => {
    return siswa.value?.sertifikat?.nomor || '-';
});

const tahunPenetapan = computed(() => {
    if (!siswa.value?.sertifikat?.tanggalpenetapan) return new Date().getFullYear();
    return new Date(siswa.value.sertifikat.tanggalpenetapan).getFullYear();
});

const predikat = computed(() => {
    if (!penilaian.value?.penilaian || penilaian.value.penilaian.length === 0) return '-';
    const allKompeten = penilaian.value.penilaian.every(d => d.kompeten);
    return allKompeten ? 'KOMPETEN' : 'BELUM KOMPETEN';
});

const predikatEnglish = computed(() => {
    if (!penilaian.value?.penilaian || penilaian.value.penilaian.length === 0) return '-';
    const allKompeten = penilaian.value.penilaian.every(d => d.kompeten);
    return allKompeten ? 'COMPETENT' : 'NOT YET COMPETENT';
});

// Format tanggal
function formatTanggal(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}

// Update status kompeten berdasarkan nilai
function updateKompeten(item) {
    item.kompeten = item.nilai >= 60;
}

// Fetch data siswa
async function fetchSiswa() {
    try {
        const data = await get(`/siswa/${siswaId}`);
        siswa.value = data;
    } catch (e) {
        console.error(e);
        alert('Gagal memuat data siswa');
    }
}

// Fetch data penilaian
async function fetchPenilaian() {
    penilaianLoading.value = true;
    creating.value = true;
    try {
        const data = await get(`/penilaian/siswa/${siswaId}`);
        penilaian.value = data;

        // Jika belum ada penilaian, akan dibuat otomatis oleh backend
        if (!penilaian.value?.penilaian || penilaian.value.penilaian.length === 0) {
            penilaian.value.penilaian = [];
        }
    } catch (e) {
        console.error(e);
        alert('Gagal memuat data penilaian');
    } finally {
        penilaianLoading.value = false;
        creating.value = false;
    }
}

// Simpan penilaian
async function savePenilaian() {
    if (!penilaian.value?.penilaian || penilaian.value.penilaian.length === 0) {
        errorToast('Gagal', 'Tidak ada data penilaian untuk disimpan');
        return;
    }

    saving.value = true;
    try {
        await put(`/penilaian/bulk/siswa/${siswaId}`, {
            penilaian: penilaian.value.penilaian
        });
        success('Berhasil', 'Data penilaian berhasil disimpan');
    } catch (e) {
        console.error(e);
        alert(e.response?.data?.message || 'Gagal menyimpan penilaian');
    } finally {
        saving.value = false;
    }
}

// Sertifikat functions
function resetSertifikatForm() {
    sertifikatForm.value = {
        instansi: '',
        ketuapenguji: '',
        nomorseri: '',
        nomor: '',
        tanggalpenetapan: '',
        tanggalcetak: '',
        tanggalambil: '',
        diambiloleh: '',
    };
    editingSertifikat.value = null;
}

function cancelSertifikatForm() {
    showSertifikatForm.value = false;
    resetSertifikatForm();
}

function editSertifikat(cert) {
    editingSertifikat.value = cert;
    sertifikatForm.value = {
        instansi: cert.instansi || '',
        ketuapenguji: cert.ketuapenguji || '',
        nomorseri: cert.nomorseri || '',
        nomor: cert.nomor || '',
        tanggalpenetapan: cert.tanggalpenetapan ? cert.tanggalpenetapan.substring(0, 10) : '',
        tanggalcetak: cert.tanggalcetak ? cert.tanggalcetak.substring(0, 10) : '',
        tanggalambil: cert.tanggalambil ? cert.tanggalambil.substring(0, 10) : '',
        diambiloleh: cert.diambiloleh || '',
    };
    showSertifikatForm.value = true;
}

async function saveSertifikat() {
    if (!sertifikatForm.value.instansi) {
        errorToast('Validasi', 'Lembaga/Instansi harus diisi');
        return;
    }

    savingSertifikat.value = true;
    try {
        const payload = {
            ...sertifikatForm.value,
            siswa_id: siswaId
        };

        if (editingSertifikat.value) {
            await put(`/sertifikat/${editingSertifikat.value.id}`, payload);
            success('Berhasil', 'Sertifikat berhasil diupdate');
        } else {
            await post('/sertifikat', payload);
            success('Berhasil', 'Sertifikat berhasil ditambahkan');
        }

        showSertifikatForm.value = false;
        resetSertifikatForm();
        // Refresh both sertifikat list and siswa (agar siswa.sertifikat terupdate untuk print)
        await Promise.all([fetchSertifikat(), fetchSiswa()]);
    } catch (e) {
        console.error(e);
        errorToast('Gagal', e.response?.data?.message || 'Gagal menyimpan sertifikat');
    } finally {
        savingSertifikat.value = false;
    }
}

async function deleteSertifikat(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus sertifikat ini?')) {
        return;
    }

    try {
        await del(`/sertifikat/${id}`);
        success('Berhasil', 'Sertifikat berhasil dihapus');
        await Promise.all([fetchSertifikat(), fetchSiswa()]);
    } catch (e) {
        console.error(e);
        errorToast('Gagal', e.response?.data?.message || 'Gagal menghapus sertifikat');
    }
}

async function fetchSertifikat() {
    sertifikatLoading.value = true;
    try {
        const data = await get(`/sertifikat/siswa/${siswaId}`);
        sertifikat.value = data || [];
    } catch (e) {
        console.error(e);
        httpError('Gagal memuat data sertifikat');
    } finally {
        sertifikatLoading.value = false;
    }
}

// Print Certificate functions
function printCertificate() {
    if (!penilaian.value?.penilaian || penilaian.value.penilaian.length === 0) {
        errorToast('Tidak Bisa Cetak', 'Data penilaian harus diisi terlebih dahulu');
        return;
    }
    if (!siswa.value?.sertifikat) {
        errorToast('Tidak Bisa Cetak', 'Data sertifikat belum ada. Tambahkan sertifikat terlebih dahulu');
        return;
    }
    window.print();
}

onMounted(async () => {
    await fetchSiswa();
    await fetchPenilaian();
    await fetchSertifikat();
});
</script>
