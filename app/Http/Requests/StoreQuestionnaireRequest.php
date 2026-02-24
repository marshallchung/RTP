<?php

namespace App\Http\Requests;

class StoreQuestionnaireRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // create
        if (Request::method() == 'POST') {
            return [
                'date_from'            => 'required|date',
                'expire_soon_date'     => 'required|date',
                'date_to'              => 'required|date',
                'original_total_score' => 'nullable|integer|min:0',
            ];
        } else {
            return [
                'date_from'            => 'required|date',
                'expire_soon_date'     => 'required|date',
                'date_to'              => 'required|date',
                'original_total_score' => 'nullable|integer|min:0',
            ];
        }
    }

    public function messages()
    {
        if (Request::method() == 'POST') {
            return [
                'file.required'      => '請上傳檔案',
                'file.mimes'         => '請將檔案存成xlsx形式',
                'date_from.date'     => '日期格式錯誤',
                'expire_soon_date.date'       => '日期格式錯誤',
                'date_to.date'       => '日期格式錯誤',
                'date_from.required' => '請輸入開放時間',
                'expire_soon_date.required'   => '請輸入即將逾期通知時間',
                'date_to.required'   => '請輸入關閉時間',
            ];
        } else {
            return [
                'file.mimes'         => '請將檔案存成xlsx形式',
                'date_from.date'     => '日期格式錯誤',
                'expire_soon_date.date'       => '日期格式錯誤',
                'date_to.date'       => '日期格式錯誤',
                'date_from.required' => '請輸入開放時間',
                'expire_soon_date.required'   => '請輸入即將逾期通知時間',
                'date_to.required'   => '請輸入關閉時間',
            ];
        }
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
