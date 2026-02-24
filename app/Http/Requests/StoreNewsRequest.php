<?php

namespace App\Http\Requests;

class StoreNewsRequest extends Request
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
            'content' => 'required|different:content-filter',
            'files.0'   => 'mimes:pdf,doc,docx,jpg,jpeg,png,gif,zip,rar,txt,csv,xlsx,odf,mp4,mov,ppt,pptx',
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
