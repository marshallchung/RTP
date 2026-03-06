<?php

namespace App\Http\Controllers\Admin;

use App\DpSubject;
use App\DpTeacher;
use App\DpTeacherSubject;
use App\Exports\DpTeacherExport;
use App\Http\Requests\StoreDpTeacherRequest;
use App\Imports\DpTeacherImport;
use App\Nfa\Repositories\DpTeacherRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Services\MailService;
use App\User;
use Auth;
use DB;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DpTeacherController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param DpTeacherRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DpTeacherRepositoryInterface $repo)
    {
        $counties = $this->getCounties();
        $counties[null] = '居住縣市';
        $dpSubjects = [null => '教授科目'] + $this->getDpSubjects()->pluck('name', 'id')->toArray();
        $data = $repo->getFilteredData();
        foreach ($data as $data_key => $data_item) {
            if ($data_item->dpTeacherSubjects) {
                foreach ($data_item->dpTeacherSubjects as $subjects_key => $data_subject) {
                    if ($data_subject->type == '種子師資' && $data_subject->pass_date) {
                        $data[$data_key]->dpTeacherSubjects[$subjects_key]->is_expired = $data_subject->getIsExpiredAttribute();
                    }
                }
            }
        }
        $routeName = (Auth::user()->hasPermission('admin-permissions') ||
            Auth::user()->hasPermission('NFA-permissions') || Auth::user()->hasPermission('DEP-permissions')) ? 'edit' : 'show';

        return view('admin.dp-teachers.index', compact('data', 'routeName', 'counties', 'dpSubjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counties = $this->getCounties();
        $dpSubjects = $this->getDpSubjects();

        return view('admin.dp-teachers.create', compact('counties', 'dpSubjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDpTeacherRequest $request
     * @param DpTeacherRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDpTeacherRequest $request, DpTeacherRepositoryInterface $repo)
    {
        $data = $repo->postData($request->all());

        $this->handleFiles($request, $data);

        Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 新增師資資料']));

        return redirect()->route('admin.dp-teachers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = DpTeacher::find($id);

        return view('admin.dp-teachers.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $counties = $this->getCounties();
        $dpSubjects = $this->getDpSubjects();
        $data = DpTeacher::find($id);

        return view('admin.dp-teachers.edit', compact('data', 'counties', 'dpSubjects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDpTeacherRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(StoreDpTeacherRequest $request, $id)
    {
        /** @var DpTeacher $dpTeacher */
        $dpTeacher = DpTeacher::find($id);
        $data = $request->all();

        $dpTeacher->update($data);

        foreach ($data['dp_subjects'] as $subjectId => $teacherType) {
            /** @var DpTeacherSubject $existsTeacherSubject */
            $existsTeacherSubject = $dpTeacher->dpTeacherSubjects()->where('dp_subject_id', $subjectId)->first();

            if ($teacherType) {
                $passDate = ($teacherType == '種子師資') ? $data['pass_date'][$subjectId] : null;
                if ($existsTeacherSubject) {
                    $dpTeacher->dpTeacherSubjects()->where('dp_subject_id', $subjectId)->update([
                        'type'      => $teacherType,
                        'pass_date' => $passDate,
                    ]);
                } else {
                    $dpTeacher->dpTeacherSubjects()->where('dp_subject_id', $subjectId)->create([
                        'dp_subject_id' => $subjectId,
                        'type'          => $teacherType,
                        'pass_date'     => $passDate,
                    ]);
                }
            } else {
                if ($existsTeacherSubject) {
                    $dpTeacher->dpTeacherSubjects()->where('dp_subject_id', $subjectId)->delete();
                }
            }
        }

        $this->handleFiles($request, $dpTeacher);

        Flash::success(trans('app.updateSuccess', ['type' => '師資資料']));

        return redirect()->route('admin.dp-teachers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        //dd($id);
        $data = DpTeacher::find($id);
        $data->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '師資資料']));

        return redirect()->route('admin.dp-teachers.index');
    }

    /**
     * @param DpTeacherRepositoryInterface $repo
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(DpTeacherRepositoryInterface $repo)
    {
        /*$dpTeacherCount = DpTeacher::query()->count();
        $dpTeacherExpiredCount = DpTeacher::with('dpTeacherSubjects.dpSubject')
            ->whereHas('dpTeacherSubjects.DpSubject', function ($q) {
                $time = strtotime("-3 year", time());
                $date = date("Y-m-d", $time);
                $q->where('type', '=', '種子師資')
                    ->where('pass_date', '<', $date);
            })->get()->count();
        $dpTeacherNotExpiredCount = $dpTeacherCount - $dpTeacherExpiredCount;*/

        return Excel::download(new DpTeacherExport($repo->getAllFilteredData()), '師資資料.xlsx');
    }

    public function importForm()
    {
        return view('admin.dp-teachers.import');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'import_file' => 'required|mimes:xls,xlsx',
        ]);

        $userId = auth()->user()->id;
        $dpSubjects = $this->getDpSubjects();

        $dpTeacherImport = new DpTeacherImport($userId, $dpSubjects);
        Excel::import($dpTeacherImport, $request->file('import_file'));

        Flash::success(trans('app.importSuccess', [
            'type'    => '師資資料',
            'success' => $dpTeacherImport->successCount,
            'failed'  => $dpTeacherImport->failedCount,
        ]));

        return redirect()->route('admin.dp-teachers.import')->with('errorMessages', $dpTeacherImport->errorMessages);
    }

    public function downloadImportSample()
    {
        $withExample = \request()->exists('example');
        $fileName = $withExample ? '師資資料庫公版格式_範例.xlsx' : '師資資料庫公版格式.xlsx';
        $path = base_path('resources/import-sample/' . $fileName);

        return response()->download($path);
    }

    public function summary()
    {
        $dpTeacherCount = DpTeacher::query()->count();
        $dpTeacherExpiredCount = DpTeacher::with('dpTeacherSubjects.dpSubject')
            ->whereHas('dpTeacherSubjects.DpSubject', function ($q) {
                $time = strtotime("-3 year", time());
                $date = date("Y-m-d", $time);
                $q->where('type', '=', '種子師資')
                    ->where('pass_date', '<', $date);
            })->get()->count();
        $dpTeacherNotExpiredCount = $dpTeacherCount - $dpTeacherExpiredCount;
        $dpTeacherSubjects = DpTeacherSubject::select([
            'dp_subject_id',
            DB::raw('count((case when `type` = \'基本師資\' then \'x\' end)) count_basic'),
            DB::raw('count((case when `type` = \'種子師資\' then \'x\' end)) count_seed'),
            DB::raw('count((case when `type` = \'種子師資\' AND DATE_ADD(pass_date,INTERVAL 3 YEAR)<NOW() then \'x\' end)) count_expired'),
        ])->with('dpSubject')->groupBy('dp_subject_id')->get();

        return view('admin.dp-teachers.summary', compact('dpTeacherCount', 'dpTeacherExpiredCount', 'dpTeacherNotExpiredCount', 'dpTeacherSubjects'));
    }

    public function sendProfileUpdateMail(DpTeacher $dpTeacher)
    {
        $dpTeacher->load('dpTeacherSubjects.dpSubject');
        $formUrl = \URL::signedRoute('dp-teacher.edit-profile', $dpTeacher);
        $mailService = app(MailService::class);
        $mailService->addDpTeacherMailToQueue($dpTeacher, $formUrl);
        if (request()->has('response_json') && request()->get('response_json')) {
            return response()->json(['msg' => "師資資料更新連結已寄出給 {$dpTeacher->name}（{$dpTeacher->email}）"]);
        } else {
            Flash::success("師資資料更新連結已寄出給 {$dpTeacher->name}（{$dpTeacher->email}）");

            return redirect()->back();
        }
    }

    private function getCounties()
    {
        $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'name')->toArray();

        return [null => ''] + $countyIdNames;
    }

    private function getDpSubjects()
    {
        return DpSubject::sorted()->where('advance', '=', false)->get();
    }
}
