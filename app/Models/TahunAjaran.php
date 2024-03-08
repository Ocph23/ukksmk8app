<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'tahun',
        'aktif',
        'deskripsi',
    ];

    protected $appends = [
        'nama',
    ];
    
    protected $casts = ['aktif' => 'boolean'];

    public function getNamaAttribute()
    {
        $nama = $this->tahun+1;
        return "{$this->tahun}/{$nama}";
    }

}
