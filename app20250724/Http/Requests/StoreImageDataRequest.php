<?php

namespace App\Http\Requests;

class StoreImageDataRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validation = [
            'files.0' => 'required',
        ];

        $imageDatum = request()->route('image_datum');
        if (
            ($imageDatum && $imageDatum->files->count() > 0)
            && !$this->hasRemovedAllFiles($imageDatum, $this->get('removed_files'))
        ) {
            unset($validation['files.0']);
        }

        return $validation;
    }

    private function hasRemovedAllFiles($imageDatum, $removedFiles)
    {
        $removedFileArray = json_decode($removedFiles);

        return isset($removedFiles) && count($removedFileArray) == $imageDatum->files->count();
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
