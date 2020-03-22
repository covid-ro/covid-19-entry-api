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
        $data = [];

        /**
         * TODO: implement phone validation, based on the country rules
         */
        if (false) { // validation failed
            $data['status'] = 'failure';
            $data['message'] = 'Validation failure';
            return response()->json($data, 400);
        }

        /**
         * TOOD: send SMS
         */
        if (false) { // failed to send SMS
            $data['status'] = 'failure';
            $data['message'] = 'Failed to send SMS to phone';
            return response()->json($data, 409);
        }

        $phoneCode = new PhoneCode();
        $phoneCode->code = PhoneCode::generateCode();
        $phoneCode->country_prefix = $request->get('phone_country_prefix');
        $phoneCode->phone_number = $request->get('phone');
        $phoneCode->phone_identifier = $request->get('phone_identifier', null);
        $phoneCode->save();

        $data['status'] = 'success';
        $data['message'] = 'SMS sent to phone';

        return response()->json($data);
    }
}
