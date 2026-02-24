<?php

namespace App\Http\Requests;

class StoreDcStageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Request::ajax()) {
            return [
                'active' => 'required',
            ];
        }

        return [
            'dc_unit_id' => 'required|exists:dc_units,id',
            'stage'      => 'required|numeric',
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
