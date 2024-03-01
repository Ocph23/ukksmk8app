<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'nis',
        'nama',
        'jk',
        'alamat',
        'tempatlahir',
        'tanggallahir',
        'tahunajaran_id',
        'jurusan_id',
        'paket_id',
    ];

    
    public function paket():BelongsTo
    {
        return $this->belongsTo(Paket::class);
    }

    public function jurusan():BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function tahunajaran():BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(DetailPenilaian::class);
    }

    public function sertifikat(): HasOne
    {
        return $this->hasOne(Sertifikat::class,'siswa_id');
    }

}
