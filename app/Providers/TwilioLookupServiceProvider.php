<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client;

/**
 * Class TwilioLookupServiceProvider
 * @package App\Providers
 */
class TwilioLookupServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton('twilioLookups', function () {
            $twilioRestClient = new Client(
                env('TWILIO_ACCOUNT_SID'),
                env('TWILIO_AUTH_TOKEN')
            );

            return $twilioRestClient->lookups;
        });
    }

    /**
     * @return void
     */
    public function boot()
    {

    }
}
