<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Address;
use App\Exports\AddressExport;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //地區清單
        $counties = $this->getCountySelectOptions();

        /** @var Builder|Address $addressQuery */
        $addressQuery = Address::orderBy('county_id')->orderBy('position')->with('county.county');

        $childrenUnits = [];
        if ($countyId = request()->get('county_id')) {
            $countyAndDistrictAccountIds = User::where('id', $countyId)
                ->orWhere('county_id', $countyId)
                ->pluck('id');
            $addressQuery->whereIn('county_id', $countyAndDistrictAccountIds);
            $childrenUnits = ['' => '-'] + Address::select('unit')
                ->where('county_id', $countyId)->groupBy('unit')->pluck('unit', 'unit')->toArray();
            if ($child = request()->get('child')) {
                $addressQuery->where('unit', $child);
            }
        }

        $addresses = $addressQuery->paginate();

        return view('admin.address.index', compact('counties', 'addresses', 'childrenUnits'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function manage(Request $request)
    {
        //地區清單
        $counties = $this->getCountySelectOptions();

        $user = auth()->user();

        $addressQuery = $this->getAddressData($request);

        $addresses = $addressQuery->paginate();

        return view('admin.address.manage', compact('counties', 'addresses', 'user'));
    }

    /**
     * Search a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $addressQuery = $this->getAddressData($request);
        $addresses = $addressQuery->paginate();
        $addresses = $addresses->items();
        return response()->json($addresses);
    }

    public function getAddressData(Request $request)
    {
        $user = auth()->user();
        /** @var Builder|Address $addressQuery */
        $addressQuery = Address::orderBy('county_id')->orderBy('position')->with('county');
        switch ($user->origin_role) {
            case 1: //平台管理員
            case 2: //消防署
                break;
            case 4: //縣市
                $county_ids = User::where('county_id', $user->id)->pluck('id')->toArray();
                $county_ids[] = $user->id;
                $addressQuery->whereIn('county_id', $county_ids);
                break;
            case 5: //分區
                $addressQuery->where('county_id', $user->id);
                break;
        }
        if ($request->input('county_id')) {
            $addressQuery->where('county_id', $request->input('county_id'));
        }
        return $addressQuery;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counties = $this->getCountyDistrictSelectOptions();

        $user = auth()->user();
        $myCounty = null;
        $myUnit = null;
        if ($user->roles()->first()->id == 5) {
            $myCounty = User::find($user->county_id);
            $myUnit = $user->name;
        }

        return view('admin.address.create', compact('counties', 'user', 'myCounty', 'myUnit'));
    }

    private function getCountySelectOptions()
    {
        $countyIdNames = User::where(function ($query) {
            /** @var Builder|User $query */
            $query->where('type', 'county')->whereNull('county_id');
        })->orWhere(function ($query) {
            /** @var Builder|User $query */
            $query->where('username', 'nfa');
        })->pluck('name', 'id')->toArray();
        $counties = [null => '-'] + $countyIdNames;

        return $counties;
    }

    /**
     * @return array
     */
    private function getCountyDistrictSelectOptions()
    {
        /** @var User $user */
        $user = auth()->user();
        /** @var Collection|User[] $countiesAndDistricts */
        $countiesAndDistrictsQuery = User::where(function ($query) {
            /** @var Builder|User $query */
            $query->where('type', 'county')->orWhere('type', 'district')->orWhere('username', 'nfa');
        })->with('county');
        //若為縣市，僅列出縣市本身與轄區分區
        if ($user->type == 'county') {
            $countiesAndDistrictsQuery->where(function ($query) use ($user) {
                /** @var Builder|User $query */
                $query->where('id', $user->id)->orWhere('county_id', $user->id);
            });
        }
        $countiesAndDistricts = $countiesAndDistrictsQuery->get();
        $countyIdNames = [];
        foreach ($countiesAndDistricts as $district) {
            if ($district->type == 'district') {
                $name = $district->county->name . ' - ' . $district->name;
            } else {
                $name = $district->name;
            }
            $countyIdNames[$district->id] = $name;
        }
        $counties = [null => '-'] + $countyIdNames;

        return $counties;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'unit' => 'required',
            'name' => 'required',
        ]);

        /** @var User $user */
        $user = auth()->user();

        $countyId = $request->get('county_id');
        $county = User::find($countyId);
        if (!empty($user->type) && !$user->hasPermOfUser($county)) {
            //非管理員，禁止指定分區為轄區以外之分區
            $countyId = $user->id;
        }
        Address::create(array_merge($request->all(), ['county_id' => $countyId]));

        return redirect()->route('admin.address.manage');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Address $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Address $address)
    {
        $counties = $this->getCountyDistrictSelectOptions();

        /** @var User $user */
        $user = auth()->user();
        if (!$user->hasPermOfUser($address->county)) {
            abort(403);
        }

        return view('admin.address.edit', compact('address', 'counties', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Address $address
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Address $address)
    {
        if ($request->has('fromPosition') && $request->has('toPosition')) {
            $county_id = $request->get('county_id', null);
            $fromId = $request->get('fromId');
            $fromPosition = $request->get('fromPosition');
            $toPosition = $request->get('toPosition');
            if ($fromPosition && $toPosition && $fromPosition !== $toPosition) {
                DB::select('call addresses_exange_position(?,?,?,?)', [$county_id, $fromId, $fromPosition, $toPosition]);
            }
        } else {
            $this->validate($request, [
                'unit' => 'required',
                'name' => 'required',
            ]);

            /** @var User $user */
            $user = auth()->user();
            if (!$user->hasPermOfUser($address->county)) {
                abort(403);
            }

            $countyId = $request->get('county_id');
            $county = User::find($countyId);
            if (!empty($user->type) && !$user->hasPermOfUser($county)) {
                //非管理員，禁止指定分區為轄區以外之分區
                $countyId = $user->id;
            }

            $address->update(array_merge($request->all(), ['county_id' => $countyId]));
        }
        if ($request->has('response_json') && $request->get('response_json')) {
            $addressQuery = $this->getAddressData($request);
            $addresses = $addressQuery->paginate();
            $addresses = $addresses->items();
            return response()->json($addresses);
        } else {

            return redirect()->route('admin.address.manage');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Address $address
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Address $address)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user->hasPermOfUser($address->county)) {
            abort(403);
        }

        $address->delete();
        if (request()->has('response_json') && request()->get('response_json')) {
            $addressQuery = $this->getAddressData(request());
            $addresses = $addressQuery->paginate();
            $addresses = $addresses->items();
            return response()->json($addresses);
        } else {
            return redirect()->back();
        }
    }

    /**
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadXlsx()
    {
        $fileName = '通訊錄.xlsx';
        $countyId = request()->get('county_id');

        return \Excel::download(new AddressExport($countyId), $fileName);
    }

    public function getChildren(Request $request)
    {
        $county_id = $request->input('county_id');

        return Address::select('unit')->groupBy('unit')->get();
    }
}
