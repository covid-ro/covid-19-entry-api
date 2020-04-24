<?php

namespace App\Providers;

use App\Service\Sts\BorderClient;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

/**
 * Class StsBorderServiceProvider
 * @package App\Providers
 */
class StsBorderServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton('stsBorder', function () {
            $headers = [];
            $headers['User-Agent'] = env('APP_NAME');
            $headers['Authorization'] = (string)env('BORDER_WEBAPI_KEY');

            $config = [];
            $config['connect_timeout'] = 3;
            $config['timeout'] = 5;
            $config['verify'] = true;
            $config['headers'] = $headers;

            return new BorderClient(
                new Client($config)
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
