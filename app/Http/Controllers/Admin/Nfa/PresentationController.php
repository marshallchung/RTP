<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Exports\CountyReportExport;
use App\Exports\ReportExport;
use App\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePresentationRequest;
use App\Jobs\ZipPresentationDocuments;
use App\Nfa\Repositories\FileRepositoryInterface;
use App\Nfa\Repositories\PresentationRepositoryInterface;
use App\Nfa\Repositories\UserRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Presentation;
use App\Services\PresentationExportService;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PresentationController extends Controller
{
    use FileUploadTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['county']) && !$user->hasPermission('create-plans-for-county')) {
            Flash::error('使用期末簡報上傳 - 資料上傳功能請用縣市權限帳號登入。');

            return redirect()->back();
        }
        if ($user->hasPermission('create-plans-for-county')) {
            $countyOptions = [null => '- 下拉選擇縣市 -'] + User::whereType('county')->pluck('name', 'id')->toArray();
            view()->share(compact('countyOptions'));
        }

        $year = $request->get('year', date('Y'));

        $files = [];
        if ($plan = $user->presentation()->where('year', $year)->first()) {
            $files = $plan->files;
        }

        return view('admin.presentation.create', compact('files', 'year'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePresentationRequest $request
     * @return Response
     */
    public function store(StorePresentationRequest $request)
    {
        if (auth()->user()->hasPermission('create-plans-for-county')) {
            $asCountyUser = User::find($request->get('county_id'));
        } else {
            $asCountyUser = $request->user();
        }
        $plan = Presentation::firstOrCreate([
            'user_id' => $asCountyUser->id,
            'year'    => $request->input('year'),
        ]);

        $this->handleFiles($request, $plan);

        Flash::success('檔案上傳成功');

        return redirect()->route('admin.presentation.create', [
            'id'   => $request->user()->id,
            'year' => $request->input('year'),
        ]);
    }

    public function download(UserRepositoryInterface $userRepo, PresentationRepositoryInterface $reportRepo, $id, $year = null)
    {
        if (!isset($year)) {
            $year = date('Y');
        }
        // $user = $userRepo->findByName($name);

        $report = $reportRepo->getPresentationFilesByUserIdAndYear($id, $year);

        $path = $this->dispatch(new ZipPresentationDocuments($report));

        return response()->download($path, "{$id}-執行成果.zip");
    }

    /**
     * 下載Excel檔案(.xlsx)。
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadXlsx()
    {
        $fileName = '深耕資訊網資料查詢';

        return \Excel::download(new ReportExport($fileName, request()->all()), $fileName . '.xlsx');
    }

    /**
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadXlsxByCounty()
    {
        $fileName = '深耕資訊網資料查詢';

        return \Excel::download(new CountyReportExport($fileName, request()->all()), $fileName . '.xlsx');
    }

    /**
     * 取得公開日期。
     *
     */
    public function getPublicDates(PresentationRepositoryInterface $reportRepo)
    {
        $publicDates = $reportRepo->getPublicDates();

        return view('admin.presentation.dates', compact('publicDates'));
    }

    /**
     * 更新公開日期。
     *
     */
    public function updatePublicDate(PresentationRepositoryInterface $reportRepo, Request $request, $id)
    {
        extract($request->only(['year', 'date', 'expire_soon_date', 'expire_date']));

        $publicDate = $reportRepo->updatePublicDate($id, $year, $date, $expire_soon_date, $expire_date);

        Flash::success(trans('app.updateSuccess', ['type' => '公開日期']));

        return redirect()->back();
    }


    /**
     *  2016年修改, 更改對民眾網開放權限。
     * @param FileRepositoryInterface $fileRepo
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle(FileRepositoryInterface $fileRepo, $id)
    {
        /** @var File $file */
        $file = $fileRepo->find($id);

        if (!$file) {
            abort(404);
        }

        $file->opendata = !($file->opendata);
        $file->save();

        return redirect()->back();
    }

    /**
     *  2016年修改, inquire依分類及縣市。
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inquire(Request $request)
    {
        $user = auth()->user();
        $user['hasPermission'] = $user->hasPermission('create-plans-for-county');
        $year = $request->get('year', date('Y'));
        $countyUsers = User::with(['presentation' => function ($query) use ($year, $user) {
            $query->where('year', $year);
            if ($user->type == 'county') {
                $query->where('user_id', $user->id);
            }
        }, 'presentation.files'])->where('type', 'county');
        if ($user->type == 'county') {
            $countyUsers->where('id', $user->id);
        }
        $countyUsers = $countyUsers->get();
        return view('admin.presentation.inquire', compact('user', 'countyUsers', 'year'));
    }

    public function export($year)
    {
        $user = auth()->user();
        $countyUsers = User::with(['presentation' => function ($query) use ($year, $user) {
            $query->where('year', $year);
            if ($user->type == 'county') {
                $query->where('user_id', $user->id);
            }
        }, 'presentation.files'])->where('type', 'county');
        if ($user->type == 'county') {
            $countyUsers->where('id', $user->id);
        }
        $countyUsers = $countyUsers->get();
        if ($countyUsers) {
            $presentationExportService = new PresentationExportService();
            $zipFile = $presentationExportService->export($countyUsers, $year);
            if (empty($zipFile)) {
                return response()->redirectTo(url('/admin/presentation/inquire?year=' . date('Y')));
            } else {
                return response()->download($zipFile);
            }
        }
        abort(404);
    }

    /**
     * 刪除檔案。
     *
     */
    public function destroyFile(Request $request, File $file)
    {
        File::destroy($file->id);
        $user = auth()->user();
        if (request()->has('response_json') && request()->get('response_json')) {
            $year = $request->get('year', date('Y'));
            $countyUsers = User::with(['presentation' => function ($query) use ($year, $user) {
                $query->where('year', $year);
                if ($user->type == 'county') {
                    $query->where('user_id', $user->id);
                }
            }, 'presentation.files'])->where('type', 'county');
            if ($user->type == 'county') {
                $countyUsers->where('id', $user->id);
            }
            $countyUsers = $countyUsers->get();
            $data = json_decode($countyUsers->toJson(JSON_PRETTY_PRINT), true);
            return response()->json($data);
        } else {
            Flash::success(trans('app.deleteSuccess', ['type' => '檔案']));

            return redirect()->route('admin.presentation.inquire', compact('year'));
        }
    }


    private function reportHasFiles($report)
    {
        foreach ($report as $category) {
            foreach ($category->items as $topic) {
                if (isset($topic->reports) && isset($topic->reports->files)) {
                    return true;
                }
            }
        }

        return false;
    }
}
