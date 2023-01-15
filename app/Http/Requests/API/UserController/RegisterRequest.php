<?php

namespace App\Http\Requests\API\UserController;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['string', 'required'],
            'family' => ['string', 'required'],
            'email' => ['string', 'required', 'email', 'unique:users,email'],
            'password' => ['string', 'required', 'min:8'],
            'username' => ['string', 'required', 'unique:users,username'],
            'phone_number' => ['string', 'required', 'unique:users,phone_number'],
        ];
    }
}
