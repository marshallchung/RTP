<?php

namespace App\Http\Requests;

class StoreDpScoreRequest extends Request
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
            'course_id' => 'required|numeric',
            'TID'       => 'required|array',
            'score'     => 'required|array',
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
