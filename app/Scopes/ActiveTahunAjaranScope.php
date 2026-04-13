<?php

namespace App\Scopes;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;

class ActiveTahunAjaranScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $activeTa = TahunAjaran::where('aktif', true)->first();
        if ($activeTa) {
            $builder->where('tahunajaran_id', $activeTa->id);
        }
    }
}
