<?php

namespace App\Services;

use App\Models\Reimbursement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReimbursementService
{
    public function store(Request $request)
    {
        $date = now()->startOfDay();

        $reim = Reimbursement::create([
            'user_id' => Auth::id(),
            'date' => $date,
            'amount' => $request->amount,
            'description' => $request->description ?? null,
        ])->refresh();

        return $reim;
    }
}
