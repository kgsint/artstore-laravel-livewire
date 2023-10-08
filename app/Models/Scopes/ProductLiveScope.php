<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ProductLiveScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if(url()->current() !== route('filament.admin.resources.products.index')) {
            $builder->whereNotNull('live_at');
        }
    }
}
