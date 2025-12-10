<?php

namespace App\Models;

use App\Traits\TenantIdTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasUuids, TenantIdTrait;

    protected $fillable = [
        'tenant_id',
        'flat_id',
        'category_id',
        'amount',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function category()
    {
        return $this->belongsTo(BillCategory::class);
    }
}
