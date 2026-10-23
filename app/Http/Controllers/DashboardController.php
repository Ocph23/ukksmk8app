<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Paket;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use Concerns\JurusanScoped;

    public function stats(Request $request)
    {
        $tahunajaranId = $request->query('tahunajaran_id');

        $paketQuery = Paket::query();
        $siswaQuery = Siswa::query();

        if ($tahunajaranId) {
            $paketQuery->where('tahunajaran_id', $tahunajaranId);
            $siswaQuery->where('tahunajaran_id', $tahunajaranId);
        }
        $this->scopeJurusan($paketQuery);
        $this->scopeJurusan($siswaQuery);

        $pakets = $paketQuery
            ->get(['jurusan_id', 'aksesorinternal', 'aksesoreksternal']);

        $jurusanCount = $tahunajaranId
            ? $pakets->pluck('jurusan_id')->filter()->unique()->count()
            : Jurusan::count();

        $aksesorCount = $pakets
            ->flatMap(fn ($paket) => [$paket->aksesorinternal, $paket->aksesoreksternal])
            ->filter()
            ->unique()
            ->count();

        return response()->json([
            'siswa' => $siswaQuery->count(),
            'jurusan' => $jurusanCount,
            'paket' => $pakets->count(),
            'aksesor' => $aksesorCount,
        ]);
    }
}
