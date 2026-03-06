<?php

namespace App\Http\Requests;

class StoreUploadRequest extends Request
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

        $validation = [
            'name' => 'required',
            'files.0' => 'mimes:pdf,doc,docx,jpg,jpeg,png,gif,zip,rar,txt,csv,xlsx,odf,mp4,mov,ppt,pptx',
        ];

        if ($this->method === 'PUT' && !$this->hasRemovedFiles($this->get('removed_files'))) {
            unset($validation['files.0']);
        }

        return $validation;
    }

    private function hasRemovedFiles($removedFiles)
    {
        return isset($removedFiles) && !empty(json_decode($removedFiles));
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
