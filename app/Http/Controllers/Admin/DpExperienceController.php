<?php

namespace App\Http\Controllers\Admin;

use App\DpExperience;
use App\Http\Requests\GetDpStudentRequest;
use App\Http\Requests\StoreDpExperienceRequest;
use App\Nfa\Repositories\DpExperienceRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\User;
use Flash;

class DpExperienceController extends Controller
{
    use FileUploadTrait;

    public function index(DpExperienceRepositoryInterface $repo)
    {
        $courses = $repo->getCourses();

        return view('admin.dp-experiences.index', compact('courses'));
    }

    public function ajaxGetStudent(GetDpStudentRequest $request, DpExperienceRepositoryInterface $repo)
    {
        return response()->json(
            $repo->getStudent($request->input('TID'))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDpExperienceRequest $request
     * @param DpExperienceRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDpExperienceRequest $request, DpExperienceRepositoryInterface $repo)
    {
        $dpExperiences = $repo->postData($request->all());
        if ($request->get('removed_files')) {
            $this->removeFiles($request->get('removed_files'));
        }
        foreach ($dpExperiences as $key => $dpExperience) {
            $files = [$request->file('files_' . $key)];
            $this->attachFiles($files, $dpExperience, '');
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
        $data = DpExperience::find($id);
        $counties = $this->getCounties();

        return view('admin.dp-experiences.edit', compact('data', 'counties'));
    }

    private function getCounties()
    {
        $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();

        return [null => '-'] + $countyIdNames;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDpExperienceRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDpExperienceRequest $request, $id)
    {
        $dpExperience = DpExperience::find($id);
        $data = $request->all();

        if (isset($data['experiences'])) {
            $data['experiences'] = implode(',', $data['experiences']);
        }

        $dpExperience->update($data);

        $this->handleFiles($request, $dpExperience);

        Flash::success(trans('app.updateSuccess', ['type' => '課程資料']));

        return redirect()->route('admin.dp-experiences.index');
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
        $data = DpExperience::find($id);
        $data->delete();
        return response()->json('ok');
        /*Flash::success(trans('app.deleteSuccess', ['type' => '課程資料']));

        return redirect()->route('admin.dp-experiences.index');*/
    }
}
