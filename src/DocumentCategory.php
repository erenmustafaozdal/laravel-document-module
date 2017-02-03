<?php

namespace ErenMustafaOzdal\LaravelDocumentModule;

use Baum\Node;
use Illuminate\Http\Request;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class DocumentCategory extends Node
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'document_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'has_description',
        'has_photo',
        'show_title',
        'show_description',
        'show_photo',
        'datatable_filter',
        'datatable_tools',
        'datatable_fast_add',
        'datatable_group_action',
        'datatable_detail',
        'description_is_editor',
        'config_propagation',
        'photo_width',
        'photo_height',
    ];





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the documents of the document category.
     */
    public function documents()
    {
        return $this->hasMany('App\Document','category_id');
    }

    /**
     * Get the thumbnails of the document category.
     */
    public function thumbnails()
    {
        return $this->hasMany('App\DocumentThumbnail','category_id');
    }

    /**
     * Get the extras of the document category.
     */
    public function extras()
    {
        return $this->hasMany('App\DocumentExtra','category_id');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Set the has_description attribute.
     *
     * @param boolean $has_description
     * @return string
     */
    public function setHasDescriptionAttribute($has_description)
    {
        $this->attributes['has_description'] = $has_description == 1 || $has_description === 'true' || $has_description === true ? true : false;
    }

    /**
     * Get the has_description attribute.
     *
     * @param boolean $has_description
     * @return string
     */
    public function getHasDescriptionAttribute($has_description)
    {
        return $has_description == 1 ? true : false;
    }

    /**
     * Set the has_photo attribute.
     *
     * @param boolean $has_photo
     * @return string
     */
    public function setHasPhotoAttribute($has_photo)
    {
        $this->attributes['has_photo'] = $has_photo == 1 || $has_photo === 'true' || $has_photo === true ? true : false;
    }

    /**
     * Get the has_photo attribute.
     *
     * @param boolean $has_photo
     * @return string
     */
    public function getHasPhotoAttribute($has_photo)
    {
        return $has_photo == 1 ? true : false;
    }

    /**
     * Set the show_title attribute.
     *
     * @param boolean $show_title
     * @return string
     */
    public function setShowTitleAttribute($show_title)
    {
        $this->attributes['show_title'] = $show_title == 1 || $show_title === 'true' || $show_title === true ? true : false;
    }

    /**
     * Get the show_title attribute.
     *
     * @param boolean $show_title
     * @return string
     */
    public function getShowTitleAttribute($show_title)
    {
        return $show_title == 1 ? true : false;
    }

    /**
     * Set the show_description attribute.
     *
     * @param boolean $show_description
     * @return string
     */
    public function setShowDescriptionAttribute($show_description)
    {
        $this->attributes['show_description'] = $show_description == 1 || $show_description === 'true' || $show_description === true ? true : false;
    }

    /**
     * Get the show_description attribute.
     *
     * @param boolean $show_description
     * @return string
     */
    public function getShowDescriptionAttribute($show_description)
    {
        return $show_description == 1 ? true : false;
    }

    /**
     * Set the show_photo attribute.
     *
     * @param boolean $show_photo
     * @return string
     */
    public function setShowPhotoAttribute($show_photo)
    {
        $this->attributes['show_photo'] = $show_photo == 1 || $show_photo === 'true' || $show_photo === true ? true : false;
    }

    /**
     * Get the show_photo attribute.
     *
     * @param boolean $show_photo
     * @return string
     */
    public function getShowPhotoAttribute($show_photo)
    {
        return $show_photo == 1 ? true : false;
    }





    /*
    |--------------------------------------------------------------------------
    | Model Events
    |--------------------------------------------------------------------------
    */

    /**
     * model boot method
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * model saved method
         *
         * @param $model
         */
        parent::saved(function($model)
        {
            // cache forget
            $category_id = $model->isRoot() ? $model->id : $model->getRoot()->id;
            $categories = \DB::table('document_categories')->select('document_categories.id','cat.id')
                ->where('document_categories.id', $category_id)
                ->join('document_categories as cat', function ($join) {
                    $join->on('cat.lft', '>=', 'document_categories.lft')
                        ->on('cat.lft', '<', 'document_categories.rgt');
                })->get();
            foreach($categories as $category) {
                \Cache::forget(implode('_', ['document_categories', $category->id]));
                \Cache::forget(implode('_', ['document_categories', 'descendantsAndSelf', 'withDocuments', $category->id]));
                \Cache::forget(implode('_', ['category_documents', $category->id]));
                \Cache::forget(implode('_', ['category_documents', 'descendants', $category->id]));
            }
        });

        /**
         * model moved method
         *
         * @param $model
         */
        parent::moved(function($model)
        {
            // cache forget
            $category_id = $model->isRoot() ? $model->id : $model->getRoot()->id;
            $categories = \DB::table('document_categories')->select('document_categories.id','cat.id')
                ->where('document_categories.id', $category_id)
                ->join('document_categories as cat', function ($join) {
                    $join->on('cat.lft', '>=', 'document_categories.lft')
                        ->on('cat.lft', '<', 'document_categories.rgt');
                })->get();
            foreach($categories as $category) {
                \Cache::forget(implode('_', ['document_categories', $category->id]));
                \Cache::forget(implode('_', ['document_categories', 'descendantsAndSelf', 'withDocuments', $category->id]));
                \Cache::forget(implode('_', ['category_documents', $category->id]));
                \Cache::forget(implode('_', ['category_documents', 'descendants', $category->id]));
            }
        });

        /**
         * model deleted method
         *
         * @param $model
         */
        parent::deleted(function($model)
        {
            // cache forget
            $category_id = $model->isRoot() ? $model->id : $model->getRoot()->id;
            $categories = \DB::table('document_categories')->select('document_categories.id','cat.id')
                ->where('document_categories.id', $category_id)
                ->join('document_categories as cat', function ($join) {
                    $join->on('cat.lft', '>=', 'document_categories.lft')
                        ->on('cat.lft', '<', 'document_categories.rgt');
                })->get();
            foreach($categories as $category) {
                \Cache::forget(implode('_', ['document_categories', $category->id]));
                \Cache::forget(implode('_', ['document_categories', 'descendantsAndSelf', 'withDocuments', $category->id]));
                \Cache::forget(implode('_', ['category_documents', $category->id]));
                \Cache::forget(implode('_', ['category_documents', 'descendants', $category->id]));
            }
        });
    }
}
