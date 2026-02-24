<?php

namespace App\Http\Requests;

class StoreDpTrainingInstitutionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Request::ajax()) {
            if (request()->has('fromPosition') && request()->has('toPosition')) {
                return [
                    'fromPosition' => 'required',
                    'fromId' => 'required',
                    'toPosition' => 'required',
                ];
            } else {
                return [
                    'active' => 'required',
                ];
            }
        }

        return [
            'name'      => 'required',
            'county_id' => 'required|numeric',
            'phone'     => 'required',
            'url'       => 'nullable|url',
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
