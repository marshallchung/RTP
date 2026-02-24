<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\HomePageCarouselImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHomePageCarouselImageRequest;
use App\Nfa\Repositories\HomePageCarouselImageRepository;
use App\Nfa\Repositories\HomePageCarouselImageRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class HomePageCarouselImageController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param HomePageCarouselImageRepositoryInterface $homePageCarouselImageRepository
     * @return \Illuminate\Http\Response
     */
    public function index(HomePageCarouselImageRepositoryInterface $homePageCarouselImageRepository)
    {
        $homePageCarouselImages = $homePageCarouselImageRepository->get();
        return view('admin.home-page-carousel-image.index', compact('homePageCarouselImages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.home-page-carousel-image.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreHomePageCarouselImageRequest $request
     * @param HomePageCarouselImageRepositoryInterface $homePageCarouselImageRepository
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHomePageCarouselImageRequest $request, HomePageCarouselImageRepositoryInterface $homePageCarouselImageRepository)
    {
        $news = $homePageCarouselImageRepository->store($request->all());

        $this->handleFiles($request, $news);

        Flash::success(trans('app.createSuccess', ['type' => '首頁輪播項目']));

        return redirect()->route('admin.home-page-carousel-image.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\HomePageCarouselImage $homePageCarouselImage
     * @return \Illuminate\Http\Response
     */
    public function show(HomePageCarouselImage $homePageCarouselImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\HomePageCarouselImage $homePageCarouselImage
     * @return \Illuminate\Http\Response
     */
    public function edit(HomePageCarouselImage $homePageCarouselImage)
    {
        return view('admin.home-page-carousel-image.edit', compact('homePageCarouselImage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\HomePageCarouselImage $homePageCarouselImage
     * @return \Illuminate\Http\Response
     */
    public function update(StoreHomePageCarouselImageRequest $request, HomePageCarouselImage $homePageCarouselImage)
    {
        if ($request->has('fromPosition') && $request->has('toPosition')) {
            $fromId = $request->get('fromId');
            $fromPosition = $request->get('fromPosition');
            $toPosition = $request->get('toPosition');
            if ($fromPosition && $toPosition && $fromPosition !== $toPosition) {
                DB::select('call home_page_carousel_images_exange_position(?,?,?)', [$fromId, $fromPosition, $toPosition]);
            }
        } else {
            $homePageCarouselImage->update($request->all());

            $this->handleFiles($request, $homePageCarouselImage);
        }
        if ($request->has('response_json') && $request->get('response_json')) {
            $dataRepo = new HomePageCarouselImageRepository();
            $data = $dataRepo->get()->items();
            return response()->json($data);
        } else {
            Flash::success(trans('app.updateSuccess', ['type' => '首頁輪播項目']));

            return redirect()->route('admin.home-page-carousel-image.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\HomePageCarouselImage $homePageCarouselImage
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(HomePageCarouselImage $homePageCarouselImage)
    {
        $homePageCarouselImage->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '首頁輪播項目']));

        return redirect()->route('admin.home-page-carousel-image.index');
    }
}
