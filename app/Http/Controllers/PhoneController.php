<?php

namespace App\Http\Controllers;

use App\PhoneCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PhoneController
 * @package App\Http\Controllers
 */
class PhoneController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function validatePhone(Request $request)
    {
        $responseData = [];

        /**
         * TODO: implement phone validation, based on the country rules
         */
        if (false) { // validation failed
            $responseData['status'] = 'failure';
            $responseData['message'] = 'Validation failure';
            return response()->json($responseData, 400);
        }

        /**
         * TOOD: send SMS
         */
        if (false) { // failed to send SMS
            $responseData['status'] = 'failure';
            $responseData['message'] = 'Failed to send SMS to phone';
            return response()->json($responseData, 409);
        }

        $phoneCode = new PhoneCode();
        $phoneCode->code = PhoneCode::generateCode();
        $phoneCode->country_prefix = $request->get('phone_country_prefix');
        $phoneCode->phone_number = $request->get('phone');
        $phoneCode->phone_identifier = $request->get('phone_identifier', null);
        $phoneCode->save();

        $responseData['status'] = 'success';
        $responseData['message'] = 'SMS sent to phone';

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function checkPhone(Request $request)
    {
        $responseData = [];

        /**
         * TODO: implement validation
         */

        $responseData['status'] = 'success';
        $responseData['message'] = 'Phone validated';

        return response()->json($responseData);
    }
}
