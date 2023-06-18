<?php

namespace App\Http\Requests\employee;

use App\Http\Traits\LocalResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResponseToMeetingRoomOccupation extends FormRequest
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
            'request_id' => 'required|exists:meeting_room_occupation_requests,id',
            'status' => 'required|in:rejected,approved'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(LocalResponse::returnError(
            "can't response to this request now errors",
            400,
            $validator->errors()
        ));
    }
}
