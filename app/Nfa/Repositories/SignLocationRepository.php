<?php

namespace App\Nfa\Repositories;

use App\SignLocation;
use App\User;

class SignLocationRepository implements SignLocationRepositoryInterface
{

    public function getSignLocations(User $countyUser = null)
    {
        $signLocationQuery = SignLocation::with('user');
        if ($countyUser) {
            $countyUserIds = User::where('id', $countyUser->id)->orWhere('county_id', $countyUser->id)->pluck('id');
            $signLocationQuery->whereIn('user_id', $countyUserIds);
        }

        return $signLocationQuery->latest('created_at')->paginate(50);
    }

    /**
     * @param User $user
     * @param $data
     * @return mixed
     */
    public function storeSignLocation($user, $data)
    {
        $signLocation = new SignLocation($data);
        //若有指定單位
        if (isset($data['user_id'])) {
            $countyUser = User::find($data['user_id']);
            if ($countyUser) {
                //替換為指定單位
                $user = $countyUser;
            }
        }

        return $user->signLocations()->save($signLocation);
    }

    /**
     * @param User $countyUser
     * @return array
     */
    public function getCountySelectOptions(User $countyUser)
    {
        $users = User::where('id', $countyUser->id)->orWhere('county_id', $countyUser->id)
            ->pluck('name', 'id')->toArray();

        return $users;
    }
}
