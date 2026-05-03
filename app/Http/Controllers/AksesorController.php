<?php

namespace App\Http\Controllers;

use App\AppHelper;
use App\Http\DatabaseHelper;
use App\Models\Aksesor;
use Illuminate\Http\Request;
use Error;
use Inertia\Inertia;
use PDOException;
use Validator;
use File;
use Illuminate\Support\Facades\Storage;
use Image;

class AksesorController extends Controller
{

    private $fieldValidate = [
        "nama" => "required",
        "instansi" => "required",
        "jk" => "required",
        "jenis" => "required",
        "catatan" => "required"
    ];

    /**
     * Inertia page untuk daftar asesor
     */
    public function indexInertia()
    {
        $asesor = Aksesor::orderBy('nama')->get();
        return Inertia::render('Aksesor/Index', [
            'asesor' => $asesor,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    public function store(Request $req)
    {
        try {
            $data = $req->validate($this->fieldValidate);
            $Aksesor = new Aksesor($data);
            if ($req->dataLogo) {
                $logo = $req->dataLogo;
                $Aksesor->logo = $this->saveFile($logo);
            }
            $Aksesor->save();
            return redirect()->route('aksesor')->with('success', 'Data asesor berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['general' => $th->getMessage()])->withInput();
        }
    }

    public function update($id, Request $req)
    {
        try {
            $data = $req->validate($this->fieldValidate);
            $Aksesor = Aksesor::find($id);
            if (!$Aksesor) {
                return redirect()->back()->withErrors(['general' => 'Data Aksesor tidak ditemukan']);
            }

            $Aksesor->fill($data);

            if ($req->dataLogo) {
                $logo = $req->dataLogo;
                $Aksesor->logo = $this->saveFile($logo);
            }

            $Aksesor->save();
            return redirect()->route('aksesor')->with('success', 'Data asesor berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['general' => $th->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $Aksesor = Aksesor::find($id);
            if (!$Aksesor) {
                return redirect()->back()->withErrors(['general' => 'Data Aksesor tidak ditemukan']);
            }
            $Aksesor->delete();
            return redirect()->route('aksesor')->with('success', 'Data asesor berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['general' => $th->getMessage()]);
        }
    }

    public function index()
    {
        $Aksesor = Aksesor::all();
        return response()->json($Aksesor, 200);
    }


    public function byid($id)
    {
        try {
            $Aksesor = Aksesor::find($id);
            if ($Aksesor == null) {
                throw new Error("Data Aksesor  tidak ditemukan ! ");
            }
            return response()->json($Aksesor, 200);
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
            $data = $req->validate($this->fieldValidate);
            $Aksesor = new Aksesor($data);
            if ($req->dataLogo) {
                $logo = $req->dataLogo;
                $Aksesor->logo = $this->saveFile($logo);
            }
            $Aksesor->save();
            return response()->json($Aksesor, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }



    public function saveFile($logo)
    {

        $imagecode = base64_decode($logo);
        $directory = public_path('/instansi', 0777, true);
        if (!file_exists($directory))
            \File::makeDirectory($directory);
        $id = uniqid('img_');
        $filename = time() . '-' . $id . '.png';
        $path = 'instansi' . '/' . $filename;
        Storage::disk('public')->put($path, $imagecode);
        return $filename;
    }

    public function put($id, Request $req)
    {
        try {
            $data = $req->validate($this->fieldValidate);
            $Aksesor = Aksesor::find($id);
            if ($Aksesor == null)
                throw new Error("Data Aksesor tidak ditemukan");

            $Aksesor->fill($data);

            if ($req->dataLogo) {
                $logo = $req->dataLogo;
                $Aksesor->logo = $this->saveFile($logo);
            }

            $Aksesor->save();
            return response()->json($Aksesor, 200);
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
            $Aksesor = Aksesor::find($id);
            if ($Aksesor == null) {
                throw new Error("Data Aksesor  tidak ditemukan ! ");
            }
            $Aksesor->delete();
            return response()->json(true, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }
}
