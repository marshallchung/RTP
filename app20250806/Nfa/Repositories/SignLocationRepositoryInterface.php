<?php

namespace App\Nfa\Repositories;

use App\User;

interface SignLocationRepositoryInterface
{
    public function getSignLocations(User $countyUser = null);

    public function storeSignLocation($user, $data);

    public function getCountySelectOptions(User $countyUser);
}
