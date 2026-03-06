<?php

namespace App\Http\Requests;

class StoreDpCivilRequest extends Request
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
            'name'      => 'required',
            'phone'     => ['required', 'regex:/^\d{1,45}$/'],
            'address'   => 'required',
            'front_man' => 'required',
            'business'  => 'required',
            'url'       => 'required|url',
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
