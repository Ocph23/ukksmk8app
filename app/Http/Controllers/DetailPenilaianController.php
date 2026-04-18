<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\DetailPenilaian;
use App\Models\Kompetensi;
use App\Models\Paket;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Error;
use Illuminate\Support\Facades\Validator;
use PDOException;

class DetailPenilaianController extends Controller
{
    private $fieldValidator = [
        "siswa_id"  => "required",
    ];

    /**
     * Get all detail penilaian for a siswa
     */
    public function index($siswaId)
    {
        try {
            $siswa = Siswa::findOrFail($siswaId);
            $detailPenilaian = DetailPenilaian::where('siswa_id', $siswaId)
                ->with('kompetensi')
                ->get();

            // If no detail exists, create from paket kompetensis
            if ($detailPenilaian->isEmpty()) {
                $paket = Paket::find($siswa->paket_id);
                if ($paket && $paket->kompetensis) {
                    foreach ($paket->kompetensis as $kompetensi) {
                        $detail = new DetailPenilaian();
                        $detail->siswa_id = $siswaId;
                        $detail->kompetensi_id = $kompetensi->id;
                        $detail->nilai = 0;
                        $detail->kompeten = false;
                        $detail->save();
                    }
                    $detailPenilaian = DetailPenilaian::where('siswa_id', $siswaId)
                        ->with('kompetensi')
                        ->get();
                }
            }

            return response()->json([
                'siswa' => $siswa,
                'penilaian' => $detailPenilaian
            ], 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    /**
     * Get single detail penilaian by ID
     */
    public function byid($id)
    {
        try {
            $detail = DetailPenilaian::with('kompetensi')->findOrFail($id);
            if ($detail == null) {
                throw new Error("Data Penilaian tidak ditemukan ! ");
            }
            return response()->json($detail, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    /**
     * Create new detail penilaian
     */
    public function post(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'siswa_id' => 'required',
                'kompetensi_id' => 'required',
                'nilai' => 'required|numeric|min:0|max:100',
                'kompeten' => 'boolean'
            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            }

            // Check if already exists
            $existing = DetailPenilaian::where('siswa_id', $req->siswa_id)
                ->where('kompetensi_id', $req->kompetensi_id)
                ->first();
            
            if ($existing) {
                throw new Error("Kompetensi sudah dinilai untuk siswa ini");
            }

            $detail = new DetailPenilaian($req->all());
            $detail->save();

            return response()->json([
                'message' => 'Penilaian berhasil ditambahkan',
                'data' => $detail
            ], 200);

        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    /**
     * Update detail penilaian
     */
    public function put($id, Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'nilai' => 'required|numeric|min:0|max:100',
                'kompeten' => 'boolean'
            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            }

            $detail = DetailPenilaian::find($id);
            if ($detail == null) {
                throw new Error("Data Penilaian tidak ditemukan");
            }

            $detail->fill($req->only(['nilai', 'kompeten']));
            $detail->save();

            return response()->json([
                'message' => 'Penilaian berhasil diupdate',
                'data' => $detail
            ], 200);

        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    /**
     * Bulk update all detail penilaian for a siswa
     */
    public function bulkUpdate($siswaId, Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'penilaian' => 'required|array',
                'penilaian.*.id' => 'required',
                'penilaian.*.nilai' => 'required|numeric|min:0|max:100',
                'penilaian.*.kompeten' => 'boolean'
            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            }

            $updated = [];
            foreach ($req->penilaian as $item) {
                $detail = DetailPenilaian::find($item['id']);
                if ($detail) {
                    $detail->nilai = $item['nilai'];
                    $detail->kompeten = $item['kompeten'] ?? false;
                    $detail->save();
                    $updated[] = $detail;
                }
            }

            return response()->json([
                'message' => count($updated) . ' penilaian berhasil diupdate',
                'data' => $updated
            ], 200);

        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    /**
     * Delete detail penilaian
     */
    public function delete($id)
    {
        try {
            $detail = DetailPenilaian::find($id);
            if ($detail == null) {
                throw new Error("Data Penilaian tidak ditemukan ! ");
            }
            $detail->delete();
            return response()->json([
                'message' => 'Penilaian berhasil dihapus'
            ], 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }
}
