<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Http\Controllers\Controller;
use App\Nfa\Repositories\UploadRepositoryInterface;
use App\Nfa\Repositories\UserRepositoryInterface;
use Illuminate\Http\Response;

class CommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param UserRepositoryInterface $userRepo
     * @param UploadRepositoryInterface $uploadRepo
     * @return  Response
     */
    public function index(UserRepositoryInterface $userRepo, UploadRepositoryInterface $uploadRepo)
    {
        $files = $uploadRepo->getCommitteeForms();

        $accounts = $userRepo->getCountyDistrictAccountsByClassThenLevel();

        return view('admin.committee.index', compact('files', 'accounts'));
    }
}
