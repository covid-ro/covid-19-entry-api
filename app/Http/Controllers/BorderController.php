<?php

namespace App\Http\Controllers;

use App\BorderCheckpoint;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

/**
 * Class BorderController
 * @package App\Http\Controllers
 */
class BorderController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCheckpointList(Request $request)
    {
        $responseData = [];

        /** @var BorderCheckpoint[] $borderCheckpointList */
        $borderCheckpointList = BorderCheckpoint::all();

        $responseData['status'] = 'success';
        $responseData['message'] = 'Border Checkpoint List';
        $responseData['data'] = $borderCheckpointList;

        return response()->json($responseData);
    }
}
