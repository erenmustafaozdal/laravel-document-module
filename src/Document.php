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
}
