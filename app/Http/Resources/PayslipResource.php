<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayslipResource extends JsonResource
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
            'user_id' => $this->user_id,
            'payroll_id' => $this->payroll_id,
            'base_salary' => $this->base_salary,
            'absence_deduction' => $this->absence_deduction,
            'overtime_bonus' => $this->overtime_bonus,
            'total_reimbursement' => $this->total_reimbursement,
            'take_home_pay' => $this->take_home_pay,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
