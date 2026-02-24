<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIntroductionRequest;
use App\Introduction;
use App\IntroductionType;
use App\Nfa\Repositories\IntroductionRepository;
use App\Nfa\Repositories\IntroductionRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use Flash;
use Illuminate\Support\Facades\DB;

class IntroductionController extends Controller
{
    use FileUploadTrait;

    public function index(IntroductionRepositoryInterface $introductionRepo)
    {
        $introductionTypes = [null => ' - 分類 - '] + IntroductionType::pluck('name', 'id')->toArray();
        /** @var IntroductionType $type */
        $type = IntroductionType::find(request('type'));
        $introductions = $introductionRepo->getIntroductions($type);

        return view('admin.introduction.index', compact('introductions', 'introductionTypes'));
    }

    public function create()
    {
        $introductionTypes = [null => ''] + IntroductionType::pluck('name', 'id')->toArray();

        return view('admin.introduction.create', compact('introductionTypes'));
    }

    public function store(StoreIntroductionRequest $request, IntroductionRepositoryInterface $introductionRepo)
    {
        $introductions = $introductionRepo->postIntroduction($request->all());

        $this->handleFiles($request, $introductions);

        Flash::success(trans('app.createSuccess', ['type' => '簡介']));

        return redirect()->route('admin.introduction.index');
    }

    public function edit(Introduction $introduction)
    {
        $introductionTypes = [null => ''] + IntroductionType::pluck('name', 'id')->toArray();

        return view('admin.introduction.edit', compact('introduction', 'introductionTypes'));
    }

    public function update(StoreIntroductionRequest $request, Introduction $introduction)
    {
        $type_id = request('type');
        if ($request->has('fromPosition') && $request->has('toPosition')) {
            $fromId = $request->get('fromId');
            $fromPosition = $request->get('fromPosition');
            $toPosition = $request->get('toPosition');
            if ($fromPosition && $toPosition && $fromPosition !== $toPosition) {
                DB::select('call introductions_exange_position(?,?,?,?)', [$type_id, $fromId, $fromPosition, $toPosition]);
            }
        } else {
            $input = $request->all();
            if ($request->input('sort')) {
                $input['introduction_type_id'] = $input['sort'];
            }
            $introduction->update($input);

            $this->handleFiles($request, $introduction);
        }
        if ($request->has('response_json') && $request->get('response_json')) {
            $uploadsRepo = new IntroductionRepository();
            $type = IntroductionType::find($type_id);
            $uploads = $uploadsRepo->getIntroductions($type)->items();
            return response()->json($uploads);
        } else {

            Flash::success(trans('app.updateSuccess', ['type' => '簡介']));

            return redirect()->route('admin.introduction.index');
        }
    }

    /**
     * @param Introduction $introduction
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Introduction $introduction)
    {
        $introduction->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '簡介']));

        return redirect()->route('admin.introduction.index');
    }
}
