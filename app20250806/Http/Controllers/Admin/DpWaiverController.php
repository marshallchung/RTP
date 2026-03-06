<?php

namespace App\Http\Controllers\Admin;

use App\DpWaiver;
use App\Http\Requests\GetDpStudentRequest;
use App\Http\Requests\StoreDpWaiverRequest;
use App\Nfa\Repositories\DpWaiverRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\User;
use Flash;
use Illuminate\Http\Request;

class DpWaiverController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param DpWaiverRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DpWaiverRepositoryInterface $repo)
    {
        $courses = $repo->getCourses();

        return view('admin.dp-waivers.index', compact('courses'));
    }

    /**
     * @param Request $request
     * @param DpWaiverRepositoryInterface $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxGetWaivers(Request $request, DpWaiverRepositoryInterface $repo)
    {
        return response()->json(
            $repo->getWaivers((int) $request->input('course_id'))
        );
    }

    public function ajaxGetStudent(GetDpStudentRequest $request, DpWaiverRepositoryInterface $repo)
    {
        return response()->json(
            $repo->getStudent($request->input('TID'))
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param DpWaiverRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function create(DpWaiverRepositoryInterface $repo)
    {
        $courses = $repo->getCourses();

        return view('admin.dp-waivers.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDpWaiverRequest $request
     * @param DpWaiverRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDpWaiverRequest $request, DpWaiverRepositoryInterface $repo)
    {
        $dpWaivers = $repo->postData($request->all());

        foreach ($dpWaivers as $key => $dpWaiver) {
            $files = $request->file('files_' . $key);
            $this->attachFiles($files, $dpWaiver, '');
        }

        if (!isset($fails[0])) {
            return response()->json([
                'msg' => trans('app.createSuccess', [
                    'type' => '防災士培訓 - 培訓課程抵免申請',
                ]),
            ]);
        } else {
            return response()->json([
                'msg' => trans('app.createPartiallySuccess', [
                    'type'   => '防災士培訓 - 培訓課程抵免申請',
                    'reason' => '不明原因',
                    'fails'  => implode(', ', $fails),
                ]),
            ]);
        }
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
        $data = DpWaiver::find($id);
        $counties = $this->getCounties();

        return view('admin.dp-waivers.edit', compact('data', 'counties'));
    }

    private function getCounties()
    {
        $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();

        return [null => '-'] + $countyIdNames;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDpWaiverRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDpWaiverRequest $request, $id)
    {
        $dpWaiver = DpWaiver::find($id);
        $data = $request->all();

        if (isset($data['waivers'])) {
            $data['waivers'] = implode(',', $data['waivers']);
        }

        $dpWaiver->update($data);

        $this->handleFiles($request, $dpWaiver);

        Flash::success(trans('app.updateSuccess', ['type' => '課程資料']));

        return redirect()->route('admin.dp-waivers.index');
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
        $data = DpWaiver::find($id);
        $data->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '課程資料']));

        return redirect()->route('admin.dp-waivers.index');
    }
}
