<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageDataRequest;
use App\ImageDatum;
use App\ImageDatumType;
use App\Nfa\Repositories\ImageDatumRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\User;
use Flash;
use Illuminate\Http\Request;

class ImageDataController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //地區清單
        $counties = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
        $counties = [null => '-'] + $counties;
        //類別清單
        $categories = ImageDatumType::pluck('name', 'id')->toArray();
        $categories = [null => '-'] + $categories;
        //過濾
        $userQuery = User::whereNotNull('type');
        $imageDatumTypeQuery = ImageDatumType::query();
        $imageDatumQuery = ImageDatum::query();
        if ($countyId = request()->get('county_id')) {
            $countyUserIds = User::where('id', $countyId)->orWhere('county_id', $countyId)->pluck('id');
            $userQuery->whereIn('id', $countyUserIds);
            $imageDatumQuery->whereIn('user_id', $countyUserIds);
        }
        if ($categoryId = request()->get('image_datum_type_id')) {
            $imageDatumTypeQuery->where('id', $categoryId);
            $imageDatumQuery->where('image_datum_type_id', $categoryId);
        }
        //        $imageDatumQuery->whereYear('created_at', '=', request('year', Carbon::now()->year));
        //取出
        $users = $userQuery->get();
        $imageDatumTypes = $imageDatumTypeQuery->get();
        $imageData = $imageDatumQuery->get();
        $data = [];
        foreach ($imageData as $imageDatum) {
            $data[$imageDatum->image_datum_type_id][$imageDatum->user_id]
                = isset($data[$imageDatum->image_datum_type_id][$imageDatum->user_id])
                ? $data[$imageDatum->image_datum_type_id][$imageDatum->user_id]++ : 1;
        }

        //有權限存取的地區
        /** @var User $authUser */
        $authUser = auth()->user();
        $hasSuperPerm = is_null($authUser->type);
        $hasPermIds = User::where('id', $authUser->id)->orWhere('county_id', $authUser->id)->pluck('id')->toArray();

        return view('admin.image-data.index', compact('counties', 'categories', 'imageDatumTypes', 'users', 'data', 'hasSuperPerm', 'hasPermIds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ImageDatumType $imageDatumType
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function create(ImageDatumType $imageDatumType, User $user)
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!$authUser->hasPermOfUser($user)) {
            abort(403);
        }

        return view('admin.image-data.create', compact('imageDatumType', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreImageDataRequest|Request $request
     * @param ImageDatumRepositoryInterface $imageDatumRepo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageDataRequest $request, ImageDatumRepositoryInterface $imageDatumRepo)
    {
        $reportUser = User::find($request->get('user_id'));
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!$authUser->hasPermOfUser($reportUser)) {
            abort(403);
        }

        $imageDatum = $imageDatumRepo->getImageDatum($request->all());

        $this->handleFiles($request, $imageDatum);

        Flash::success(trans('app.createSuccess', ['type' => 'image-datum']));

        return redirect()->route('admin.image-data.index');
    }

    /**
     * Display the specified resource.
     *
     * @param ImageDatumType $imageDatumType
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(ImageDatumType $imageDatumType, User $user)
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        $hasPermission = $authUser->hasPermOfUser($user);
        $imageDatum = ImageDatum::where('image_datum_type_id', $imageDatumType->id)
            ->where('user_id', $user->id)
            ->first();
        if (!$imageDatum) {
            abort(404);
        }

        return view('admin.image-data.show', compact('imageDatum', 'hasPermission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ImageDatumType $imageDatumType
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(ImageDatumType $imageDatumType, User $user)
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!$authUser->hasPermOfUser($user)) {
            abort(403);
        }
        $imageDatum = ImageDatum::where('image_datum_type_id', $imageDatumType->id)
            ->where('user_id', $user->id)
            ->first();
        if (!$imageDatum) {
            abort(404);
        }

        return view('admin.image-data.edit', compact('imageDatum', 'imageDatumType', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreImageDataRequest $request
     * @param ImageDatum $imageDatum
     * @return \Illuminate\Http\Response
     */
    public function update(StoreImageDataRequest $request, ImageDatum $imageDatum)
    {
        $reportUser = User::find($request->get('user_id'));
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!$authUser->hasPermOfUser($reportUser)) {
            abort(403);
        }

        $imageDatum->update($request->all());

        $this->handleFiles($request, $imageDatum);

        Flash::success(trans('app.updateSuccess', ['type' => 'image-datum']));

        return redirect()->route('admin.image-data.show', [$imageDatum->image_datum_type_id, $imageDatum->user_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ImageDatum $imageDatum
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(ImageDatum $imageDatum)
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!$authUser->hasPermOfUser($imageDatum->user)) {
            abort(403);
        }

        $imageDatum->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => 'image-datum']));

        return redirect()->route('admin.image-data.index');
    }
}
