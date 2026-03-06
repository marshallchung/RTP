<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSignLocationRequest;
use App\Nfa\Repositories\SignLocationRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\SignLocation;
use App\Exports\SignLocationExport;
use App\User;
use Flash;

class SignLocationController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param SignLocationRepositoryInterface $signLocationRepo
     * @return \Illuminate\Http\Response
     */
    public function index(SignLocationRepositoryInterface $signLocationRepo)
    {
        $counties = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
        $counties = [null => '-'] + $counties;

        /** @var User $countyUser */
        $countyUser = User::find(request('county_id'));
        $signLocations = $signLocationRepo->getSignLocations($countyUser);

        return view('admin.sign-location.index', compact('signLocations', 'counties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param SignLocationRepositoryInterface $signLocationRepo
     * @return \Illuminate\Http\Response
     */
    public function create(SignLocationRepositoryInterface $signLocationRepo)
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        $countySelectOptions = $signLocationRepo->getCountySelectOptions($authUser);

        return view('admin.sign-location.create', compact('authUser', 'countySelectOptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSignLocationRequest $request
     * @param SignLocationRepositoryInterface $signLocationRepo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSignLocationRequest $request, SignLocationRepositoryInterface $signLocationRepo)
    {
        $user = auth()->user();
        $signLocation = $signLocationRepo->storeSignLocation($user, $request->all());

        $this->handleFiles($request, $signLocation);

        Flash::success(trans('app.createSuccess', ['type' => '防災避難看板']));

        return redirect()->route('admin.sign-location.index');
    }

    /**
     * Display the specified resource.
     *
     * @param SignLocation $signLocation
     * @return \Illuminate\Http\Response
     */
    public function show(SignLocation $signLocation)
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        $hasPermission = $authUser->hasPermOfUser($signLocation->user);
        $signLocationData = [
            'latitude'  => $signLocation->latitude,
            'longitude' => $signLocation->longitude,
            'info'      => $signLocation->info,
        ];

        return view('admin.sign-location.show', compact('signLocation', 'signLocationData', 'hasPermission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param SignLocation $signLocation
     * @param SignLocationRepositoryInterface $signLocationRepo
     * @return \Illuminate\Http\Response
     */
    public function edit(SignLocation $signLocation, SignLocationRepositoryInterface $signLocationRepo)
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!$authUser->hasPermOfUser($signLocation->user)) {
            abort(403);
        }
        $countySelectOptions = $signLocationRepo->getCountySelectOptions($authUser);

        return view('admin.sign-location.edit', compact('signLocation', 'authUser', 'countySelectOptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreSignLocationRequest $request
     * @param SignLocation $signLocation
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSignLocationRequest $request, SignLocation $signLocation)
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!$authUser->hasPermOfUser($signLocation->user)) {
            abort(403);
        }

        $signLocation->update($request->all());

        $this->handleFiles($request, $signLocation);

        Flash::success(trans('app.updateSuccess', ['type' => '防災避難看板']));

        return redirect()->route('admin.sign-location.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SignLocation $signLocation
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(SignLocation $signLocation)
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!$authUser->hasPermOfUser($signLocation->user)) {
            abort(403);
        }

        $signLocation->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '防災避難看板']));

        return redirect()->route('admin.sign-location.index');
    }

    public function map()
    {
        $signLocations = [];
        SignLocation::all()->each(function (SignLocation $signLocation) use (&$signLocations) {
            $signLocations[] = [
                'latitude'  => $signLocation->latitude,
                'longitude' => $signLocation->longitude,
                'info'      => $signLocation->info,
            ];
        });

        return view('admin.sign-location.map', compact('signLocations'));
    }

    public function downloadXlsx()
    {
        $fileName = '防災避難看板.xlsx';
        $countyId = request()->get('county_id');

        return \Excel::download(new SignLocationExport($countyId), $fileName);
    }
}
