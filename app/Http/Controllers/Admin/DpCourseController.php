<?php

namespace App\Http\Controllers\Admin;

use App\DpCourse;
use App\DpDocument;
use App\DpResult;
use App\Http\Requests\StoreCourseDocumentRequest;
use App\Http\Requests\StoreCourseResultRequest;
use App\Http\Requests\StoreDpCourseRequest;
use App\Nfa\Repositories\DpCourseRepository;
use App\Nfa\Repositories\DpCourseRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Flash;
use Illuminate\Support\Facades\DB;

class DpCourseController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param DpCourseRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DpCourseRepositoryInterface $repo)
    {
        $data = $repo->getData();
        DB::statement("UPDATE `dp_courses` SET `organizer`='社團法人中華民國紅十字會' WHERE `organizer`='中華民國紅十字會';");
        DB::statement("UPDATE `dp_courses` SET `organizer`='消防署' WHERE `organizer`='內政部消防署';");
        DB::statement("UPDATE `dp_courses` SET `organizer`='國立屏東科技大學' WHERE `organizer`='國立屏東科技大學災害防救科技研究中心';");
        DB::statement("UPDATE `dp_courses` SET `organizer`='屏東縣' WHERE `organizer`='屏東縣政府社會處';");
        DB::statement("UPDATE `dp_courses` SET `organizer`='新竹市' WHERE `organizer`='新竹市政府';");
        DB::statement("UPDATE `dp_courses` SET `organizer`='國立屏東科技大學' WHERE `organizer`='高雄醫學大學 / 國立屏東科技大學災害防救科技研究中心';");

        return view('admin.dp-courses.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        //地區清單
        $counties = $this->getCounties();
        $dptraining = User::whereIn('type', ['dp-training'])
            ->whereNull('county_id')
            ->pluck('name', 'id')
            ->toArray();
        $dpcounties = [null => '-'];
        if ($user->origin_role == 4 || $user->origin_role == 5) {
            $dpcounties += [$user->id => $user->name];
        } else {
            $dpcounties = User::whereIn('type', ['county'])
                ->whereNull('county_id')
                ->pluck('name', 'id')
                ->toArray();
            $dpcounties += [2 => '消防署'] + $dpcounties;
        }

        return view('admin.dp-courses.create', compact('counties', 'dptraining', 'dpcounties'));
    }

    private function getCounties()
    {
        $user = Auth::user();
        if ($user->origin_role == 4 || $user->origin_role == 5) {
            return [null => '-'] + [$user->id => $user->name];
        } else {
            $countyIdNames = User::whereIn('type', ['county', 'dp-training'])
                ->whereNull('county_id')
                ->pluck('name', 'id')
                ->toArray();

            return [null => '-'] + [2 => '內政部消防署'] + $countyIdNames;
        }

        return collect();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDpCourseRequest $request
     * @param DpCourseRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDpCourseRequest $request, DpCourseRepositoryInterface $repo)
    {
        $data = $repo->postData($request->all());

        $this->handleFiles($request, $data);

        Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 新增培訓課程']));

        return redirect()->route('admin.dp-courses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DpCourse::with('files')->where('id', $id)->first();
        $counties = $this->getCounties();
        $user = Auth::user();
        //地區清單
        $dptraining = User::whereIn('type', ['dp-training'])
            ->whereNull('county_id')
            ->pluck('name', 'id')
            ->toArray();
        $dpcounties = [];
        if ($user->origin_role == 4 || $user->origin_role == 5) {
            $dpcounties = [$user->id => $user->name];
        } else {
            $dpcounties = User::whereIn('type', ['county'])
                ->whereNull('county_id')
                ->pluck('name', 'id')
                ->toArray();
            $dpcounties = [2 => '消防署'] + $dpcounties;
        }

        return view('admin.dp-courses.edit', compact('data', 'counties', 'dptraining', 'dpcounties'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDpCourseRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDpCourseRequest $request, $id)
    {
        $dpCourse = DpCourse::find($id);
        $data = $request->all();
        if (!array_key_exists('stop_signup', $data)) {
            $data['stop_signup'] = 0;
        }
        if (!array_key_exists('exclusive', $data)) {
            $data['exclusive'] = 0;
        }

        if (isset($data['courses'])) {
            $data['courses'] = implode(',', $data['courses']);
        }

        $dpCourse->update($data);

        $this->handleFiles($request, $dpCourse);
        if ($request->has('response_json') && $request->get('response_json')) {
            $courseRepo = new DpCourseRepository();
            $course = $courseRepo->getData()->items();
            return response()->json($course);
        } else {
            Flash::success(trans('app.updateSuccess', ['type' => '課程資料']));

            return redirect()->route('admin.dp-courses.index');
        }
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
        $data = DpCourse::find($id);
        $data->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '課程資料']));

        return redirect()->route('admin.dp-courses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function documentCreate(Request $request)
    {
        $user = auth()->user();

        $files = [];
        if ($plan = $user->dpDocument()->first()) {
            $files = $plan->files;
        }

        return view('admin.dp-courses.create_document', compact('files'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCourseDocumentRequest $request
     * @return Response
     */
    public function documentStore(StoreCourseDocumentRequest $request)
    {
        $asCountyUser = $request->user();
        $plan = DpDocument::firstOrCreate([
            'user_id' => $asCountyUser->id,
        ]);

        $this->handleFiles($request, $plan);

        Flash::success('檔案上傳成功');

        return redirect()->route('admin.dp-courses-document.create', [
            'id'   => $request->user()->id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function resultCreate(Request $request)
    {
        $user = auth()->user();

        $files = [];
        if ($plan = $user->dpResult()->first()) {
            $files = $plan->files;
        }

        return view('admin.dp-courses.create_result', compact('files'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCourseResultRequest $request
     * @return Response
     */
    public function resultStore(StoreCourseResultRequest $request)
    {
        $asCountyUser = $request->user();
        $plan = DpResult::firstOrCreate([
            'user_id' => $asCountyUser->id,
        ]);

        $this->handleFiles($request, $plan);

        Flash::success('檔案上傳成功');

        return redirect()->route('admin.dp-courses-result.create', [
            'id'   => $request->user()->id,
        ]);
    }
}
