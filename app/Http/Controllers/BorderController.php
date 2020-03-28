<?php

namespace App\Http\Controllers;

use App\BorderCheckpoint;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        try {
            $this->validateGetCheckpointRequest($request);
        } catch (\Exception $validationException) {
            $responseData['status'] = 'error';
            $responseData['message'] = $validationException->getMessage();

            return response()->json($responseData, 400);
        }

        /** @var string|null $statusFilter */
        $statusFilter = $request->get('status', null);

        if (empty($statusFilter)) { // all results
            $borderCheckpoints = BorderCheckpoint::withTrashed();
        } else {
            if (BorderCheckpoint::STATUS_ACTIVE === $statusFilter) {
                /** @var Builder $borderCheckpoints */
                $borderCheckpoints = BorderCheckpoint::whereNull('deleted_at');
            } else {
                $borderCheckpoints = BorderCheckpoint::withTrashed()->whereNotNull('deleted_at');
            }
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
        if ($request->has('status')) { // optional
            if (!in_array($request->get('status'), [BorderCheckpoint::STATUS_ACTIVE, BorderCheckpoint::STATUS_INACTIVE])) {
                throw new Exception('Invalid value for parameter: status');
            }
        }
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    private function validateUpdateCheckpointRequest(Request $request)
    {
        if ($request->has('name')) {
            if (
                empty($request->get('name')) ||
                strlen($request->get('name')) > 255
            ) {
                throw new Exception('Invalid value for parameter: name');
            }
        }

        if ($request->has('status')) {
            if (!in_array($request->get('status'), [BorderCheckpoint::STATUS_ACTIVE, BorderCheckpoint::STATUS_INACTIVE])) {
                throw new Exception('Invalid value for parameter: status');
            }
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getCheckpoint(Request $request, $id)
    {
        $responseData = [];

        /** @var BorderCheckpoint|null $borderCheckpoint */
        $borderCheckpoint = BorderCheckpoint::withTrashed()->find($id);

        if (empty($borderCheckpoint)) {
            $responseData['status'] = 'error';
            $responseData['message'] = 'Not Found';
            return response()->json($responseData, 404);
        }

        $responseData['status'] = 'success';
        $responseData['message'] = 'Border checkpoint';
        $responseData['data'] = $borderCheckpoint;
        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateCheckpoint(Request $request, $id)
    {
        $responseData = [];

        /** @var BorderCheckpoint|null $borderCheckpoint */
        $borderCheckpoint = BorderCheckpoint::withTrashed()->find($id);

        if (empty($borderCheckpoint)) {
            $responseData['status'] = 'error';
            $responseData['message'] = 'Not Found';
            return response()->json($responseData, 404);
        }

        try {
            $this->validateUpdateCheckpointRequest($request);
        } catch (\Exception $validationException) {
            $responseData['status'] = 'error';
            $responseData['message'] = $validationException->getMessage();

            return response()->json($responseData, 400);
        }

        if ($request->has('name')) {
            $borderCheckpoint->name = $request->get('name');
        }

        /** @var string|null $statusValue */
        $statusValue = $request->get('status', null);

        if (BorderCheckpoint::STATUS_ACTIVE === $statusValue) {
            $borderCheckpoint->restore();
        } else if (BorderCheckpoint::STATUS_INACTIVE === $statusValue) {
            $borderCheckpoint->delete();
        }

        $responseData['status'] = 'success';
        $responseData['message'] = 'Border checkpoint';
        $responseData['data'] = $borderCheckpoint;
        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function deleteCheckpoint(Request $request, $id)
    {
        $responseData = [];

        /** @var BorderCheckpoint|null $borderCheckpoint */
        $borderCheckpoint = BorderCheckpoint::withTrashed()->find($id);

        if (empty($borderCheckpoint)) {
            $responseData['status'] = 'error';
            $responseData['message'] = 'Not Found';
            return response()->json($responseData, 404);
        }

        $borderCheckpoint->delete();

        $responseData['status'] = 'success';
        $responseData['message'] = 'Border checkpoint';
        $responseData['data'] = $borderCheckpoint;
        return response()->json($responseData);
    }
}
