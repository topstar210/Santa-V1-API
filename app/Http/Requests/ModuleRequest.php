<?php

namespace App\Http\Requests;


class ModuleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => 'required|max:255',
            'description'   => 'nullable|max:3000',
            'status'         => 'required',
            'image'         => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ];
    }

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
     * @return array
     * Custom validation message
     */
    public function messages()
    {
        return [
            'name.required'    => 'Product name is missing!',
            'name.max'         => 'Product name is too long!',
            'status.required'  => 'Product status is missing!',
            'description.max'  => 'Description is too long!',
            'price.required'   => 'Product price is missing',
            'price.numeric'    => 'Product price is not a numeric value',
            'image.image'      => 'File should be an Image',
            'image.max'        => 'Product image is too big. Maximum allow 2MB',
        ];
    }
}
