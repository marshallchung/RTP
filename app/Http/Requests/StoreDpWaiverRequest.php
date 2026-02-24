<?php

namespace App\Http\Requests;

class StoreDpWaiverRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //if (Request::ajax()) {
        //    return [
        //        'active' => 'required',
        //    ];
        //}

        return [
            'dp_student_id' => 'required|exists:dp_students,id',
            'dp_course_id'  => 'required|array',
            'waiverName'    => 'required|array',
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
            'dp_student_id.required' => '請先輸入正確的防災士身分證字號',
            'dp_student_id.exists'   => '請先輸入正確的防災士身分證字號',
        ];
    }
}
