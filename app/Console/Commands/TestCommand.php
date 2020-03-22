<?php

namespace App\Console\Commands;

use App\PhoneCode;
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
        $this->info('Testing PhoneCode...');

        $phoneCode = new PhoneCode();
        $phoneCode->code = PhoneCode::generateCode();
        $phoneCode->phone_number = '+40729031984';
        $phoneCode->save();

        dd($phoneCode->attributesToArray());
    }
}
