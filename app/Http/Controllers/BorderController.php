<?php

namespace App\Http\Controllers;

use App\BorderCheckpoint;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        /** @var string|null $statusFilter */
        $statusFilter = $request->get('status', null);

        /** @var Builder $borderCheckpoints */
        $borderCheckpoints = BorderCheckpoint::where(DB::raw('1'), 1);

        if (BorderCheckpoint::STATUS_ACTIVE === $statusFilter) {
            $borderCheckpoints->whereNull('deleted_at');
        } else if (BorderCheckpoint::STATUS_INACTIVE === $statusFilter) {
            $borderCheckpoints->whereNotNull('deleted_at');
        }

        /** @var BorderCheckpoint[] $borderCheckpointList */
        $borderCheckpointList = $borderCheckpoints->get();

        $responseData['status'] = 'success';
        $responseData['message'] = 'Border Checkpoint List';
        $responseData['data'] = $borderCheckpointList;

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    private function validateGetCheckpointRequest(Request $request)
    {
        if ($request->has('status')) {
            if (!in_array($request->get('status'), [BorderCheckpoint::STATUS_ACTIVE, BorderCheckpoint::STATUS_INACTIVE])) {
                throw new Exception('Invalid value for parameter: status');
            }
        }
    }
}
