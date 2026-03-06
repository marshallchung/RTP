<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class PresentationExportService
{
    /**
     * @param Collect $countyUsers
     * @return string
     */
    public function export(Collection $countyUsers, $year, bool $forceRegenerate = false)
    {
        $fullName = (intval($year) - 1911) . "年期末簡報";
        $temp_path = sys_get_temp_dir();
        $zipFilePath = $temp_path . DIRECTORY_SEPARATOR . $fullName . '.zip';
        if (\File::exists($zipFilePath) && !$forceRegenerate) {
            // 檔案已存在，且不要求重新生成，就直接回傳檔案路徑
            return $zipFilePath;
        }
        foreach ($countyUsers as $one_user) {
            $tmpDir = $temp_path . DIRECTORY_SEPARATOR . $fullName . DIRECTORY_SEPARATOR . $one_user->name;
            if (is_dir($tmpDir)) {
                $objects = scandir($tmpDir);
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        unlink($tmpDir . '/' . $object);
                    }
                }
                reset($objects);
                rmdir($tmpDir);
            }
            mkdir($tmpDir, 0777, true);
        }
        $tmpDir = $temp_path . DIRECTORY_SEPARATOR . $fullName;

        if (file_exists($tmpDir . '.zip')) {
            unlink($tmpDir . '.zip');
        }

        error_reporting(E_ALL ^ E_DEPRECATED);
        $file_count = 0;
        foreach ($countyUsers as $one_user) {
            foreach ($one_user->presentation as $one_presentation) {
                if ($file_list = $one_presentation->files()->get()) {
                    $file_list = $file_list->toArray();
                    foreach ($file_list as $file) {
                        $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $file['path']);
                        if (file_exists($filePath)) {
                            copy($filePath, $tmpDir . DIRECTORY_SEPARATOR . $one_user->name . DIRECTORY_SEPARATOR . $file['name']);
                            $file_count++;
                        }
                    }
                }
            }
        }
        $files = glob($tmpDir . DIRECTORY_SEPARATOR . '*');
        if ($file_count > 0) {
            $zip = \Zip::create($zipFilePath);
            $zip->add($files);
            $zip->close();
        } else {
            $zipFilePath = '';
        }

        return $zipFilePath;
    }
}
