<?php

namespace App\Nfa\Repositories;

interface DpCourseRepositoryInterface
{
    public function getData();

    public function getDashboardData();

    public function postdata($data);
}
