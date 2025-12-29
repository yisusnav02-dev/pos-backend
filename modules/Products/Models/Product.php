<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'cost',
        'sku',
        'barcode',
        'category_id',
        'id_unit',
        'image',
        'availability',
        'status',
        'preparation_time'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost'  => 'decimal:2',
    ];


    // Relaciones
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productWarehouse()
    {
        return $this->hasMany(ProductWarehouse::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse')
                    ->withPivot('stock', 'min_stock', 'max_stock')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('availability', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('barcode', 'like', "%{$search}%");
        });
    }

    // Métodos
    public function isActive(): bool
    {
        return (bool) $this->status;
    }

    public function isAvailable(): bool
    {
        return $this->availability && $this->isActive();
    }

    public function getProfitMarginAttribute(): float
    {
        if (!$this->cost || $this->cost == 0) return 0;
        return round((($this->price - $this->cost) / $this->cost) * 100, 2);
    }
}
