<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Dedoc\Scramble\Attributes\Group;

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
        return $this->success('Fetched all users.', User::limit(5)->get(), 200);
    }
}
