<?php

namespace App\Http\Requests;

class StoreDpCourseRequest extends Request
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
            'url'       => 'nullable',
            'county_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to'   => 'required|date',
            'name'      => 'required',
            'content'   => 'required|different:content-filter',
            'email'     => 'required|email',
            'phone'     => 'required',
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

    public function messages()
    {
        return [
            'url.required'     => '報名連結未填寫',
            'content.required' => '培訓計畫摘要未填寫',
            'email.required'   => '電子郵件未填寫',
            'phone.required'   => '連絡電話未填寫',
        ];
    }
}
