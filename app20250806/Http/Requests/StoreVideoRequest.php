<?php

namespace App\Http\Requests;

class StoreVideoRequest extends Request
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
            'title'   => 'required',
            'content' => 'nullable',
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
