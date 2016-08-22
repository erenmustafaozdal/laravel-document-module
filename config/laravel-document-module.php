<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General config
    |--------------------------------------------------------------------------
    */
    'date_format'                   => 'd.m.Y H:i:s',

    /*
    |--------------------------------------------------------------------------
    | URL config
    |--------------------------------------------------------------------------
    */
    'url' => [
        'document_category'         => 'document-categories',   // document categories url
        'document'                  => 'documents',             // documents url
        'admin_url_prefix'          => 'admin',                 // admin dashboard url prefix
        'middleware'                => ['auth', 'permission']   // document module middleware
    ],

    /*
    |--------------------------------------------------------------------------
    | Controller config
    | if you make some changes on controller, you create your controller
    | and then extend the Laravel Document Module Controller. If you don't need
    | change controller, don't touch this config
    |--------------------------------------------------------------------------
    */
    'controller' => [
        'document_category_admin_namespace' => 'ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers',
        'document_admin_namespace'          => 'ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers',
        'document_category_api_namespace'   => 'ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers',
        'document_api_namespace'            => 'ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers',
        'document_category'                 => 'DocumentCategoryController',
        'document'                          => 'DocumentController',
        'document_category_api'             => 'DocumentCategoryApiController',
        'document_api'                      => 'DocumentApiController'
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes on / off
    | if you don't use any route; set false
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'admin' => [
            'document_category'             => true,                // admin document category resource route
            'document'                      => true,                // admin document resource route
            'document_publish'              => true,                // admin document publish get route
            'document_notPublish'           => true,                // admin document not publish get route
            'category_categories'           => true,                // admin category nested categories resource route
            'category_documents'            => true,                // admin category documents resource route
            'category_documents_publish'    => true,                // admin category documents publish get route
            'category_documents_notPublish' => true                 // admin category documents not publish get route
        ],
        'api' => [
            'document_category'             => true,                // api document category resource route
            'document_category_models'      => true,                // api document category model post route
            'document_category_move'        => true,                // api document category move post route
            'document_category_detail'      => true,                // api document category detail get route
            'document'                      => true,                // api document resource route
            'document_group'                => true,                // api document group post route
            'document_detail'               => true,                // api document detail get route
            'document_fastEdit'             => true,                // api document fast edit post route
            'document_publish'              => true,                // api document publish get route
            'document_notPublish'           => true,                // api document not publish get route
            'category_categories_index'     => true,                // api category nested categories index get route
            'category_documents_index'      => true,                // api category documents index get route
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | View config
    |--------------------------------------------------------------------------
    | dot notation of blade view path, its position on the /resources/views directory
    */
    'views' => [
        // document category view
        'document_category' => [
            'layout'                => 'laravel-modules-core::layouts.admin',           // user layout
            'index'                 => 'laravel-modules-core::document_category.index', // get document category index view blade
            'create'                => 'laravel-modules-core::document_category.operation',// get document category create view blade
            'show'                  => 'laravel-modules-core::document_category.show',  // get document category show view blade
            'edit'                  => 'laravel-modules-core::document_category.operation',// get document category edit view blade
        ],
        // document view
        'document' => [
            'layout'                => 'laravel-modules-core::layouts.admin',           // user layout
            'index'                 => 'laravel-modules-core::document.index',          // get document index view blade
            'create'                => 'laravel-modules-core::document.operation',      // get document create view blade
            'show'                  => 'laravel-modules-core::document.show',           // get document show view blade
            'edit'                  => 'laravel-modules-core::document.operation',      // get document edit view blade
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Models config
    |--------------------------------------------------------------------------
    |
    | ## Options
    |
    | - default_img_path                : model default avatar or photo
    |
    | --- uploads                       : model uploads options
    | - type                            : file type [image,file]
    | - number_type                     : file number type [multiple,single]
    | - column                          : file database column
    | - path                            : file path
    | - max_size                        : file allowed maximum size
    | - aspect_ratio                    : if file is image; crop aspect ratio
    | - mimes                           : file allowed mimes
    | - thumbnails                      : if file is image; its thumbnails options
    |
    | NOT: Thumbnails fotoğrafları yüklenirken bakılır:
    |       1. eğer post olarak x1, y1, x2, y2, width ve height değerleri gönderilmemiş ise bu değerlere göre
    |       thumbnails ayarlarında belirtilen resimleri sistem içine kaydeder.
    |       Yani bu değerler post edilmişse aşağıdaki değerleri yok sayar.
    |       2. Eğer yukarıdaki ilgili değerler post edilmemişse, thumbnails ayarlarında belirtilen değerleri
    |       dikkate alarak thumbnails oluşturur
    |
    |       Ölçü Belirtme:
    |       1. İstenen resmin width ve height değerleri verilerek istenen net bir ölçüde resimler oluşturulabilir
    |       2. Width değeri null verilerek, height değerine göre ölçeklenebilir
    |       3. Height değeri null verilerek, width değerine göre ölçeklenebilir
    |--------------------------------------------------------------------------
    */
    'document' => [
        'default_img_path'              => 'vendor/laravel-modules-core/assets/global/img/document',
        'uploads' => [
            // document options
            'file' => [
                'type'                  => 'file',
                'number_type'           => 'single',
                'column'                => 'document',
                'path'                  => 'uploads/document',
                'max_size'              => '5120',
                'mimes'                 => 'doc,docx,docm,dotm,dot,pdf,xls,xlsx,xlm,xla,xlc,xlt,xlw,mpp,mpt,xlam,xlsb,xlsm,xltm,thmx,ppt,pps,pot,ppam,pptm,sldm,ppsm,potm,xml,xsl,jpeg,jpg,jpe,png,gif,psd,css,csv,html,htm,txt,text,conf,def,list,log,in,json,odc,otc,odb,odf,odft,odg,otg,odi,oti,odp,otp,ods,ots,odt,odm,ott,oth'
            ],
            // document photo options
            'photo' => [
                'type'                  => 'image',
                'number_type'           => 'single',
                'column'                => 'photo.photo',
                'path'                  => 'uploads/document',
                'max_size'              => '5120',
                'aspect_ratio'          => 16/9,
                'mimes'                 => 'jpeg,jpg,jpe,png',
                'thumbnails' => [
                    'small'             => [ 'width' => 35, 'height' => 35],
                    'normal'            => [ 'width' => 300, 'height' => 300],
                    'big'               => [ 'width' => 800, 'height' => 800],
                ]
            ]
        ]
    ],
];
