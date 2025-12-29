<?php

namespace Modules\Comanda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comanda extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'table_id',
        'reabierto',
        'mesero',
        'comensales',
        'category',
        'priority',
        'status',
        'total'
    ];

    // Relaciones
    public function items()
    {
        return $this->hasMany(ComandaItem::class);
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('status', 1);
    }

    public function scopePorMesa($query, int $tableId)
    {
        return $query->where('table_id', $tableId);
    }

    public function scopePorEstado($query, string $status)
    {
        return $query->where('status', $status);
    }

    // Métodos
    public function recalcularTotal(): float
    {
        $total = $this->items->sum(fn ($item) => $item->total);

        $this->update(['total' => $total]);

        return $total;
    }

    public function cambiarEstado(string $status): void
    {
        $this->update(['status' => $status]);
    }

    public function puedeCerrarse(): bool
    {
        return $this->items()->count() > 0;
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

    public function getPriorityLabelAttribute()
    {
        $labels = [
            1 => 'Normal',
            2 => 'High'
        ];

        return $labels[$this->priority] ?? $this->priority;
    }

    public function getCategoryLabelAttribute()
    {
        $labels = [
            1 => 'Cocina',
            2 => 'Bar',
            3 => 'Postres',
        ];

        return $labels[$this->category] ?? $this->category;
    }
}
