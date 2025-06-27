<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Group;
use App\Http\Resources\Api\V1\UserResource;

#[Group('Users')]
class UserController extends Controller
{
    use ApiResponse;

    /**
     * Get 5 users data (for testing)
     * @unauthenticated
     */
    public function index()
    {
        return UserResource::collection(User::limit(5)->get())->additional([
            'status' => 'success',
            'message' => 'Fetched all users.'
        ])->response()->setStatusCode(200);
    }
}
