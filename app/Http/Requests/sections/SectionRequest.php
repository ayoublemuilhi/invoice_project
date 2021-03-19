<?php

namespace App\Http\Requests\sections;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
            'section_name' => 'bail|required|unique:sections|max:255',

        ];
    }
    public function messages()
    {
        return [
            'section_name.required' => 'اسم القسم مطلوب',
            'section_name.unique' => ' القسم مسجل مسبقا',
            'section_name.max' => 'لا يجوز أن يكون اسم القسم أكبر من 255 حرفًا'

        ];
    }
}
