<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\DocumentCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\AdminBaseController;
// events
use ErenMustafaOzdal\LaravelDocumentModule\Events\DocumentCategory\StoreSuccess;
use ErenMustafaOzdal\LaravelDocumentModule\Events\DocumentCategory\StoreFail;
use ErenMustafaOzdal\LaravelDocumentModule\Events\DocumentCategory\UpdateSuccess;
use ErenMustafaOzdal\LaravelDocumentModule\Events\DocumentCategory\UpdateFail;
use ErenMustafaOzdal\LaravelDocumentModule\Events\DocumentCategory\DestroySuccess;
use ErenMustafaOzdal\LaravelDocumentModule\Events\DocumentCategory\DestroyFail;
// requests
use ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\DocumentCategory\ApiStoreRequest;
use ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\DocumentCategory\ApiUpdateRequest;


class DocumentCategoryApiController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Datatables
     */
    public function index(Request $request)
    {
        $page_categories = DocumentCategory::select(['id','name','created_at']);
        // if is filter action
        if ($request->has('action') && $request->input('action') === 'filter') {
            $page_categories->filter($request);
        }

        $addColumns = [
            'addUrls' => [
                'edit_page'     => ['route' => 'admin.page_category.edit', 'id' => true],
                'relations'     => ['route' => 'admin.page_category.page.index', 'id' => true]
            ]
        ];
        $editColumns = [
            'created_at'        => function($model) { return $model->created_at_table; }
        ];
        $removeColumns = [];
        return $this->getDatatables($page_categories, $addColumns, $editColumns, $removeColumns);
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
        $page_category = DocumentCategory::where('id',$id)->select(['id','name', 'created_at','updated_at']);

        $editColumns = [
            'created_at'    => function($model) { return $model->created_at_table; },
            'updated_at'    => function($model) { return $model->updated_at_table; }
        ];
        return $this->getDatatables($page_category, [], $editColumns, []);
    }

    /**
     * get model data for edit
     *
     * @param $id
     * @param Request $request
     * @return DocumentCategory
     */
    public function fastEdit($id, Request $request)
    {
        return DocumentCategory::find($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ApiStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreRequest $request)
    {
        return $this->storeModel(DocumentCategory::class, $request, [
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  DocumentCategory $page_category
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, DocumentCategory $page_category)
    {
        return $this->updateModel($page_category, $request, [
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DocumentCategory  $page_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentCategory $page_category)
    {
        return $this->destroyModel($page_category, [
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
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
        if ( $this->destroyGroupAction(DocumentCategory::class, $request->input('id'), []) ) {
            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }

    /**
     * get roles with query
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function models(Request $request)
    {
        return DocumentCategory::where('name', 'like', "%{$request->input('query')}%")->get(['id','name']);
    }
}
