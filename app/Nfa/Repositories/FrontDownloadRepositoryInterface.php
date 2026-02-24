<?php

namespace App\Nfa\Repositories;

interface FrontDownloadRepositoryInterface
{
    public function getData();

    public function getDashboardData();

    public function postData($data);
}
