<?php

namespace App\Services;

use App\DcUnit;

class DcCertificationExportService
{
    /**
     * @param String $star
     * @param DcUnit $dc_unit
     * @param Array $reports
     * @param bool $forceRegenerate
     * @return string
     */
    public function export(DcUnit $dc_unit, $star, bool $forceRegenerate = false)
    {
        $star_name = "";
        if ($star === '1') {
            $star_name = "一星";
        } elseif ($star === '2') {
            $star_name = "二星";
        } elseif ($star === '3') {
            $star_name = "三星";
        }
        $fullName = sprintf('%s_%s_標章申請表', $dc_unit->county->name . $dc_unit->name, $star_name);
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

        $dc_certifications = $dc_unit->dcCertifications->keyBy('term');
        foreach ($dc_certifications as $term_id => $one_certification) {
            if ($file_list = $one_certification->files()->get()) {
                $file_list = $file_list->toArray();
                foreach ($file_list as $file) {
                    $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $file['path']);
                    if (file_exists($filePath)) {
                        copy($filePath, $tmpDir . DIRECTORY_SEPARATOR . $file['name']);
                    }
                }
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
