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
        if (Sentinel::getUser()->is_super_admin || Sentinel::hasAccess('admin.document_category.store')) {
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
        return [
            'name'          => 'required|max:255',
            'parent'        => 'required|integer',
            'position'      => 'required|in:firstChild,lastChild,before,after',
            'related'       => 'required|integer'
        ];
    }
}
