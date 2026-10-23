<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SertifikatController extends Controller
{
    use Concerns\JurusanScoped;
    private $fieldValidator = [
        'instansi' => 'string|max:255|nullable',
        'ketuapenguji' => 'string|max:255|nullable',
        'nomorseri' => 'string|max:100|nullable',
        'nomor' => 'string|max:100|nullable',
        'tanggalpenetapan' => 'date|nullable',
        'tanggalcetak' => 'date|nullable',
        'tanggalambil' => 'date|nullable',
        'diambiloleh' => 'string|max:255|nullable',
        'siswa_id' => 'required|exists:siswas,id',
    ];

    public function verifikasi(Request $req)
    {
        try {
            $nomor = $req->query('nomor');
            if (!$nomor) {
                return response()->json(['message' => 'Nomor sertifikat diperlukan'], 400);
            }

            $sertifikat = Sertifikat::with(['siswa.jurusan', 'siswa.tahunajaran', 'siswa.paket'])
                ->where('nomorseri', $nomor)
                ->orWhere('nomor', $nomor)
                ->first();

            if (!$sertifikat) {
                return response()->json(['message' => 'Sertifikat tidak ditemukan'], 404);
            }

            return response()->json($sertifikat);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        try {
            $sertifikat = Sertifikat::with('siswa')->get();
            $sertifikat = $sertifikat->filter(fn ($item) => !$this->userJurusanId() || $item->siswa?->jurusan_id === $this->userJurusanId())->values();
            return response()->json($sertifikat);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function byid($id)
    {
        try {
            $sertifikat = Sertifikat::with('siswa')->find($id);
            if (!$sertifikat) {
                return response()->json([
                    'message' => 'Sertifikat tidak ditemukan',
                ], 404);
            }
            $this->ensureJurusan($sertifikat->siswa?->jurusan_id);
            return response()->json($sertifikat);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function bySiswa($siswaId)
    {
        try {
            $sertifikat = Sertifikat::with('siswa')
                ->where('siswa_id', $siswaId)
                ->get();
            $this->ensureJurusan($sertifikat->first()?->siswa?->jurusan_id ?? \App\Models\Siswa::findOrFail($siswaId)->jurusan_id);
            return response()->json($sertifikat);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function post(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), $this->fieldValidator);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $this->ensureJurusan(\App\Models\Siswa::findOrFail($req->siswa_id)->jurusan_id);

            $sertifikat = Sertifikat::create($req->all());
            return response()->json($sertifikat->load('siswa'), 201);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function put(Request $req, $id)
    {
        try {
            $sertifikat = Sertifikat::find($id);
            if (!$sertifikat) {
                return response()->json([
                    'message' => 'Sertifikat tidak ditemukan',
                ], 404);
            }
            $this->ensureJurusan($sertifikat->siswa?->jurusan_id ?? \App\Models\Siswa::findOrFail($sertifikat->siswa_id)->jurusan_id);

            $validator = Validator::make($req->all(), $this->fieldValidator);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $sertifikat->update($req->all());
            return response()->json($sertifikat->load('siswa'));
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $sertifikat = Sertifikat::find($id);
            if (!$sertifikat) {
                return response()->json([
                    'message' => 'Sertifikat tidak ditemukan',
                ], 404);
            }
            $this->ensureJurusan($sertifikat->siswa?->jurusan_id ?? \App\Models\Siswa::findOrFail($sertifikat->siswa_id)->jurusan_id);

            $sertifikat->delete();
            return response()->json([
                'message' => 'Sertifikat berhasil dihapus',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
