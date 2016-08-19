<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\Document;

use App\Http\Requests\Request;
use Sentinel;

class ApiStoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Sentinel::getUser()->is_super_admin || Sentinel::hasAccess('api.document.store')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $max = config('laravel-document-module.document.uploads.max_size');
        $mimes = config('laravel-document-module.document.uploads.mimes');
        return [
            'category_id'       => 'required|integer',
            'title'             => 'required|max:255',
            'document'          => "required|max:{$max}|mimes:{$mimes}"
        ];
    }
}
