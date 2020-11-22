<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigureStakeLimitRequest extends FormRequest
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
            'id' => ['required', 'uuid'],
            'timeDuration' => ['required', 'numeric', 'min:300', "max:86400"],
            'stakeLimit ' => ['required', 'numeric', 'min:1', 'max:10000000'],
            'hotPercentage' => ['required', 'integer', 'min:1', 'max:100'],
            'restrictionExpires' => ['required', 'numeric', 'min:0', 'not_between:0,60'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => trans('validation.required', ['attribute' => trans('stakes.id')]),
            'timeDuration.required' => trans('validation.required', ['attribute' => trans('stakes.timeDuration')]),
            'stakeLimit.required' => trans('validation.required', ['attribute' => trans('stakes.stakeLimit')]),
            'hotPercentage.required' => trans('validation.required', ['attribute' => trans('stakes.hotPercentage')]),
            'restrictionExpires.required' => trans('validation.required', ['attribute' => trans('stakes.restrictionExpires')]),
            
            'timeDuration.numeric' => trans('validation.numeric', ['attribute' => trans('stakes.timeDuration')]),
            'stakeLimit.numeric' => trans('validation.numeric', ['attribute' => trans('stakes.stakeLimit')]),
            'restrictionExpires.numeric' => trans('validation.numeric', ['attribute' => trans('stakes.restrictionExpires')]),

            'timeDuration.min' => trans('validation.min', ['attribute' => trans('stakes.timeDuration')]),
            'stakeLimit.min' => trans('validation.min', ['attribute' => trans('stakes.stakeLimit')]),
            'hotPercentage.min' => trans('validation.min', ['attribute' => trans('stakes.hotPercentage')]),
            'restrictionExpires.min' => trans('validation.min', ['attribute' => trans('stakes.restrictionExpires')]),
            
            'timeDuration.max' => trans('validation.max', ['attribute' => trans('stakes.timeDuration')]),
            'stakeLimit.max' => trans('validation.max', ['attribute' => trans('stakes.stakeLimit')]),
            'hotPercentage.max' => trans('validation.max', ['attribute' => trans('stakes.hotPercentage')]),
            
            'hotPercentage.integer' => trans('validation.integer', ['attribute' => trans('stakes.hotPercentage')]),
            'restrictionExpires.not_between' => trans('validation.not_between', ['attribute' => trans('stakes.restrictionExpires')]),
        ];
    }
}
