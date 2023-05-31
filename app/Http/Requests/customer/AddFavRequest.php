<?php

namespace App\Http\Requests\customer;

use App\Http\Traits\LocalResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddFavRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'room_id' => 'required|exists:rooms,id',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(LocalResponse::returnError(
            "can't handle this request now see errors.",
            400,
            $validator->errors()
        ));
    }
    public function values()
    {
        return [
            'room_id' => $this->room_id,
            'customer_id' => Auth::user()->id,
        ];
    }
}
