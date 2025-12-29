<?php

namespace Modules\Products\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        // Cargar relaciones necesarias si no están cargadas
        $this->loadMissing('category', 'productWarehouse.warehouse');

        // Obtener stock total sumando todos los almacenes
        $stockTotal = $this->productWarehouse->sum('stock');
        $minStock = $this->productWarehouse->sum('min_stock');
        $maxStock = $this->productWarehouse->sum('max_stock');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => (float) $this->price,
            'description' => $this->description,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? $this->category->name : null;
            }),
            'available' => $this->availability,

            /* 
                'warehouses' => $this->productWarehouse->map(function ($pw) {
                    return [
                        // 'warehouse_id' => $pw->warehouse_id,
                        'warehouse_name' => $pw->warehouse->name ?? null,
                        'stock' => $pw->stock,
                        'min_stock' => $pw->min_stock,
                        'max_stock' => $pw->max_stock,
                    ];
                }),
                'unit' => $this->unit ?? null,
                'image' => $this->image,
                'image_url' => $this->image ? asset('storage/' . $this->image) : null,
             */
        ];
    }
}
