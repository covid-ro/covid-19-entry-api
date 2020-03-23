<?php

namespace App\Console\Commands;

use App\IsolationAddress;
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
//        $isolationAddress = new IsolationAddress();
//        $isolationAddress->user_id = 1;
//        $isolationAddress->city_full_address = 'Home sweet home';
//        $isolationAddress->city_arrival_date = new \DateTime();
//        $isolationAddress->save();

//        /** @var User $user */
//        $user = User::find(1);
//
//        print_r($user->isolationAddresses()->get());
    }
}
