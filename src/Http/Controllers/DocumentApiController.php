<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Document;
use App\DocumentCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\AdminBaseController;
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
            $pages = Document::with('category');
        } else {
            $pages = DocumentCategory::findOrFail($id)->pages();
        }
        $pages->select(['id','category_id','slug','title','is_publish','created_at']);

        // if is filter action
        if ($request->has('action') && $request->input('action') === 'filter') {
            $pages->filter($request);
        }

        // urls
        $addUrls = [
            'publish'       => ['route' => 'api.page.publish', 'id' => true],
            'not_publish'   => ['route' => 'api.page.notPublish', 'id' => true],
            'edit_page'     => ['route' => 'admin.page.edit', 'id' => true]
        ];
        if( ! is_null($id)) {
            $addUrls['edit_page'] = [
                'route'     => 'admin.page_category.page.edit',
                'id'        => $id,
                'model'     => config('laravel-page-module.url.page')
            ];
            $addUrls['show'] = [
                'route'     => 'admin.page_category.page.show',
                'id'        => $id,
                'model'     => config('laravel-page-module.url.page')
            ];
        }

        $addColumns = [
            'addUrls'           => $addUrls,
            'status'            => function($model) { return $model->is_publish; },
        ];
        $editColumns = [
            'created_at'        => function($model) { return $model->created_at_table; }
        ];
        $removeColumns = ['is_publish','category_id'];
        return $this->getDatatables($pages, $addColumns, $editColumns, $removeColumns);
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
        $page = Document::with([
            'category' => function($query)
            {
                return $query->select(['id','name']);
            }
        ])->where('id',$id)->select(['id','category_id','title','slug','description','content','created_at','updated_at']);

        $editColumns = [
            'created_at'    => function($model) { return $model->created_at_table; },
            'updated_at'    => function($model) { return $model->updated_at_table; }
        ];
        return $this->getDatatables($page, [], $editColumns, []);
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
        ])->where('id',$id)->first(['id','category_id','title','slug','description','is_publish']);
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
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Document $page
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, Document $page)
    {
        $result = $this->updateModel($page, $request, [
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);

        // publish
        $request->input('is_publish') === 'true' ? $this->updateModelPublish($page, true, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ]) : $this->updateModelPublish($page, false, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ]);
        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Document  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $page)
    {
        return $this->destroyModel($page, [
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
    }

    /**
     * publish model
     *
     * @param Document $page
     * @return \Illuminate\Http\Response
     */
    public function publish(Document $page)
    {
        return $this->updateModelPublish($page, true, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ]);
    }

    /**
     * not publish model
     *
     * @param Document $page
     * @return \Illuminate\Http\Response
     */
    public function notPublish(Document $page)
    {
        return $this->updateModelPublish($page, false, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ]);
    }

    /**
     * update content page
     *
     * @param Document $page
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function contentUpdate(Document $page, Request $request)
    {
        return $this->updateModel($page, $request, [
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
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
                break;
        }
        $action = camel_case($request->input('action')) . 'GroupAction';
        if ( $this->$action(Document::class, $request->input('id'), $events) ) {
            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }
}
