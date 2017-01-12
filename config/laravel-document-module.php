<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General config
    |--------------------------------------------------------------------------
    */
    'date_format'                   => 'd.m.Y H:i:s',
    'icons' => [
        'document'                  => 'icon-doc',
        'document_category'         => 'icon-doc'
    ],

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
            'document_category_detail'      => true,                // api document category detail post route
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
                'relation'              => false,
                'relation_model'        => null,
                'type'                  => 'file',
                'column'                => 'document',
                'path'                  => 'uploads/document',
                'max_size'              => '5120',
                'mimes'                 => 'conf,css,csv,def,doc,docm,docx,dot,dotm,gif,htm,html,in,jpe,jpeg,jpg,json,list,log,mpp,mpt,odb,odc,odf,odft,odg,odi,odm,odp,ods,odt,otc,otg,oth,oti,otp,ots,ott,pdf,png,pot,potm,ppam,pps,ppsm,ppt,pptm,psd,sldm,text,thmx,txt,xla,xlam,xlc,xlm,xls,xlsb,xlsm,xlsx,xlt,xltm,xlw,xml,xsl',
            ],
            // document photo options
            'photo' => [
                'relation'              => 'hasOne',
                'relation_model'        => '\App\DocumentPhoto',
                'type'                  => 'image',
                'column'                => 'photo.photo',
                'path'                  => 'uploads/document',
                'max_size'              => '5120',
                'mimes'                 => 'jpeg,jpg,jpe,png',
                'aspect_ratio'          => 16/9,
                'thumbnails' => [
                    'small'             => [ 'width' => 35, 'height' => 35],
                    'normal'            => [ 'width' => 300, 'height' => 300],
                    'big'               => [ 'width' => 800, 'height' => 800],
                ]
            ]
        ]
    ],






    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'document_category' => [
            'title'                 => 'Belge Kategorileri',
            'routes' => [
                'admin.document_category.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar belge kategorileri veri tablosu sayfasına gidebilir.',
                ],
                'admin.document_category.create' => [
                    'title'         => 'Ekleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar belge kategorisi ekleme sayfasına gidebilir',
                ],
                'admin.document_category.store' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar belge kategorisi ekleyebilir',
                ],
                'admin.document_category.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar belge kategorisi bilgilerini görüntüleyebilir',
                ],
                'admin.document_category.edit' => [
                    'title'         => 'Düzenleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar belge kategorisini düzenleme sayfasına gidebilir',
                ],
                'admin.document_category.update' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar belge kategorisini düzenleyebilir',
                ],
                'admin.document_category.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar belge kategorisini silebilir',
                ],
                'api.document_category.index' => [
                    'title'         => 'Listeleme',
                    'description'   => 'Bu izne sahip olanlar belge kategorilerini veri tablosunda listeleyebilir',
                ],
                'api.document_category.store' => [
                    'title'         => 'Hızlı Ekleme',
                    'description'   => 'Bu izne sahip olanlar belge kategorilerini veri tablosunda hızlı ekleyebilir.',
                ],
                'api.document_category.update' => [
                    'title'         => 'Hızlı Düzenleme',
                    'description'   => 'Bu izne sahip olanlar belge kategorilerini veri tablosunda hızlı düzenleyebilir.',
                ],
                'api.document_category.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar belge kategorilerini veri tablosunda silebilir',
                ],
                'api.document_category.models' => [
                    'title'         => 'Seçim İçin Listeleme',
                    'description'   => 'Bu izne sahip olanlar belge kategorilerini bazı seçim kutularında listeleyebilir',
                ],
                'api.document_category.move' => [
                    'title'         => 'Taşıma',
                    'description'   => 'Bu izne sahip olanlar belge kategorilerini taşıyarak yerini değiştirebilir.',
                ],
                'api.document_category.detail' => [
                    'title'         => 'Detaylar',
                    'description'   => 'Bu izne sahip olanlar belge kategorilerinin detay bilgilerini getirebilir.',
                ],
            ],
        ],
        'document' => [
            'title'                 => 'Belgeler',
            'routes' => [
                'admin.document.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar belgeler veri tablosu sayfasına gidebilir.',
                ],
                'admin.document.create' => [
                    'title'         => 'Ekleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar belge ekleme sayfasına gidebilir',
                ],
                'admin.document.store' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar belge ekleyebilir',
                ],
                'admin.document.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar belge bilgilerini görüntüleyebilir',
                ],
                'admin.document.edit' => [
                    'title'         => 'Düzenleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar belgeyi düzenleme sayfasına gidebilir',
                ],
                'admin.document.update' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar belgeyi düzenleyebilir',
                ],
                'admin.document.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar belgeyi silebilir',
                ],
                'admin.document.publish' => [
                    'title'         => 'Yayınlama',
                    'description'   => 'Bu izne sahip olanlar belgeyi yayınlayabilir',
                ],
                'admin.document.notPublish' => [
                    'title'         => 'Yayından Kaldırma',
                    'description'   => 'Bu izne sahip olanlar belgeyi yayından kaldırabilir',
                ],
                'api.document.index' => [
                    'title'         => 'Listeleme',
                    'description'   => 'Bu izne sahip olanlar belgeleri veri tablosunda listeleyebilir',
                ],
                'api.document.store' => [
                    'title'         => 'Hızlı Ekleme',
                    'description'   => 'Bu izne sahip olanlar belgeleri veri tablosunda hızlı ekleyebilir.',
                ],
                'api.document.update' => [
                    'title'         => 'Hızlı Düzenleme',
                    'description'   => 'Bu izne sahip olanlar belgeleri veri tablosunda hızlı düzenleyebilir.',
                ],
                'api.document.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar belgeleri veri tablosunda silebilir',
                ],
                'api.document.group' => [
                    'title'         => 'Toplu İşlem',
                    'description'   => 'Bu izne sahip olanlar belgeler veri tablosunda toplu işlem yapabilir',
                ],
                'api.document.detail' => [
                    'title'         => 'Detaylar',
                    'description'   => 'Bu izne sahip olanlar belgeler tablosunda detayını görebilir.',
                ],
                'api.document.fastEdit' => [
                    'title'         => 'Hızlı Düzenleme Bilgileri',
                    'description'   => 'Bu izne sahip olanlar belgeler tablosunda hızlı düzenleme amacıyla bilgileri getirebilir.',
                ],
                'api.document.publish' => [
                    'title'         => 'Hızlı Yayınlama',
                    'description'   => 'Bu izne sahip olanlar belgeler tablosunda belgeyi yayınlanyabilir.',
                ],
                'api.document.notPublish' => [
                    'title'         => 'Hızlı Yayından Kaldırma',
                    'description'   => 'Bu izne sahip olanlar belgeler tablosunda belgeyi yayından kaldırabilir.',
                ],
            ],
        ],
    ],
];
