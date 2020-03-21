<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * Class PingController
 * @package App\Http\Controllers
 */
class PingController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $data = [];
        $data['status'] = 'success';
        $data['message'] = 'pong';

        return response()->json($data);
    }
}
