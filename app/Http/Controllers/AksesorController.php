<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\Aksesor;
use Illuminate\Http\Request;
use Error;
use PDOException;
use Validator;

class AksesorController extends Controller
{
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
            $errorMessage["messsage"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    public function post(Request $req)
    {

        try {
            $validator = Validator::make($req->all(), [
                "nama" => "required",
                "instansi" => "required",
                "jk" => "required",
                "jenis" => "required",
            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $Aksesor = new Aksesor();
                $Aksesor->nama = $req->nama;
                $Aksesor->instansi = $req->instansi;
                $Aksesor->jk = $req->jk;
                $Aksesor->jenis = $req->jenis;
                $Aksesor->save();
                return response()->json($Aksesor, 200);
            }
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["messsage"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }


    public function put($id, Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                "nama" => "required",
                "instansi" => "required",
                "jk" => "required",
                "jenis" => "required",
            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $Aksesor = Aksesor::find($id);
                if ($Aksesor == null)
                    throw new Error("Data Aksesor tidak ditemukan");

                $Aksesor->nama = $req->nama;
                $Aksesor->instansi = $req->instansi;
                $Aksesor->jk = $req->jk;
                $Aksesor->jenis = $req->jenis;
                $Aksesor->save();
                return response()->json($Aksesor, 200);
            }
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["messsage"] = $th->getMessage();
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
            $errorMessage["messsage"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }
}
