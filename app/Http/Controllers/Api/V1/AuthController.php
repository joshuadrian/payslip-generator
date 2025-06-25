<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\AuthException;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function authenticate(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);

            $user = User::where('username', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw new AuthException;
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return $this->success(['token' => $token]);
        } catch (ValidationException $e) {
            return $this->error('Unprocessable Entity', $e->errors(), 422);
        } catch (AuthException $e) {
            return $this->error($e->getMessage(), [], $e->getCode());
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->error('Internal server error', [], 500);
        }
    }
}
