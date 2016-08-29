<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\Document;

use App\Http\Requests\Request;
use Sentinel;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Sentinel::getUser()->is_super_admin || Sentinel::hasAccess('admin.document.update')) {
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
        $max_photo = config('laravel-document-module.document.uploads.photo.max_size');
        $mimes_photo = config('laravel-document-module.document.uploads.photo.mimes');

        $rules = [
            'category_id'       => 'required|integer',
            'title'             => 'required|max:255'
        ];

        // photo elfinder mi
        if ($this->has('photo') && is_string($this->photo)) {
            return $rules['photo'] = "elfinder_max:{$max_photo}|elfinder:{$mimes_photo}";
        } else {
            for($i = 0; $i < count($this->file('photo')); $i++) {
                $rules['photo.' . $i] = "max:{$max_photo}|image|mimes:{$mimes_photo}";
            }
        }

        return $rules;
    }
}
