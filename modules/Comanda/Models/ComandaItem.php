<?php

namespace Modules\Comanda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Products\Models\Product;

class ComandaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'comanda_id',
        'product_id',
        'order',
        'cantidad',
        'subtotal',
        'total',
        'status',
        'modificadores'
    ];

    // Relaciones
    public function comanda()
    {
        return $this->belongsTo(Comanda::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopePorProducto($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    // Métodos
    public function calcularTotales(): void
    {
        $subtotal = $this->product
            ? $this->product->price * $this->cantidad
            : $this->subtotal;

        $this->update([
            'subtotal' => $subtotal,
            'total' => $subtotal
        ]);
    }

    public function cambiarEstado(string $status): void
    {
        $this->update(['status' => $status]);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            1 => 'Pendiente',
            2 => 'Preparando',
            3 => 'Listo',
            4 => 'Entregado'
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
