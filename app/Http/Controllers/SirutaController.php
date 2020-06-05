<?php

namespace App\Http\Controllers;

use App\County;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class SirutaController
 * @package App\Http\Controllers
 */
class SirutaController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCountyList(Request $request)
    {
        $countyList = County::all();

        return response()->json($countyList);
    }
}
