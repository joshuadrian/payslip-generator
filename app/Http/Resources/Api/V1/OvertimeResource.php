<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "uid" => $this->uid,
            "user_id" => $this->user_id,
            "date" => $this->date,
            "duration_hours" => $this->duration_hours,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
