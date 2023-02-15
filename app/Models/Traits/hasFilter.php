<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait hasFilter
{
    public function scopeFilter(Builder $builder, $filter)
    {
        return $filter->filter($builder);
    }
}
