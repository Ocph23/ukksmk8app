<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\Paket;
use Illuminate\Http\Request;
use Error;
use PDOException;
use Validator;

class PaketController extends Controller
{

    private $fieldValidator=[
        "kode" => "required",
        "alokasiwaktu" => "required",
        "bentukpenugasan" => "required",
        "judultugas" => "required",
        "jurusan_id" => "required",
        "tahunajaran_id" => "required",
        "aksesorinternal" => "required",
        "aksesoreksternal" => "required",
    ];

    public function index()
    {
        $Paket = Paket::all();
        return response()->json($Paket, 200);
    }


    public function byid($id)
    {
        try {
            $Paket = Paket::find($id);
            $Paket->kompetensis;
            if ($Paket == null) {
                throw new Error("Data Paket  tidak ditemukan ! ");
            }
            return response()->json($Paket, 200);
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

            $validator = Validator::make($req->all(),$this->fieldValidator);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $Paket = new Paket();
                $Paket->kode = $req->kode;
                $Paket->alokasiwaktu = $req->alokasiwaktu;
                $Paket->bentukpenugasan = $req->bentukpenugasan;
                $Paket->judultugas = $req->judultugas;
                $Paket->jurusan_id = $req->jurusan_id;
                $Paket->tahunajaran_id = $req->tahunajaran_id;
                $Paket->aksesorinternal = $req->aksesorinternal;
                $Paket->aksesoreksternal = $req->aksesoreksternal;
                $Paket->save();
                return response()->json($Paket, 200);
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
            $validator = Validator::make($req->all(), $this->fieldValidator);
            if ($validator->fails()) {
                var_dump($validator->errors());
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $Paket = Paket::find($id);
                if ($Paket == null)
                    throw new Error("Data Paket tidak ditemukan");

                $Paket->kode = $req->kode;
                $Paket->alokasiwaktu = $req->alokasiwaktu;
                $Paket->bentukpenugasan = $req->bentukpenugasan;
                $Paket->judultugas = $req->judultugas;
                $Paket->jurusan_id = $req->jurusan_id;
                $Paket->tahunajaran_id = $req->tahunajaran_id;
                $Paket->aksesorinternal = $req->aksesorinternal;
                $Paket->aksesoreksternal = $req->aksesoreksternal;
                $Paket->save();
                return response()->json($Paket, 200);
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
            $Paket = Paket::find($id);
            if ($Paket == null) {
                throw new Error("Data Paket  tidak ditemukan ! ");
            }
            $Paket->delete();
            return response()->json(true, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["messsage"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }
}
