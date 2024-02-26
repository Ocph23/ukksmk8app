<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'tahun',
        'deskripsi',
    ];

    protected $appends = [
        'nama',
    ];
    

    public function getNamaAttribute()
    {
        $nama = $this->tahun+1;
        return "{$this->tahun}|{$nama}";
    }

}
