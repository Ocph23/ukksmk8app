<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\DetailPenilaian;
use App\Models\Paket;
use App\Models\Penilaian;
use DateTime;
use Illuminate\Http\Request;
use Error;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use PDOException;
use Validator;

class PenilaianController extends Controller
{
    private $fieldValidator = [
        "mulai" => "required",
        "selesai" => "required",
        "siswa_id"  => "required",
        "paket_id"  => "required",
    ];

    public function index()
    {
        $Penilaian = Penilaian::all();
        return response()->json($Penilaian, 200);
    }

    public function byid($id)
    {
        try {
            $Penilaian = Penilaian::find($id);
            $Penilaian->siswa;
            $Penilaian->paket;
            $Penilaian->paket->kompetensis;
            $Penilaian->detail;
            if ($Penilaian == null) {
                throw new Error("Data Penilaian  tidak ditemukan ! ");
            }
            return response()->json($Penilaian, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    public function bysiswaid($id)
    {
        try {
            $Penilaian = Penilaian::where('siswa_id','=',$id)->first();
            if ($Penilaian == null) {
               $Penilaian = new Penilaian();
               $Penilaian->siswa_id=$id;
               $Penilaian->mulai=new DateTime();
               $Penilaian->selesai=new DateTime();
               $Penilaian->save();
            }


            $Penilaian->siswa;
            $Penilaian->paket;
            $Penilaian->paket->kompetensis;
            $Penilaian->detail;
            return response()->json($Penilaian, 200);
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
                var_dump($validator->errors());
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $Penilaian = new Penilaian();
                $this->setRequestToModel($Penilaian, $req);
                $paket = Paket::find($Penilaian->paket_id);
                $paket->kompetensis;
                $Penilaian->save();

                $details = new Collection();
                foreach ($paket->kompetensis as $row) {
                    $details->push([
                        'penilaian_id' => $Penilaian->id,
                        'nilai'      => 0,
                        'kompeten'   => false,
                        'kompetensi_id' => $row->id
                    ]);
                }
                DetailPenilaian::insert($details->toArray());
                return response()->json($Penilaian, 200);
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
                $Penilaian = Penilaian::find($id);
                if ($Penilaian == null)
                    throw new Error("Data Penilaian tidak ditemukan");

                $this->setRequestToModel($Penilaian, $req);
                $Penilaian->save();
                $details = new Collection();
                foreach ($req->detail as $row) {
                    $data = DetailPenilaian::find($row['id']);
                    $data->nilai=$row['nilai'];
                    $data->kompeten=$row['kompeten'];
                    $data->save();
                }
                return response()->json($Penilaian, 200);
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
            $Penilaian = Penilaian::find($id);
            if ($Penilaian == null) {
                throw new Error("Data Penilaian  tidak ditemukan ! ");
            }
            $Penilaian->delete();
            return response()->json(true, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }



    private function setRequestToModel($model, $req)
    {
        $model->mulai = $req->mulai;
        $model->selesai = $req->selesai;
        $model->siswa_id = $req->siswa_id;
        $model->paket_id = $req->paket_id;
    }
}
