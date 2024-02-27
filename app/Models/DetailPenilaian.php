<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nilai',
        'kompeten',
        'penilaian_id',
        'kompetensi_id',
    ];
}
