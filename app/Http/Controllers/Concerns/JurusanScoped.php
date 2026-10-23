<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait JurusanScoped
{
    protected function userJurusanId(): ?int
    {
        $user = auth()->user();
        return $user && $user->role === 'jurusan' ? (int) $user->jurusan_id : null;
    }

    protected function scopeJurusan(Builder $query, string $column = 'jurusan_id'): Builder
    {
        if ($jurusanId = $this->userJurusanId()) {
            $query->where($column, $jurusanId);
        }

        return $query;
    }

    protected function ensureJurusan(int|string|null $jurusanId): void
    {
        $ownedJurusanId = $this->userJurusanId();
        if ($ownedJurusanId && (int) $jurusanId !== $ownedJurusanId) {
            abort(403, 'Anda tidak memiliki akses ke jurusan ini.');
        }
    }
}
