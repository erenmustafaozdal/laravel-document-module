<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Document;
use App\DocumentCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\AdminBaseController;
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


class DocumentApiController extends AdminBaseController
{
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
            $documents = DocumentCategory::findOrFail($id)->documents();
        }
        $documents->select(['id','category_id','title','document','size','is_publish','created_at']);

        // if is filter action
        if ($request->has('action') && $request->input('action') === 'filter') {
            $documents->filter($request);
        }

        // urls
        $addUrls = [
            'publish'       => ['route' => 'api.document.publish', 'id' => true],
            'not_publish'   => ['route' => 'api.document.notPublish', 'id' => true],
            'edit_page'     => ['route' => 'admin.document.edit', 'id' => true]
        ];
        if( ! is_null($id)) {
            $addUrls['edit_page'] = [
                'route'     => 'admin.document_category.document.edit',
                'id'        => $id,
                'model'     => config('laravel-document-module.url.document')
            ];
            $addUrls['show'] = [
                'route'     => 'admin.document_category.document.show',
                'id'        => $id,
                'model'     => config('laravel-document-module.url.document')
            ];
        }
        $addColumns = [
            'addUrls'           => $addUrls,
            'status'            => function($model) { return $model->is_publish; },
        ];
        $editColumns = [
            'created_at'        => function($model) { return $model->created_at_table; },
            'size'              => function($model) { return $model->size_table; }
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
                return $query->select(['id','name']);
            },
            'description','photo'
        ])->where('id',$id)->select(['id','category_id','title','document','size','created_at','updated_at']);

        $editColumns = [
            'size'          => function($model) { return $model->size_table; },
            'created_at'    => function($model) { return $model->created_at_table; },
            'updated_at'    => function($model) { return $model->updated_at_table; }
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
        return $this->storeModel(Document::class, $request, [
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ], config('laravel-document-module.document.uploads'), null, true);
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
        return $this->updateModel($document, $request, [
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  FileRepository  $file
     * @param  Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(FileRepository $file, Document $document)
    {
        $file->deleteDirectory(config('laravel-document-module.document.uploads.path') . "/{$document->id}");
        return $this->destroyModel($document, [
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
    }

    /**
     * publish model
     *
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function publish(Document $document)
    {
        return $this->updateModelPublish($document, true, [
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
        return $this->updateModelPublish($document, false, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ]);
    }

    /**
     * group action method
     *
     * @param  FileRepository  $file
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function group(FileRepository  $file, Request $request)
    {
        $events = [];
        switch($request->input('action')) {
            case 'publish':
                $events['success'] = PublishSuccess::class;
                $events['fail'] = PublishFail::class;
                break;
            case 'not_publish':
                $events['success'] = NotPublishSuccess::class;
                $events['fail'] = NotPublishFail::class;
                break;
            case 'destroy':
                foreach($request->input('id') as $id) {
                    $file->deleteDirectory(config('laravel-document-module.document.uploads.path') . "/{$id}");
                }
                break;
        }
        $action = camel_case($request->input('action')) . 'GroupAction';
        if ( $this->$action(Document::class, $request->input('id'), $events) ) {
            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }
}
