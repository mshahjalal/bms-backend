<?php

namespace App\Models;

use App\Traits\TenantIdTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasUuids, TenantIdTrait;

    protected $fillable = [
        'tenant_id',
        'building_id',
        'renter_id',
        'number',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
