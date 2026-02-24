<?php

namespace App\Nfa\Repositories;

interface DcDownloadRepositoryInterface
{
    public function getData();

    public function getDashboardData();

    public function postData($data);
}
