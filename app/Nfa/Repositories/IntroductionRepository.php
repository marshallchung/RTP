<?php

namespace App\Nfa\Repositories;

use App\Introduction;
use App\IntroductionType;
use Auth;

class IntroductionRepository implements IntroductionRepositoryInterface
{
    public function getIntroductions(IntroductionType $type = null)
    {
        $query = Introduction::sorted()->with(['author', 'introductionType']);
        if ($type) {
            $query->where('introduction_type_id', $type->id);
        }

        return $query->paginate(20);
    }

    public function getDashboardIntroductions()
    {
        return Introduction::sorted()->with('author')->where('active', true)->simplePaginate(5);
    }

    public function postIntroduction($data)
    {
        $user = Auth::user();

        $data['introduction_type_id'] = $data['introduction_type_id'];

        return $user->introductions()->create($data);
    }
}
