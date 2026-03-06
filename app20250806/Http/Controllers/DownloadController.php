<?php

namespace App\Http\Controllers;

use App\Nfa\Repositories\FileRepositoryInterface;

class DownloadController extends Controller
{
    public function get(FileRepositoryInterface $fileRepo, $year, $month, $id)
    {
        $file = $fileRepo->getFileById($id);
        if (file_exists(storage_path("app/{$file->path}"))) {
            if (substr($file->mime_type, 0, 5) === 'image') {
                $header = [
                    'Content-Type' => $file->mime_type,
                    'Content-Disposition' => 'inline; filename="' . $file->name . '"',
                ];
                return response()->file(storage_path("app/{$file->path}"), $header);
            } else {
                return response()->download(storage_path("app/{$file->path}"), $file->name);
            }
        } else {
            return response([], 404);
        }
    }

    public function stream(FileRepositoryInterface $fileRepo, $year, $month, $id)
    {

        $file = $fileRepo->getFileById($id);
        if (file_exists(storage_path("app/{$file->path}"))) {
            $header = [
                "Content-type" => $file->mime_type,
                'Content-Disposition' => 'inline; filename="' . $file->name . '"'
            ];
            $data = file_get_contents(storage_path("app/{$file->path}"));
            return response()->make($data, 200, $header);
        } else {
            return response([], 404);
        }
    }
}
