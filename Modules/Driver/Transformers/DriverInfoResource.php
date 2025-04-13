<?php

namespace Modules\Driver\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isNull;

class DriverInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $plate_number = $plate_string = null;
        if (!is_null($this->bus->plate_number)){
            $plate = explode("-",$this->bus->plate_number);
            $plate_number = $plate[0] ?? null;
            $plate_string = $plate[1] ?? null;
        }
        return [
            "fullName" => $this->full_name,
            "startTime" => $this->formattedStartTime,
            "endTime" => $this->formattedEndTime,
            "workingDays" => $this->days,
            "busInfo" =>[
                    "plateNumbers" => $plate_number,
                    "plateString" => $plate_string,
                    "routeNumber" => $this->bus?->route?->number,
            ]
        ];
    }
}
