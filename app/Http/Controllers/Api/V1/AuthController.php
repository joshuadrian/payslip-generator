<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\AuthException;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

#[Group('Authentication')]
class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Get authorization token
     * @unauthenticated
     */
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

            /**
             * Success
             *
             * @body array{
             *      status:'success',
             *      message: Authenticated,
             *      data: array{
             *          token: string
             *      }
             *  }
             */
            return $this->success('Authenticated', ['token' => $token], 200);
        } catch (ValidationException $e) {
            return $this->error('Unprocessable Entity', $e->errors(), 422);
        } catch (AuthException $e) {
            return $this->error($e->getMessage(), [], 401);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->error('Internal server error', [], 500);
        }
    }
}
