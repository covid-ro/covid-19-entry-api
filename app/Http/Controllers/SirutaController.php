<?php

namespace App\Http\Controllers;

use App\City;
use App\County;
use App\Settlement;
use Illuminate\Http\JsonResponse;

/**
 * Class SirutaController
 * @package App\Http\Controllers
 */
class SirutaController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getCountyList()
    {
        $countyList = County::all();

        $responseData['status'] = 'success';
        $responseData['message'] = 'County list';
        $responseData['data'] = $countyList;

        return response()->json($responseData);
    }

    /**
     * @param int $countyId
     * @return JsonResponse
     */
    public function getCityList(int $countyId)
    {
        /** @var County|null $county */
        $county = County::find($countyId);

        if (empty($county)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not Found'
            ], 404);
        }

        $cityList = City::where('siruta_parent_id', '=', $county->siruta_id)->get();

        $responseData['status'] = 'success';
        $responseData['message'] = 'City list';
        $responseData['data'] = $cityList;

        return response()->json($responseData);
    }

    /**
     * @param int $countyId
     * @return JsonResponse
     */
    public function getSettlementList(int $countyId)
    {
        /** @var County|null $county */
        $county = County::find($countyId);

        if (empty($county)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not Found'
            ], 404);
        }

        $settlementList = Settlement::join('cities', 'cities.siruta_id', '=', 'settlements.siruta_parent_id')
            ->where('cities.siruta_parent_id', '=', $county->siruta_id)
            ->select('settlements.*')
            ->get();

        $responseData['status'] = 'success';
        $responseData['message'] = 'Settlement list';
        $responseData['data'] = $settlementList;

        return response()->json($responseData);
    }
}
