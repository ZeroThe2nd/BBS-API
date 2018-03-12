<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(AuthServiceProvider::class);
        // $this->app->register(EventServiceProvider::class);

        // Production-only providers
        if ($this->app->environment() === 'production') {
            // Nothing here yet
        }

        // Development-only providers
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Wn\Generators\CommandsServiceProvider::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
