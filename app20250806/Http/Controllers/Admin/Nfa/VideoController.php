<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Nfa\Repositories\VideoRepository;
use App\Nfa\Repositories\VideoRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Video;
use Auth;
use Flash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    use FileUploadTrait;

    public function index(VideoRepositoryInterface $videoRepo)
    {
        $video = $videoRepo->getVideo();
        $render = $video->links()->render();
        $video = $video->items();
        foreach ($video as $key => $value) {
            $video[$key]['sort_name'] = $value->sort_name;
        }
        $routeName = (Auth::user()->hasPermission('create-video')) ? 'edit' : 'show';

        return view('admin.video.index', compact('video', 'routeName', 'render'));
    }

    public function create()
    {
        $sorts = self::sorts();

        return view('admin.video.create', compact('sorts'));
    }

    public static function sorts(): array
    {
        $options = [null => '無'];
        foreach (Video::$sorts as $idx => $sort) {
            $subOptions = [];
            $subSorts = Arr::get($sort, 'sub_sorts', []);
            foreach ($subSorts as $subSort) {
                $subOptions[$idx . '_' . $subSort] = $sort['name'] . ' > ' . $subSort;
            }
            $subOptions[$idx] = $sort['name'] . ' > 其他';
            $options[$sort['name']] = $subOptions;
        }

        return $options;
    }

    public function store(StoreVideoRequest $request, VideoRepositoryInterface $videoRepo)
    {
        $video = $videoRepo->create($request->all());

        $this->handleFiles($request, $video);

        Flash::success(trans('app.createSuccess', ['type' => '宣導資訊']));

        return redirect()->route('admin.video.index');
    }

    public function edit(Video $video)
    {
        $sorts = self::sorts();
        $selected_sort = null;
        if ($video->sort) {
            $selected_sort = $video->sort;
            if ($video->sub_sort) {
                $selected_sort .= '_' . $video->sub_sort;
            }
        }

        return view('admin.video.edit', compact('video', 'sorts', 'selected_sort'));
    }

    public function show(Video $video)
    {
        return view('admin.video.show', compact('video'));
    }

    public function update(StoreVideoRequest $request, Video $video, VideoRepositoryInterface $videoRepo)
    {
        if ($request->has('fromPosition') && $request->has('toPosition')) {
            $fromId = $request->get('fromId');
            $fromPosition = $request->get('fromPosition');
            $toPosition = $request->get('toPosition');
            if ($fromPosition && $toPosition && $fromPosition !== $toPosition) {
                DB::select('call videos_exange_position(?,?,?)', [$fromId, $fromPosition, $toPosition]);
            }
        } else {
            $video->update($request->all());
            //$videoRepo->update($video, $request->all());

            $this->handleFiles($request, $video);
        }

        if ($request->has('response_json') && $request->get('response_json')) {
            $videoRepo = new VideoRepository();
            $video = $videoRepo->getVideo();
            $render = $video->links()->render();
            $video = $video->items();
            foreach ($video as $key => $value) {
                $video[$key]['sort_name'] = $value->sort_name;
            }
            return response()->json(['video' => $video, 'render' => $render]);
        } else {
            Flash::success(trans('app.updateSuccess', ['type' => '宣導資訊']));

            return redirect()->route('admin.video.index');
        }
    }

    /**
     * @param Video $video
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Video $video)
    {
        $video->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '宣導資訊']));

        return redirect()->route('admin.video.index');
    }
}
