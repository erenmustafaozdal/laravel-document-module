<?php

namespace ErenMustafaOzdal\LaravelDocumentModule;

use Illuminate\Database\Eloquent\Model;

class DocumentDescription extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'document_descriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'description' ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['documents'];





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the description of the document.
     */
    public function document()
    {
        return $this->belongsTo('App\Document');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}
