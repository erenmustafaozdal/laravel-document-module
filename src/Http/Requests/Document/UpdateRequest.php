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
        $photoValidation = $this->has('photo') || $this->file('photo')
            ? "max:{$max_photo}|image|mimes:{$mimes_photo}"
            : "";

        $rules = [
            'category_id'       => 'required|integer',
            'title'             => 'required|max:255',
            'photo'             => $photoValidation
        ];

        for($i = 0; $i < count($this->file('photo')); $i++) {
            $rules['photo.' . $i] = $photoValidation;
        }

        return $rules;
    }
}
