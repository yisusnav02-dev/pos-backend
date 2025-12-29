<?php

namespace Modules\Comanda\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComandaResource extends JsonResource
{
    public function toArray($request)
    {
        // Cargar relaciones necesarias si no están cargadas
        $this->loadMissing('items');

        $itemsAgrupados = $this->items
            ->groupBy('order')
            ->map(function ($items) {
                return ComandaItemResource::collection($items)->values();
            })
            ->values();

        return [
            'id' => $this->id,
            'numberComanda' => explode("_", $this->number)[1],
            'tableId' => $this->table_id,
            'reabierto' => $this->reabierto,
            'comensales' => $this->comensales,
            'mesero' => $this->mesero,
            'priority' => $this->priority_label,
            'category' => $this->category_label, 
            'status' => $this->status_label,
            'total' => (float) $this->total,
            'items' => $itemsAgrupados
        ];
    }
}
