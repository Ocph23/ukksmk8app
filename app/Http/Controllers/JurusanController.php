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
        $Jurusan = Jurusan::all();
        return response()->json($Jurusan, 200);
    }


    public function byid($id)
    {
        try {
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
