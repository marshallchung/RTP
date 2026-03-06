<?php

namespace App\Http\Controllers;

use App\DcCertification;
use App\DcDownload;
use App\DcUnit;
use App\DcUser;
use App\Exports\DcUnitExport;
use App\FrontIntroduction;
use App\Nfa\Repositories\DcUnitRepository;
use App\Nfa\Traits\FileUploadTrait;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DcController extends Controller
{
    use FileUploadTrait;

    public function intro(Request $request)
    {
        return view('dc.intro', [
            'intro' => FrontIntroduction::find(2),
        ]);
    }

    public function download(Request $request)
    {
        $page = (object) [
            'title'    => '推動韌性社區',
            'subtitle' => '相關資料下載',
            'search' => '/dc/download/search'
        ];
        $categoryOption = DcDownload::with('files')->where('active', 1)->orderBy('position')->groupBy('category')->pluck('category')->toArray();

        return view('dp.panel', compact('categoryOption', 'page'));
    }

    public function search(Request $request)
    {
        $data = DcDownload::with(['author', 'files'])->where('active', 1);
        if ($category = $request->get('category')) {
            $data->where('category', '=', $category);
        }
        $data = $data->orderBy('position')->latest('created_at')->paginate(20);
        $data = $data->items();

        return response()->json($data);
    }

    public function unitIndex()
    {
        $countyOptions = User::where('type', 'county')->pluck('name', 'id')->toArray();
        $countyOptions = [null => '- 縣市 -'] + $countyOptions;

        return view('dc.unit.index', compact('countyOptions'));
    }

    public function unitSearch(Request $request)
    {
        $repo = new DcUnitRepository();
        $rankCount = $repo->getRankCount($request->get('county', null));
        $unitDataList = DcUnit::select(DB::raw('dc_units.id,dc_units.name,dc_units.county_id,dc_units.rank,dc_units.population,dc_units.location,dc_units.is_experienced,dc_units.environment,dc_units.risk,dc_units.pattern,dc_units.manager'))->with(['county'])->where('active', true);
        if ($county = $request->get('county', null)) {
            $unitDataList->where('county_id', $county);
        }
        if ($search = $request->get('search', null)) {
            $unitDataList->where('name', 'like', "%{$search}%");
        }
        if ($rank = $request->get('rank', null)) {
            $unitDataList->where('rank', $rank);
        }
        if ($export = $request->get('export', null)) {
            $unitDataList = $unitDataList->get();
            return Excel::download(new DcUnitExport($unitDataList), '韌性社區名單查詢.xlsx');
        } else {
            $unitDataList = $unitDataList->paginate(15);
            $pagination = $unitDataList->links()->render();
            $startIndex = ($unitDataList->currentPage() - 1) * 15 + 1;
            $unitDataList = $unitDataList->items();
            $data = compact('unitDataList', 'pagination', 'startIndex', 'rankCount');
            return response()->json($data);
        }
    }

    public function unitShow(DcUnit $dcUnit)
    {
        return view('dc.unit.show', compact('dcUnit'));
    }

    public function unit(Request $request)
    {
        /** @var DcUnit $dcUnit */
        $data = Auth::guard('dc')->user()->dcUnit;
        $counties = $this->getCounties();

        return view('dc.unit', compact('data', 'counties'));
    }

    public function unitUpdate(Request $request)
    {
        $data = $request->all();
        /** @var DcUnit $dcUnit */
        $dcUnit = Auth::guard('dc')->user()->dcUnit;

        $dcUnit->update($data);
        $this->handleFiles($request, $dcUnit, 'dc-location');

        flash('資料更新成功。');

        return redirect()->route('dc.unit');
    }

    public function upload(Request $request)
    {
        $files = null;

        /** @var DcUnit $data */
        $data = Auth::guard('dc')->user()->dcUnit;

        if ($stage = $request->input('stage')) {
            $files = $data->files()->where('name', 'like', $stage . '%')->get();
        }

        return view('dc.upload', compact('data', 'files'));
    }

    public function uploadUpdate(Request $request)
    {
        $data = Auth::guard('dc')->user()->dcUnit;

        $this->handleFiles($request, $data, 'dc-stages');

        return response()->json([
            'msg' => '檔案更新成功',
        ]);
    }

    public function certification(Request $request)
    {
        $files = null;

        /** @var DcUnit $data */
        $data = Auth::guard('dc')->user()->dcUnit;
        $county = $data->county()->get();
        $county_name = "";
        if ($county) {
            $county = $county->toArray();
            $county_name = $county[0]['name'];
        }

        //        $files = $data->files()->where('memo', 'dc-certifications')->get();
        $dc_certifications = $data->dcCertifications->keyBy('term');

        return view('dc.certification', compact('data', 'files', 'dc_certifications', 'county_name'));
    }

    //    public function certificationUpdate(Request $request)
    //    {
    //        $data = Auth::guard('dc')->user()->dcUnit;
    //
    //        $this->handleFiles($request, $data, 'dc-certifications');
    //
    //        return response()->json([
    //            'msg' => '檔案更新成功'
    //        ]);
    //    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function certificationUpdate(Request $request)
    {
        //        dd($request->all());
        if ($request->ajax()) {
            $this->validate($request, [
                'active' => 'required',
            ]);
        }
        $this->validate($request, [
            'dc_unit_id' => 'required|exists:dc_units,id',
        ]);

        /** @var DcUser $user */
        $user = Auth::guard('dc')->user();
        /** @var DcUnit $dcUnit */
        $dcUnit = $user->dcUnit;

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
                //                $request->files->set('files', $request->file("files_{$idx}")??[]);
                $this->handleFiles($request, $dcCertification, '', "files_{$idx}");
                //若已無檔案，則刪除
                if ($dcCertification->files->count() == 0 && !$request->hasFile("files_{$idx}")) {
                    $dcCertification->delete();
                }
            }
        }

        //        $this->handleFiles($request, $data, 'dc-certifications');

        //Flash::success('檔案更新成功');

        //return redirect()->route('dc.certification');
        return response()->json([
            'msg' => '檔案更新成功'
        ]);
    }

    private function getCounties()
    {
        $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();

        return [null => '-'] + $countyIdNames;
    }
}
