<?php

namespace App\Http\Controllers\Admin;

use App\DcUnit;
use App\Http\Requests\StoreDcStageRequest;
use App\Nfa\Repositories\DcStageRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\User;
use Flash;
use Illuminate\Http\Request;

class DcStageController extends Controller
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
        $counties = $this->getCounties();
        $user = auth()->user();
        $lockCounty = null;
        if ($user->origin_role >= 4) {
            $lockCounty = User::whereId($user->id)->first();
        }
        $dc_unit_name = $request->get('dc_unit_name');
        $countyAccount = $lockCounty ? $lockCounty : User::find($request->get('county_id'));
        //$dc_units = ['' => '-'] + $repo->getDcUnits();
        $dc_units = ['' => '-'];
        if ($countyAccount) {
            $dc_units += $repo->getDcUnitsOfCounty($countyAccount, $dc_unit_name);
        }
        $dc_unit = null;
        $files = null;

        if ($dc_unit_id = $request->input('dc_unit_id')) {
            $dc_unit = DcUnit::find($dc_unit_id);

            if ($stage = $request->input('stage')) {
                $files = $dc_unit->files()->where('name', 'like', $stage . '%')->get();
            }
        }
        return view('admin.dc-stages.index', compact('lockCounty', 'counties', 'dc_units', 'dc_unit', 'files'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDcStageRequest $request)
    {
        $data = DcUnit::find($request->input('dc_unit_id'));

        /*if ($request->hasFile("files") || $request->has("removed_files")) {
            $this->handleFiles($request, $data, '', "files");
        }*/

        $this->handleFiles($request, $data, 'dc-stages');

        Flash::success(trans('app.createSuccess', ['type' => '韌性社區 - 各階段提報']));

        return redirect()->back();
        /*return response()->json([
            'msg' => trans('app.createSuccess', [
                'type' => '韌性社區 - 各階段提報',
            ]),
        ]);*/
    }

    private function getCounties()
    {
        $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
        return [null => '-'] + $countyIdNames;
    }
}
