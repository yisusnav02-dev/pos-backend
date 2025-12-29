<?php

namespace Modules\Comanda\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComandaItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'orderNumber' => $this->order,
            'name' => $this->whenLoaded('product', function () {
                 return $this->product->name;
            }),
            'quantity' => $this->cantidad,
            'price' => (float) $this->subtotal,
            'status' => $this->status_label,
            'notas' => $this->modificadores
        ];
    }
}
