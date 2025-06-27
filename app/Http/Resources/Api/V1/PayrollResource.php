<?php

namespace App\Http\Resources\Api\V1;

use App\Models\AttendancePeriod;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'attendance_period_id' => $this->attendance_period_id,
            'is_locked' => $this->is_locked,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'attendance_period' => AttendancePeriodResource::make($this->whenLoaded('attendancePeriod')),
        ];
    }
}
