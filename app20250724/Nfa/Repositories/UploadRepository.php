<?php

namespace App\Nfa\Repositories;

use App\Upload;
use Auth;

class UploadRepository implements UploadRepositoryInterface
{
    public function getUploads()
    {
        return Upload::sorted()->with('user', 'files')->latest('created_at')->paginate(20);
    }

    public function getViewUploads()
    {
        return Upload::sorted()->with('user', 'files')->where('active', true)->latest('created_at')->paginate(20);
    }

    public function getDashboardUploads()
    {
        return Upload::sorted()->with('user', 'files')->where('type', 'related-files')
            ->where('active', true)->latest('created_at')->paginate(20);
    }

    public function getCommitteeForms()
    {
        /** @var Upload $evaluation */
        $evaluation = Upload::where('type', 'evaluation')->where('active', true)->latest('created_at')->first();
        /** @var Upload $instructions */
        $instructions = Upload::where('type', 'evaluation-instructions')->where('active', true)->latest('created_at')->first();

        if ($evaluation) {
            $evaluation = $evaluation->files()->first();
        }
        if ($instructions) {
            $instructions = $instructions->files()->first();
        }

        return compact('evaluation', 'instructions');
    }

    public function addUpload($data)
    {
        $user = Auth::user();

        return $user->uploads()->create($data);
    }
}
