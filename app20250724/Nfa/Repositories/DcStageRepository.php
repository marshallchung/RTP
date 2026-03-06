<?php

namespace App\Nfa\Repositories;

use App\DcUnit;
use App\User;

class DcStageRepository implements DcStageRepositoryInterface
{
    public function getDcUnits()
    {
        $dcUnitQuery = DcUnit::query();
        /** @var User $user */
        $user = auth()->user();
        if ($user->origin_role >= 4) {
            $dcUnitQuery->where('county_id', $user->id);
        }
        return $dcUnitQuery->pluck('name', 'id')->toArray();
    }

    public function getDcUnitsOfCounty(User $countyAccount, $dc_unit_name = null)
    {
        $dcUnitQuery = DcUnit::where('county_id', $countyAccount->id);
        if ($dc_unit_name) {
            $dcUnitQuery->where('name', 'LIKE', "%{$dc_unit_name}%");
        }
        /** @var User $user */
        $user = auth()->user();
        if ($user->origin_role >= 4 && $user->origin_role != 6) {
            $dcUnitQuery->where('county_id', $user->id);
        }
        return $dcUnitQuery->pluck('name', 'id')->toArray();
    }
}
