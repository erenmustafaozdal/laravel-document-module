<?php

namespace ErenMustafaOzdal\LaravelDocumentModule;

use Illuminate\Support\ServiceProvider;

class LaravelDocumentModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../config/laravel-document-module.php' => config_path('laravel-document-module.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('ErenMustafaOzdal\LaravelModulesBase\LaravelModulesBaseServiceProvider');
        $this->app->register('Baum\Providers\BaumServiceProvider');

        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-document-module.php', 'laravel-document-module'
        );

        $router = $this->app['router'];
        // model binding
        $router->model(config('laravel-document-module.url.document'),  'App\Document');
        $router->model(config('laravel-document-module.url.document_category'),  'App\DocumentCategory');
    }
}
