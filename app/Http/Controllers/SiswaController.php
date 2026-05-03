<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\DetailPenilaian;
use App\Models\Jurusan;
use App\Models\Paket;
use App\Models\Penilaian;
use App\Models\Sertifikat;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Error;
use Illuminate\Support\Facades\Date;
use Inertia\Inertia;
use PDOException;
use Validator;

class SiswaController extends Controller
{

    private $fieldValidate =  [
        "nis" => "required",
        "nama" => "required",
        "jk"  => "required",
        'tempatlahir' => "required",
        'tanggallahir' => "required",
        "alamat"  => "required",
        "jurusan_id"  => "required",
        "tahunajaran_id"  => "required",
        "paket_id"  => "required",
    ];

    /**
     * Inertia page untuk daftar siswa dengan filter dan pagination
     */
    public function indexInertia(Request $request)
    {

        $activeTa = TahunAjaran::where('aktif', true)->first();
        $defaultTaId = $activeTa ? $activeTa->id : null;

        // Check if filter is explicitly set in URL (including empty value)
        $hasTaFilter = $request->has('tahunajaran_id');
        $filterTa = $hasTaFilter ? ($request->query('tahunajaran_id') ?: null) : $defaultTaId;

        $filterJurusan = $request->query('jurusan_id', '');
        $search = $request->query('search', '');
        $perPage = $request->query('per_page', 15);

        // Build query
        $query = Siswa::with(['jurusan', 'tahunajaran', 'paket']);

        if ($filterTa) {
            $query->where('tahunajaran_id', $filterTa);
        }

        if ($filterJurusan) {
            $query->where('jurusan_id', $filterJurusan);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $siswa = $query->latest()->paginate($perPage)->withQueryString();

        // Get filter options
        $tahunAjaranList = TahunAjaran::orderBy('tahun', 'desc')->get();
        $jurusanList = Jurusan::orderBy('nama')->get();

        // Get paket options based on selected tahun ajaran
        $paketList = Paket::when($filterTa, function ($q) use ($filterTa) {
            return $q->where('tahunajaran_id', $filterTa);
        })
            ->with('jurusan')
            ->orderBy('kode')
            ->get();

        return Inertia::render('Siswa/Index', [
            'activeTahunAjaran' => $activeTa,
            'siswa' => $siswa,
            'tahunAjaranList' => $tahunAjaranList,
            'jurusanList' => $jurusanList,
            'paketList' => $paketList->map(fn($p) => [
                'id' => $p->id,
                'kode' => $p->kode,
                'judultugas' => $p->judultugas,
                'tahunajaran_id' => $p->tahunajaran_id,
                'jurusan_id' => $p->jurusan_id,
            ]),
            'filters' => [
                'tahunajaran_id' => $filterTa,
                'jurusan_id' => $filterJurusan,
                'search' => $search,
            ],
        ]);
    }

    /**
     * Get all pakets for form dropdown
     */
    public function getPaketList()
    {
        $pakets = Paket::select('id', 'kode', 'judultugas', 'tahunajaran_id', 'jurusan_id')
            ->orderBy('kode')
            ->get();
        return response()->json($pakets);
    }

    public function index()
    {
        $siswa = Siswa::all();
        return response()->json($siswa, 200);
    }


    public function byid($id)
    {
        try {
            $siswa = Siswa::with([
                'jurusan',
                'tahunajaran',
                'paket.eksternal',
                'paket.internal',
                'sertifikat',
                'penilaian.kompetensi'
            ])->findOrFail($id);
            return response()->json($siswa, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }


    public function bynis($nis)
    {
        try {
            $siswa = Siswa::where("nis", $nis);
            if ($siswa == null) {
                throw new Error("Data Siswa NIS :  {$nis} tidak ditemukan ! ");
            }
            return response()->json($siswa, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }


    public function bytahunajaran($id)
    {
        try {
            $data = Siswa::where("tahunajaran_id", $id)->get();
            foreach ($data as $key => $value) {
                $value->tahunajaran;
                $value->jurusan;
                $value->paket;
                $value->penilaian;
            }
            return response()->json($data, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    public function post(Request $req)
    {

        try {
            $validator = Validator::make($req->all(), $this->fieldValidate);
            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $siswa = new Siswa($req->all());
                // $tgl = new DateTime($siswa->tanggallahir);
                // $siswa->tanggallahir = $tgl->format('Y-m-d');
                $siswa->save();
                return response()->json($siswa, 200);
            }
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }



    public function updateSertifikat(Request $req)
    {

        try {
            $validator = Validator::make(
                $req->all(),
                [
                    'instansi' => "required",
                    'ketuapenguji' => "required",
                    'nomorseri' => "required",
                    'nomor' => "required",
                    'tanggalpenetapan' => "required",
                    'tanggalcetak' => "required",
                    'tanggalambil' => "required",
                    'diambiloleh' => "required",
                    'siswa_id' => "required"
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                throw new Error("Periksa Kembali Data Anda");
            } else {
                if (!$req->id) {
                    $sertifikat = new Sertifikat($req->all());
                    $sertifikat->tanggalpenetapan = Carbon::createFromDate($sertifikat->tanggalpenetapan);
                    $sertifikat->save();
                } else {
                    $sertifikat = Sertifikat::find($req->id);
                    $sertifikat->fill($req->all());
                    $sertifikat->tanggalpenetapan = Carbon::createFromDate($sertifikat->tanggalpenetapan);
                    $sertifikat->tanggalcetak = Carbon::createFromDate($sertifikat->tanggalcetak);
                    $sertifikat->tanggalambil = Carbon::createFromDate($sertifikat->tanggalambil);
                    $sertifikat->save();
                }

                return response()->json($sertifikat, 200);
            }
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }



    public function put($id, Request $req)
    {
        try {
            $validator = Validator::make($req->all(), $this->fieldValidate);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $siswa = Siswa::find($id);
                if ($siswa == null)
                    throw new Error("Data Siswa tidak ditemukan");
                $siswa->fill($req->all());
                // $tgl = new DateTime($siswa->tanggallahir);
                // $siswa->tanggallahir = $tgl->format('Y-m-d');
                $siswa->save();
                if ($req->penilaian) {
                    foreach ($req->penilaian as $key => $value) {
                        if (!$value['id']) {
                            $row = new DetailPenilaian($value);
                            $row->save();
                            $value['id'] = $row->id;
                        } else {
                            $row = DetailPenilaian::find($value['id']);
                            if (isset($value['kompetensi'])) {
                                if ($value['kompetensi']['paket_id'] != $siswa->paket_id) {
                                    $row->delete();
                                } else {
                                    $row->fill($value);
                                    $row->save();
                                }
                            }
                        }
                    }

                    $siswa->penilaian = $siswa->penilaian()->get();
                }
                return response()->json($siswa, 200);
            }
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }


    public function delete($id)
    {
        try {
            $siswa = Siswa::find($id);
            if ($siswa == null) {
                throw new Error("Data Siswa  tidak ditemukan ! ");
            }
            $siswa->delete();
            return response()->json(true, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    public function getKompetensiBySiswaId($id)
    {
        try {
            $siswa = Siswa::with(['paket.kompetensis'])->findOrFail($id);
            if ($siswa == null) {
                throw new Error("Data Siswa tidak ditemukan ! ");
            }

            return response()->json([
                'siswa' => $siswa,
                'kompetensis' => $siswa->paket->kompetensis
            ], 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }
}
