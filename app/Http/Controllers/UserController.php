<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $responseData['status'] = 'success';
        $responseData['message'] = 'User created';

        return response()->json($responseData);
    }
}