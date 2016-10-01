<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Controllers;

use Illuminate\Http\Request;

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
use ErenMustafaOzdal\LaravelDocumentModule\Events\DocumentCategory\MoveSuccess;
use ErenMustafaOzdal\LaravelDocumentModule\Events\DocumentCategory\MoveFail;
// requests
use ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\DocumentCategory\ApiStoreRequest;
use ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\DocumentCategory\ApiUpdateRequest;
use ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\DocumentCategory\ApiMoveRequest;
// services
use LMBCollection;


class DocumentCategoryApiController extends BaseNodeController
{
    /**
     * default relation datas
     *
     * @var array
     */
    private $relations = [
        'thumbnails' => [
            'relation_type'     => 'hasMany',
            'relation'          => 'thumbnails',
            'relation_model'    => '\App\DocumentThumbnail',
            'datas'             => null
        ],
        'extras' => [
            'relation_type'     => 'hasMany',
            'relation'          => 'extras',
            'relation_model'    => '\App\DocumentExtra',
            'datas'             => null
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @param integer|null $id
     * @return array
     */
    public function index(Request $request, $id = null)
    {
        return $this->getNodes(DocumentCategory::class, $id);
    }

    /**
     * get detail
     *
     * @param integer $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function detail($id, Request $request)
    {
        return DocumentCategory::where('id', $id)
            ->select('has_description','has_photo')
            ->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ApiStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreRequest $request)
    {
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        $document_category = DocumentCategory::find($request->parent);
        if ($document_category->config_propagation) {
            $this->setRelationDefine($document_category);
        }
        return $this->storeNode(DocumentCategory::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  DocumentCategory $document_category
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, DocumentCategory $document_category)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        $this->updateModel($document_category);

        return [
            'id'        => $document_category->id,
            'name'      => $document_category->name_uc_first
        ];
    }

    /**
     * Move the specified node.
     *
     * @param  ApiMoveRequest $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function move(ApiMoveRequest $request, $id)
    {
        $document_category = DocumentCategory::findOrFail($id);
        $this->setEvents([
            'success'   => MoveSuccess::class,
            'fail'      => MoveFail::class
        ]);
        $parent = DocumentCategory::find($request->related);
        if ($parent->config_propagation) {
            $this->setRelationDefine($parent);
        }
        return $this->moveModel($document_category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DocumentCategory  $document_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentCategory $document_category)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($document_category);
    }

    /**
     * get roles with query
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function models(Request $request)
    {
        if($request->has('id')) {
            $document_category = DocumentCategory::find($request->input('id'));
            $models = $document_category->descendantsAndSelf()->where('name', 'like', "%{$request->input('query')}%");

        } else {
            $models = DocumentCategory::where('name', 'like', "%{$request->input('query')}%");
        }

        $models = $models->get(['id','parent_id','lft','rgt','depth','name']);
        return LMBCollection::renderAncestorsAndSelf($models,'/',['name_uc_first']);
    }
}
