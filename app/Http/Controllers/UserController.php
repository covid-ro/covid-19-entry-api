<?php

namespace App\Http\Controllers;

use App\IsolationAddress;
use App\ItineraryCountry;
use App\User;
use Carbon\Carbon;
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

        /**
         * TODO: validate fields
         */

        $user->name = $request->get('name');
        $user->surname = $request->get('surname');
        $user->email = $request->get('email');
        $user->cnp = $request->get('cnp');
        $user->document_type = $request->get('document_type');
        $user->document_series = $request->get('document_series');
        $user->document_number = $request->get('document_number');

        $user->travelling_from_country_code = $request->get('travelling_from_country_code');
        $user->travelling_from_city = $request->get('travelling_from_city');
        $user->travelling_from_date = $request->get('travelling_from_date');
        $user->home_country_return_date = Carbon::now();

//        if (empty($request->get('isolation_addresses'))) {
//            // validation error
//        }

        /** @var array $isolationAddressData */
        foreach ($request->get('isolation_addresses') as $isolationAddressData) {
            $isolationAddress = new IsolationAddress();
            $isolationAddress->user_id = $user->id;
            $isolationAddress->city_id = null; // TODO
            $isolationAddress->county_id = null; // TODO
            $isolationAddress->city_full_address = $isolationAddressData['city_full_address'];
            $isolationAddress->city_arrival_date = $isolationAddressData['city_arrival_date'];
            $isolationAddress->city_departure_date = $isolationAddressData['city_departure_date'] ?? null;
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