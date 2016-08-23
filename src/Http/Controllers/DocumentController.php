<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers;

use App\Http\Requests;
use App\Document;
use App\DocumentCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseController;
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
use ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\Document\StoreRequest;
use ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\Document\UpdateRequest;

class DocumentController extends BaseController
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
            return view(config('laravel-document-module.views.document.index'));
        }

        $document_category = DocumentCategory::findOrFail($id);
        return view(config('laravel-document-module.views.document.index'), compact('document_category'));
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
            return view(config('laravel-document-module.views.document.create'), compact('operation'));
        }

        $document_category = DocumentCategory::findOrFail($id);
        return view(config('laravel-document-module.views.document.create'), compact('document_category','operation'));
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
            $redirect = 'document_category.document.index';
            $this->setRelationRouteParam($id, config('laravel-document-module.url.document'));
        }

        $this->setFileOptions(config('laravel-document-module.document.uploads'));
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        $this->setOperationRelation([
            [
                'relation_type'     => 'hasOne',
                'relation'          => 'description',
                'relation_model'    => '\App\DocumentDescription',
                'datas' => [
                    'description'   => $request->description
                ]
            ]
        ]);
        return $this->storeModel(Document::class,$redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param integer|Document $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function show($firstId, $secondId = null)
    {
        $document = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-document-module.views.document.show'), compact('document'));
        }

        $document_category = DocumentCategory::findOrFail($firstId);
        return view(config('laravel-document-module.views.document.show'), compact('document', 'document_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer|Document $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function edit($firstId, $secondId = null)
    {
        $operation = 'edit';
        $document = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-document-module.views.document.edit'), compact('document','operation'));
        }

        $document_category = DocumentCategory::findOrFail($firstId);
        return view(config('laravel-document-module.views.document.edit'), compact('document', 'document_category','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param integer|Document $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $firstId, $secondId = null)
    {
        $document = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'document_category.document.show';
            $this->relatedModelId = $firstId;
            $this->modelRouteRegex = config('laravel-document-module.url.document');
        }

        $result = $this->updateModel($document,$request, [
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ], [],$redirect);

        // publish
        $request->has('is_publish') ? $this->updateModelPublish($document, true, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ]) : $this->updateModelPublish($document, false, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ]);
        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer|Document $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function destroy($firstId, $secondId = null)
    {
        $document = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'index';
        } else {
            $redirect = 'document_category.document.index';
            $this->relatedModelId = $firstId;
            $this->modelRouteRegex = config('laravel-document-module.url.document');
        }

        return $this->destroyModel($document, [
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ], $redirect);
    }

    /**
     * publish model
     *
     * @param integer|Document $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function publish($firstId, $secondId = null)
    {
        $document = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'document_category.document.show';
            $this->relatedModelId = $firstId;
            $this->modelRouteRegex = config('laravel-document-module.url.document');
        }
        return $this->updateModelPublish($document, true, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ],$redirect);
    }

    /**
     * not publish model
     *
     * @param integer|Document $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function notPublish($firstId, $secondId = null)
    {
        $document = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'document_category.document.show';
            $this->relatedModelId = $firstId;
            $this->modelRouteRegex = config('laravel-document-module.url.document');
        }
        return $this->updateModelPublish($document, false, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ],$redirect);
    }
}
