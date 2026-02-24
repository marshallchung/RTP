<?php

namespace App\Nfa\Repositories;

use App\File;

class FileRepository implements FileRepositoryInterface
{
    public function getFileById($id)
    {
        return File::where('uid', $id)->first();
    }

    public function find($id)
    {
        return File::find($id);
    }
}
