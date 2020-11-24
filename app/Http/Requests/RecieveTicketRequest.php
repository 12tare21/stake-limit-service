<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecieveTicketRequest extends FormRequest
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
            // 'id' => ['required', 'uuid'],
            'deviceId' => ['required', 'uuid'],
            'stake' => ['required', 'numeric'],
        ];
    }
    
    public function messages()
    {
        return [
            'id.required' => trans('validation.required', ['attribute' => trans('stakes.id')]),
            'deviceId.required' => trans('validation.required', ['attribute' => trans('stakes.deviceId')]),
            'stake.required' => trans('validation.required', ['attribute' => trans('stakes.stake')]),
            
            'id.uuid' => trans('validation.uuid', ['attribute' => trans('stakes.id')]),
            'deviceId.uuid' => trans('validation.uuid', ['attribute' => trans('stakes.deviceId')]),

            'stake.numeric' => trans('validation.numeric', ['attribute' => trans('stakes.stake')]),
        ];
    }
}