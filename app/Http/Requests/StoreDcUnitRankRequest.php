<?php

namespace App\Http\Requests;

class StoreDcUnitRankRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rank_started_date' => 'required_unless:rank,未審查',
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
