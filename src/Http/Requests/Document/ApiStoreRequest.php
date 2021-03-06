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
        return hasPermission('api.document.store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $mimes = config('laravel-document-module.document.uploads.file.mimes');
        $max = config('laravel-document-module.document.uploads.file.max_size');

        $rules = [
            'category_id'       => 'required|integer',
            'title'             => 'required|max:255'
        ];

        // document elfinder mi
        if ($this->has('document') && is_string($this->document)) {
            $rules['document'] = "required|elfinder_max:{$max}|elfinder:{$mimes}";
        } else if (is_array($this->document)){
            for($i = 0; $i < count($this->document); $i++) {
                $rules['document.' . $i] = "required|max:{$max}|mimes:{$mimes}";
            }
        } else {
            $rules['document'] = "required|max:{$max}|mimes:{$mimes}";
        }

        return $rules;
    }
}
