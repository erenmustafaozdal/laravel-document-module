<?php

namespace ErenMustafaOzdal\LaravelDocumentModule\Http\Requests\DocumentCategory;

use App\Http\Requests\Request;
use Sentinel;

class StoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $hackedRoute = 'admin.document_category.store';
        if ( ! is_null($this->segment(4))) {
            $hackedRoute = 'admin.document_category.document_category.store#####' .$this->segment(3);
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
        $rules = [
            'name'          => 'required|max:255',
            'parent'        => 'integer',
            'photo_width'   => 'numeric',
            'photo_height'  => 'numeric',
        ];

        // group number rules extend
        if ($this->has('group-thumbnail') && is_array($this->get('group-thumbnail'))) {
            for ($i = 0; $i < count($this->get('group-thumbnail')); $i++) {
                $rules['group-thumbnail.' . $i . '.thumbnail_slug'] = 'alpha_dash|max:255';
                $rules['group-thumbnail.' . $i . '.thumbnail_width'] = 'numeric';
                $rules['group-thumbnail.' . $i . '.thumbnail_height'] = 'numeric';
            }
        }

        return $rules;
    }
}
