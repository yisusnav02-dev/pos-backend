<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state',
        'country',
        'phone',
        'is_default',
        'status',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'status'     => 'boolean',
    ];

    // Relaciones
    public function productWharehouse()
    {
        return $this->hasMany(ProductWarehouse::class);
    }


    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Métodos
    public function availableProducts()
    {
        return $this->productWarehouse()->available();
    }

    public function isDefault(): bool
    {
        return $this->is_default;
    }
}
