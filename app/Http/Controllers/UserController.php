<?php

namespace App\Http\Controllers;

use alcea\cnp\Cnp;
use App\IsolationAddress;
use App\ItineraryCountry;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @throws Exception
     */
    private function validateCreateUserRequest(Request $request)
    {
        if (empty($request->get('name'))) {
            throw new Exception('Missing required parameter: name');
        }

        if (strlen($request->get('name') > 64)) {
            throw new Exception('Invalid value for parameter: name');
        }

        if (empty($request->get('surname'))) {
            throw new Exception('Missing required parameter: surname');
        }

        if (strlen($request->get('surname') > 64)) {
            throw new Exception('Invalid value for parameter: surname');
        }

        if (
            !empty($request->get('email')) && // optional
            !filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)
        ) {
            throw new Exception('Invalid value for parameter: email');
        }

        if (empty($request->get('cnp'))) {
            throw new Exception('Missing required parameter: cnp');
        }

        if (!Cnp::validate($request->get('cnp'))) {
            throw new Exception('Invalid value for parameter: cnp');
        }

        if (empty($request->get('document_type'))) {
            throw new Exception('Missing required parameter: document_type');
        }

        if (!in_array($request->get('document_type'), [User::DOCUMENT_TYPE_IDENTITY_CARD, User::DOCUMENT_TYPE_PASSPORT])) {
            throw new Exception('Invalid value for parameter: document_type');
        }

        if (
            !empty($request->get('document_series')) && // optional
            strlen($request->get('document_series')) > 16
        ) {
            throw new Exception('Invalid value for parameter: document_series');
        }

        if (empty($request->get('document_number'))) {
            throw new Exception('Missing required parameter: document_number');
        }

        if (strlen($request->get('document_number')) > 32) {
            throw new Exception('Invalid value for parameter: document_number');
        }

        if (empty($request->get('travelling_from_country_code'))) {
            throw new Exception('Missing required parameter: travelling_from_country_code');
        }

        if (2 !== strlen($request->get('travelling_from_country_code'))) {
            throw new Exception('Invalid value for parameter: travelling_from_country_code');
        }

        if (empty($request->get('travelling_from_city'))) {
            throw new Exception('Missing required parameter: travelling_from_city');
        }

        if (strlen($request->get('travelling_from_city')) > 32) {
            throw new Exception('Invalid value for parameter: travelling_from_city');
        }

        if (empty($request->get('travelling_from_date'))) {
            throw new Exception('Missing required parameter: travelling_from_date');
        }

        try {
            Carbon::createFromFormat('Y-m-d', $request->get('travelling_from_date'));
        } catch (\Exception $exception) {
            throw new Exception('Invalid value for parameter: travelling_from_date');
        }

        /**
         * Validate Isolation Addresses
         */
        if (empty($request->get('isolation_addresses'))) {
            throw new Exception('Missing required parameter: isolation_addresses');
        }

        /** @var array $isolationAddress */
        foreach ($request->get('isolation_addresses') as $isolationAddress) {
            // TODO: validate county

            // TODO: validate city

            if (empty($isolationAddress['city_full_address'])) {
                throw new Exception('Missing required parameter: isolation_addresses|city_full_address');
            }

            if (strlen($isolationAddress['city_full_address']) > 256) {
                throw new Exception('Invalid value for parameter: isolation_addresses|city_full_address');
            }

            try {
                Carbon::createFromFormat('Y-m-d', $isolationAddress['city_arrival_date']);
            } catch (\Exception $exception) {
                throw new Exception('Invalid value for parameter: isolation_addresses|city_arrival_date');
            }

            if (!empty($isolationAddress['city_departure_date'])) {
                try {
                    Carbon::createFromFormat('Y-m-d', $isolationAddress['city_departure_date']);
                } catch (\Exception $exception) {
                    throw new Exception('Invalid value for parameter: isolation_addresses|city_departure_date');
                }
            }
        }

        if (empty($request->get('question_1_answer'))) {
            throw new Exception('Missing required parameter: question_1_answer');
        }

        if (strlen($request->get('question_1_answer')) > 512) {
            throw new Exception('Invalid value for parameter: question_1_answer');
        }

        if (empty($request->get('question_2_answer'))) {
            throw new Exception('Missing required parameter: question_2_answer');
        }

        if (strlen($request->get('question_2_answer')) > 512) {
            throw new Exception('Invalid value for parameter: question_2_answer');
        }

        if (empty($request->get('question_3_answer'))) {
            throw new Exception('Missing required parameter: question_3_answer');
        }

        if (strlen($request->get('question_3_answer')) > 512) {
            throw new Exception('Invalid value for parameter: question_3_answer');
        }

        if (!$request->has('symptom_fever')) {
            throw new Exception('Missing required parameter: symptom_fever');
        }

        if (!in_array($request->get('symptom_fever'), [0, 1, 'true', 'false'])) {
            throw new Exception('Invalid value for parameter: symptom_fever');
        }

        if (!$request->has('symptom_swallow')) {
            throw new Exception('Missing required parameter: symptom_swallow');
        }

        if (!in_array($request->get('symptom_swallow'), [0, 1, 'true', 'false'])) {
            throw new Exception('Invalid value for parameter: symptom_swallow');
        }

        if (!$request->has('symptom_breathing')) {
            throw new Exception('Missing required parameter: symptom_breathing');
        }

        if (!in_array($request->get('symptom_breathing'), [0, 1, 'true', 'false'])) {
            throw new Exception('Invalid value for parameter: symptom_breathing');
        }

        if (!$request->has('symptom_cough')) {
            throw new Exception('Missing required parameter: symptom_cough');
        }

        if (!in_array($request->get('symptom_cough'), [0, 1, 'true', 'false'])) {
            throw new Exception('Invalid value for parameter: symptom_cough');
        }

        /**
         * Validate Itinerary Countries
         */
        if (empty($request->get('itinerary_countries'))) {
            throw new Exception('Missing required parameter: itinerary_countries');
        }

        /** @var string $itineraryCountry */
        foreach ($request->get('itinerary_countries') as $itineraryCountry) {
            if (2 !== strlen($itineraryCountry)) {
                throw new Exception('Invalid value for parameter: itinerary_countries');
            }
        }

        if (empty($request->get('vehicle_type'))) {
            throw new Exception('Missing required parameter: vehicle_type');
        }

        if (!in_array($request->get('vehicle_type'), [User::VEHICLE_TYPE_AUTO, User::VEHICLE_TYPE_AMBULANCE])) {
            throw new Exception('Invalid value for parameter: vehicle_type');
        }

        if (
            !empty($request->get('vehicle_registration_no')) && // optional
            strlen($request->get('vehicle_registration_no')) > 16
        ) {
            throw new Exception('Invalid value for parameter: vehicle_registration_no');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request)
    {
        $responseData = [];

        /** @var User $user */
        $user = Auth::user();

        /**
         * Check if the user was identified
         */
        if (empty($user->id)) {
            return response()->json([
                'status' => 'error',
                'reason' => 'Unauthorized'
            ], 401);
        }

        /**
         * TODO: erase user history???
         */

        try {
            $this->validateCreateUserRequest($request);
        } catch (Exception $validationException) {
            $responseData['status'] = 'error';
            $responseData['message'] = $validationException->getMessage();

            return response()->json($responseData, 400);
        }

        $user->name = $request->get('name');
        $user->surname = $request->get('surname');
        $user->email = $request->get('email');
        $user->cnp = $request->get('cnp');
        $user->document_type = $request->get('document_type');
        $user->document_series = $request->get('document_series');
        $user->document_number = $request->get('document_number');

        $user->travelling_from_country_code = $request->get('travelling_from_country_code');
        $user->travelling_from_city = $request->get('travelling_from_city');
        $user->travelling_from_date = Carbon::createFromFormat('Y-m-d', $request->get('travelling_from_date'));
        $user->home_country_return_date = Carbon::now();

        /** @var array $isolationAddressData */
        foreach ($request->get('isolation_addresses') as $isolationAddressData) {
            $isolationAddress = new IsolationAddress();
            $isolationAddress->user_id = $user->id;
            $isolationAddress->city_id = null; // TODO
            $isolationAddress->county_id = null; // TODO
            $isolationAddress->city_full_address = $isolationAddressData['city_full_address'];
            $isolationAddress->city_arrival_date = Carbon::createFromFormat('Y-m-d', $isolationAddressData['city_arrival_date']);
            $isolationAddress->city_departure_date = !empty($isolationAddressData['city_departure_date']) ? Carbon::createFromFormat('Y-m-d', $isolationAddressData['city_departure_date']) : null;
            $isolationAddress->save();
        }

        $user->question_1_answer = $request->get('question_1_answer');
        $user->question_2_answer = $request->get('question_2_answer');
        $user->question_3_answer = $request->get('question_3_answer');

        $user->symptom_fever = (bool)$request->get('symptom_fever');
        $user->symptom_swallow = (bool)$request->get('symptom_swallow');
        $user->symptom_breathing = (bool)$request->get('symptom_breathing');
        $user->symptom_cough = (bool)$request->get('symptom_cough');

        /** @var string $countryIso2Code */
        foreach ($request->get('itinerary_countries') as $countryIso2Code) {
            $itineraryCountry = new ItineraryCountry();
            $itineraryCountry->user_id = $user->id;
            $itineraryCountry->country_code = $countryIso2Code;
            $itineraryCountry->save();
        }

        $user->vehicle_type = $request->get('vehicle_type');
        $user->vehicle_registration_no = $request->get('vehicle_registration_no');

        $user->save();

        $responseData['status'] = 'success';
        $responseData['message'] = 'User created';

        return response()->json($responseData);
    }
}
