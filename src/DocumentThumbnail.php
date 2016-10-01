<?php

namespace ErenMustafaOzdal\LaravelDocumentModule;

use Illuminate\Database\Eloquent\Model;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class DocumentThumbnail extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'document_category_thumbnails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'slug','photo_width', 'photo_height' ];

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
     * Get the photo of the document.
     */
    public function documentCategory()
    {
        return $this->belongsTo('App\DocumentCategory');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */
}
