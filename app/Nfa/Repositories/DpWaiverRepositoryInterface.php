<?php

namespace App\Nfa\Repositories;

interface DpWaiverRepositoryInterface
{
    public function getCourses();

    public function getWaivers($course_id);

    public function getStudent($TID);

    public function getDashboardData();

    public function postdata($data);
}
