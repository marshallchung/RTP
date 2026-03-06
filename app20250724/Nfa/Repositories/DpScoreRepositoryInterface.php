<?php

namespace App\Nfa\Repositories;

interface DpScoreRepositoryInterface
{
    public function getCourses();

    public function getCourseStudents($id);

    public function getDashboardData();

    public function postdata($data);
}
