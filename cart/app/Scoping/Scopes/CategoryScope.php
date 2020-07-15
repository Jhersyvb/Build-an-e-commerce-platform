<?php

namespace App\Scoping\Scopes;

use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class CategoryScope implements Scope
{
    public function apply(Builder $builder, $value)
    {
        if ($value === null) {
            return $builder;
        }

        return $builder->whereHas('categories', function ($builder) use ($value) {
            $builder->where('slug', $value);
        });
    }
}
