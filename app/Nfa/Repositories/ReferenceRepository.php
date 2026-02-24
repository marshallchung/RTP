<?php

namespace App\Nfa\Repositories;

use App\Reference;
use Auth;

class ReferenceRepository implements ReferenceRepositoryInterface
{
    public function getReferences()
    {
        return Reference::with('author')->latest('created_at')->paginate(20);
    }

    public function getDashboardReferences()
    {
        return Reference::with('author')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    public function postReference($data)
    {
        $user = Auth::user();

        return $user->references()->create($data);
    }
}
