<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductWarehouse  extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'stock',
        'min_stock',
        'max_stock'
    ];

    protected $casts = [
        'product_id'   => 'integer',
        'warehouse_id' => 'integer',
        'stock'        => 'integer',
        'min_stock'    => 'integer',
        'max_stock'    => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeCritical($query)
    {
        return $query->whereColumn('stock', '<', 'min_stock');
    }

    public function scopeOverstock($query)
    {
        return $query->whereColumn('stock', '>', 'max_stock');
    }

    // Métodos útiles
    public function addStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }

    public function reduceStock(int $quantity): void
    {
        $this->decrement('stock', $quantity);
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    public function isCritical(): bool
    {
        return $this->stock < $this->min_stock;
    }
}
