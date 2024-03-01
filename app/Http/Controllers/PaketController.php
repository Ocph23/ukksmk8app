<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\Kompetensi;
use App\Models\Paket;
use Illuminate\Http\Request;
use Error;
use Illuminate\Database\Eloquent\Collection;
use PDOException;
use Validator;

class PaketController extends Controller
{

    private $fieldValidator = [
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
            $Paket->eksternal;
            $Paket->eksternal;
            $Paket->internal;
            if ($Paket == null) {
                throw new Error("Data Paket  tidak ditemukan ! ");
            }
            return response()->json($Paket, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    public function bytahunajaran($id)
    {
        try {
            $data = Paket::where("tahunajaran_id", $id)->get();
            foreach ($data as $key => $value) {
                $value->kompetensi;
                $value->jurusan;
            }

            return response()->json($data, 200);
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
                $Paket = new Paket();
                $this->setFieldData($Paket, $req);
                $Paket->save();
                $items = [];
                foreach ($req->kompetensis as $row) {
                    $komp = new Kompetensi($row);
                    $komp->paket_id = $Paket->id;
                    $komp->save();
                }

                $model = Paket::find($Paket->id);
                $model->kompetensis;
                return response()->json($model, 200);
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
                var_dump($validator->errors());
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $Paket = Paket::find($id);
                if ($Paket == null)
                    throw new Error("Data Paket tidak ditemukan");
                $Paket->fill($req->all());
                $Paket->save();
                $kompetensis = $req->kompetensis;
                foreach ($kompetensis as $key=> $row) {
                    $komp = null;
                    if ($row["id"]) {
                        $komp = Kompetensi::find($row["id"]);
                        $komp->fill($row);
                    } else {
                        $komp = new Kompetensi($row);
                    }
                    $komp->save();  
                    $kompetensis[$key]['id']=$komp->id;
                }


                $dataInDatabase = Kompetensi::where('paket_id', $Paket->id)->get();
                $dataInDatabaseArr = $dataInDatabase->all();

                foreach ($dataInDatabaseArr as $key => $value) {
                    $data = collect($kompetensis)->where('id', $value->id)->first();
                    if (!$data) {
                        $value->delete();
                    }
                }

                return response()->json($Paket, 200);
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
            $Paket = Paket::find($id);
            if ($Paket == null) {
                throw new Error("Data Paket  tidak ditemukan ! ");
            }
            $Paket->delete();
            return response()->json(true, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }


    private function setFieldData($Paket, $req)
    {
        $Paket->kode = $req->kode;
        $Paket->alokasiwaktu = $req->alokasiwaktu;
        $Paket->bentukpenugasan = $req->bentukpenugasan;
        $Paket->judultugas = $req->judultugas;
        $Paket->jurusan_id = $req->jurusan_id;
        $Paket->tahunajaran_id = $req->tahunajaran_id;
        $Paket->aksesorinternal = $req->aksesorinternal;
        $Paket->aksesoreksternal = $req->aksesoreksternal;
    }
}
