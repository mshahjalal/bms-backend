<?php

namespace App\Models;

use App\Traits\TenantIdTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BillCategory extends Model
{
    use HasUuids, TenantIdTrait;

    protected $fillable = [
        'tenant_id',
        'name',
    ];

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
