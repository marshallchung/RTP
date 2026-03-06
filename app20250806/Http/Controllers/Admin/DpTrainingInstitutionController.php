<?php

namespace App\Http\Controllers\Admin;

use App\DpTrainingInstitution;
use App\Http\Requests\StoreDpTrainingInstitutionRequest;
use App\Nfa\Repositories\DpTrainingInstitutionRepository;
use App\Nfa\Repositories\DpTrainingInstitutionRepositoryInterface;
use App\User;
use Auth;
use Flash;
use Illuminate\Support\Facades\DB;

class DpTrainingInstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param DpTrainingInstitutionRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DpTrainingInstitutionRepositoryInterface $repo)
    {
        $counties = $this->getCounties();
        $counties[null] = '- 居住縣市 -';
        $data = $repo->getFilteredData();
        return view('admin.dp-training-institution.index', compact('data', 'counties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counties = $this->getCounties();
        return view('admin.dp-training-institution.create', compact('counties'));
    }

    private function getCounties()
    {
        $user = Auth::user();
        switch ($user->origin_role) {
            case 1:
            case 2:
            case 6:
                $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
                break;

            case 4:
                $countyIdNames = User::where('id', $user->id)->pluck('name', 'id')->toArray();
                break;
            case 5:
                $countyIdNames = User::where('id', $user->county_id)->pluck('name', 'id')->toArray();
                break;
            default:
                $countyIdNames = [];
        }
        return [null => '-'] + $countyIdNames;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDpTrainingInstitutionRequest $request
     * @param DpTrainingInstitutionRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDpTrainingInstitutionRequest $request, DpTrainingInstitutionRepositoryInterface $repo)
    {
        $data = $repo->postData($request->all());

        Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 新增防災士培訓機構']));

        return redirect()->route('admin.dp-training-institution.index');
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
        $data = DpTrainingInstitution::find($id);
        $counties = $this->getCounties();
        return view('admin.dp-training-institution.edit', compact('data', 'counties'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDpTrainingInstitutionRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDpTrainingInstitutionRequest $request, $id)
    {
        if ($request->has('fromPosition') && $request->has('toPosition')) {
            $fromId = $request->get('fromId');
            $fromPosition = $request->get('fromPosition');
            $toPosition = $request->get('toPosition');
            if ($fromPosition && $toPosition && $fromPosition !== $toPosition) {
                DB::select('call dp_training_institutions_exange_position(?,?,?)', [$fromId, $fromPosition, $toPosition]);
            }
        } else {
            $dpTrainingInstitution = DpTrainingInstitution::find($id);
            $data = $request->all();

            $dpTrainingInstitution->update($data);
        }
        if ($request->has('response_json') && $request->get('response_json')) {
            $dataRepo = new DpTrainingInstitutionRepository();
            $data = $dataRepo->getFilteredData()->items();
            return response()->json($data);
        } else {
            Flash::success(trans('app.updateSuccess', ['type' => '防災士培訓機構']));

            return redirect()->route('admin.dp-training-institution.index');
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
        $data = DpTrainingInstitution::find($id);
        $data->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '防災士培訓機構']));

        return redirect()->route('admin.dp-training-institution.index');
    }
}
