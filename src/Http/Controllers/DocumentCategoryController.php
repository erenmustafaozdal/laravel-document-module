<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers;

use App\Http\Requests;
use App\DocumentCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseNodeController;
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

class DocumentCategoryController extends BaseNodeController
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

        $parent_document_category = DocumentCategory::findOrFail($id);
        return view(config('laravel-document-module.views.document_category.index'), compact('parent_document_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $operation = 'create';
        if (is_null($id)) {
            return view(config('laravel-document-module.views.document_category.create'), compact('operation'));
        }

        $parent_document_category = DocumentCategory::findOrFail($id);
        return view(config('laravel-document-module.views.document_category.create'), compact('parent_document_category','operation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $id = null)
    {
        if (is_null($id)) {
            $redirect = 'index';
        } else {
            $redirect = 'document_category.document_category.index';
            $this->setRelationRouteParam($id, config('laravel-document-module.url.document_category'));
        }

        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        return $this->storeModel(DocumentCategory::class,$redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param integer|DocumentCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function show($firstId, $secondId = null)
    {
        $document_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-document-module.views.document_category.show'), compact('document_category'));
        }

        $parent_document_category = DocumentCategory::findOrFail($firstId);
        return view(config('laravel-document-module.views.document_category.show'), compact('parent_document_category','document_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer|DocumentCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function edit($firstId, $secondId = null)
    {
        $operation = 'edit';
        $document_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-document-module.views.document_category.edit'), compact('document_category','operation'));
        }

        $parent_document_category = DocumentCategory::findOrFail($firstId);
        return view(config('laravel-document-module.views.document_category.edit'), compact('parent_document_category','document_category','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param integer|DocumentCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $firstId, $secondId = null)
    {
        $document_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'document_category.document_category.show';
            $this->setRelationRouteParam($firstId, config('laravel-document-module.url.document_category'));
        }

        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($document_category, $redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer|DocumentCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function destroy($firstId, $secondId = null)
    {
        $document_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'index';
        } else {
            $redirect = 'document_category.document_category.index';
            $this->setRelationRouteParam($firstId, config('laravel-document-module.url.document_category'));
        }

        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($document_category, $redirect);
    }
}
