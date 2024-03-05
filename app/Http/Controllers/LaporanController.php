<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function laporanKelulusan($ta, $jurusan)
    {
        $dataSiswa = Siswa::where("tahunajaran_id", $ta)
            ->where("jurusan_id", $jurusan)->get();
        $laporan = [];
        if ($dataSiswa) {
            foreach ($dataSiswa as $key => $value) {
                $siswa = [
                    "nis" => $value->nis,
                    "nama" => $value->nama,
                    "jk" => $value->jk,
                    "ttl" => $value->tempatlahir . " ," . (new Carbon($value->tanggallahir))->format('d M Y'),
                ];

                $paket = $value->paket;
                $siswa["paket"] = $paket->kode . " - " . $paket->judultugas;

                if ($value->sertifikat) {
                    $siswa["status"] = "Lulus";
                } else {
                    $siswa["status"] = "";
                }

                array_push($laporan, $siswa);
            }
        }

        return response()->json($laporan, 200);
    }


    public function laporanAksesor($ta)
    {

        $pakets = Paket::where("tahunajaran_id", $ta)->get();
        $laporan = [];
        if ($pakets) {
            foreach ($pakets as $key => $value) {
                $aksesor1 = [
                    "nama" => $value->internal->nama,
                    "jk" => $value->internal->jk,
                    "instansi" => $value->internal->instansi,
                    "jk" => $value->internal->jk,
                    "jenis" => 'internal'
                ];
                array_push($laporan, $aksesor1);
                $aksesor2 = [
                    "nama" => $value->eksternal->nama,
                    "jk" => $value->eksternal->jk,
                    "instansi" => $value->eksternal->instansi,
                    "jk" => $value->eksternal->jk,
                    "jenis" => 'eksternal'
                ];
                array_push($laporan, $aksesor2);
            }
        }
        return response()->json($laporan, 200);
    }
}
