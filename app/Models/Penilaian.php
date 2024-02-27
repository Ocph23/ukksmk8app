<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penilaian extends Model
{
    use HasFactory;
    protected $fillable = [
        'mulai',
        'selesai',
        'paket_id',
        'siswa_id',
    ];


    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    
    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class);
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPenilaian::class);
    }
}
