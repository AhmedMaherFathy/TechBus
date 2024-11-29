<?php

namespace Modules\Admin\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'custom_id' => $this->custom_id,
            'first_name' => $this->first_name,
            'last_name' =>$this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
