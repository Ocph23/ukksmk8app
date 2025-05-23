<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nilai',
        'kompeten',
        'siswa_id',
        'kompetensi_id',
    ];

    public $timestamps = false;
    protected $casts = ['kompeten' => 'boolean', 'nilai'=>'double'];
    public function kompetensi():BelongsTo{
        return $this->BelongsTo(Kompetensi::class);
    }

}
