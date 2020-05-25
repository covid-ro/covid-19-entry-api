<?php

namespace App\Providers;

use App\Service\EvidentaPopulatiei\SearchClient;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

/**
 * Class EvidentaPopulatieiProvider
 * @package App\Providers
 */
class EvidentaPopulatieiProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton('evidentaPopulatiei', function () {
            $headers = [];
            $headers['User-Agent'] = env('APP_NAME');

            $config = [];
            $config['connect_timeout'] = 3;
            $config['timeout'] = 5;
            $config['verify'] = true;
            $config['headers'] = $headers;

            return new SearchClient(
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
