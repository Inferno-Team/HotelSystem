<?php

namespace App\Http\Requests\customer;

use App\Http\Traits\LocalResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OccupationRequest extends FormRequest
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
            'type' => 'required|in:double,classic,family,junior,single',
            'from' => 'required|string',
            'to' => 'required|string',
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
            'customer_id' => Auth::user()->id,
            // 'room_id' => $this->room_id,
            'room_type' => $this->type,
            'from' => Carbon::parse($this->from)->format("Y-m-d h:i:s"),
            'to' => Carbon::parse($this->to)->format("Y-m-d h:i:s"),
        ];
    }
}
