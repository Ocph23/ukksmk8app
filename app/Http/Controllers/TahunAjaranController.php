<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Error;
use Inertia\Inertia;
use PDOException;
use Validator;

class TahunAjaranController extends Controller
{
    /**
     * Inertia page untuk daftar tahun ajaran
     */
    public function indexInertia()
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun', 'desc')->get();
        return Inertia::render('TahunAjaran/Index', [
            'tahunAjaran' => $tahunAjaran,
        ]);
    }

    /**
     * Simpan tahun ajaran baru (Inertia)
     */
    public function store(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                "tahun" => "required",
                "kepala_sekolah" => "required",
                "nip" => "required",
                "deskripsi" => "required",
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Jika aktif, nonaktifkan yang lain
            if ($req->boolean('aktif', false)) {
                TahunAjaran::where('aktif', true)->update(['aktif' => false]);
            }

            $TahunAjaran = new TahunAjaran();
            $TahunAjaran->tahun = $req->tahun;
            $TahunAjaran->kepala_sekolah = $req->kepala_sekolah;
            $TahunAjaran->nip = $req->nip;
            $TahunAjaran->deskripsi = $req->deskripsi;
            $TahunAjaran->aktif = $req->boolean('aktif', false);
            $TahunAjaran->save();

            return redirect()->route('tahunajaran')->with('success', 'Data tahun ajaran berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['general' => $th->getMessage()])->withInput();
        }
    }

    /**
     * Update tahun ajaran (Inertia)
     */
    public function update($id, Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                "tahun" => "required",
                "kepala_sekolah" => "required",
                "nip" => "required",
                "deskripsi" => "required",
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $TahunAjaran = TahunAjaran::find($id);
            if (!$TahunAjaran) {
                return redirect()->back()->withErrors(['general' => 'Data TahunAjaran tidak ditemukan']);
            }

            $TahunAjaran->tahun = $req->tahun;
            $TahunAjaran->kepala_sekolah = $req->kepala_sekolah;
            $TahunAjaran->nip = $req->nip;
            $TahunAjaran->deskripsi = $req->deskripsi;
            $TahunAjaran->save();

            return redirect()->route('tahunajaran')->with('success', 'Data tahun ajaran berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['general' => $th->getMessage()])->withInput();
        }
    }

    /**
     * Hapus tahun ajaran (Inertia)
     */
    public function destroy($id)
    {
        try {
            $TahunAjaran = TahunAjaran::find($id);
            if (!$TahunAjaran) {
                return redirect()->back()->withErrors(['general' => 'Data TahunAjaran tidak ditemukan']);
            }
            $TahunAjaran->delete();
            return redirect()->route('tahunajaran')->with('success', 'Data tahun ajaran berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['general' => $th->getMessage()]);
        }
    }

    // ============ API Methods (tetap untuk API routes) ============

    public function index()
    {
        $TahunAjaran = TahunAjaran::all();
        return response()->json($TahunAjaran, 200);
    }

    public function byid($id)
    {
        try {
            if ($id == "aktif") {
                $TahunAjaran = TahunAjaran::where('aktif', false)->first();
            } else {
                $TahunAjaran = TahunAjaran::find($id);
            }
            return response()->json($TahunAjaran, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    public function aktif()
    {
        try {
            $TahunAjaran = TahunAjaran::where('aktif', true)->first();
            return response()->json($TahunAjaran, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    public function setActive($id, Request $req)
    {
        try {
            $ta = TahunAjaran::find($id);
            if (!$ta) {
                throw new Error("Data TahunAjaran tidak ditemukan");
            }
            TahunAjaran::where('aktif', true)->update(['aktif' => false]);
            $ta->aktif = true;
            $ta->save();
            return response()->json($ta, 200);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    public function post(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                "tahun" => "required",
                "kepala_sekolah" => "required",
                "nip" => "required",
                "deskripsi" => "required",
            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            }

            TahunAjaran::whereIn('aktif', [true])->update(['aktif' => false]);
            $TahunAjaran = new TahunAjaran();
            $TahunAjaran->tahun = $req->tahun;
            $TahunAjaran->kepala_sekolah = $req->kepala_sekolah;
            $TahunAjaran->nip = $req->nip;
            $TahunAjaran->deskripsi = $req->deskripsi;
            $TahunAjaran->aktif = $req->aktif;
            $TahunAjaran->save();
            return response()->json($TahunAjaran, 200);
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
            $validator = Validator::make($req->all(), [
                "tahun" => "required",
                "kepala_sekolah" => "required",
                "nip" => "required",
                "deskripsi" => "required",
            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            }

            $TahunAjaran = TahunAjaran::find($id);
            if ($TahunAjaran == null)
                throw new Error("Data TahunAjaran tidak ditemukan");

            $TahunAjaran->tahun = $req->tahun;
            $TahunAjaran->kepala_sekolah = $req->kepala_sekolah;
            $TahunAjaran->nip = $req->nip;
            $TahunAjaran->deskripsi = $req->deskripsi;
            $TahunAjaran->save();
            return response()->json($TahunAjaran, 200);
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
            $TahunAjaran = TahunAjaran::find($id);
            if ($TahunAjaran == null) {
                throw new Error("Data TahunAjaran  tidak ditemukan ! ");
            }
            $TahunAjaran->delete();
            return response()->json(true, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }
}
