<?php

namespace App\Http\Requests;

class StoreDpTeacherRequest extends Request
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
//            'location'  => 'required',
//            'belongsTo' => 'required',
//            'title'     => 'required',
            'name'   => 'required',
            'tid'    => 'required',
//            'content'   => 'required|different:content-filter',
            'email'  => 'nullable|email',
            'phone'  => 'nullable',
            'mobile' => 'nullable',
            //'introduction_type_id' => 'exists:introduction_types,id',
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
