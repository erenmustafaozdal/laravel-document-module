<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\Document;

use ErenMustafaOzdal\LaravelModulesBase\Requests\BaseRequest;
use Sentinel;

class StoreRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $hackedRoute = 'admin.document.store';
        if ( ! is_null($this->segment(4))) {
            $hackedRoute = 'admin.document_category.document.store#####' .$this->segment(3);
        }
        return hasPermission($hackedRoute);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $max = config('laravel-document-module.document.uploads.file.max_size');
        $mimes = config('laravel-document-module.document.uploads.file.mimes');
        $max_photo = config('laravel-document-module.document.uploads.photo.max_size');
        $mimes_photo = config('laravel-document-module.document.uploads.photo.mimes');

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

        // photo elfinder mi
        if ($this->has('photo') && is_string($this->photo)) {
            $rules['photo'] = "elfinder_max:{$max_photo}|elfinder:{$mimes_photo}";
        } else  if (is_array($this->photo)){
            for($i = 0; $i < count($this->file('photo')); $i++) {
                $rules['photo.' . $i] = "max:{$max_photo}|image|mimes:{$mimes_photo}";
            }
        } else {
            $rules['photo'] = "max:{$max_photo}|image|mimes:{$mimes_photo}";
        }

        return $rules;
    }

    /**
     * get message of the rules
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];

        // photo message eklenir
        if ( ( $this->has('photo') || $this->file('photo') ) && is_array($this->photo)) {
            foreach ($this->photo as $key => $val) {
                $item = $key + 1;
                $messages['photo.' . $key . '.required'] = "{$item}. Fotoğraf alanı gereklidir.";
                $messages['photo.' . $key . '.elfinder_max'] = "{$item}. Fotoğraf alanı en fazla :size bayt boyutunda olmalıdır.";
                $messages['photo.' . $key . '.elfinder'] = "{$item}. Fotoğraf dosya biçimi :values olmalıdır.";
                $messages['photo.' . $key . '.max'] = "{$item}. Fotoğraf değeri :max kilobayt değerinden küçük olmalıdır.";
                $messages['photo.' . $key . '.image'] = "{$item}. Fotoğraf alanı resim dosyası olmalıdır.";
                $messages['photo.' . $key . '.mimes'] = "{$item}. Fotoğraf dosya biçimi :values olmalıdır.";
            }
        }

        // document message eklenir
        if ( $this->file('document') && is_array($this->document)) {
            foreach ($this->document as $key => $val) {
                $item = $key + 1;
                $messages['document.' . $key . '.required'] = "{$item}. Dosya alanı gereklidir.";
                $messages['document.' . $key . '.elfinder_max'] = "{$item}. Dosya alanı en fazla :size bayt boyutunda olmalıdır.";
                $messages['document.' . $key . '.elfinder'] = "{$item}. Dosya dosya biçimi :values olmalıdır.";
                $messages['document.' . $key . '.max'] = "{$item}. Dosya değeri :max kilobayt değerinden küçük olmalıdır.";
                $messages['document.' . $key . '.mimes'] = "{$item}. Dosya dosya biçimi :values olmalıdır.";
            }
        }

        return $messages;
    }
}
