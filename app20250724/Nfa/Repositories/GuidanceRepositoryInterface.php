<?php

namespace App\Nfa\Repositories;

interface GuidanceRepositoryInterface
{
    public function getData();

    public function getDashboardData();

    public function postData($data);
}
