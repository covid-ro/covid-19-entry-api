<?php

namespace App\Http\Controllers;

use alcea\cnp\Cnp;
use App\BorderCheckpoint;
use App\Declaration;
use App\DeclarationCode;
use App\DeclarationSignature;
use App\IsolationAddress;
use App\ItineraryCountry;
use App\Symptom;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

/**
 * Class DeclarationController
 * @package App\Http\Controllers
 */
class DeclarationController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createDeclaration(Request $request)
    {
        $responseData = [];
        $responseData['declaration_code'] = null;

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
         * Validate request
         */
        try {
            $this->validateDeclarationRequest($request);
        } catch (Exception $validationException) {
            $responseData['status'] = 'error';
            $responseData['message'] = $validationException->getMessage();

            return response()->json($responseData, 400);
        }

        /**
         * Generate unique declaration code
         */
        $declarationCode = DeclarationCode::generateDeclarationCode();

        /**
         * Save declaration details
         */
        $declaration = new Declaration();
        $declaration->user_id = $user->id;
        $declaration->declarationcode_id = $declarationCode->id;

        /**
         * User details
         */
        $declaration->name = $request->get('name');
        $declaration->surname = $request->get('surname');
        $declaration->email = $request->get('email');
        $declaration->cnp = $request->get('cnp');
        $declaration->sex = $this->getSexFromCnp($request->get('cnp'));
        $declaration->birth_date = $this->getBirthDateFromCnp($request->get('cnp'));

        /**
         * Border checkpoint
         */
        if ($request->has('border_checkpoint_id')) {
            /** @var BorderCheckpoint|null $borderCheckpoint */
            $borderCheckpoint = BorderCheckpoint::find($request->get('border_checkpoint_id'));

            if (!empty($borderCheckpoint)) {
                $declaration->border_checkpoint_id = $borderCheckpoint->id;
            }
        }

        /**
         * Document details
         */
        $declaration->document_type = $request->get('document_type');
        $declaration->document_series = $request->get('document_series');
        $declaration->document_number = $request->get('document_number');

        /**
         * Travel details
         */
        $declaration->travelling_from_country_code = $request->get('travelling_from_country_code');
        $declaration->travelling_from_city = $request->get('travelling_from_city');
        $declaration->travelling_from_date = Carbon::createFromFormat('Y-m-d', $request->get('travelling_from_date'));
        $declaration->home_country_return_date = Carbon::now();

        /**
         * Questions answers
         */
        $declaration->q_visited = $request->get('q_visited');
        $declaration->q_contacted = $request->get('q_contacted');
        $declaration->q_hospitalized = $request->get('q_hospitalized');

        /**
         * Vehicle details
         */
        $declaration->vehicle_type = $request->get('vehicle_type');
        $declaration->vehicle_registration_no = $this->prepareVehicleRegistrationNumber($request->get('vehicle_registration_no'));

        $declaration->save();

        /**
         * Symptoms
         */
        foreach ($request->get('symptoms') as $symptomName) {
            $symptom = Symptom::where('name', trim($symptomName))->first();
            $declaration->symptoms()->attach($symptom);
        }

        /**
         * Isolation addresses
         */
        /** @var array $isolationAddressData */
        foreach ($request->get('isolation_addresses') as $isolationAddressData) {
            $isolationAddress = new IsolationAddress();
            $isolationAddress->declaration_id = $declaration->id;
            $isolationAddress->city = $isolationAddressData['city'];
            $isolationAddress->county = $isolationAddressData['county'];
            $isolationAddress->city_full_address = $isolationAddressData['city_full_address'];
            $isolationAddress->city_arrival_date = Carbon::createFromFormat('Y-m-d', $isolationAddressData['city_arrival_date']);
            $isolationAddress->city_departure_date = !empty($isolationAddressData['city_departure_date']) ? Carbon::createFromFormat('Y-m-d', $isolationAddressData['city_departure_date']) : null;
            $isolationAddress->save();
        }

        /**
         * Itinerary countries
         */
        /** @var string $countryIso2Code */
        foreach ($request->get('itinerary_countries') as $countryIso2Code) {
            $itineraryCountry = new ItineraryCountry();
            $itineraryCountry->declaration_id = $declaration->id;
            $itineraryCountry->country_code = $countryIso2Code;
            $itineraryCountry->save();
        }

        if ($request->has('signature')) {
            $declarationSignature = new DeclarationSignature();
            $declarationSignature->declaration_id = $declaration->id;
            $declarationSignature->image = $request->get('signature');
            $declarationSignature->save();
        }

        $responseData['status'] = 'success';
        $responseData['message'] = 'Declaration created';
        $responseData['declaration_code'] = $declarationCode->code;

        return response()->json($responseData);
    }

    /**
     * @param string $vehicleRegistrationNumber
     * @return string
     */
    private function prepareVehicleRegistrationNumber(string $vehicleRegistrationNumber): string
    {
        return str_replace([' ', '-'], '', $vehicleRegistrationNumber);
    }

    /**
     * @param string $cnp
     * @return string|null
     *
     * @see https://ro.wikipedia.org/wiki/Cod_numeric_personal
     */
    private function getSexFromCnp(string $cnp): ?string
    {
        /** @var array $explodedCnp */
        $explodedCnp = str_split($cnp);

        if (in_array($explodedCnp[0], [1, 3, 5, 7])) {
            return 'M';
        } else if (in_array($explodedCnp[0], [2, 4, 6, 8])) {
            return 'F';
        } else {
            return null; // Unknown
        }
    }

    /**
     * @param string $cnp
     * @return Carbon|null
     */
    private function getBirthDateFromCnp(string $cnp): ?Carbon
    {
        /** @var array $explodedCnp */
        $explodedCnp = str_split($cnp);

        try {
            return Carbon::createFromFormat(
                'y-m-d',
                $explodedCnp[1] . $explodedCnp[2] . '-' . $explodedCnp[3] . $explodedCnp[4] . '-' . $explodedCnp[5] . $explodedCnp[6]
            );
        } catch (InvalidArgumentException $invalidArgumentException) {
            return null;
        }
    }

    /**
     * @param $declarationCode
     * @return JsonResponse
     */
    public function getDeclaration($declarationCode)
    {
        $responseData = [];

        /** @var Declaration|null $declaration */
        $declaration = Declaration::join('declaration_codes', 'declaration_codes.id', '=', 'declarations.declarationcode_id')
            ->where('declaration_codes.code', $declarationCode)
            ->select('declarations.*')
            ->first();

        if (empty($declaration)) {
            return response()->json([
                'status' => 'error',
                'reason' => 'Not Found'
            ], 404);
        }

        $responseData['status'] = 'success';
        $responseData['message'] = 'Declaration details';
        $responseData['declaration'] = $declaration->toArray();
        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDeclarationList(Request $request)
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 50;

        $responseData = [];
        $responseData['status'] = 'success';
        $responseData['message'] = 'Declaration details';

        $declarationList = Declaration::whereNull('deleted_at');

        if ($request->has('vehicle_type')) {
            $declarationList->where('declarations.vehicle_type', $request->get('vehicle_type'));
        }

        if ($request->has('vehicle_registration_no')) {
            $declarationList->where('declarations.vehicle_registration_no', $this->prepareVehicleRegistrationNumber($request->get('vehicle_registration_no')));
        }

        $declarationList = $declarationList->paginate($perPage);

        return response()->json($declarationList);
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    private function validateDeclarationRequest(Request $request)
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

        if ($request->has('border_checkpoint_id')) { // optional
            /** @var BorderCheckpoint|null $borderCheckpoint */
            $borderCheckpoint = BorderCheckpoint::find($request->get('border_checkpoint_id'));

            if (empty($borderCheckpoint)) {
                throw new Exception('Invalid value for parameter: border_checkpoint_id');
            }
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
        } catch (Exception $exception) {
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
            if (empty($isolationAddress['city'])) {
                throw new Exception('Missing required parameter: isolation_addresses|city');
            }

            if (strlen($isolationAddress['city']) > 64) {
                throw new Exception('Invalid value for parameter: isolation_addresses|city');
            }

            if (empty($isolationAddress['county'])) {
                throw new Exception('Missing required parameter: isolation_addresses|county');
            }

            if (strlen($isolationAddress['county']) > 64) {
                throw new Exception('Invalid value for parameter: isolation_addresses|county');
            }

            if (empty($isolationAddress['city_full_address'])) {
                throw new Exception('Missing required parameter: isolation_addresses|city_full_address');
            }

            if (strlen($isolationAddress['city_full_address']) > 256) {
                throw new Exception('Invalid value for parameter: isolation_addresses|city_full_address');
            }

            try {
                Carbon::createFromFormat('Y-m-d', $isolationAddress['city_arrival_date']);
            } catch (Exception $exception) {
                throw new Exception('Invalid value for parameter: isolation_addresses|city_arrival_date');
            }

            if (!empty($isolationAddress['city_departure_date'])) {
                try {
                    Carbon::createFromFormat('Y-m-d', $isolationAddress['city_departure_date']);
                } catch (Exception $exception) {
                    throw new Exception('Invalid value for parameter: isolation_addresses|city_departure_date');
                }
            }
        }

        if (!$request->has('q_visited')) {
            throw new Exception('Missing required parameter: q_visited');
        }

        if (!in_array($request->get('q_visited'), [0, 1, true, false, '0', '1', 'true', 'false'])) {
            throw new Exception('Invalid value for parameter: q_visited');
        }

        if (!$request->has('q_contacted')) {
            throw new Exception('Missing required parameter: q_contacted');
        }

        if (!in_array($request->get('q_contacted'), [0, 1, true, false, '0', '1', 'true', 'false'])) {
            throw new Exception('Invalid value for parameter: q_contacted');
        }

        if (!$request->has('q_hospitalized')) {
            throw new Exception('Missing required parameter: q_hospitalized');
        }

        if (!in_array($request->get('q_hospitalized'), [0, 1, true, false, '0', '1', 'true', 'false'])) {
            throw new Exception('Invalid value for parameter: q_hospitalized');
        }

        /**
         * Validate symptoms
         */
        if (!($request->has('symptoms'))) {
            throw new Exception('Missing required parameter: symptoms');
        }

        if (!is_array($request->get('symptoms'))) {
            throw new Exception('Invalid value for parameter: symptoms');
        }

        /** @var string $symptomName */
        foreach ($request->get('symptoms') as $symptomName) {
            /** @var Symptom $symptom */
            $symptom = Symptom::where('name', trim((string)$symptomName))->first();

            if (empty($symptom)) {
                throw new Exception('Invalid value for parameter: symptoms');
            }
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

        if ($request->has('signature')) {
            if (base64_encode(base64_decode($request->get('signature'), true)) !== $request->get('signature')) {
                throw new Exception('Invalid value for parameter: signature');
            }
        }
    }

    /**
     * @param $declarationCode
     * @return JsonResponse
     */
    public function getDeclarationSignature($declarationCode)
    {
        $responseData = [];

        /** @var Declaration|null $declaration */
        $declaration = Declaration::join('declaration_codes', 'declaration_codes.id', '=', 'declarations.declarationcode_id')
            ->where('declaration_codes.code', $declarationCode)
            ->select('declarations.*')
            ->first();

        if (empty($declaration)) {
            return response()->json([
                'status' => 'error',
                'reason' => 'Not Found'
            ], 404);
        }

        if (empty($declaration->declarationsignature)) {
            return response()->json([
                'status' => 'error',
                'reason' => 'Not Found'
            ], 404);
        }

        $responseData['status'] = 'success';
        $responseData['message'] = 'Declaration details';
        $responseData['signature'] = $declaration->declarationsignature->image;
        return response()->json($responseData);
    }
}
