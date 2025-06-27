<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneratedPayslipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'earnings' => [
                'base_salary' => [
                    'amount' => $this->base_salary,
                ],
                'reimbursements' => [
                    'amount' => $this->total_reimbursement
                ],
                'overtimes' => [
                    'total_hours' => $this->total_overtime_hours,
                    'amount' => $this->overtime_bonus
                ],
            ],
            'deductions' => [
                'absences' => [
                    'total_absences' => $this->total_absences,
                    'amount' => $this->absence_deduction
                ]
            ],
            'take_home_pay' => [
                'amount' => $this->take_home_pay
            ]
        ];
    }
}
