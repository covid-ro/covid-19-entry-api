<?php

namespace App\Http\Controllers;

use App\EmailCode;
use App\Mail\EmailValidationCode;
use App\Service\CodeGenerator;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * @param Request $request
     * @throws Exception
     */
    private function validateEmailRequest(Request $request)
    {
        if (empty($request->get('email'))) { // required
            throw new Exception('Missing required parameter: email');
        }

        if (!empty($request->get('email')) &&
            !filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) { // required & valid
            throw new Exception('Invalid value for parameter: email');
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
    public function validateEmail(Request $request)
    {
        $responseData = [];

        /**
         * Validate user input
         */
        try {
            $this->validateEmailRequest($request);
        } catch (Exception $validationException) {
            $responseData['status'] = 'error';
            $responseData['message'] = $validationException->getMessage();

            return response()->json($responseData, 400);
        }

        /**
         * Throttle check
         */
        $throttleCheck = EmailCode::where('email', '=', $request->get('email'))
            ->where('created_at', '>=', (Carbon::now())->subMinute())
            ->where('status', '=', EmailCode::STATUS_ACTIVE)
            ->count();

        if (!empty($throttleCheck)) {
            $responseData['status'] = 'error';
            $responseData['message'] = 'Too Many Requests';
            $responseData['details'] = 'A message was already sent to this email in the last minute. Try again later.';

            return response()->json($responseData, 429);
        }

        try {
            DB::beginTransaction(); // we should make sure the code is not saved if it can't be sent
            $emailCode = new EmailCode();
            $emailCode->code = (new CodeGenerator())->generateSmsCode();
            $emailCode->email = $request->get('email');
            $emailCode->phone_identifier = $request->get('phone_identifier', '');
            $emailCode->status = EmailCode::STATUS_ACTIVE;
            $emailCode->save();

// TODO: Make sure SMTP is set
            Mail::to($request->get('email'))->send(new EmailValidationCode($emailCode->code));
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            $responseData['status'] = 'error';
            $responseData['message'] = $exception->getMessage();
            return response()->json($responseData, 400);
        }


        $responseData['status'] = 'success';
        $responseData['message'] = 'Code sent to email address.';

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function checkEmail(Request $request)
    {
        $responseData = [];

        // TODO

        return response()->json($responseData);
    }
}
