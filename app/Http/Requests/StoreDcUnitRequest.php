<?php

namespace App\Http\Requests;

class StoreDcUnitRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Request::ajax()) {
            if (request()->has('within_plan')) {
                return [
                    'within_plan' => 'required',
                ];
            } elseif (request()->has('native')) {
                return [
                    'native' => 'required',
                ];
            } else {
                return [
                    'active' => 'required',
                ];
            }
        }

        return [
            'name'              => 'required',
            'population'        => 'required|numeric',
            'county_id'         => 'required|numeric',
            'location'          => 'nullable',
            'is_experienced'    => 'required',
            'environment'       => 'nullable',
            'risk'              => 'nullable',
            'rank_started_date' => 'nullable',
            'extension_date' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'rank_started_date.required_unless' => '請填寫星等生效日期',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
