<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paket extends Model
{
    use HasFactory;
    protected $fillable = [
        "id" ,
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

    public function jurusan():BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function eksternal():BelongsTo
    {
        return $this->belongsTo(Aksesor::class, "aksesoreksternal");
    }
    public function internal():BelongsTo
    {
        return $this->belongsTo(Aksesor::class, "aksesorinternal");
    }
}
