<?php

namespace App\Nfa\Repositories;

interface UploadRepositoryInterface
{
    public function getUploads();

    public function getViewUploads();

    public function getDashboardUploads();

    public function getCommitteeForms();

    public function addUpload($data);
}
