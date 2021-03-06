<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Document;
use App\DocumentCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseController;
use ErenMustafaOzdal\LaravelModulesBase\Repositories\FileRepository;
// events
use ErenMustafaOzdal\LaravelDocumentModule\Events\Document\StoreSuccess;
use ErenMustafaOzdal\LaravelDocumentModule\Events\Document\StoreFail;
use ErenMustafaOzdal\LaravelDocumentModule\Events\Document\UpdateSuccess;
use ErenMustafaOzdal\LaravelDocumentModule\Events\Document\UpdateFail;
use ErenMustafaOzdal\LaravelDocumentModule\Events\Document\DestroySuccess;
use ErenMustafaOzdal\LaravelDocumentModule\Events\Document\DestroyFail;
use ErenMustafaOzdal\LaravelDocumentModule\Events\Document\PublishSuccess;
use ErenMustafaOzdal\LaravelDocumentModule\Events\Document\PublishFail;
use ErenMustafaOzdal\LaravelDocumentModule\Events\Document\NotPublishSuccess;
use ErenMustafaOzdal\LaravelDocumentModule\Events\Document\NotPublishFail;
// requests
use ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\Document\ApiStoreRequest;
use ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\Document\ApiUpdateRequest;


class DocumentApiController extends BaseController
{
    /**
     * default urls of the model
     *
     * @var array
     */
    private $urls = [
        'publish'       => ['route' => 'api.document.publish', 'id' => true],
        'not_publish'   => ['route' => 'api.document.notPublish', 'id' => true],
        'edit_page'     => ['route' => 'admin.document.edit', 'id' => true]
    ];

    /**
     * default realtion urls of the model
     *
     * @var array
     */
    private $relationUrls = [
        'edit_page' => [
            'route'     => 'admin.document_category.document.edit',
            'id'        => 0,
            'model'     => ''
        ],
        'show' => [
            'route'     => 'admin.document_category.document.show',
            'id'        => 0,
            'model'     => ''
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request  $request
     * @param integer|null $id
     * @return Datatables
     */
    public function index(Request $request, $id = null)
    {
        // query
        if (is_null($id)) {
            $documents = Document::with('category');
        } else {
            $categories = DocumentCategory::findOrFail($id)->getDescendantsAndSelf()->keyBy('id')->keys();
            $documents = Document::whereIn('category_id',$categories);
        }
        $documents->select(['id','category_id','title','document','size','is_publish','created_at']);

        // if is filter action
        if ($request->has('action') && $request->input('action') === 'filter') {
            $documents->filter($request);
        }

        // urls
        $addUrls = $this->urls;
        if( ! is_null($id)) {
            $this->relationUrls['edit_page']['id'] = $id;
            $this->relationUrls['edit_page']['model'] = config('laravel-document-module.url.document');
            $this->relationUrls['show']['id'] = $id;
            $this->relationUrls['show']['model'] = config('laravel-document-module.url.document');
            $addUrls = array_merge($addUrls, $this->relationUrls);
        }
        $addColumns = [
            'addUrls'           => $addUrls,
            'status'            => function($model) { return $model->is_publish; },
        ];
        $editColumns = [
            'created_at'        => function($model) { return $model->created_at_table; },
            'size'              => function($model) { return $model->size_table; },
            'title'              => function($model) { return $model->title_uc_first; },
            'category.name'   => function($model) { return $model->category->name_uc_first; },
        ];
        $removeColumns = ['is_publish','category_id'];
        return $this->getDatatables($documents, $addColumns, $editColumns, $removeColumns);
    }

    /**
     * get detail
     *
     * @param integer $id
     * @param Request $request
     * @return Datatables
     */
    public function detail($id, Request $request)
    {
        $document = Document::with([
            'category' => function($query)
            {
                return $query->select(['id','name','has_description','has_photo']);
            },
            'description' => function($query)
            {
                return $query->select(['id','document_id','description']);
            },
            'photo' => function($query)
            {
                return $query->select(['id','document_id','photo']);
            }
        ])->where('id',$id)->select(['id','category_id','title','document','size','created_at','updated_at']);

        $editColumns = [
            'size'          => function($model) { return $model->size_table; },
            'title'         => function($model) { return $model->title_uc_first; },
            'created_at'    => function($model) { return $model->created_at_table; },
            'updated_at'    => function($model) { return $model->updated_at_table; },
            'photo.photo'   => function($model) { return !is_null($model->photo) ? $model->photo->getPhoto([], 'big', true, 'document','document_id') : ''; },
            'category.name'   => function($model) { return $model->category->name_uc_first; },
        ];
        return $this->getDatatables($document, [], $editColumns, []);
    }

    /**
     * get model data for edit
     *
     * @param integer $id
     * @param Request $request
     * @return Datatables
     */
    public function fastEdit($id, Request $request)
    {
        return Document::with([
            'category' => function($query)
            {
                return $query->select(['id','name']);
            }
        ])->where('id',$id)->first(['id','category_id','title','is_publish']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ApiStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreRequest $request)
    {
        $this->setToFileOptions($request, ['document' => 'file']);
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        return $this->storeModel(Document::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Document $document
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, Document $document)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($document);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($document);
    }

    /**
     * publish model
     *
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function publish(Document $document)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => true ] ]
        ]);
        return $this->updateAlias($document, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ]);
    }

    /**
     * not publish model
     *
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function notPublish(Document $document)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => false ] ]
        ]);
        return $this->updateAlias($document, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ]);
    }

    /**
     * group action method
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function group(Request $request)
    {
        $this->clearCache();
        if ( $this->groupAlias(Document::class) ) {
            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }

    /**
     * clear cache
     *
     * @return void
     */
    private function clearCache()
    {
        // cache forget
        foreach(\DB::table('document_categories')->get(['id']) as $category) {
            \Cache::forget(implode('_', ['document_categories', 'descendantsAndSelf', 'withDocuments', $category->id]));
            \Cache::forget(implode('_', ['category_documents', $category->id]));
            \Cache::forget(implode('_', ['category_documents', 'descendants', $category->id]));
        }
    }
}
