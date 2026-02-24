<?php

namespace App\Nfa\Repositories;

interface DpStudentRepositoryInterface
{
    public function getData();

    public function getFilteredData();

    public function getAllData();

    public function getAllFilteredData();

    public function getPassCount();

    public function getDashboardData();

    public function postdata($data);
}
