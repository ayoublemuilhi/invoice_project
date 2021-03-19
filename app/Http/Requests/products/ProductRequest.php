<?php

namespace App\Http\Requests\products;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'Product_name' => 'bail|required|max:255',
            'section_id' => 'required'

        ];
    }
    public function messages()
    {
        return [
            'Product_name.required' => 'اسم المنتج مطلوب',
            'section_id.required' => 'يجب تحديد القسم'

        ];
    }
}
