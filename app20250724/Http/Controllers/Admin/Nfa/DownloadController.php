<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Http\Controllers\Controller;
use App\Nfa\Repositories\FileRepositoryInterface;
use Auth;

class DownloadController extends Controller
{
    public function get(FileRepositoryInterface $fileRepo, $year, $month, $id)
    {
        if (!Auth::check()) {
            dd('permission denied.');
        }

        $file = $fileRepo->getFileById($id);

        return response()->download(storage_path("app/{$file->path}"), $file->name);
    }

    public function stream(FileRepositoryInterface $fileRepo, $year, $month, $id)
    {
        if (!Auth::check()) {
            dd('permission denied.');
        }

        $file = $fileRepo->getFileById($id);
        $header = [
            "Content-type" => $file->mime_type
        ];

        return response()->streamDownload(
            function () use ($file) {
                file_get_contents(storage_path("app/{$file->path}"));
            },
            $file->name,
            $header
        );
    }
}
