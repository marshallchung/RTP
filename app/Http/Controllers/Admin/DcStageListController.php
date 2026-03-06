<?php

namespace App\Http\Controllers\Admin;

use App\DcUnit;
use App\Http\Requests\StoreDcStageRequest;
use App\Nfa\Repositories\DcStageRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\User;
use Flash;
use Illuminate\Http\Request;

class DcStageListController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param DcStageRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, DcStageRepositoryInterface $repo)
    {
        // DCunit
        // dc_units join files where files.post_type = 	App\DcUnit , post_id = 	App\DcUnit.id
        $query = DcUnit::query()->with(['files'])
            ->orderBy('updated_at','desc');

        // 分頁，每頁 20 筆
        $list = $query->paginate(20);

        // 手動加上 query string（例如搜尋、篩選條件）
        $list->appends($request->query());

        $dataList = [];
        foreach($list as $data) {
            $dataList[] = [
                'county_name' => $data->county->name,
                'name'  => $data->name,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
                'files'       => $data->files->take(2)->map(function ($file) {
                    return [
                    'name' => $file->file_name,
                    'path' => $file->file_path,
                    'size' => $file->file_size,
                    ];
                })->toArray(),
            ];
        }
        return view('admin.dc-stages-list.index', [
            'list'             => $list,
            'pagination'  => $list->links()->render(), // Laravel 9+ 建議直接用 $list->links()
        ]);
    }

}
