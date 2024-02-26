<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Error;
use PDOException;
use Validator;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $TahunAjaran = TahunAjaran::all();
        return response()->json($TahunAjaran, 200);
    }


    public function byid($id)
    {
        try {
            $TahunAjaran = TahunAjaran::find($id);
            if ($TahunAjaran == null) {
                throw new Error("Data TahunAjaran  tidak ditemukan ! ");
            }
            return response()->json($TahunAjaran, 200);
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
                "tahun" => "required",
                "deskripsi" => "required",
            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $TahunAjaran = new TahunAjaran();
                $TahunAjaran->tahun = $req->tahun;
                $TahunAjaran->deskripsi = $req->deskripsi;
                $TahunAjaran->save();
                return response()->json($TahunAjaran, 200);
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
                "tahun" => "required",
                "deskripsi" => "required",

            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $TahunAjaran = TahunAjaran::find($id);
                if ($TahunAjaran == null)
                    throw new Error("Data TahunAjaran tidak ditemukan");

                    $TahunAjaran->tahun = $req->tahun;
                    $TahunAjaran->deskripsi = $req->deskripsi;
                $TahunAjaran->save();
                return response()->json($TahunAjaran, 200);
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
            $TahunAjaran = TahunAjaran::find($id);
            if ($TahunAjaran == null) {
                throw new Error("Data TahunAjaran  tidak ditemukan ! ");
            }
            $TahunAjaran->delete();
            return response()->json(true, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["messsage"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }
}
