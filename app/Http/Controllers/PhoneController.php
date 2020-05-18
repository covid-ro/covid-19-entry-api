<?php

namespace App\Http\Controllers;

use App\PhoneCode;
use App\Service\CodeGenerator;
use App\Service\Sts\SmsClient;
use App\User;
use Carbon\Carbon;
use Exception;
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
     * @throws Exception
     */
    private function validatePhoneRequest(Request $request)
    {
        if (empty($request->get('phone_country_prefix'))) { // required
            throw new Exception('Missing required parameter: phone_country_prefix');
        }

        if (strlen($request->get('phone_country_prefix')) > 3) {
            throw new Exception('Invalid value for parameter: phone_country_prefix');
        }

        if (empty($request->get('phone'))) { // required
            throw new Exception('Missing required parameter: phone');
        }

        if (strlen($request->get('phone')) > 32) {
            throw new Exception('Invalid value for parameter: phone');
        }

        if ($request->has('phone_identifier')) { // optional
            if (strlen($request->get('phone_identifier')) > 255) {
                throw new Exception('Invalid value for parameter: phone_identifier');
            }
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function validatePhone(Request $request)
    {
        $responseData = [];

        /**
         * Validate user input
         */
        try {
            $this->validatePhoneRequest($request);
        } catch (Exception $validationException) {
            $responseData['status'] = 'error';
            $responseData['message'] = $validationException->getMessage();

            return response()->json($responseData, 400);
        }

        $phoneCode = new PhoneCode();
        $phoneCode->code = (new CodeGenerator())->generateSmsCode();
        $phoneCode->country_prefix = $request->get('phone_country_prefix');
        $phoneCode->phone_number = $request->get('phone');
        $phoneCode->phone_identifier = $request->get('phone_identifier', '');
        $phoneCode->status = PhoneCode::STATUS_ACTIVE;
        $phoneCode->save();

        try {
            /** @var Lookups $twilioLookups */
            $twilioLookups = app('twilioLookups');

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

        /** @var SmsClient $smsClient */
        $smsClient = app('stsSms');

        try {
            $smsClient->sendMessage(
                $smsClient->preparePhoneNumber($phoneCode->formatted_phone_number),
                'Codul dumneavoastra de validare este ' . $phoneCode->code
            );
        } catch (\Exception $smsClientException) {
            $responseData['status'] = 'error';
            $responseData['message'] = 'Failed to send SMS to phone';
            $responseData['details'] = $smsClientException->getMessage();

            $phoneCode->notes = $smsClientException->getMessage();
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
     * @throws Exception
     */
    private function validateCheckPhoneRequest(Request $request)
    {
        if ($request->has('phone_identifier')) { // optional
            if (strlen($request->get('phone_identifier')) > 255) {
                throw new Exception('Invalid value for parameter: phone_identifier');
            }
        }

        if ($request->has('phone')) { // optional
            if (strlen($request->get('phone')) > 32) {
                throw new Exception('Invalid value for parameter: phone');
            }

            if (empty($request->get('phone_country_prefix'))) { // required
                throw new Exception('Missing required parameter: phone_country_prefix');
            }

            if (strlen($request->get('phone_country_prefix')) > 3) {
                throw new Exception('Invalid value for parameter: phone_country_prefix');
            }
        }

        /**
         * Either phone OR phone_identifier is required
         */
        if (
            !$request->has('phone_identifier') &&
            !$request->has('phone')
        ) {
            throw new Exception('Missing required parameter: phone OR phone_identifier');
        }

        if (empty($request->get('phone_validation_code'))) { // required
            throw new Exception('Missing required parameter: phone_validation_code');
        }

        if (
            6 !== strlen($request->get('phone_validation_code')) ||
            intval($request->get('phone_validation_code')) < 100000 ||
            intval($request->get('phone_validation_code')) > 999999
        ) {
            throw new Exception('Invalid value for parameter: phone_validation_code');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function checkPhone(Request $request)
    {
        $responseData = [];

        try {
            $this->validateCheckPhoneRequest($request);
        } catch (Exception $validationException) {
            $responseData['status'] = 'error';
            $responseData['message'] = $validationException->getMessage();

            return response()->json($responseData, 400);
        }

        /** @var PhoneCode|null $phoneCode */
        $phoneCode = PhoneCode::whereNull('deleted_at');

        if ($request->has('phone_identifier')) {
            $phoneCode->where('phone_identifier', $request->get('phone_identifier'));
        } else if ($request->has('phone')) {
            $phoneCode->where('phone_number', $request->get('phone'));
            $phoneCode->where('country_prefix', $request->get('phone_country_prefix'));
        }

        $phoneCode->where('code', $request->get('phone_validation_code'));
        $phoneCode->whereDate('created_at', '>=', Carbon::now()->subMinutes(30)); // sent in the last 30 minutes
        $phoneCode = $phoneCode->first();

        if (empty($phoneCode)) {
            $responseData['status'] = 'error';
            $responseData['message'] = 'Invalid value for parameter: code';
            return response()->json($responseData, 400);
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

        $user->token = (new CodeGenerator())->generateUserToken(32);
        $user->save();

        $responseData['status'] = 'success';
        $responseData['message'] = 'Phone validated';
        $responseData['token'] = $user->token;

        return response()->json($responseData);
    }
}
