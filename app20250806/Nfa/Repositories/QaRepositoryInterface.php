<?php

namespace App\Nfa\Repositories;

interface QaRepositoryInterface
{
    public function getQas($keyWord, $sort);

    public function getDashboardQa();

    public function postQa($data);
}
