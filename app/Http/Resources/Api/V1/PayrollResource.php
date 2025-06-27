<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\PayslipResource;
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
            'is_ready' => $this->is_ready,
            'message' => $this->message,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'attendance_period' => AttendancePeriodResource::make($this->whenLoaded('attendancePeriod')),
            'payslips' => PayslipResource::collection($this->whenLoaded('payslips')),
        ];
    }
}
