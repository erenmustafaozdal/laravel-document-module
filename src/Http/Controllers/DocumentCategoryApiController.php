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
     * @return array
     */
    public function index(Request $request)
    {
        return $this->getNodes(DocumentCategory::class, $request);
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
