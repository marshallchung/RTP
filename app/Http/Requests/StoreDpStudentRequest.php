<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class StoreDpStudentRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Request::ajax()) {
            if (request()->has('willingness')) {
                return [
                    'willingness' => 'required',
                ];
            } else {
                return [
                    'active' => 'required',
                ];
            }
        }

        return [
            'TID'               => 'required|unique:dp_students,id,' . Auth::user()->id,
            'name'              => 'required',
            'birth'             => 'required|numeric',
            'gender'            => 'required',
            //            'field'             => 'required',
            //'phone'              => 'required',
            'mobile'            => 'nullable|regex:/^[\d]{4}[-]?[\d]{6}$/',
            'email'             => 'nullable|email',
            'address'           => 'required',
            'county_id'         => 'required|numeric',
            //'community'          => 'required',
            'unit_first_course' => 'required',
            'date_first_finish' => 'required',
            'plan'              => 'required',
            'score_academic'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'TID.unique'                 => '此身分證字號已註冊過',
            'TID.required'               => '請輸入身分證字號',
            'name.required'              => '請輸入姓名',
            'birth.required'             => '請輸入生日',
            'gender.required'            => '請輸入性別',
            'field.required'             => '請輸入工作領域',
            //'phone.required' => '請輸入市內電話',
            'mobile.regex'               => '行動電話格式錯誤',
            'mobile.required'            => '請輸入行動電話',
            'email.required'             => '請輸入E-mail',
            'email.email'                => 'E-mail格式錯誤',
            'address.required'           => '請輸入居住地址',
            'county_id.required'         => '請輸入所屬縣市',
            'unit_first_course.required' => '請輸入受訓單位',
            'date_first_finish.required' => '請輸入證書發放日期',
            //'community.required' => '請輸入所屬村里或社區',
            'plan.required'              => '請輸入培訓計畫名稱',
            'score_academic.required'    => '請輸入學科測驗成績',
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
