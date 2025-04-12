<?php

namespace Modules\Driver\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // dd($this->bus);
        return [
            "fullName" => $this->full_name,
            "startTime" => $this->formattedStartTime,
            "endTime" => $this->formattedEndTime,
            "workingDays" => $this->days,
            "busInfo" =>[
                    "PlateNumber" => optional($this->bus)->plate_number,
                    "routeNumber" => optional(optional($this->bus)->route)->number,
            ]
        ];
    }
}
