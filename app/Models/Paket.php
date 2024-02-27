<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paket extends Model
{
    use HasFactory;
    protected $fillable = [
        "kode" ,
        "alokasiwaktu" ,
        "bentukpenugasan" ,
        "judultugas" ,
        "jurusan_id" ,
        "tahunajaran_id" ,
        "aksesorinternal" ,
        "aksesoreksternal" ,
    ];


    public function kompetensis(): HasMany
    {
        return $this->hasMany(Kompetensi::class);
    }
}
