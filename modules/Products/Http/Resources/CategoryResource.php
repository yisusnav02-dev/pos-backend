<?php

namespace Modules\Products\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            
            /*
                'products_count' => $this->whenCounted('products', $this->products_count),
            */
        ];
    }
}