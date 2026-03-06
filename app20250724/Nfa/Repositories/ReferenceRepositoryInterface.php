<?php

namespace App\Nfa\Repositories;

interface ReferenceRepositoryInterface
{
    public function getReferences();

    public function getDashboardReferences();

    public function postReference($data);
}
