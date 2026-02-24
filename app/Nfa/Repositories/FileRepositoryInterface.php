<?php

namespace App\Nfa\Repositories;

interface FileRepositoryInterface
{
    public function getFileById($id);

    public function find($id);
}
