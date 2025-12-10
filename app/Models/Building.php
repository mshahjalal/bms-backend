<?php

namespace App\Models;

use App\Traits\TenantIdTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasUuids, TenantIdTrait;

    protected $fillable = [
        'tenant_id',
        'name',
        'address',
    ];

    public function flats()
    {
        return $this->hasMany(Flat::class);
    }
}
