<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aksesor extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'nama',
        'jk',
        'instansi',
        'jenis',
        'catatan'
    ];
}
