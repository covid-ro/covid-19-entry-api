<?php

namespace App\Console\Commands;

use App\Declaration;
use App\DeclarationCode;
use App\IsolationAddress;
use App\Service\CodeGenerator;
use App\Sts\SmsClient;
use App\Sts\SmsClientException;
use App\User;
use Illuminate\Console\Command;

/**
 * Class TestCommand
 * @package App\Console\Commands
 */
class TestCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'covid:test';

    /**
     * @var string
     */
    protected $description = 'Covid test command';

    /**
     * @return void
     */
    public function handle()
    {
//        /** @var Declaration $declaration */
//        $declaration = Declaration::find(144);
//
//        dd($declaration->bordercheckpoint->name);

//        /** @var SmsClient $smsClient */
//        $smsClient = app('stsSms');

//        try {
//            $smsClient->sendMessage('0729031984', 'Testing SMS implementation');
//            dd('SMS sent');
//        } catch (\Exception $smsClientException) {
//            echo 'Exception caught: ' . $smsClientException->getMessage();
//        }

//        /** @var User $user */
//        $user = User::find(1);
//        dd($user->declarations->first()->getAttributes());

//        /** @var Declaration $declaration */
//        $declaration = Declaration::with('user')->find(1);
//        dd($declaration->getAttributes());
//        dd($declaration->user->getAttributes());
//        dd($declaration->declarationCode->getAttributes());
//        dd($declaration->isolationaddresses->first()->getAttributes());
//        dd($declaration->itinerarycountries->first()->getAttributes());

//        $declarationCode = DeclarationCode::generateDeclarationCode();
//        dd($declarationCode->getAttributes());

//        $isolationAddress = new IsolationAddress();
//        $isolationAddress->user_id = 1;
//        $isolationAddress->city_full_address = 'Home sweet home';
//        $isolationAddress->city_arrival_date = new \DateTime();
//        $isolationAddress->save();

//        /** @var User $user */
//        $user = User::with('isolationAddresses')->with('itineraryCountries')->find(1);
//
//        print_r($user);
    }
}
