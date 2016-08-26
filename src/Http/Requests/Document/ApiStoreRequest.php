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
        $mimes = config('laravel-document-module.document.uploads.file.mimes');
        $max = config('laravel-document-module.document.uploads.file.max_size');
        $documentValidation = $this->has('document') || $this->file('document') ? "required|max:{$max}|mimes:{$mimes}" : "required";

        $rules = [
            'category_id'       => 'required|integer',
            'title'             => 'required|max:255'
        ];

        for($i = 0; $i < count($this->file('document')); $i++) {
            $rules['document.' . $i] = $documentValidation;
        }

        return $rules;
    }
}
