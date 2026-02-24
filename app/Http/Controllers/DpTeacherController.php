<?php

namespace App\Http\Controllers;

use App\DpSubject;
use App\DpTeacher;
use App\Nfa\Traits\FileUploadTrait;
use App\User;
use Flash;
use Illuminate\Http\Request;
use URL;

class DpTeacherController extends Controller
{
    use FileUploadTrait;

    /**
     * 師資資料庫編輯Profile。
     *
     */
    public function editProfile(DpTeacher $dpTeacher, Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        $data = $dpTeacher;
        $counties = $this->getCounties();
        $dpSubjects = $this->getDpSubjects();

        $formActionUrl = URL::temporarySignedRoute('dp-teacher.update-profile', now()->addMinutes(30), $dpTeacher);

        return view('dp-teacher.edit', compact('counties', 'dpSubjects', 'data', 'formActionUrl'));
    }

    /**
     * 取得師資的居住縣市。
     *
     */
    private function getCounties()
    {
        $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'name')->toArray();

        return [null => ''] + $countyIdNames;
    }

    /**
     * 取得師資的教授科目。
     *
     */
    private function getDpSubjects()
    {
        return DpSubject::sorted()->get();
    }

    /**
     * 師資資料庫更新Profile。
     *
     */
    public function updateProfile(DpTeacher $dpTeacher, Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        $requestData = $request->all();
        unset($requestData['dp_subjects']);
        unset($requestData['pass_date']);

        $dpTeacher->update($requestData);
        $this->handleFiles($request, $dpTeacher);
        Flash::success('師資資料已更新');

        return redirect()->back();
    }
}
