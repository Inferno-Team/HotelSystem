<?php

namespace App\Http\Requests\manager;

use App\Http\Traits\LocalResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddNewRoomRequest extends FormRequest
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
            'type' => 'required|in:single,double',
            "number" => 'required|unique:rooms,number',
            'price' => 'required|numeric',
            'images' => 'array',
            'images.*' => 'image|mimes:png,jpg'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(LocalResponse::returnError("can't create this room", 400, $validator->errors()));
    }
    public function values()
    {
        return [
            "type" => $this->type,
            "number" => $this->number,
            "price" => $this->price,
            "hotel_id" => Auth::user()->hotel->id,
        ];
    }
}
