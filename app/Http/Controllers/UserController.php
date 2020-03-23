<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request)
    {
        $responseData = [];

        /** @var User $user */
        $user = Auth::user();

        /**
         * Check if the user was identified
         */
        if (empty($user->id)) {
            return response()->json([
                'status' => 'error',
                'reason' => 'Unauthorized'
            ], 401);
        }

        $responseData['status'] = 'success';
        $responseData['message'] = 'User created';

        return response()->json($responseData);
    }
}