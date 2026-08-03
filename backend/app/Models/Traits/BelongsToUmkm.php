<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToUmkm
{
    protected static function bootBelongsToUmkm(): void
    {
        static::addGlobalScope('umkm', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('umkm_id', auth()->user()->umkm_id);
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && empty($model->umkm_id)) {
                $model->umkm_id = auth()->user()->umkm_id;
            }
        });
    }
}
