<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Reimbursement;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ReimbursementController extends Controller
{
    use ApiResponse;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $reim = $request->validate([
                'amount' => 'required|decimal:0,2',
                'description' => 'string',
            ]);

            $date = now()->startOfDay();

            $reim = Reimbursement::create([
                'user_id' => Auth::id(),
                'date' => $date,
                'amount' => $request->amount,
                'description' => $request->description ?? null,
            ])->refresh();

            /**
             * Success
             *
             * @body array{
             *      status:'success',
             *      message: 'Successfully submitted reimbursement request.',
             *      data: array<App\Models\Reimbursement>
             *  }
             */
            return $this->success('Successfully submitted reimbursement request.', $reim, 201);
        } catch (ValidationException $e) {
            return $this->error('Unprocessable entity.', $e->errors(), 422);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->error('Internal server error.', [], 500);
        }
    }
}
