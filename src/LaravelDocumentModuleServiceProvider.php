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
        // merge default configs with publish configs
        $this->mergeDefaultConfig();

        $router = $this->app['router'];
        // model binding
        $router->model(config('laravel-document-module.url.document'),  'App\Document');
        $router->model(config('laravel-document-module.url.document_category'),  'App\DocumentCategory');
    }

    /**
     * merge default configs with publish configs
     */
    protected function mergeDefaultConfig()
    {
        $config = $this->app['config']->get('laravel-document-module', []);
        $default = require __DIR__.'/../config/default.php';

        // admin document category routes
        $route = $config['routes']['admin']['document_category'];
        $default['routes']['admin']['document_category'] = $route;
        // admin document routes
        $route = $config['routes']['admin']['document'];
        $default['routes']['admin']['document'] = $route;
        $default['routes']['admin']['document_publish'] = $route;
        $default['routes']['admin']['document_notPublish'] = $route;
        // admin sub document categories nested categories
        $route = $config['routes']['admin']['nested_sub_categories'];
        $default['routes']['admin']['category_categories'] = $route;
        // admin sub document categories documents
        $route = $config['routes']['admin']['sub_category_documents'];
        $default['routes']['admin']['category_documents'] = $route;
        $default['routes']['admin']['category_documents_publish'] = $route;
        $default['routes']['admin']['category_documents_notPublish'] = $route;

        // api document category routes
        $apiCat = $config['routes']['api']['document_category'];
        $default['routes']['api']['document_category'] = $apiCat;
        // api sub document categories nested categories
        $apiSubCat = $config['routes']['api']['nested_sub_categories'];
        $default['routes']['api']['category_categories_index'] = $apiSubCat;

        $default['routes']['api']['document_category_models'] = $apiCat || $apiSubCat;
        $default['routes']['api']['document_category_move'] = $apiCat || $apiSubCat;
        $default['routes']['api']['document_category_detail'] = $apiCat || $apiSubCat;

        // api document routes
        $apiModel = $config['routes']['api']['document'];
        $default['routes']['api']['document'] = $apiModel;
        // api sub document categories documents
        $apiSubModel = $config['routes']['api']['sub_category_documents'];
        $default['routes']['api']['category_documents_index'] = $apiSubModel;

        $default['routes']['api']['document_group'] = $apiModel || $apiSubModel;
        $default['routes']['api']['document_detail'] = $apiModel || $apiSubModel;
        $default['routes']['api']['document_fastEdit'] = $apiModel || $apiSubModel;
        $default['routes']['api']['document_publish'] = $apiModel || $apiSubModel;
        $default['routes']['api']['document_notPublish'] = $apiModel || $apiSubModel;

        $config['routes'] = $default['routes'];


        $path = unsetReturn($config['document']['uploads'],'path');
        $default['document']['uploads']['file']['path'] = $path;
        $default['document']['uploads']['photo']['path'] = $path;
        $max_size = unsetReturn($config['document']['uploads'],'max_size');
        $default['document']['uploads']['file']['max_size'] = $max_size;
        $default['document']['uploads']['photo']['max_size'] = $max_size;
        $default['document']['uploads']['file']['mimes'] = unsetReturn($config['document']['uploads'],'file_mimes');
        $default['document']['uploads']['photo']['aspect_ratio'] = unsetReturn($config['document']['uploads'],'photo_aspect_ratio');
        $default['document']['uploads']['photo']['mimes'] = unsetReturn($config['document']['uploads'],'photo_mimes');
        $default['document']['uploads']['photo']['thumbnails'] = unsetReturn($config['document']['uploads'],'photo_thumbnails');
        $config['document']['uploads']['file'] = $default['document']['uploads']['file'];
        $config['document']['uploads']['photo'] = $default['document']['uploads']['photo'];

        $this->app['config']->set('laravel-document-module', $config);
    }
}
