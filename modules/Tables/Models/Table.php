<?php

namespace Modules\Tables\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'capacity',
        'status',
        'type',
        'description',
        'location',
        'min_consumption',
        'sort_order',
        'coordinates',
        'active'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'min_consumption' => 'decimal:2',
        'sort_order' => 'integer',
        'coordinates' => 'array'
    ];

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 2);
    }

    public function scopeReserved($query)
    {
        return $query->where('status', 3);
    }

    public function scopeUnderMaintenance($query)
    {
        return $query->where('status', 4);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    public function scopeByCapacity($query, $minCapacity, $maxCapacity = null)
    {
        $query->where('capacity', '>=', $minCapacity);
        
        if ($maxCapacity) {
            $query->where('capacity', '<=', $maxCapacity);
        }
        
        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Métodos
    public function isAvailable()
    {
        return $this->status === 1;
    }

    public function isOccupied()
    {
        return $this->status === 2;
    }

    public function isReserved()
    {
        return $this->status === 3;
    }

    public function isUnderMaintenance()
    {
        return $this->status === 4;
    }

    public function markAsOccupied()
    {
        $this->update(['status' => 2]);
    }

    public function markAsAvailable()
    {
        $this->update(['status' => 1]);
    }

    public function markAsReserved()
    {
        $this->update(['status' => 3]);
    }

    public function markAsMaintenance()
    {
        $this->update(['status' => 4]);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            1 => 'Disponible',
            2 => 'Ocupada',
            3 => 'Reservada',
            4 => 'Mantenimiento'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            1 => 'Interior',
            2 => 'Exterior',
            3 => 'Terraza',
            4 => 'VIP'
        ];

        return $labels[$this->type] ?? $this->type;
    }
}