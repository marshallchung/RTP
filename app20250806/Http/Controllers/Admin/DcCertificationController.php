<?php

namespace App\Http\Controllers\Admin;

use App\DcCertification;
use App\DcUnit;
use App\Http\Requests\StoreDcCertificationRequest;
use App\Nfa\Repositories\DcStageRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Services\DcCertificationExportService;
use App\User;
use Flash;
use Illuminate\Http\Request;

class DcCertificationController extends Controller
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
        /*
        files->post_id = dc_certifications.id
        dc_certifications.term = dc.certification.items 的 index // items index <= 14（一星項目），index <= 30（二星項目），index > 30（三星項目）
        如果 dc_certifications.term 存在，表示該項目有繳交檔案
        可把該社區所有存在的檔案依照星等打包下載，不存在檔案的便不需顯示下載案鈕
        */
        $counties = $this->getCounties();

        /** @var User $user */
        $user = auth()->user();
        $lockCounty = null;
        if ($user->origin_role >= 4 && $user->origin_role != 6) {
            $lockCounty = User::whereId($user->id)->first();
        }

        /** @var User $countyAccount */
        $countyAccount = $lockCounty ? $lockCounty : User::find($request->get('county_id'));
        $dc_units = ['' => '-'];
        $dc_unit_name = $request->get('dc_unit_name');
        if ($countyAccount) {
            $dc_units += $repo->getDcUnitsOfCounty($countyAccount, $dc_unit_name);
        }
        $dc_unit = null;
        $files = null;

        $has_1_star = false;
        $has_2_star = false;
        $has_3_star = false;
        $file_list = [];
        if ($dc_unit_id = $request->input('dc_unit_id')) {
            /** @var DcUnit $dc_unit */
            $dc_unit = DcUnit::find($dc_unit_id);
            $dc_certifications = $dc_unit->dcCertifications->keyBy('term');
            foreach ($dc_certifications as $term_id => $one_certification) {
                $file_list[$term_id] = $one_certification->files()->get()->toArray();
                if ($term_id <= 14) {
                    //一星項目
                    $has_1_star = true;
                } elseif ($term_id <= 30) {
                    //二星項目
                    $has_2_star = true;
                } else {
                    //三星項目
                    $has_3_star = true;
                }
            }
        } else {
            $dc_certifications = collect();
        }

        return view(
            'admin.dc-certifications.index',
            compact('lockCounty', 'counties', 'dc_units', 'dc_unit', 'dc_certifications', 'has_1_star', 'has_2_star', 'has_3_star', 'file_list')
        );
    }

    public function export()
    {
        $star = request('star');
        $dc_unit_id = request('dc_unit_id');
        if ($dc_unit_id && in_array($star, ['1', '2', '3'])) {
            /** @var DcUnit $dc_unit */
            $dc_unit = DcUnit::find($dc_unit_id);
            $dcCertificationExportService = new DcCertificationExportService();
            $zipFile = $dcCertificationExportService->export($dc_unit, $star);
            if (empty($zipFile)) {
                return response()->redirectTo(url('/admin/dc-certifications?dc_unit_id=' . $dc_unit_id . '&county_id=' . $dc_unit->county_id));
            } else {
                return response()->download($zipFile);
            }
        }
        abort(404);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDcCertificationRequest $request
     * @param DcStageRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(StoreDcCertificationRequest $request, DcStageRepositoryInterface $repo)
    {
        /** @var DcUnit $dcUnit */
        $dcUnit = DcUnit::find($request->input('dc_unit_id'));
        /** @var User $user */
        $user = auth()->user();

        //逐一處理檔案
        foreach (config('dc.certification.items') as $idx => $item) {
            //僅在有檔案或有刪除檔案時處理
            if ($request->hasFile("files_{$idx}") || $request->has("removed_files_{$idx}")) {
                //對應 DcCertification
                /** @var DcCertification $dcCertification */
                $dcCertification = DcCertification::updateOrCreate([
                    'dc_unit_id' => $dcUnit->id,
                    'term'       => $idx,
                ], [
                    'user_id' => $user->id,
                ]);
                //調整為 handleFiles 所需欄位名稱
                $request->request->set('removed_files', $request->get("removed_files_{$idx}"));
                //                $request->files->set('files', $request->file("files_{$idx}"));
                $this->handleFiles($request, $dcCertification, '', "files_{$idx}");
                //若已無檔案，則刪除
                if ($dcCertification->files->count() == 0) {
                    $dcCertification->delete();
                }
            }
        }

        //        $this->handleFiles($request, $data, 'dc-certifications');

        Flash::success(trans('app.updateSuccess', ['type' => '韌性社區 - 參與標章申請表']));

        return redirect()->route('admin.dc-certifications.index', [
            'county_id'  => $dcUnit->county_id,
            'dc_unit_id' => $dcUnit->id,
        ]);
        //        return response()->json([
        //            'msg' => trans('app.createSuccess', [
        //                'type' => '韌性社區 - 參與標章申請表>'.json_encode($request->file('files_1')).'<',
        //            ]),
        //        ]);
    }

    private function getCounties()
    {
        $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();

        return [null => '-'] + $countyIdNames;
    }
}
