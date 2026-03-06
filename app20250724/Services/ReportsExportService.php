<?php

namespace App\Services;

use App\User;

class ReportsExportService
{
    /**
     * @param String $year
     * @param User $account
     * @param Array $reports
     * @param bool $forceRegenerate
     * @return string
     */
    public function export($year, User $account, $reports, bool $forceRegenerate = false)
    {
        $fullName = sprintf('%s_%s_成果資料', $year, $account->name);
        $temp_path = sys_get_temp_dir();
        $zipFilePath = $temp_path . DIRECTORY_SEPARATOR . $fullName . '.zip';
        if (\File::exists($zipFilePath) && !$forceRegenerate) {
            // 檔案已存在，且不要求重新生成，就直接回傳檔案路徑
            return $zipFilePath;
        }

        $tmpDir = $temp_path . DIRECTORY_SEPARATOR . $fullName;
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
        mkdir($tmpDir);

        if (file_exists($tmpDir . '.zip')) {
            unlink($tmpDir . '.zip');
        }

        error_reporting(E_ALL ^ E_DEPRECATED);

        foreach ($reports as $file) {
            $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $file['path']);
            if (file_exists($filePath)) {
                copy($filePath, $tmpDir . DIRECTORY_SEPARATOR . $file['name']);
            }
        }
        $files = glob($tmpDir . DIRECTORY_SEPARATOR . '*');
        if (count($files) > 0) {
            $zip = \Zip::create($zipFilePath);
            $zip->add($files);
            $zip->close();
        } else {
            $zipFilePath = '';
        }

        return $zipFilePath;
    }
}
