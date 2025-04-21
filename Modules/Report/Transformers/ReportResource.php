<?php

namespace Modules\Report\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'note' => $this->note,
            'description' => $this->description,
            'createdAt' => $this->created_at,
            'user' => [
                'id' => $this->user->custom_id,
                'fullName' => $this->user->first_name." ".$this->user->last_name,
                'email' => $this->user->email,
            ],
        ];
    }
}
