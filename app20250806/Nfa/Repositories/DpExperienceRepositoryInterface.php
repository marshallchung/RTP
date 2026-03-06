<?php

namespace App\Nfa\Repositories;

interface DpExperienceRepositoryInterface
{
    public function getCourses();

    public function getStudent($TID);

    public function getDashboardData();

    public function postdata($data);
}
