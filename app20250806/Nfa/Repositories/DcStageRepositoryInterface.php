<?php

namespace App\Nfa\Repositories;

use App\User;

interface DcStageRepositoryInterface
{
    public function getDcUnits();

    public function getDcUnitsOfCounty(User $countyAccount);
}
