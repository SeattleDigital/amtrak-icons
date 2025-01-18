<?php

declare(strict_types=1);

namespace AmtrakUI\Materialicons;

use AmtrakUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class AmtrakMaterialiconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('amtrak-materialicons', []);

            $factory->add('materialicons', array_merge(['path' => __DIR__ . '/../resources/svg'], $config));
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/amtrak-materialicons.php', 'amtrak-materialicons');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/svg' => public_path('vendor/amtrak-materialicons'),
            ], 'amtrak-materialicons');

            $this->publishes([
                __DIR__ . '/../config/amtrak-materialicons.php' => $this->app->configPath('amtrak-materialicons.php'),
            ], 'amtrak-materialicons-config');
        }
    }
}
