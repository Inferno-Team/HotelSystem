<?php

namespace App\Http\Requests\utils;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Http\Traits\LocalResponse;

class LoginRequest extends FormRequest
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
            'email' =>  'required|email|exists:users,email',
            'password' =>  'required'
        ];
    }
    // public function messages()
    // {
    //     return [
    //         'email.required' => 'بريد الالكتروني مطلوب',
    //         'email.exists' => 'بريد الالكتروني غير موجود',
    //         'email.email' => 'هذا الحقل ليس بريد الكتروني',
    //         'password.required' => 'كلمة المرور يجب ان تكون موجودة',

    //     ];
    // }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(LocalResponse::returnError('خطأ في تجسيل الدخول', 401, $validator->errors()));
    }
}
