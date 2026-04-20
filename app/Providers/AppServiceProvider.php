<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('LaravelLocalization', \Mcamara\LaravelLocalization\Facades\LaravelLocalization::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register middleware aliases for laravel-localization
        $router = $this->app['router'];
        $router->aliasMiddleware('localize',                \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class);
        $router->aliasMiddleware('localeSessionRedirect',   \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class);
        $router->aliasMiddleware('localeViewPath',          \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class);
        $router->aliasMiddleware('localizationRedirect',    \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class);
        $router->aliasMiddleware('localeCookieRedirect',    \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class);
    }
}
