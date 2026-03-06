<?php

namespace App\Nfa\Repositories;

interface DpTrainingInstitutionRepositoryInterface
{
    public function getData();

    public function getFilteredData();

    public function getAllData();

    public function getAllFilteredData();

    public function getDashboardData();

    public function postdata($data);
}
