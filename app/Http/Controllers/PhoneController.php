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
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

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
            /** @var PhoneNumberUtil $phoneNumberUtil */
            $phoneNumberUtil = app('libPhoneNumber');

            $phone = $phoneNumberUtil->parse(
                $request->get('phone'),
                $this->getRegionByCountryCode($request->get('phone_country_prefix'))
            );

            if (!$phoneNumberUtil->isValidNumber($phone)) {
                throw new NumberParseException(NumberParseException::NOT_A_NUMBER, 'Invalid phone number');
            }

            /**
             * Store formatted phone number and country code
             */
            $phoneCode->country_code = $phone->getCountryCode();
            $phoneCode->formatted_phone_number = $phone->getNationalNumber();
        } catch (NumberParseException $numberParseException) {
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

    /**
     * @param int $countryCode
     * @return string
     */
    private function getRegionByCountryCode(int $countryCode): string
    {
        $matrix = [];

        $matrix['CA'] = 1;
        $matrix['PR'] = 1;
        $matrix['US'] = 1;
        $matrix['UM'] = 1;
        $matrix['KZ'] = 7;
        $matrix['RU'] = 7;
        $matrix['EG'] = 20;
        $matrix['ZA'] = 27;
        $matrix['GR'] = 30;
        $matrix['NL'] = 31;
        $matrix['BE'] = 32;
        $matrix['FR'] = 33;
        $matrix['ES'] = 34;
        $matrix['HU'] = 36;
        $matrix['IT'] = 39;
        $matrix['RO'] = 40;
        $matrix['CH'] = 41;
        $matrix['AT'] = 43;
        $matrix['GG'] = 44;
        $matrix['IM'] = 44;
        $matrix['JE'] = 44;
        $matrix['GB'] = 44;
        $matrix['DK'] = 45;
        $matrix['SE'] = 46;
        $matrix['BV'] = 47;
        $matrix['NO'] = 47;
        $matrix['SJ'] = 47;
        $matrix['PL'] = 48;
        $matrix['DE'] = 49;
        $matrix['PE'] = 51;
        $matrix['MX'] = 52;
        $matrix['CU'] = 53;
        $matrix['AR'] = 54;
        $matrix['BR'] = 55;
        $matrix['CL'] = 56;
        $matrix['CO'] = 57;
        $matrix['VE'] = 58;
        $matrix['MY'] = 60;
        $matrix['AU'] = 61;
        $matrix['CX'] = 61;
        $matrix['CC'] = 61;
        $matrix['ID'] = 62;
        $matrix['PH'] = 63;
        $matrix['NZ'] = 64;
        $matrix['SG'] = 65;
        $matrix['TH'] = 66;
        $matrix['JP'] = 81;
        $matrix['KR'] = 82;
        $matrix['VN'] = 84;
        $matrix['CN'] = 86;
        $matrix['TR'] = 90;
        $matrix['IN'] = 91;
        $matrix['PK'] = 92;
        $matrix['AF'] = 93;
        $matrix['LK'] = 94;
        $matrix['MM'] = 95;
        $matrix['IR'] = 98;
        $matrix['SS'] = 211;
        $matrix['MA'] = 212;
        $matrix['EH'] = 212;
        $matrix['DZ'] = 213;
        $matrix['TN'] = 216;
        $matrix['LY'] = 218;
        $matrix['GM'] = 220;
        $matrix['SN'] = 221;
        $matrix['MR'] = 222;
        $matrix['ML'] = 223;
        $matrix['GN'] = 224;
        $matrix['CI'] = 225;
        $matrix['BF'] = 226;
        $matrix['NE'] = 227;
        $matrix['TG'] = 228;
        $matrix['BJ'] = 229;
        $matrix['MU'] = 230;
        $matrix['LR'] = 231;
        $matrix['SL'] = 232;
        $matrix['GH'] = 233;
        $matrix['NG'] = 234;
        $matrix['TD'] = 235;
        $matrix['CF'] = 236;
        $matrix['CM'] = 237;
        $matrix['CV'] = 238;
        $matrix['ST'] = 239;
        $matrix['GQ'] = 240;
        $matrix['GA'] = 241;
        $matrix['CG'] = 242;
        $matrix['CD'] = 243;
        $matrix['AO'] = 244;
        $matrix['GW'] = 245;
        $matrix['IO'] = 246;
        $matrix['SC'] = 248;
        $matrix['SD'] = 249;
        $matrix['RW'] = 250;
        $matrix['ET'] = 251;
        $matrix['SO'] = 252;
        $matrix['DJ'] = 253;
        $matrix['KE'] = 254;
        $matrix['TZ'] = 255;
        $matrix['UG'] = 256;
        $matrix['BI'] = 257;
        $matrix['MZ'] = 258;
        $matrix['ZM'] = 260;
        $matrix['MG'] = 261;
        $matrix['TF'] = 262;
        $matrix['YT'] = 262;
        $matrix['RE'] = 262;
        $matrix['ZW'] = 263;
        $matrix['NA'] = 264;
        $matrix['MW'] = 265;
        $matrix['LS'] = 266;
        $matrix['BW'] = 267;
        $matrix['SZ'] = 268;
        $matrix['KM'] = 269;
        $matrix['SH'] = 290;
        $matrix['ER'] = 291;
        $matrix['AW'] = 297;
        $matrix['FO'] = 298;
        $matrix['GL'] = 299;
        $matrix['GI'] = 350;
        $matrix['PT'] = 351;
        $matrix['LU'] = 352;
        $matrix['IE'] = 353;
        $matrix['IS'] = 354;
        $matrix['AL'] = 355;
        $matrix['MT'] = 356;
        $matrix['CY'] = 357;
        $matrix['FI'] = 358;
        $matrix['BG'] = 359;
        $matrix['LT'] = 370;
        $matrix['LV'] = 371;
        $matrix['EE'] = 372;
        $matrix['MD'] = 373;
        $matrix['AM'] = 374;
        $matrix['BY'] = 375;
        $matrix['AD'] = 376;
        $matrix['MC'] = 377;
        $matrix['SM'] = 378;
        $matrix['VA'] = 379;
        $matrix['UA'] = 380;
        $matrix['RS'] = 381;
        $matrix['ME'] = 382;
        $matrix['HR'] = 385;
        $matrix['SI'] = 386;
        $matrix['BA'] = 387;
        $matrix['MK'] = 389;
        $matrix['CZ'] = 420;
        $matrix['SK'] = 421;
        $matrix['LI'] = 423;
        $matrix['FK'] = 500;
        $matrix['GS'] = 500;
        $matrix['BZ'] = 501;
        $matrix['GT'] = 502;
        $matrix['SV'] = 503;
        $matrix['HN'] = 504;
        $matrix['NI'] = 505;
        $matrix['CR'] = 506;
        $matrix['PA'] = 507;
        $matrix['PM'] = 508;
        $matrix['HT'] = 509;
        $matrix['GP'] = 590;
        $matrix['BL'] = 590;
        $matrix['MF'] = 590;
        $matrix['BO'] = 591;
        $matrix['GY'] = 592;
        $matrix['EC'] = 593;
        $matrix['GF'] = 594;
        $matrix['PY'] = 595;
        $matrix['MQ'] = 596;
        $matrix['SR'] = 597;
        $matrix['UY'] = 598;
        $matrix['BQ'] = 599;
        $matrix['CW'] = 599;
        $matrix['TL'] = 670;
        $matrix['AQ'] = 672;
        $matrix['HM'] = 672;
        $matrix['NF'] = 672;
        $matrix['BN'] = 673;
        $matrix['NR'] = 674;
        $matrix['PG'] = 675;
        $matrix['TO'] = 676;
        $matrix['SB'] = 677;
        $matrix['VU'] = 678;
        $matrix['FJ'] = 679;
        $matrix['PW'] = 680;
        $matrix['WF'] = 681;
        $matrix['CK'] = 682;
        $matrix['NU'] = 683;
        $matrix['WS'] = 685;
        $matrix['KI'] = 686;
        $matrix['NC'] = 687;
        $matrix['TV'] = 688;
        $matrix['PF'] = 689;
        $matrix['TK'] = 690;
        $matrix['FM'] = 691;
        $matrix['MH'] = 692;
        $matrix['KP'] = 850;
        $matrix['HK'] = 852;
        $matrix['MO'] = 853;
        $matrix['KH'] = 855;
        $matrix['LA'] = 856;
        $matrix['PN'] = 870;
        $matrix['BD'] = 880;
        $matrix['TW'] = 886;
        $matrix['MV'] = 960;
        $matrix['LB'] = 961;
        $matrix['JO'] = 962;
        $matrix['SY'] = 963;
        $matrix['IQ'] = 964;
        $matrix['KW'] = 965;
        $matrix['SA'] = 966;
        $matrix['YE'] = 967;
        $matrix['OM'] = 968;
        $matrix['PS'] = 970;
        $matrix['AE'] = 971;
        $matrix['IL'] = 972;
        $matrix['BH'] = 973;
        $matrix['QA'] = 974;
        $matrix['BT'] = 975;
        $matrix['MN'] = 976;
        $matrix['NP'] = 977;
        $matrix['TJ'] = 992;
        $matrix['TM'] = 993;
        $matrix['AZ'] = 994;
        $matrix['GE'] = 995;
        $matrix['KG'] = 996;
        $matrix['UZ'] = 998;

        foreach ($matrix as $key => $value) {
            if ($value == $countryCode) {
                return $key;
            }
        }

        return 'RO';
    }
}
