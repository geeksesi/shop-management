<?php

namespace App\Http\Requests\API\OrderController;

use App\Enums\OrderPaymentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class OrderRequest extends FormRequest
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
            'payment_status' => [new Enum(OrderPaymentStatusEnum::class), 'required'],
            'product_ids' => ['array', 'required'],
            'quantities' => ['array']
        ];
    }
}
