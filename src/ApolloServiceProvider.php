<?php

namespace IsTianXin\Apollo;

use Illuminate\Support\ServiceProvider;
use IsTianXin\Apollo\Console\Commands\StartApollo;

class ApolloServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //register command
        if ($this->app->runningInConsole()) {
            $this->commands([StartApollo::class]);
        }

        $this->app->singleton(ApolloService::class, function () {
            return new ApolloService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/apollo.php', 'apollo');

        $this->publishes([
            __DIR__ . '/storage/apollo' => storage_path() . '/apollo'
        ], 'apollo');
    }
}
