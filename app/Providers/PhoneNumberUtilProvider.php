<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use libphonenumber\PhoneNumberUtil;

/**
 * Class PhoneNumberUtilProvider
 * @package App\Providers
 */
class PhoneNumberUtilProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton('libPhoneNumber', function () {
            return PhoneNumberUtil::getInstance();
        });
    }

    /**
     * @return void
     */
    public function boot()
    {

    }
}
