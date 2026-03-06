<?php

namespace App\Nfa\Traits;

use App\File;
use finfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait FileUploadTrait
{
    /**
     * @param Request $request
     * @param $model
     * @param string $memo
     * @param string $fileField
     */
    public function handleFiles($request, $model, $memo = '', $fileField = 'files', $year = null)
    {
        Log::debug('handleFiles');
        if ($this->hasRemovedFiles($request->get('removed_files'))) {
            $this->removeFiles($request->get('removed_files'));
        }

        // Laravel 5.5 似乎無法用 $request->files->set('files', $request->file("files_{$idx}")??[]); 修改檔案欄位
        // 只能先把 $fileField 作為參數傳入
        if ($request->hasFile($fileField)) {
            Log::debug('request hasFile');
            $this->attachFiles($request->file($fileField), $model, $memo, $year);
        } else {
            Log::debug('request NO File');
        }
    }

    public function hasRemovedFiles($removedFiles)
    {
        return isset($removedFiles) && !empty(json_decode($removedFiles));
    }

    protected function removeFiles($files)
    {
        File::destroy(json_decode($files));
    }

    /**
     * @param UploadedFile[] $files
     * @param $post
     */
    protected function attachFiles($files, $post, $memo, $year = null)
    {
        $attachments = [];
        $files = array_filter($files);
        Log::debug('attachFiles files count: ' . count($files));
        foreach ($files as $file) {
            $ext = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            try {
                $mimeType = $file->getMimeType();
            } catch (\Exception $e) {
                $mimeType = $file->getClientMimeType();
            }
            $origName = $this->mbPathinfo($file->getClientOriginalName())['filename'];
            $path = 'uploads' . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m');

            $uid = md5(uniqid(rand()));
            $file_path = storage_path('app/' . $path);
            Log::debug('file path: ' . $file_path . ',name: ' . $uid);
            $file->move($file_path, $uid);
            $file_data = [
                'uid'       => $uid,
                'name'      => "{$origName}.{$ext}",
                'path'      => $path . DIRECTORY_SEPARATOR . $uid,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'memo'      => $memo,
            ];
            if ($year) {
                $file_data['created_at'] = $year . date('-m-d H:i:s');
            }
            array_push($attachments, new File($file_data));
        }
        $post->files()->saveMany($attachments);
    }

    private function mbPathinfo($filepath)
    {
        preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', $filepath, $matches);

        if ($matches[1]) {
            $pathinfo['dirname'] = $matches[1];
        }
        if ($matches[2]) {
            $pathinfo['basename'] = $matches[2];
        }
        if ($matches[5]) {
            $pathinfo['extension'] = $matches[5];
        }
        if ($matches[3]) {
            $pathinfo['filename'] = $matches[3];
        }

        return $pathinfo;
    }
}
