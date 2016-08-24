<?php

namespace ErenMustafaOzdal\LaravelDocumentModule;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class Document extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'title',
        'document',
        'size',
        'is_publish'
    ];





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * query filter with id scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $request)
    {
        // filter id
        if ($request->has('id')) {
            $query->where('id',$request->get('id'));
        }
        // filter title
        if ($request->has('title')) {
            $query->where('title', 'like', "%{$request->get('title')}%");
        }
        // filter document
        if ($request->has('document')) {
            $query->where('document', 'like', "%{$request->get('document')}%");
        }
        // filter category
        if ($request->has('category')) {
            $query->whereHas('category', function ($query) use($request) {
                $query->where('name', 'like', "%{$request->get('category')}%");
            });
        }
        // filter size
        if ($request->has('size_from')) {
            $query->where('size', '>=', $request->get('size_from'));
        }
        if ($request->has('size_to')) {
            $query->where('size', '<=', $request->get('size_to'));
        }
        // filter status
        if ($request->has('status')) {
            $query->where('is_publish',$request->get('status'));
        }
        // filter created_at
        if ($request->has('created_at_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->get('created_at_from')));
        }
        if ($request->has('created_at_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->get('created_at_to')));
        }
        return $query;
    }





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the category of the document.
     */
    public function category()
    {
        return $this->belongsTo('App\DocumentCategory');
    }

    /**
     * Get the document description.
     */
    public function description()
    {
        return $this->hasOne('App\DocumentDescription','document_id');
    }

    /**
     * Get the document photo.
     */
    public function photo()
    {
        return $this->hasOne('App\DocumentPhoto','document_id');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Get the title attribute.
     *
     * @param  string $title
     * @return string
     */
    public function getTitleAttribute($title)
    {
        return ucfirst_tr($title);
    }

    /**
     * Get the size attribute.
     *
     * @param integer $size
     * @return string
     */
    public function getSizeAttribute($size)
    {
        return (int) $size;
    }

    /**
     * Get the size attribute for humans.
     *
     * @return string
     */
    public function getSizeForHumansAttribute()
    {
        return humanFileSize($this->size);
    }

    /**
     * Get the size attribute for datatable.
     *
     * @return array
     */
    public function getSizeTableAttribute()
    {
        return [
            'display'       => $this->size_for_humans,
            'number'        => $this->size
        ];
    }

    /**
     * Set the is_publish attribute.
     *
     * @param boolean $is_publish
     * @return string
     */
    public function setIsPublishAttribute($is_publish)
    {
        $this->attributes['is_publish'] = $is_publish == 1 || $is_publish === 'true' || $is_publish === true ? true : false;
    }

    /**
     * Get the is_publish attribute.
     *
     * @param boolean $is_publish
     * @return string
     */
    public function getIsPublishAttribute($is_publish)
    {
        return $is_publish == 1 ? true : false;
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
     * Get the created_at attribute for datatable.
     *
     * @return array
     */
    public function getCreatedAtTableAttribute()
    {
        return [
            'display'       => $this->created_at_for_humans,
            'timestamp'     => Carbon::parse($this->created_at)->timestamp,
        ];
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

    /**
     * Get the updated_at attribute for datatable.
     *
     * @return array
     */
    public function getUpdatedAtTableAttribute()
    {
        return [
            'display'       => $this->updated_at_for_humans,
            'timestamp'     => Carbon::parse($this->updated_at)->timestamp,
        ];
    }
}
