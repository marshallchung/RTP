<?php

namespace App\Http\Controllers\Admin;

use App\DpCourse;
use App\DpScore;
use App\Http\Requests\StoreDpScoreRequest;
use App\Nfa\Repositories\DpScoreRepositoryInterface;
use App\User;
use Flash;
use Illuminate\Http\Request;

class DpScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param DpScoreRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DpScoreRepositoryInterface $repo)
    {
        //$courses = array_merge(['-'],  $repo->getCourses());
        $courses = $repo->getCourses();

        return view('admin.dp-scores.index', compact('courses'));
    }

    public function ajaxGetCourseStudents(Request $request, DpScoreRepositoryInterface $repo)
    {
        if (!$course_id = $request->input('course_id')) {
            return response()->json([
                'msg' => '目前尚無資料',
            ]);
        }

        return response()->json(
            $repo->getCourseStudents($course_id)
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $course = DpCourse::find((int) $request->input('course_id'));

        return view('admin.dp-scores.create', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDpScoreRequest $request
     * @param DpScoreRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDpScoreRequest $request, DpScoreRepositoryInterface $repo)
    {
        $fails = $repo->postData($request->all());

        if (!isset($fails[0])) {
            Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 登錄成績']));
        } else {
            Flash::success(trans('app.createPartiallySuccess', [
                'type'   => '防災士培訓 - 登錄成績',
                'reason' => '資料庫無身份證匹配之防災士',
                'fails'  => implode(', ', $fails),
            ]));
        }

        return redirect()->route('admin.dp-scores.index');
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
        $data = DpScore::find($id);
        $counties = $this->getCounties();

        return view('admin.dp-scores.edit', compact('data', 'counties'));
    }

    private function getCounties()
    {
        $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();

        return [null => '-'] + $countyIdNames;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDpScoreRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDpScoreRequest $request, $id)
    {
        $dpScore = DpScore::find($id);
        $data = $request->all();

        if (isset($data['scores'])) {
            $data['scores'] = implode(',', $data['scores']);
        }

        $dpScore->update($data);

        $this->handleFiles($request, $dpScore);

        Flash::success(trans('app.updateSuccess', ['type' => '課程資料']));

        return redirect()->route('admin.dp-scores.index');
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
        $data = DpScore::find($id);
        $data->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '課程資料']));

        return redirect()->route('admin.dp-scores.index');
    }
}
