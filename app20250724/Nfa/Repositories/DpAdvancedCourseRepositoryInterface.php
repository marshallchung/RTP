<?php

namespace App\Nfa\Repositories;

interface DpAdvancedCourseRepositoryInterface
{
    public function getData();

    public function getDashboardData();

    public function postdata($data);
}
