<?php
//max level nested function 100 hatasını düzeltiyor
ini_set('xdebug.max_nesting_level', 300);

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
/*==========  Document Category Module  ==========*/
Route::group([
    'prefix' => config('laravel-document-module.url.admin_url_prefix'),
    'middleware' => config('laravel-document-module.url.middleware'),
    'namespace' => config('laravel-document-module.controller.document_category_admin_namespace')
], function()
{
    if (config('laravel-document-module.routes.admin.document_category')) {
        Route::resource(config('laravel-document-module.url.document_category'), config('laravel-document-module.controller.document_category'), [
            'names' => [
                'index'         => 'admin.document_category.index',
                'create'        => 'admin.document_category.create',
                'store'         => 'admin.document_category.store',
                'show'          => 'admin.document_category.show',
                'edit'          => 'admin.document_category.edit',
                'update'        => 'admin.document_category.update',
                'destroy'       => 'admin.document_category.destroy',
            ]
        ]);
    }

    // category categories
    if (config('laravel-document-module.routes.admin.category_categories')) {
        Route::group(['middleware' => 'nested_model:DocumentCategory'], function() {
            Route::resource(config('laravel-document-module.url.document_category') . '/{id}/' . config('laravel-document-module.url.document_category'), config('laravel-document-module.controller.document_category'), [
                'names' => [
                    'index' => 'admin.document_category.document_category.index',
                    'create' => 'admin.document_category.document_category.create',
                    'store' => 'admin.document_category.document_category.store',
                    'show' => 'admin.document_category.document_category.show',
                    'edit' => 'admin.document_category.document_category.edit',
                    'update' => 'admin.document_category.document_category.update',
                    'destroy' => 'admin.document_category.document_category.destroy',
                ]
            ]);
        });
    }
});

/*==========  Document Module  ==========*/
Route::group([
    'prefix'        => config('laravel-document-module.url.admin_url_prefix'),
    'middleware'    => config('laravel-document-module.url.middleware'),
    'namespace'     => config('laravel-document-module.controller.document_admin_namespace')
], function()
{
    // admin publish document
    if (config('laravel-document-module.routes.admin.document_publish')) {
        Route::get('document/{' . config('laravel-document-module.url.document') . '}/publish', [
            'as'                => 'admin.document.publish',
            'uses'              => config('laravel-document-module.controller.document').'@publish'
        ]);
    }
    // admin not publish document
    if (config('laravel-document-module.routes.admin.document_notPublish')) {
        Route::get('document/{' . config('laravel-document-module.url.document') . '}/not-publish', [
            'as'                => 'admin.document.notPublish',
            'uses'              => config('laravel-document-module.controller.document').'@notPublish'
        ]);
    }
    if (config('laravel-document-module.routes.admin.document')) {
        Route::resource(config('laravel-document-module.url.document'), config('laravel-document-module.controller.document'), [
            'names' => [
                'index'         => 'admin.document.index',
                'create'        => 'admin.document.create',
                'store'         => 'admin.document.store',
                'show'          => 'admin.document.show',
                'edit'          => 'admin.document.edit',
                'update'        => 'admin.document.update',
                'destroy'       => 'admin.document.destroy',
            ]
        ]);
    }

    /*==========  Category documents  ==========*/
    // admin publish document
    if (config('laravel-document-module.routes.admin.category_documents_publish')) {
        Route::get(config('laravel-document-module.url.document_category') . '/{id}/' . config('laravel-document-module.url.document') . '/{' . config('laravel-document-module.url.document') . '}/publish', [
            'middleware'        => 'related_model:DocumentCategory,documents',
            'as'                => 'admin.document_category.document.publish',
            'uses'              => config('laravel-document-module.controller.document').'@publish'
        ]);
    }
    // admin not publish document
    if (config('laravel-document-module.routes.admin.category_documents_notPublish')) {
        Route::get(config('laravel-document-module.url.document_category') . '/{id}/' . config('laravel-document-module.url.document') . '/{' . config('laravel-document-module.url.document') . '}/not-publish', [
            'middleware'        => 'related_model:DocumentCategory,documents',
            'as'                => 'admin.document_category.document.notPublish',
            'uses'              => config('laravel-document-module.controller.document').'@notPublish'
        ]);
    }

    // category documents
    if (config('laravel-document-module.routes.admin.category_documents')) {
        Route::group(['middleware' => 'related_model:DocumentCategory,documents'], function() {
            Route::resource(config('laravel-document-module.url.document_category') . '/{id}/' . config('laravel-document-module.url.document'), config('laravel-document-module.controller.document'), [
                'names' => [
                    'index' => 'admin.document_category.document.index',
                    'create' => 'admin.document_category.document.create',
                    'store' => 'admin.document_category.document.store',
                    'show' => 'admin.document_category.document.show',
                    'edit' => 'admin.document_category.document.edit',
                    'update' => 'admin.document_category.document.update',
                    'destroy' => 'admin.document_category.document.destroy',
                ]
            ]);
        });
    }
});



/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
*/
/*==========  Document Category Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-document-module.url.middleware'),
    'namespace'     => config('laravel-document-module.controller.document_category_api_namespace')
], function()
{
    // api document category
    if (config('laravel-document-module.routes.api.document_category_models')) {
        Route::post('document-category/models', [
            'as'                => 'api.document_category.models',
            'uses'              => config('laravel-document-module.controller.document_category_api').'@models'
        ]);
    }
    // api document category move
    if (config('laravel-document-module.routes.api.document_category_move')) {
        Route::post('document-category/{id}/move', [
            'as'                => 'api.document_category.move',
            'uses'              => config('laravel-document-module.controller.document_category_api').'@move'
        ]);
    }
    // data table detail row
    if (config('laravel-document-module.routes.api.document_category_detail')) {
        Route::get('document-category/{id}/detail', [
            'as'                => 'api.document_category.detail',
            'uses'              => config('laravel-document-module.controller.document_category_api').'@detail'
        ]);
    }
    // document category resource
    if (config('laravel-document-module.routes.api.document_category')) {
        Route::resource(config('laravel-document-module.url.document_category'), config('laravel-document-module.controller.document_category_api'), [
            'names' => [
                'index'         => 'api.document_category.index',
                'store'         => 'api.document_category.store',
                'update'        => 'api.document_category.update',
                'destroy'       => 'api.document_category.destroy',
            ]
        ]);
    }

    // category categories
    if (config('laravel-document-module.routes.api.category_categories_index')) {
        Route::get(config('laravel-document-module.url.document_category') . '/{id}/' . config('laravel-document-module.url.document_category'), [
            'middleware'        => 'nested_model:DocumentCategory',
            'as'                => 'api.document_category.document_category.index',
            'uses'              => config('laravel-document-module.controller.document_category_api').'@index'
        ]);
    }
});

/*==========  Document Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-document-module.url.middleware'),
    'namespace'     => config('laravel-document-module.controller.document_api_namespace')
], function()
{
    // api group action
    if (config('laravel-document-module.routes.api.document_group')) {
        Route::post('document/group-action', [
            'as'                => 'api.document.group',
            'uses'              => config('laravel-document-module.controller.document_api').'@group'
        ]);
    }
    // data table detail row
    if (config('laravel-document-module.routes.api.document_detail')) {
        Route::get('document/{id}/detail', [
            'as'                => 'api.document.detail',
            'uses'              => config('laravel-document-module.controller.document_api').'@detail'
        ]);
    }
    // get document category edit data for modal edit
    if (config('laravel-document-module.routes.api.document_fastEdit')) {
        Route::post('document/{id}/fast-edit', [
            'as'                => 'api.document.fastEdit',
            'uses'              => config('laravel-document-module.controller.document_api').'@fastEdit'
        ]);
    }
    // api publish document
    if (config('laravel-document-module.routes.api.document_publish')) {
        Route::post('document/{' . config('laravel-document-module.url.document') . '}/publish', [
            'as'                => 'api.document.publish',
            'uses'              => config('laravel-document-module.controller.document_api').'@publish'
        ]);
    }
    // api not publish document
    if (config('laravel-document-module.routes.api.document_notPublish')) {
        Route::post('document/{' . config('laravel-document-module.url.document') . '}/not-publish', [
            'as'                => 'api.document.notPublish',
            'uses'              => config('laravel-document-module.controller.document_api').'@notPublish'
        ]);
    }
    if (config('laravel-document-module.routes.api.document')) {
        Route::resource(config('laravel-document-module.url.document'), config('laravel-document-module.controller.document_api'), [
            'names' => [
                'index'         => 'api.document.index',
                'store'         => 'api.document.store',
                'update'        => 'api.document.update',
                'destroy'       => 'api.document.destroy',
            ]
        ]);
    }
    // category documents
    if (config('laravel-document-module.routes.api.category_documents_index')) {
        Route::get(config('laravel-document-module.url.document_category') . '/{id}/' . config('laravel-document-module.url.document'), [
            'middleware'        => 'related_model:DocumentCategory,documents',
            'as'                => 'api.document_category.document.index',
            'uses'              => config('laravel-document-module.controller.document_api').'@index'
        ]);
    }
});


// download document
Route::group([
    'namespace'     => config('laravel-document-module.controller.document_admin_namespace')
], function() {
    Route::get('belge/{' . config('laravel-document-module.url.document') . '}', [
        'as' => 'download.document',
        'uses' => config('laravel-document-module.controller.document') . '@download'
    ]);
});
