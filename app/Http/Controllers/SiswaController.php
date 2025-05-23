<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\DetailPenilaian;
use App\Models\Penilaian;
use App\Models\Sertifikat;
use App\Models\Siswa;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Error;
use Illuminate\Support\Facades\Date;
use PDOException;
use Validator;

class SiswaController extends Controller
{

    private $fieldValidate =  [
        "nis" => "required",
        "nama" => "required",
        "jk"  => "required",
        'tempatlahir' => "required",
        'tanggallahir' => "required",
        "alamat"  => "required",
        "jurusan_id"  => "required",
        "tahunajaran_id"  => "required",
        "paket_id"  => "required",
    ];
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
            $siswa->jurusan;
            $siswa->sertifikat;

            $siswa->paket->eksternal;
            foreach ($siswa->penilaian as $key => $value) {
                # code...
                $value->kompetensi;
            }
            return response()->json($siswa, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
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
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }


    public function bytahunajaran($id)
    {
        try {
            $data = Siswa::where("tahunajaran_id", $id)->get();
            foreach ($data as $key => $value) {
                $value->tahunajaran;
                $value->jurusan;
                $value->paket;
                $value->penilaian;
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
            $validator = Validator::make($req->all(), $this->fieldValidate);
            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $siswa = new Siswa($req->all());
                // $tgl = new DateTime($siswa->tanggallahir);
                // $siswa->tanggallahir = $tgl->format('Y-m-d');
                $siswa->save();
                return response()->json($siswa, 200);
            }
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }



    public function updateSertifikat(Request $req)
    {

        try {
            $validator = Validator::make(
                $req->all(),
                [
                    'instansi' => "required",
                    'ketuapenguji' => "required",
                    'nomorseri' => "required",
                    'nomor' => "required",
                    'tanggalpenetapan' => "required",
                    'siswa_id' => "required"
                ]
            );
            if ($validator->fails()) {
                $errors = $validator->errors();
                throw new Error("Periksa Kembali Data Anda");
            } else {
                if (!$req->id) {
                    $sertifikat = new Sertifikat($req->all());
                    $sertifikat->tanggalpenetapan = Carbon::createFromDate($sertifikat->tanggalpenetapan);
                    $sertifikat->save();
                } else {
                    $sertifikat = Sertifikat::find($req->id);
                    $sertifikat->fill($req->all());
                    $sertifikat->tanggalpenetapan = Carbon::createFromDate($sertifikat->tanggalpenetapan);
                    $sertifikat->tanggalcetak = Carbon::createFromDate($sertifikat->tanggalcetak);
                    $sertifikat->tanggalambil = Carbon::createFromDate($sertifikat->tanggalambil);
                    $sertifikat->save();
                }

                return response()->json($sertifikat, 200);
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
            $validator = Validator::make($req->all(), $this->fieldValidate);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                $siswa = Siswa::find($id);
                if ($siswa == null)
                    throw new Error("Data Siswa tidak ditemukan");
                $siswa->fill($req->all());
                // $tgl = new DateTime($siswa->tanggallahir);
                // $siswa->tanggallahir = $tgl->format('Y-m-d');
                $siswa->save();
                if ($req->penilaian) {
                    foreach ($req->penilaian as $key => $value) {
                        if (!$value['id']) {
                            $row = new DetailPenilaian($value);
                            $row->save();
                            $value['id'] = $row->id;
                        } else {
                            $row = DetailPenilaian::find($value['id']);
                            if (isset($value['kompetensi'])) {
                                if ($value['kompetensi']['paket_id'] != $siswa->paket_id) {
                                    $row->delete();
                                } else {
                                    $row->fill($value);
                                    $row->save();
                                }
                            }
                        }
                    }

                    $siswa->penilaian = $siswa->penilaian()->get();
                }
                return response()->json($siswa, 200);
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
            $siswa = Siswa::find($id);
            if ($siswa == null) {
                throw new Error("Data Siswa  tidak ditemukan ! ");
            }
            $siswa->delete();
            return response()->json(true, 200);
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }
}
