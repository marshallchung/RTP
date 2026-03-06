<?php

namespace App\Nfa\Repositories;

interface UserRepositoryInterface
{
    public function getUsers();

    public function getCountyDistrictAccounts();

    public function getCountyDistrictAccountsWithQuetionnaire();

    public function getCountyDistrictAccountsByClassThenArea($withQuestionnires = false);

    public function getCountyDistrictAccountsByClassThenLevel();

    public function findById($id);

    public function findParentCountyOrNull($user);

    public function updatePassword($password);

    public function resetAccountPassword($id, $is_alias);
}
