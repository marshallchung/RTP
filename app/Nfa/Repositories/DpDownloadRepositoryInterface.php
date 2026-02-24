<?php

namespace App\Nfa\Repositories;

interface DpDownloadRepositoryInterface
{
    public function getData();

    public function getDashboardData();

    public function postData($data);
}
