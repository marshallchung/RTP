<?php

namespace App\Http\Requests;

class StorePresentationRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->has('topic_id') && !$this->hasFile('files')) {
            return ['files.0' => 'required'];
        }

        $validation = [];

        $filesCount = $this->file('files') ? count($this->file('files')) : 0;

        if ($filesCount > 0) {
            $fileIndex = $filesCount - 1;

            do {
                $validation["files.{$fileIndex}"] = 'mimes:pdf,doc,docx,jpg,jpeg,png,gif,zip,rar,txt,csv,xlsx,odf,mp4,mov,ppt,pptx';
            } while ($fileIndex--);
        }

        return $validation;
    }

    public function messages()
    {
        return [
            'files.0.required' => trans('validation.emptyUpload'),
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
