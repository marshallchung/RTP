<?php

namespace App\Nfa\Repositories;

interface DpResourceRepositoryInterface
{
    public function getData();

    public function postData($data);
}
