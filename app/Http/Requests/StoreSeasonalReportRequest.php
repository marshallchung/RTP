<?php

namespace App\Http\Requests;

use App\Topic;

class StoreSeasonalReportRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->has('removed_files') && !$this->hasFile('files')) {
            return [];
        }

        if ($this->has('topic_id') && !$this->hasFile('files')) {
            return [
                'files.0' => 'required',
            ];
        }

        $validation = [
            'year'   => 'required',
            'season' => 'required',
        ];

        $filesCount = count($this->file('files')) - 1;

        $topic = Topic::where('title', '災害潛勢圖資')->first();
        $topicId = ($topic) ? $topic->id : -1;
        if ($topic && $this->get('topic_id_for_validation') == $topicId) {
            do {
                $validation["files.{$filesCount}"] = 'mimes:kml,kmz,bin,xml,zip';
            } while ($filesCount--);
        } else {
            do {
                $validation["files.{$filesCount}"] = 'mimes:pdf,doc,docx,jpg,jpeg,png,gif,zip,rar,txt,csv,xlsx,odf,mp4,mov';
            } while ($filesCount--);
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
