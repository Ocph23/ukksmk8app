<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\Kompetensi;
use App\Models\Paket;
use Illuminate\Http\Request;
use Error;
use Illuminate\Support\Facades\Validator;
use PDOException;

class KompetensiController extends Controller
{
    private $fieldValidate = [
        "kode" => "required",
        "elemen" => "required",
        "paket_id" => "required",
    ];

    /**
     * Get all kompetensi
     */
    public function index()
    {
        $kompetensi = Kompetensi::with('paket')->get();
        return response()->json($kompetensi, 200);
    }

    /**
     * Get kompetensi by ID
     */
    public function byid($id)
    {
        try {
            $kompetensi = Kompetensi::with('paket')->findOrFail($id);
            if ($kompetensi == null) {
                throw new Error("Data Kompetensi tidak ditemukan ! ");
            }
            return response()->json($kompetensi, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    /**
     * Get kompetensi by paket ID
     */
    public function bypaket($paketId)
    {
        try {
            $kompetensi = Kompetensi::where('paket_id', $paketId)
                ->orderBy('kode')
                ->get();
            
            return response()->json($kompetensi, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    /**
     * Create new kompetensi
     */
    public function post(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), $this->fieldValidate);
            
            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            }

            // Verify paket exists
            $paket = Paket::find($req->paket_id);
            if ($paket == null) {
                throw new Error("Paket tidak ditemukan!");
            }

            $kompetensi = new Kompetensi($req->all());
            $kompetensi->save();

            return response()->json([
                'message' => 'Kompetensi berhasil ditambahkan',
                'data' => $kompetensi
            ], 200);

        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    /**
     * Update kompetensi
     */
    public function put($id, Request $req)
    {
        try {
            $validator = Validator::make($req->all(), $this->fieldValidate);
            
            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            }

            $kompetensi = Kompetensi::find($id);
            if ($kompetensi == null) {
                throw new Error("Data Kompetensi tidak ditemukan");
            }

            // Verify paket exists
            $paket = Paket::find($req->paket_id);
            if ($paket == null) {
                throw new Error("Paket tidak ditemukan!");
            }

            $kompetensi->fill($req->all());
            $kompetensi->save();

            return response()->json([
                'message' => 'Kompetensi berhasil diupdate',
                'data' => $kompetensi
            ], 200);

        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    /**
     * Delete kompetensi
     */
    public function delete($id)
    {
        try {
            $kompetensi = Kompetensi::find($id);
            if ($kompetensi == null) {
                throw new Error("Data Kompetensi tidak ditemukan ! ");
            }
            
            $kompetensi->delete();
            return response()->json([
                'message' => 'Kompetensi berhasil dihapus'
            ], 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    /**
     * Bulk create kompetensi for a paket
     */
    public function bulkPost(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                "paket_id" => "required",
                "kompetensi" => "required|array",
                "kompetensi.*.kode" => "required",
                "kompetensi.*.elemen" => "required",
            ]);
            
            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            }

            // Verify paket exists
            $paket = Paket::find($req->paket_id);
            if ($paket == null) {
                throw new Error("Paket tidak ditemukan!");
            }

            $created = [];
            foreach ($req->kompetensi as $komp) {
                $kompetensi = new Kompetensi([
                    'kode' => $komp['kode'],
                    'elemen' => $komp['elemen'],
                    'paket_id' => $req->paket_id,
                ]);
                $kompetensi->save();
                $created[] = $kompetensi;
            }

            return response()->json([
                'message' => count($created) . ' kompetensi berhasil ditambahkan',
                'data' => $created
            ], 200);

        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }
}
