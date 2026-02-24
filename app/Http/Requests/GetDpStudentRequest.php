<?php

namespace App\Http\Requests;

class GetDpStudentRequest extends Request
{
    public function messages()
    {
        return [
            'TID.exists' => '查無此身分證字號',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'TID' => 'exists:dp_students,TID',
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
