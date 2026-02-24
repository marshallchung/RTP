<?php

namespace App\Nfa\Repositories;

interface DcUnitRepositoryInterface
{
    public function getData();

    public function getFilteredData();

    public function getAllData();

    public function getAllFilteredData();

    public function getRankCount();

    public function getWithinPlanCount();

    public function getNativeCount();

    public function getExpireCount();

    public function getDateExtensionCount();

    public function getDashboardUnits();

    public function postData($data);
}
