<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Error;
use PDOException;
use Validator;

class JurusanController extends Controller
{

    private $fieldValidator = [
        "nama" => "required",
        "kode" => "required",
        "deskripsi" => "required",
    ];
    public function index()
    {
        $Jurusan = Jurusan::all();
        return response()->json($Jurusan, 200);
    }


    public function getJurusan(){
        return view('jurusan',["data"=>Jurusan::all()]);
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
            $validator =Validator::make($req->all(), $this->fieldValidator);

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
