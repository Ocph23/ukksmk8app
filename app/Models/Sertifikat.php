<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertifikat extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'instansi',
        'ketuapenguji',
        'nomorseri',
        'nomor',
        'tanggalpenetapan',
        'tanggalcetak',
        'tanggalambil',
        'diambiloleh',
        'siswa_id',
    ];

    public $timestamps = false;

    
}
