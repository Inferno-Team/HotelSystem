<?php

namespace App\Http\Requests\utils;

use App\Http\Traits\LocalResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

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
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(LocalResponse::returnError("can't register now see errors", 400, $validator->errors()));
    }
    public function values()
    {
        return [
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'name' => $this->name,
            'type' => 'customer',
        ];
    }
}
