<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Error;
use Inertia\Inertia;
use PDOException;
use Validator;

class JurusanController extends Controller
{
    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    private $fieldValidator = [
        "nama" => "required",
        "kode" => "required",
        "deskripsi" => "required",
    ];

    /**
     * Inertia page untuk daftar jurusan
     */
    public function indexInertia()
    {
        $this->ensureAdmin();
        $jurusan = Jurusan::orderBy('nama')->get();
        return Inertia::render('Jurusan/Index', [
            'jurusan' => $jurusan,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    public function store(Request $req)
    {
        $this->ensureAdmin();
        try {
            $validator = Validator::make($req->all(), $this->fieldValidator);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $Jurusan = new Jurusan($req->all());
            $Jurusan->save();
            return redirect()->route('jurusan')->with('success', 'Data jurusan berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['general' => $th->getMessage()])->withInput();
        }
    }

    public function update($id, Request $req)
    {
        $this->ensureAdmin();
        try {
            $validator = Validator::make($req->all(), $this->fieldValidator);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $Jurusan = Jurusan::find($id);
            if (!$Jurusan) {
                return redirect()->back()->withErrors(['general' => 'Data Jurusan tidak ditemukan']);
            }

            $Jurusan->nama = $req->nama;
            $Jurusan->kode = $req->kode;
            $Jurusan->deskripsi = $req->deskripsi;
            $Jurusan->save();
            return redirect()->route('jurusan')->with('success', 'Data jurusan berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['general' => $th->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $this->ensureAdmin();
        try {
            $Jurusan = Jurusan::find($id);
            if (!$Jurusan) {
                return redirect()->back()->withErrors(['general' => 'Data Jurusan tidak ditemukan']);
            }
            $Jurusan->delete();
            return redirect()->route('jurusan')->with('success', 'Data jurusan berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['general' => $th->getMessage()]);
        }
    }

    public function index()
    {
        $Jurusan = auth()->user()?->isAdmin()
            ? Jurusan::all()
            : Jurusan::whereKey(auth()->user()?->jurusan_id)->get();
        return response()->json($Jurusan, 200);
    }


    public function byid($id)
    {
        try {
            if (!auth()->user()?->isAdmin() && (int) $id !== (int) auth()->user()?->jurusan_id) {
                abort(403, 'Anda tidak memiliki akses ke jurusan ini.');
            }
            $Jurusan = Jurusan::find($id);
            if ($Jurusan == null) {
                throw new Error("Data Jurusan  tidak ditemukan ! ");
            }
            return response()->json($Jurusan, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    public function post(Request $req)
    {
        $this->ensureAdmin();

        try {
            $validator = Validator::make($req->all(), $this->fieldValidator);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $Jurusan = new Jurusan($req->all());
                $Jurusan->save();
                return response()->json($Jurusan, 200);
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
        $this->ensureAdmin();
        try {
            $validator = Validator::make($req->all(), $this->fieldValidator);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $Jurusan = Jurusan::find($id);
                if ($Jurusan == null)
                    throw new Error("Data Jurusan tidak ditemukan");

                $Jurusan->nama = $req->nama;
                $Jurusan->kode = $req->kode;
                $Jurusan->deskripsi = $req->deskripsi;
                $Jurusan->save();
                return response()->json($Jurusan, 200);
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
        $this->ensureAdmin();
        try {
            $Jurusan = Jurusan::find($id);
            if ($Jurusan == null) {
                throw new Error("Data Jurusan  tidak ditemukan ! ");
            }
            $Jurusan->delete();
            return response()->json(true, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }
}
