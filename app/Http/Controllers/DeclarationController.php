<?php

namespace App\Http\Controllers;

use alcea\cnp\Cnp;
use App\BorderCheckpoint;
use App\Declaration;
use App\DeclarationCode;
use App\DeclarationSignature;
use App\IsolationAddress;
use App\ItineraryCountry;
use App\Service\EvidentaPopulatiei\SearchClient;
use App\Service\EvidentaPopulatiei\SearchClientException;
use App\Symptom;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

        /** @var User $user */
        $user = Auth::user();

        /**
         * Check if the user was identified
         */
        if (empty($user->id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
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

        $evidentaPopulatieiAddress = null;

        /**
         * For Romanian citizens, query Evidenta Populatiei for home address
         */
        if (
            false && // TODO: to be triple-checked!!!
            true === (bool)$request->get('is_romanian', false) &&
            true === (bool)$request->get('home_isolated', false)
        ) {
            /** @var SearchClient $evidentaPopulatieiSearchClient */
            $evidentaPopulatieiSearchClient = app('evidentaPopulatiei');

            try {
                $evidentaPopulatieiAddress = $evidentaPopulatieiSearchClient->getAddress(
                    $request->get('cnp'),
                    $request->get('name'),
                    $request->get('surname')
                );
            } catch (SearchClientException $searchClientException) {
                $responseData['status'] = 'error';
                $responseData['message'] = $searchClientException->getMessage();
                return response()->json($responseData, 400);
            }
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
        $declaration->is_romanian = (bool)$request->get('is_romanian');
        $declaration->sex = $declaration->is_romanian ? $this->getSexFromCnp($request->get('cnp')) : null;
        $declaration->birth_date = ($request->has('birth_date') && !empty($request->get('birth_date'))) ? new Carbon($request->get('birth_date')) : $this->getBirthDateFromCnp($request->get('cnp'));

        if (!empty($evidentaPopulatieiAddress)) {
            $declaration->home_address = $evidentaPopulatieiAddress;
        }

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
        $declaration->travelling_from_date = $request->has('travelling_from_date') ? new Carbon($request->get('travelling_from_date')) : null;
        $declaration->home_country_return_date = Carbon::now();
        $declaration->travel_route = $request->get('travel_route', null);

        /**
         * Questions answers
         */
        $declaration->q_visited = $request->has('q_visited') ? (bool)$request->get('q_visited') : null;
        $declaration->q_contacted = $request->has('q_contacted') ? (bool)$request->get('q_contacted') : null;
        $declaration->q_hospitalized = $request->has('q_hospitalized') ? (bool)$request->get('q_hospitalized') : null;

        /**
         * Vehicle details
         */
        $declaration->vehicle_type = $request->get('vehicle_type');
        $declaration->vehicle_registration_no = $request->has('vehicle_registration_no') ? $this->prepareVehicleRegistrationNumber($request->get('vehicle_registration_no')) : null;

        $declaration->accept_personal_data = $request->get('accept_personal_data');
        $declaration->accept_read_law = $request->get('accept_read_law');

        $declaration->home_isolated = (bool)$request->get('home_isolated');

        $declaration->save();

        /**
         * Symptoms
         */
        if ($request->has('symptoms')) {
            foreach ($request->get('symptoms') as $symptomName) {
                $symptom = Symptom::where('name', trim($symptomName))->first();
                $declaration->symptoms()->attach($symptom);
            }
        }

        if (false === $declaration->home_isolated) {
            /**
             * Isolation addresses
             */
            /** @var array $isolationAddressData */
            foreach ($request->get('isolation_addresses') as $isolationAddressData) {
                $isolationAddress = new IsolationAddress();
                $isolationAddress->declaration_id = $declaration->id;
                $isolationAddress->city = $isolationAddressData['city'];
                $isolationAddress->county = $isolationAddressData['county'];
                $isolationAddress->street = $isolationAddressData['street'];
                $isolationAddress->number = $isolationAddressData['number'];
                $isolationAddress->bloc = $isolationAddressData['bloc'];
                $isolationAddress->entry = $isolationAddressData['entry'];
                $isolationAddress->apartment = $isolationAddressData['apartment'];
                $isolationAddress->save();
            }
        }

        /**
         * Itinerary countries
         */
        if ($request->has('itinerary_countries')) {
            /** @var string $countryIso2Code */
            foreach ($request->get('itinerary_countries') as $countryIso2Code) {
                $itineraryCountry = new ItineraryCountry();
                $itineraryCountry->declaration_id = $declaration->id;
                $itineraryCountry->country_code = $countryIso2Code;
                $itineraryCountry->save();
            }
        }

        if ($request->has('signature')) {
            $declarationSignature = new DeclarationSignature();
            $declarationSignature->declaration_id = $declaration->id;
            $declarationSignature->image = $request->get('signature');
            $declarationSignature->save();
        }

        /** @var Declaration $declaration */
        $declaration = Declaration::find($declaration->id);

        $responseData['status'] = 'success';
        $responseData['message'] = 'Declaration created';
        $responseData['declaration'] = $declaration->toArray();

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
                'message' => 'Not Found'
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

        if ($request->has('cnp')) {
            $declarationList->where('declarations.cnp', '=', $request->get('cnp'));
        }

        if ($request->has('dsp_user_name')) {
            $declarationList->where('declarations.dsp_user_name', '=', $request->get('dsp_user_name'));
        }

        if ($request->has('vehicle_type')) {
            $declarationList->where('declarations.vehicle_type', $request->get('vehicle_type'));
        }

        if ($request->has('vehicle_registration_no')) {
            $declarationList->where('declarations.vehicle_registration_no', $this->prepareVehicleRegistrationNumber($request->get('vehicle_registration_no')));
        }

        $declarationList->orderBy('id', 'desc');
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

        if (!is_string($request->get('name')) || strlen($request->get('name') > 64)) {
            throw new Exception('Invalid value for parameter: name');
        }

        if (empty($request->get('surname'))) {
            throw new Exception('Missing required parameter: surname');
        }

        if (!is_string($request->get('surname')) || strlen($request->get('surname') > 64)) {
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

        /**
         * Don't validate CNP!
         */
//        if (!Cnp::validate($request->get('cnp'))) {
//            throw new Exception('Invalid value for parameter: cnp');
//        }

        if (!$request->has('is_romanian')) {
            throw new Exception('Missing required parameter: is_romanian');
        }

        if (!is_bool($request->get('is_romanian'))) {
            throw new Exception('Missing required parameter: is_romanian');
        }

        if (
            (!$request->has('birth_date') || empty($request->get('birth_date'))) && // Birth date is missing or empty
            !Cnp::validate($request->get('cnp')) // CNP is not valid, meaning we cannot extract the birth date
        ) {
            throw new Exception('Missing required parameter: birth_date');
        }

        if ($request->has('birth_date') && !empty($request->get('birth_date'))) {
            try {
                new Carbon($request->get('birth_date'));
            } catch (Exception $exception) {
                throw new Exception('Invalid value for parameter: birth_date');
            }
        }

        if ($request->has('border_checkpoint_id')) { // optional
            /** @var BorderCheckpoint|null $borderCheckpoint */
            $borderCheckpoint = BorderCheckpoint::find($request->get('border_checkpoint_id'));

            if (empty($borderCheckpoint)) {
                throw new Exception('Invalid value for parameter: border_checkpoint_id');
            }
        }

        if (
            $request->has('document_type') && // optional
            !in_array($request->get('document_type'), [User::DOCUMENT_TYPE_IDENTITY_CARD, User::DOCUMENT_TYPE_PASSPORT])
        ) {
            throw new Exception('Invalid value for parameter: document_type');
        }

        if (
            $request->has('document_series') && // optional
            (!is_string($request->get('document_series')) || strlen($request->get('document_series')) > 16)
        ) {
            throw new Exception('Invalid value for parameter: document_series');
        }

        if (
            $request->has('document_number') && // optional
            (
                !is_string($request->get('document_number')) ||
                strlen($request->get('document_number')) > 32
            )
        ) {
            throw new Exception('Invalid value for parameter: document_number');
        }

        if (empty($request->get('travelling_from_country_code'))) {
            throw new Exception('Missing required parameter: travelling_from_country_code');
        }

        if (!is_string($request->get('travelling_from_country_code')) || 2 !== strlen($request->get('travelling_from_country_code'))) {
            throw new Exception('Invalid value for parameter: travelling_from_country_code');
        }

        if (
            $request->has('travelling_from_city') && // optional
            (
                !is_string($request->get('travelling_from_city')) ||
                strlen($request->get('travelling_from_city')) > 32
            )
        ) {
            throw new Exception('Invalid value for parameter: travelling_from_city');
        }

        if ($request->has('travelling_from_date')) {
            try {
                new Carbon(($request->get('travelling_from_date')));
            } catch (Exception $exception) {
                throw new Exception('Invalid value for parameter: travelling_from_date');
            }
        }

        if (
            $request->has('travel_route') && // optional
            (
                !is_string($request->get('travel_route')) ||
                strlen($request->get('travel_route') > 255)
            )
        ) {
            throw new Exception('Invalid value for parameter: travel_route');
        }

        if (!$request->has('home_isolated')) {
            throw new Exception('Missing required parameter: home_isolated');
        }

        if (false === (bool)$request->get('home_isolated', false)) {
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

                if (!is_string($isolationAddress['city']) || strlen($isolationAddress['city']) > 64) {
                    throw new Exception('Invalid value for parameter: isolation_addresses|city');
                }

                if (empty($isolationAddress['county'])) {
                    throw new Exception('Missing required parameter: isolation_addresses|county');
                }

                if (!is_string($isolationAddress['county']) || strlen($isolationAddress['county']) > 64) {
                    throw new Exception('Invalid value for parameter: isolation_addresses|county');
                }

                if (!empty($isolationAddress['street'])) {
                    if (strlen($isolationAddress['street']) > 64) {
                        throw new Exception('Invalid value for parameter: isolation_addresses|street');
                    }
                }

                if (!empty($isolationAddress['number'])) {
                    if (strlen($isolationAddress['number']) > 16) {
                        throw new Exception('Invalid value for parameter: isolation_addresses|number');
                    }
                }

                if (!empty($isolationAddress['bloc'])) {
                    if (strlen($isolationAddress['bloc']) > 16) {
                        throw new Exception('Invalid value for parameter: isolation_addresses|bloc');
                    }
                }

                if (!empty($isolationAddress['entry'])) {
                    if (strlen($isolationAddress['entry']) > 16) {
                        throw new Exception('Invalid value for parameter: isolation_addresses|entry');
                    }
                }

                if (!empty($isolationAddress['apartment'])) {
                    if (strlen($isolationAddress['apartment']) > 16) {
                        throw new Exception('Invalid value for parameter: isolation_addresses|apartment');
                    }
                }
            }
        }

        if ($request->has('q_visited')) {
            if (!in_array($request->get('q_visited'), [0, 1, true, false, '0', '1', 'true', 'false'])) {
                throw new Exception('Invalid value for parameter: q_visited');
            }
        }

        if ($request->has('q_contacted')) {
            if (!in_array($request->get('q_contacted'), [0, 1, true, false, '0', '1', 'true', 'false'])) {
                throw new Exception('Invalid value for parameter: q_contacted');
            }
        }

        if ($request->has('q_hospitalized')) {
            if (!in_array($request->get('q_hospitalized'), [0, 1, true, false, '0', '1', 'true', 'false'])) {
                throw new Exception('Invalid value for parameter: q_hospitalized');
            }
        }

        /**
         * Validate symptoms
         */
        if ($request->has('symptoms')) {
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
        }

        /**
         * Validate Itinerary Countries
         */
        if ($request->has('itinerary_countries')) {
            /** @var string $itineraryCountry */
            foreach ($request->get('itinerary_countries') as $itineraryCountry) {
                if (!is_string($itineraryCountry) || 2 !== strlen($itineraryCountry)) {
                    throw new Exception('Invalid value for parameter: itinerary_countries');
                }
            }

        }

        if (
            $request->has('vehicle_type') && // optional
            !in_array($request->get('vehicle_type'), [User::VEHICLE_TYPE_AUTO, User::VEHICLE_TYPE_AMBULANCE])
        ) {
            throw new Exception('Invalid value for parameter: vehicle_type');
        }

        if (
            $request->has('vehicle_registration_no') && // optional
            (
                !is_string($request->get('vehicle_registration_no')) ||
                strlen($request->get('vehicle_registration_no')) > 16
            )
        ) {
            throw new Exception('Invalid value for parameter: vehicle_registration_no');
        }

        /**
         * Validate accepted terms
         */
        if (empty($request->get('accept_personal_data'))) {
            throw new Exception('Missing required parameter: accept_personal_data');
        } else if (!is_bool($request->get('accept_personal_data'))) {
            throw new Exception('Invalid value for parameter: accept_personal_data');
        }

        if (empty($request->get('accept_read_law'))) {
            throw new Exception('Missing required parameter: accept_read_law');
        } else if (!is_bool($request->get('accept_read_law'))) {
            throw new Exception('Invalid value for parameter: accept_read_law');
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
                'message' => 'Not Found'
            ], 404);
        }

        if (empty($declaration->declarationsignature)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not Found'
            ], 404);
        }

        $responseData['status'] = 'success';
        $responseData['message'] = 'Declaration details';
        $responseData['signature'] = $declaration->declarationsignature->image;
        return response()->json($responseData);
    }

    /**
     * @param $declarationCode
     * @param Request $request
     * @return JsonResponse
     */
    public function updateDeclaration($declarationCode, Request $request)
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
                'message' => 'Not Found'
            ], 404);
        }

        /**
         * Validate input
         */
        try {
            $this->validateUpdateDeclarationRequest($request);
        } catch (Exception $validationException) {
            $responseData['status'] = 'error';
            $responseData['message'] = $validationException->getMessage();

            return response()->json($responseData, 400);
        }

        /**
         * Validate Declaration status
         */
        if (
            false && // TODO: disabled for the moment for testing purposes (discussed with Lucian)
            (
                (
                    $request->has('is_dsp_before_border') &&
                    true === (bool)$request->get('is_dsp_before_border', false)
                ) ||
                Declaration::STATUS_DSP_VALIDATED === $declaration->status
            )
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid declaration status',
                'details' => 'Declaration was already validated by DSP'
            ], 409);
        }

        if (
            false && // TODO: disabled for the moment for testing purposes (discussed with Lucian)
            (
                (
                    $request->has('is_dsp_before_border') &&
                    true === (bool)$request->get('is_dsp_before_border', false)
                ) ||
                Declaration::STATUS_BORDER_VALIDATED !== $declaration->status
            )
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid declaration status',
                'details' => 'Only the Declarations validated at the Border can be validated by DSP'
            ], 409);
        }

        $declaration->status = Declaration::STATUS_DSP_VALIDATED;
        $declaration->dsp_user_name = $request->get('dsp_user_name');
        $declaration->dsp_measure = $request->get('dsp_measure');
        $declaration->dsp_validated_at = Carbon::now();
        $declaration->save();

        $responseData['status'] = 'success';
        $responseData['message'] = 'Declaration details';
        $responseData['declaration'] = $declaration->toArray();
        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    private function validateUpdateDeclarationRequest(Request $request)
    {
        if (!$request->has('dsp_user_name')) {
            throw new Exception('Missing required parameter: dsp_user_name');
        }

        if (empty($request->get('dsp_user_name')) || strlen($request->get('dsp_user_name')) > 64) {
            throw new Exception('Invalid value for parameter: dsp_user_name');
        }

        if (!$request->has('dsp_measure')) {
            throw new Exception('Missing required parameter: dsp_measure');
        }

        if (empty($request->get('dsp_measure')) || strlen($request->get('dsp_measure')) > 255) {
            throw new Exception('Invalid value for parameter: dsp_measure');
        }
    }

    /**
     * @param string $cnp
     * @return JsonResponse
     */
    public function getDeclarationByCnp(string $cnp)
    {
        $responseData = [];

        /** @var Collection|null $declarations */
        $declarations = Declaration::where('cnp', '=', $cnp)
            ->where(function ($query) {
                $query->whereNull('border_viewed_at');
                $query->orWhere('border_viewed_at', '>', (Carbon::now())->subMinutes(5));
            })
            ->orderBy('id', 'desc')
            ->get();

        $declarationList = [];

        /** @var Declaration $declaration */
        foreach ($declarations as $declaration) {
            /**
             * Mark Declaration as being viewed, if needed
             */
            if (empty($declaration->border_viewed_at)) {
                $declaration->border_viewed_at = Carbon::now();
                $declaration->save();
            }

            $declarationList[] = $declaration->toArray();
        }

        $responseData['status'] = 'success';
        $responseData['message'] = 'Declaration list';
        $responseData['declarationList'] = $declarationList;

        return response()->json($responseData);
    }

    /**
     * @param string $declarationCode
     * @return JsonResponse
     */
    public function viewDeclaration(string $declarationCode)
    {
        $responseData = [];

        /** @var Declaration|null $declaration */
        $declaration = Declaration::join('declaration_codes', 'declaration_codes.id', '=', 'declarations.declarationcode_id')
            ->where('declaration_codes.code', $declarationCode)
            ->where(function ($query) {
                $query->whereNull('declarations.border_viewed_at');
                $query->orWhere('declarations.border_viewed_at', '>', (Carbon::now())->subMinutes(5));
            })
            ->select('declarations.*')
            ->first();

        if (empty($declaration)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not Found'
            ], 404);
        }

        /**
         * Mark Declaration as being viewed, if needed
         */
        if (empty($declaration->border_viewed_at)) {
            $declaration->border_viewed_at = Carbon::now();
            $declaration->save();
        }

        $responseData['status'] = 'success';
        $responseData['message'] = 'Declaration details';
        $responseData['declaration'] = $declaration->toArray();
        return response()->json($responseData);
    }

    /**
     * @param string $code
     * @return JsonResponse
     */
    public function searchDeclaration(string $code)
    {
        $responseData = [];
        $responseData['status'] = 'success';
        $responseData['message'] = 'Declaration search result';
        $responseData['declarations'] = [];

        /** @var Collection $declarationList */
        $declarationList = Declaration::join('declaration_codes', 'declaration_codes.id', '=', 'declarations.declarationcode_id')
            ->whereNull('declarations.deleted_at')
            ->where(function ($query) use ($code) {
                $query->where('declaration_codes.code', $code);
                $query->orWhere('declarations.cnp', '=', $code);
            })
            ->select('declarations.*')
            ->get();

        if (!empty($declarationList->count())) {
            /** @var Declaration $declaration */
            foreach ($declarationList as $declaration) {
                /**
                 * Process Declaration
                 */
                $declaration = $this->processDeclaration($declaration);

                $responseData['declarations'][] = $declaration->toArray();
            }
        }

        $responseData['declarations'] = $declarationList;

        return response()->json($responseData);
    }

    /**
     * @param Declaration $declaration
     * @return Declaration
     */
    protected function processDeclaration(Declaration $declaration): Declaration
    {
        // TODO: implement logic @here

        return $declaration;
    }
}
