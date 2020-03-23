<?php

namespace App\Http\Controllers;

use App\PhoneCode;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Lookups;

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

        /** @var Lookups $twilioLookups */
        $twilioLookups = app('twilioLookups');

        /**
         * TODO: Validate required fields
         */

        $phoneCode = new PhoneCode();
        $phoneCode->code = PhoneCode::generateCode();
        $phoneCode->country_prefix = $request->get('phone_country_prefix');
        $phoneCode->phone_number = $request->get('phone');
        $phoneCode->phone_identifier = $request->get('phone_identifier', null);
        $phoneCode->status = PhoneCode::STATUS_ACTIVE;
        $phoneCode->save();

        try {
            $twilioPhone = $twilioLookups
                ->v1
                ->phoneNumbers($request->get('phone_country_prefix') . $request->get('phone'))
                ->fetch(['type' => 'mobile']);

            /**
             * Store formatted phone number and country code form Twilio
             */
            $phoneCode->country_code = $twilioPhone->countryCode;
            $phoneCode->formatted_phone_number = $twilioPhone->phoneNumber;
        } catch (TwilioException $twilioException) {
            $responseData['status'] = 'error';
            $responseData['message'] = 'Validation failure';

            $phoneCode->notes = 'Failed to validate phone number';
            $phoneCode->status = PhoneCode::STATUS_INACTIVE;
            $phoneCode->save();

            return response()->json($responseData, 400);
        }

        $phoneCode->save();

        /**
         * TOOD: send SMS
         */
        if (false) { // failed to send SMS
            $responseData['status'] = 'error';
            $responseData['message'] = 'Failed to send SMS to phone';

            $phoneCode->notes = 'Failed to send SMS to phone';
            $phoneCode->status = PhoneCode::STATUS_INACTIVE;
            $phoneCode->save();

            return response()->json($responseData, 409);
        }

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

        /** @var PhoneCode|null $phoneCode */
        $phoneCode = PhoneCode::where('phone_identifier', $request->get('phone_identifier'))
            ->where('code', $request->get('phone_validation_code'))
            ->first();

        if (empty($phoneCode)) {
            $responseData['status'] = 'error';
            $responseData['message'] = 'Invalid code';
            return response()->json($responseData, 409);
        }

        /**
         * Delete PhoneCode after usage
         */
        $phoneCode->delete();

        /** @var User|null $user */
        $user = User::where('phone_number', $phoneCode->formatted_phone_number)->first();

        if (empty($user)) {
            $user = new User();
            $user->phone_number = $phoneCode->formatted_phone_number;
            $user->country_code = $phoneCode->country_code;
        }

        $user->token = User::generateToken();
        $user->save();

        $responseData['status'] = 'success';
        $responseData['message'] = 'Phone validated';
        $responseData['token'] = $user->token;

        return response()->json($responseData);
    }
}
