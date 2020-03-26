<?php

namespace App\Providers;

use App\Sts\SmsClient;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

/**
 * Class StsSmsServiceProvider
 * @package App\Providers
 */
class StsSmsServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton('stsSms', function () {
            $headers = [];
            $headers['User-Agent'] = env('APP_NAME');

            $config = [];
            $config['connect_timeout'] = 3;
            $config['timeout'] = 5;
            $config['verify'] = true;
            $config['headers'] = $headers;

            return new SmsClient(
                new Client($config),
                env('SMS_WEBAPI_USERNAME'),
                env('SMS_WEBAPI_PASSWORD')
            );
        });
    }

    /**
     * @return void
     */
    public function boot()
    {

    }
}