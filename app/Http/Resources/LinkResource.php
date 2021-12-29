<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'user' => new UserResource($this->user),
            'products' => ProductResource::collection($this->products),
        ];
    }
}
