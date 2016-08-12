<?php

namespace ErenMustafaOzdal\LaravelDocumentModule;

use Baum\Node;
use Carbon\Carbon;

class DocumentCategory extends Node
{
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
    protected $fillable = ['name','has_description','has_photo'];





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * query has description scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasDescription($query)
    {
        return $query->where('has_description', true);
    }

    /**
     * query has photo scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasPhoto($query)
    {
        return $query->where('has_photo', true);
    }





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





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Get the name attribute.
     *
     * @param  string $name
     * @return string
     */
    public function getNameAttribute($name)
    {
        return ucfirst_tr($name);
    }

    /**
     * Set the has_description attribute.
     *
     * @param boolean $has_description
     * @return string
     */
    public function setHasDescritionAttribute($has_description)
    {
        $this->attributes['has_description'] = $has_description == 1 || $has_description === 'true' || $has_description === true ? true : false;
    }

    /**
     * Get the has_description attribute.
     *
     * @param boolean $has_description
     * @return string
     */
    public function getHasDescritionAttribute($has_description)
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
     * Get the created_at attribute.
     *
     * @param  $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format(config('laravel-document-module.date_format'));
    }

    /**
     * Get the created_at attribute for humans.
     *
     * @return string
     */
    public function getCreatedAtForHumansAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    /**
     * Get the updated_at attribute.
     *
     * @param  $date
     * @return string
     */
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format(config('laravel-document-module.date_format'));
    }

    /**
     * Get the updated_at attribute for humans.
     *
     * @return string
     */
    public function getUpdatedAtForHumansAttribute()
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}
