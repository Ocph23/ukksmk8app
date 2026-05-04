<template>
    <!-- Hidden Print Container - Only visible during print -->
    <div id="certificate-print-container">
        <!-- FRONT PAGE -->
        <div class="cert-front">
            <!-- Header Sekolah -->
            <div class="cert-header">
                <img
                    class="logo-smkn8"
                    src="/assets/images/LOGO_SMKN8.png"
                    alt="Logo SMKN 8"
                />
                <div class="school-info">
                    <h2 style="font-size: 25px; font-weight: bold">
                        PEMERINTAH KOTA JAYAPURA
                    </h2>
                    <h4 style="font-size: 18px; font-weight: bold">
                        DINAS PENDIDIKAN DAN KEBUDAYAAN
                    </h4>
                    <h3>
                        <b class="cert-blue"
                            >SMK NEGERI 8 TEKNOLOGI, INFORMASI DAN KOMUNIKASI
                            KOTA JAYAPURA</b
                        >
                    </h3>
                    <p class="cert-alamat">
                        Jl. Gelanggang II RT 04 RW 01 Kelurahan Waena, Distrik
                        Heram, Kota Jayapura, Kode Pos 99358
                    </p>
                    <p class="cert-kontak flex justify-center gap-2 ">
                        <i class="flex gap-1"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .9 2.9a2 2 0 0 1-.5 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.4a2 2 0 0 1 2.1-.5c.9.5 1.9.8 2.9.9A2 2 0 0 1 22 16.9z"/>
                            </svg> (0967) 5170108;</i>
                        <i class="flex gap-1"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                            <path d="M3 7l9 6 9-6"/>
                            </svg> smkn8.kotajayapura@gmail.com;</i>
                        <i class="flex gap-1">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M2 12h20"/>
                            <path d="M12 2a15 15 0 0 1 0 20a15 15 0 0 1 0-20z"/>
                            </svg> https://smkn8tikjayapura.sch.id</i>
                    </p>
                </div>
                <img
                    v-if="siswa?.paket?.eksternal?.logo"
                    class="logo-instansi"
                    :src="`/storage/instansi/${siswa.paket.eksternal.logo}`"
                    alt="Logo Instansi"
                />
                <div v-else style="width: 90px; height: 90px"></div>
            </div>

            <hr class="garis-pisah" />

            <!-- Isi Sertifikat -->
            <div class="cert-content">
                <div class="nomor-seri">
                    Nomor Seri : {{ siswa?.sertifikat?.nomorseri }}
                </div>
                <div class="judul-sertifikat">
                    <h3>SERTIFIKAT KOMPETENSI</h3>
                    <h4
                        style="
                            font-weight: bold;
                            font-size: 18px;
                            margin-top: 0px;
                        "
                    >
                        Certificate Of Competency
                    </h4>
                    <h3
                        style="
                            font-weight: bold;
                            font-size: 18px;
                            margin-top: 20px;
                        "
                    >
                        Nomor : {{ sertifikatNomor }}
                    </h3>
                </div>

                <p class="paragraf-pembuka">
                    Sertifikasi ini diselenggarakan berdasarkan Pedoman
                    Penyelenggaraan Uji Kompetensi Keahlian Sekolah Menengah
                    Kejuruan Tahun {{ tahunPenetapan }} menyatakan bahwa :
                </p>

                <table class="tabel-siswa">
                    <tbody>
                        <tr>
                            <td class="label">Nama</td>
                            <td class="separator">:</td>
                            <td class="value" style="text-transform: uppercase">
                                {{ siswa?.nama }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Tempat, Tanggal Lahir</td>
                            <td class="separator">:</td>
                            <td class="value">
                                {{ siswa?.tempatlahir }},
                                {{ formatTanggal(siswa?.tanggallahir) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Sekolah</td>
                            <td class="separator">:</td>
                            <td class="value">
                                SMK Negeri 8 Teknologi Informasi dan Komunikasi
                                Kota Jayapura
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Jurusan</td>
                            <td class="separator">:</td>
                            <td class="value">{{ siswa?.jurusan?.nama }}</td>
                        </tr>
                    </tbody>
                </table>

                <p class="paragraf-lulus">
                    Dinyatakan <span class="lulus"><b>LULUS</b></span> Uji
                    Kompetensi Kejuruan dan diakui telah memiliki kompetensi
                    seperti tercantum di balik sertifikat ini.
                </p>
            </div>

            <!-- Tanda Tangan -->
            <div class="tanda-tangan">
                <div class="ttd-kiri">
                    <p>Mengetahui,</p>
                    <p>Plt. Kepala Sekolah</p>
                    <div class="ttd-line"></div>
                    <p class="ttd-nama">
                        {{ siswa?.tahunajaran?.kepala_sekolah }}
                    </p>
                    <p class="ttd-nip">NIP. {{ siswa?.tahunajaran?.nip }}</p>
                </div>
                <div class="ttd-kanan">
                    <p>
                        Jayapura,
                        {{ formatTanggal(siswa?.sertifikat?.tanggalpenetapan) }}
                    </p>
                    <p>Ketua Tim Penguji</p>
                    <div class="ttd-line"></div>
                    <p class="ttd-nama">{{ siswa?.paket?.eksternal?.nama }}</p>
                    <p class="ttd-instansi">
                        {{ siswa?.paket?.eksternal?.instansi }}
                    </p>
                </div>
            </div>
        </div>

        <!-- BACK PAGE - Daftar Kompetensi -->
        <div class="cert-back">
            <div class="back-header">
                <h4
                    style="
                        text-transform: uppercase;
                        font-size: 20px;
                        font-weight: bold;
                    "
                >
                    Kompetensi Keahlian {{ siswa?.jurusan?.nama }}
                </h4>
                <h4
                    style="
                        text-transform: uppercase;
                        font-size: 20px;
                        font-weight: bold;
                    "
                >
                    Daftar Kompetensi/Subkompetensi
                </h4>
            </div>

            <div class="tugas-info">
                <h4>
                    <b
                        >Tugas : {{ siswa?.paket?.kode }} -
                        {{ siswa?.paket?.judultugas }}</b
                    >
                </h4>
            </div>

            <table class="tabel-nilai">
                <thead>
                    <tr>
                        <th style="width: 50px">No.</th>
                        <th style="width: 150px">Kode</th>
                        <th>Kompetensi</th>
                        <th style="width: 100px">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in penilaian" :key="item.id">
                        <td style="text-align: center">{{ index + 1 }}</td>
                        <td>{{ item.kompetensi?.kode }}</td>
                        <td>{{ item.kompetensi?.elemen }}</td>
                        <td style="text-align: center">
                            <span v-if="siswa?.paket?.basisnilai">{{
                                item.nilai
                            }}</span>
                            <span v-else>
                                <span v-if="item.kompeten" class="cert-check"
                                    >✓</span
                                >
                                <span v-else class="cert-cross">✗</span>
                            </span>
                        </td>
                    </tr>
                    <tr v-if="siswa?.paket?.basisnilai" class="rata-rata">
                        <td style="text-align: center" colspan="3">
                            <b>Nilai Rata-Rata</b>
                        </td>
                        <td style="text-align: center">
                            <b>{{ averageNilai }}</b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    siswa: {
        type: Object,
        required: true,
    },
    penilaian: {
        type: Array,
        required: true,
    },
    sertifikatNomor: {
        type: String,
        required: true,
    },
    tahunPenetapan: {
        type: Number,
        required: true,
    },
    averageNilai: {
        type: String,
        required: true,
    },
    formatTanggal: {
        type: Function,
        required: true,
    },
});
</script>

<style>
/* Certificate Front & Back */
.cert-front,
.cert-back {
    width: 100%;
    min-height: 100vh;
    padding: 60px 100px;
    margin: 0px;
    position: relative;
    box-sizing: border-box;
    background-image: url("/assets/images/certificate2.webp");
    background-size: 100% 100%;
    background-repeat: no-repeat;
    background-position: center;
}

/* Header */
.cert-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.logo-smkn8 {
    width: 90px;
    height: 90px;
    object-fit: contain;
}

.logo-instansi {
    width: 90px;
    height: 90px;
    object-fit: contain;
}

.school-info {
    text-align: center;
    flex: 1;
}

.school-info h2 {
    font-size: 18px;
    margin-top: 30px;
    line-height: 1.3;
}

.school-info h4 {
    font-size: 14px;
    margin: 2px 0;
}

.school-info h3 {
    font-size: 16px;
    margin: 4px 0;
}

.cert-alamat {
    font-size: 11px;
    margin: 1px 0;
    font-style: italic;
}

.cert-kontak {
    font-size: 11px;
    margin: 1px 0;
}

.garis-pisah {
    border: none;
    border-top: 2px solid #484848;
    margin: 0px 0 5px 0;
}

/* Certificate Content */
.cert-content {
    padding: 0px 20px;
    text-align: center;
}

.nomor-seri {
    font-size: 14px;
    margin-bottom: 5px;
    text-align: right;
}

.judul-sertifikat {
    margin: 10px 0;
    text-align: center;
}

.judul-sertifikat h3 {
    font-size: 22px;
    text-decoration: underline;
    margin: 0;
    font-weight: bold;
}

.judul-sertifikat h4 {
    font-size: 16px;
    font-style: italic;
    margin: 5px 0;
}

.judul-sertifikat h5 {
    font-size: 14px;
    margin: 10px 0 0 0;
}

.paragraf-pembuka {
    font-size: 13px;
    text-align: left;
    margin: 10px 0;
    margin-top: 30px;
    line-height: 1.6;
}

.menyatakan {
    font-size: 16px;
    text-align: center;
    margin: 20px 0;
    text-decoration: underline;
}

/* Student Info Table */
.tabel-siswa {
    width: 100%;
    margin: 10px 40px;
    text-align: left;
}

.tabel-siswa .label {
    width: 200px;
    font-size: 14px;
    padding: 3px 0;
}

.tabel-siswa .separator {
    width: 20px;
    font-size: 14px;
    padding: 3px 0;
}

.tabel-siswa .value {
    font-size: 14px;
    padding: 3px 0;
}

.paragraf-lulus {
    font-size: 14px;
    margin: 10px 0;
    text-align: left;
    line-height: 1.6;
}

.lulus {
    font-style: italic;
    text-decoration: underline;
}

/* Signatures */
.tanda-tangan {
    display: flex;
    justify-content: space-between;
    margin-top: 3px;
    padding: 0 40px;
}

.ttd-kiri,
.ttd-kanan {
    text-align: center;
    width: 45%;
}

.ttd-kiri p,
.ttd-kanan p {
    margin: 3px 0;
    font-size: 14px;
}

.ttd-line {
    width: 250px;
    margin: 60px auto 10px auto;
}

.ttd-nama {
    font-weight: bold;
    text-decoration: underline;
    font-size: 14px;
}

.ttd-nip {
    font-size: 13px;
}

.ttd-instansi {
    font-size: 13px;
}

/* Back Page */
.back-header {
    text-align: center;
    margin-bottom: 20px;
    margin-top: 30px;
}

.back-header h3 {
    font-size: 18px;
    margin: 5px 0;
    text-align: center;
}

.tugas-info {
    margin: 20px 0;
    padding: 0 20px;
}

.tugas-info h4 {
    font-size: 16px;
}

/* Nilai Table */
.tabel-nilai {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
}

.tabel-nilai th,
.tabel-nilai td {
    border: 1px solid #333;
    padding: 8px;
    font-size: 13px;
}

.tabel-nilai th {
    background-color: #f0f0f0;
    font-weight: bold;
    text-align: center;
}

.tabel-nilai .rata-rata {
    background-color: #f9f9f9;
    font-weight: bold;
}

.cert-check {
    color: green;
    font-size: 18px;
    font-weight: bold;
}

.cert-cross {
    color: red;
    font-size: 18px;
    font-weight: bold;
}

.cert-blue {
    color: #4875d5;
}

/* Hide print container by default */
#certificate-print-container {
    display: none;
}

@media print {
    icon-cop {
        color: black !important;
    }

    h2 {
        font-size: 40px;
    }

    @page {
        margin: 0;
        size: auto;
    }

    body * {
        visibility: hidden !important;
    }

    #certificate-print-container,
    #certificate-print-container * {
        visibility: visible !important;
    }

    #certificate-print-container {
        display: block !important;
        position: fixed;
        left: 0;
        top: 0;
        width: 100vw;
        padding: 0 !important;
        margin: 0 !important;
        z-index: 99999;
    }

    .cert-front,
    .cert-back {
        width: 100vw;
        min-height: 100vh;
        padding: 40px 60px;
        box-sizing: border-box;
        box-shadow: none !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        background-size: 100% 100% !important;
        background-repeat: no-repeat !important;
    }

    .cert-front {
        page-break-after: always;
        break-after: page;
    }

    .cert-back {
        page-break-after: avoid;
        break-after: avoid;
    }
}
</style>
