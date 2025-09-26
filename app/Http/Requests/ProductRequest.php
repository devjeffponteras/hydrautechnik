<?php

namespace App\Http\Requests;

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
            'sku' => 'required|max:150',
            'name' => 'required|max:150',
            'author' => 'nullable|max:150',
            'book_type' => 'nullable',
            'file_url' => 'nullable|max:204800',
            'ebook_price' => 'nullable',
            'ebook_discount_price' => 'nullable',
            // 'category_id' => 'nullable',
            'category_id' => 'required|exists:product_categories,id',
            'price' => 'required',
            'discount_price' => 'nullable',
            'mobile_price' => 'nullable',
            'mobile_discount_price' => 'nullable',
            'long_description' => 'nullable', 
            'reorder_point' => 'required',
            'size' => 'nullable',
            'weight' => 'nullable',
            'texture' => 'nullable',
            'uom' => 'nullable',
            'meta_title' => 'max:60',
            'meta_keyword' => 'max:160',
            'meta_description' => 'max:160',
        ];
    }
}
