<?php

namespace Modules\Tables\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status_label,
            'mesa' => explode(" ", $this->name)[1],
            'capacity' => $this->capacity,
            'orders' => []
            
            /* 
                'min_consumption' => $this->min_consumption ? (float) $this->min_consumption : null,
                'is_available' => $this->isAvailable(),
                'is_occupied' => $this->isOccupied(),
                'is_reserved' => $this->isReserved(),
                'is_under_maintenance' => $this->isUnderMaintenance(),
            */
        ];
    }
}