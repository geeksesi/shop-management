<?php

namespace App\Http\Requests\API\ProductController;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'category_id' => ['required','exists:categories,id'],
            'creator' => ['required','exists:users,id'],
            'description' => ['required'],
            'quantity' => ['nullable','integer'],
            'weight' => ['nullable'],
            'price' => ['nullable'],
            'sale_price' => ['nullable'],
            'type' => ['required','string'],
        ];
    }
}