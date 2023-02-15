<?php

namespace App\Http\Requests\API\ProductController;

use App\Enums\ProductTypeEnum;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create',[Product::class,$this->product]);
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
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required'],
            'quantity' => ['nullable', 'integer'],
            'price' => ['nullable'],
            'type' => ['required', 'string', new Enum(ProductTypeEnum::class)],
        ];
    }
}
