<?php

return [
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
    | Models config
    |--------------------------------------------------------------------------
    |
    | ## Options
    |
    | - default_img_path                : model default avatar or photo
    |
    | --- uploads                       : model uploads options
    | - relation                        : file is in the relation table and what is relation type [false|hasOne|hasMany]
    | - relation_model                  : relation model [\App\Model etc...]
    | - type                            : file type [image,file]
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
                'relation'              => false,
                'relation_model'        => null,
                'type'                  => 'file',
                'column'                => 'document',
            ],
            // document photo options
            'photo' => [
                'relation'              => 'hasOne',
                'relation_model'        => '\App\DocumentPhoto',
                'type'                  => 'image',
                'column'                => 'photo.photo',
            ]
        ]
    ],
];
