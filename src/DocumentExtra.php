<?php

namespace ErenMustafaOzdal\LaravelDocumentModule;

use Illuminate\Database\Eloquent\Model;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class DocumentExtra extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'document_category_columns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name','type' ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['documentCategory'];
    public $timestamps = false;





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the document category of the column.
     */
    public function documentCategory()
    {
        return $this->belongsTo('App\DocumentCategory');
    }

    /**
     * Get the documents of the document extra columns.
     */
    public function documents()
    {
        return $this->belongsToMany('App\Document','document_document_category_column','column_id','document_id')
            ->withPivot('value');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}
