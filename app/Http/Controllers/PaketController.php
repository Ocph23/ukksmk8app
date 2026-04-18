<?php

namespace App\Http\Controllers;

use App\Http\DatabaseHelper;
use App\Models\Aksesor;
use App\Models\Jurusan;
use App\Models\Kompetensi;
use App\Models\Paket;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Error;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Inertia;
use PDOException;
use Validator;

class PaketController extends Controller
{

    private $fieldValidator = [
        "kode" => "required",
        "basisnilai" => "required",
        "alokasiwaktu" => "required",
        "bentukpenugasan" => "required",
        "judultugas" => "required",
        "jurusan_id" => "required",
        "aksesorinternal" => "required",
        "aksesoreksternal" => "required",
    ];

    public function index()
    {
        $Paket = Paket::with('jurusan')->get();
        return response()->json($Paket, 200);
    }
    public function indexInertia(Request $request)
    {
        $tahunajaran_id = $request->query('tahunajaran_id');
        if (!$tahunajaran_id) {
            //Get Tahunan Ajaran Aktif
            $tahunajaran_id = TahunAjaran::where('aktif', true)->first()->id;
        }
        $Paket = Paket::where('tahunajaran_id', $tahunajaran_id)->with('jurusan', 'internal', 'eksternal')->get();
        $jurusan = Jurusan::all();
        $tahunajaran = TahunAjaran::all();
        $assesors = Aksesor::all();
        return Inertia::render('Paket/Index', [
            'pakets' => $Paket,
            'jurusan' => $jurusan,
            'tahunajaran' => $tahunajaran,
            'assesors' => $assesors,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }


    public function byid($id)
    {
        try {
            $Paket = Paket::find($id);
            $Paket->kompetensis;
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
                $activeTa = TahunAjaran::where('aktif', true)->first();
                $Paket = new Paket($req->all());
                $Paket->tahunajaran_id = $activeTa->id;
                $Paket->save();
                $items = [];
                // foreach ($req->kompetensis as $row) {
                //     $komp = new Kompetensi($row);
                //     $komp->paket_id = $Paket->id;
                //     $komp->save();
                // }

                // $model = Paket::find($Paket->id);
                // $model->kompetensis;
                return response()->json($Paket, 200);
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
                return response()->json($Paket, 200);
            }
        } catch (PDOException $ex) {
            return response()->json(DatabaseHelper::GetErrorPDOError($ex), 400);
        } catch (\Throwable $th) {
            $errorMessage["message"] = $th->getMessage();
            return response()->json($errorMessage, 400);
        }
    }

    public function putDetail($id, Request $req)
    {
        try {
            $validator = Validator::make($req->all(), ['kompetensis' => 'required']);
            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            } else {
                if ($req->kompetensis != null) {
                    $kompetensis = $req->kompetensis;
                    foreach ($kompetensis as $key => $row) {
                        $komp = null;
                        if ($row["id"]) {
                            $komp = Kompetensi::find($row["id"]);
                            $komp->fill($row);
                        } else {
                            $komp = new Kompetensi($row);
                        }
                        $komp->save();
                        $kompetensis[$key]['id'] = $komp->id;
                    }

                    $dataInDatabase = Kompetensi::where('paket_id', $id)->get();
                    $dataInDatabaseArr = $dataInDatabase->all();

                    foreach ($dataInDatabaseArr as $key => $value) {
                        $data = collect($kompetensis)->where('id', $value->id)->first();
                        if (!$data) {
                            $value->delete();
                        }
                    }
                    return response()->json($kompetensis, 200);
                }

                $errorMessage["message"] = "Maaf  terjadi kesalahan, silahkan ulangi";
                return response()->json($errorMessage, 400);
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

    /**
     * Inertia methods
     */

    public function store(Request $request)
    {
        $taActive = TahunAjaran::where('aktif', true)->first();
        if (!$taActive) {
            return redirect()->route('paket')->with('error', 'Tidak ada tahun ajaran aktif');
        }

        $validated = $request->validate($this->fieldValidator);

        $Paket = new Paket($validated);
        $Paket->tahunajaran_id = $taActive->id;
        $Paket->save();

        return redirect()->route('paket')->with('success', 'Data paket berhasil ditambahkan');
    }

    public function update($id, Request $request)
    {
        $validated = $request->validate($this->fieldValidator);

        $Paket = Paket::findOrFail($id);
        $Paket->fill($validated);
        $Paket->save();

        return redirect()->route('paket')->with('success', 'Data paket berhasil diperbarui');
    }

    public function destroy($id)
    {
        $Paket = Paket::findOrFail($id);
        $Paket->delete();

        return redirect()->route('paket')->with('success', 'Data paket berhasil dihapus');
    }

    public function updateDetailInertia($id, Request $request)
    {
        $validated = $request->validate(['kompetensis' => 'required']);

        if ($request->kompetensis != null) {
            $kompetensis = $request->kompetensis;
            foreach ($kompetensis as $key => $row) {
                $komp = null;
                if ($row["id"]) {
                    $komp = Kompetensi::find($row["id"]);
                    $komp->fill($row);
                } else {
                    $komp = new Kompetensi($row);
                }
                $komp->save();
                $kompetensis[$key]['id'] = $komp->id;
            }

            $dataInDatabase = Kompetensi::where('paket_id', $id)->get();
            $dataInDatabaseArr = $dataInDatabase->all();

            foreach ($dataInDatabaseArr as $key => $value) {
                $data = collect($kompetensis)->where('id', $value->id)->first();
                if (!$data) {
                    $value->delete();
                }
            }
        }

        return redirect()->back()->with('success', 'Detail paket berhasil diperbarui');
    }
}
