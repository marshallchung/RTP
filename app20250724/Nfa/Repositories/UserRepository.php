<?php

namespace App\Nfa\Repositories;

use App\Models\UsersPasswordHistory;
use App\User;
use App\UserAlias;
use Auth;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function getUsers()
    {
        /** @var User $user */
        $user = auth()->user();
        $userQuery = User::where('username', '!=', 'admin')->with('userAliases');
        if (!in_array($user->origin_role, [1, 2, 6])) {
            $userQuery->where('id', $user->id);
        }

        return $userQuery->get();
    }

    public function getCountyDistrictAccountsByClassThenArea($withQuestionnires = false)
    {
        if (!$withQuestionnires) {
            $accounts = $this->getCountyDistrictAccounts();
        } else {
            $accounts = $this->getCountyDistrictAccountsWithQuetionnaire();
        }

        $accountsByClass = $this->sortAccountsByClass($accounts);
        $accountsByArea = $this->sortAccountsByArea($accountsByClass);

        //dd($accountsByArea);

        return $accountsByArea;
    }

    public function getCountyDistrictAccounts()
    {
        return User::where('type', 'county')->orWhere('type', 'district')->get();
    }

    public function getCountyDistrictAccountsWithQuetionnaire()
    {
        return User::with('questionnaires')->where('type', 'county')->orWhere('type', 'district')->get();
    }

    private function sortAccountsByClass($accounts)
    {
        $counties = [];
        $countyIdIndex = [];

        foreach ($accounts as $account) {
            if ($account->class == 0) {
                $account->districts = [
                    '1' => [],
                    '2' => [],
                ];

                array_push($counties, $account);

                $countyIdIndex[$account->id] = count($counties) - 1;
            } else {
                $index = $countyIdIndex[$account->county_id];

                $districts = $counties[$index]->districts;

                array_push($districts[$account->class], $account);

                $counties[$index]->districts = $districts;
            }
        }

        return $counties;
    }

    private function sortAccountsByArea($accounts)
    {
        $areas = [
            ['name' => '北部', 'accounts' => []],
            ['name' => '中部', 'accounts' => []],
            ['name' => '南部', 'accounts' => []],
            ['name' => '東部', 'accounts' => []],
            ['name' => '離島地區', 'accounts' => []],
        ];

        foreach ($accounts as $account) {
            array_push($areas[$account->area]['accounts'], $account);
        }

        return $areas;
    }

    public function getCountyDistrictAccountsByClassThenLevel()
    {
        $accounts = $this->getCountyDistrictAccounts();

        $accountsByClass = $this->sortAccountsByClass($accounts);

        $accountsByLevel = $this->sortAccountsByLevel($accountsByClass);

        return $accountsByLevel;
    }

    private function sortAccountsByLevel($accounts)
    {
        $levels = [
            ['name' => '第一梯', 'accounts' => []],
            ['name' => '第二梯', 'accounts' => []],
        ];

        foreach ($accounts as $account) {
            array_push($levels[$account->level - 1]['accounts'], $account);
        }

        return $levels;
    }

    public function findByName($name)
    {
        return User::find($name);
    }

    public function findParentCountyOrNull($user)
    {
        $countyId = $user->county_id;

        if ($countyId === null) {
            return null;
        }

        return $this->findById($countyId);
    }

    public function findById($id)
    {
        return User::find($id);
    }

    public function updatePassword($password)
    {
        $user = Auth::user();
        $user->password = bcrypt($password);
        $user->change_default = 0;
        $user->next_change = date("Y-m-d H:i:s", strtotime("+3 month"));
        UsersPasswordHistory::create(['user_id' => $user->id, 'password' => $user->password]);
        return $user->save();
    }

    public function resetAccountPassword($id, $is_alias = null)
    {
        if ($is_alias) {
            /** @var UserAlias $user */
            $user = UserAlias::find($id);
            $user->password = md5('5KM2wztB');
        } else {
            /** @var User $user */
            $user = User::find($id);
            $user->password = bcrypt('5KM2wztB');
        }

        return $user->save();
    }
}
