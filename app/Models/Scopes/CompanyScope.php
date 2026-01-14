<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (auth() && @auth()->user()->role === 'PARTNER') {
            $builder->where('company_id', auth()->user()->selected_company_id);
        }
    }
}
