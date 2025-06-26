<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Dedoc\Scramble\Attributes\Group;

#[Group('User (Testing purpose only)')]
class UserController extends Controller
{
    use ApiResponse;

    /**
     * Get 5 users data
     * @unauthenticated
     */
    public function index()
    {
        return $this->success('Successfully fetched users data.', User::limit(5)->get(), 200);
    }
}
