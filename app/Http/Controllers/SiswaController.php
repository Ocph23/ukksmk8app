<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Error;
use PDOException;
use Validator;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::all();
        return response()->json($siswa, 200);
    }


    public function byid($id)
    {
        try {
            $siswa = Siswa::find($id);
            if ($siswa == null) {
                throw new Error("Data Siswa  tidak ditemukan ! ");
            }
            return response()->json($siswa, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["messsage"] = $th->getMessage();
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
            $errorMessage["messsage"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    public function post(Request $req)
    {

        try {
            $validator = Validator::make($req->all(), [
                "nis" => "required",
                "nama" => "required",
                "jk"  => "required",
                "alamat"  => "required",
            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $siswa = new Siswa();
                $siswa->nis = $req->nis;
                $siswa->nama = $req->nama;
                $siswa->jk = $req->jk;
                $siswa->alamat = $req->alamat;
                $siswa->save();
                return response()->json($siswa, 200);
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
                "nis" => "required",
                "nama" => "required",
                "jk"  => "required",
                "alamat"  => "required",
            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $siswa = Siswa::find($id);
                if ($siswa == null)
                    throw new Error("Data Siswa tidak ditemukan");

                $siswa->nis = $req->nis;
                $siswa->nama = $req->nama;
                $siswa->jk = $req->jk;
                $siswa->alamat = $req->alamat;
                $siswa->save();
                return response()->json($siswa, 200);
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
            $siswa = Siswa::find($id);
            if ($siswa == null) {
                throw new Error("Data Siswa  tidak ditemukan ! ");
            }
            $siswa->delete();
            return response()->json(true, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["messsage"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }
}
