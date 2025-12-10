<?php

namespace App\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait TenantIdTrait
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->tenant_id) {
                $model->tenant_id = Auth::user()->tenant_id;
            }
        });
    }
}
