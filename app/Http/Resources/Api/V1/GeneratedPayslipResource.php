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
                    'amount' => $this->base_salary ?? 0,
                ],
                'reimbursements' => [
                    'amount' => $this->total_reimbursement ?? 0
                ],
                'overtimes' => [
                    'total_hours' => $this->total_overtime_hours ?? 0,
                    'amount' => $this->overtime_bonus ?? 0
                ],
            ],
            'deductions' => [
                'absences' => [
                    'total_absences' => $this->total_absences ?? 0,
                    'amount' => $this->absence_deduction ?? 0
                ]
            ],
            'take_home_pay' => [
                'amount' => $this->take_home_pay ?? 0
            ]
        ];
    }
}
