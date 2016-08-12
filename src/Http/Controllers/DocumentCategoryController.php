<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers;

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
use ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\DocumentCategory\StoreRequest;
use ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\DocumentCategory\UpdateRequest;

class DocumentCategoryController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if (is_null($id)) {
            return view(config('laravel-document-module.views.document_category.index'));
        }

        $document_category = DocumentCategory::findOrFail($id);
        return view(config('laravel-document-module.views.document_category.index'), compact('document_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(config('laravel-document-module.views.document_category.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return $this->storeModel(DocumentCategory::class, $request, [
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ], [], 'index');
    }

    /**
     * Display the specified resource.
     *
     * @param  DocumentCategory  $document_category
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentCategory $document_category)
    {
        return view(config('laravel-document-module.views.document_category.show'), compact('document_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param DocumentCategory $document_category
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentCategory $document_category)
    {
        return view(config('laravel-document-module.views.document_category.edit'), compact('document_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  DocumentCategory  $document_category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, DocumentCategory $document_category)
    {
        return $this->updateModel($document_category,$request, [
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ], [],'show');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DocumentCategory  $document_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentCategory $document_category)
    {
        return $this->destroyModel($document_category, [
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ], 'index');
    }
}
