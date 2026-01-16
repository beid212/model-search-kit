<?php

namespace Beid212\ModelSearchKit\Providers;

use Illuminate\Support\ServiceProvider;

class ModelSearchKitServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/mskit.php', 'mskit'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/mskit.php' => config_path('mskit.php'),
        ]);
    }
}
