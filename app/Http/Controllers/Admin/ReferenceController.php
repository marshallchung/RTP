<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreReferenceRequest;
use App\Nfa\Repositories\ReferenceRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Reference;
use Flash;

class ReferenceController extends Controller
{
    use FileUploadTrait;

    public function index(ReferenceRepositoryInterface $referencesRepo)
    {
        $references = $referencesRepo->getReferences();

        return view('admin.references.index', compact('references'));
    }

    public function create()
    {
        $introductionTypes = self::introductionTypes();
        return view('admin.references.create', compact('introductionTypes'));
    }

    public static function introductionTypes()
    {
        return [
            '-'          => '',
            '相關規範'       => '相關規範',
            '參考資料'       => '參考資料',
            '縣市執行計畫書下載區' => '縣市執行計畫書下載區',
        ];
    }

    public function store(StoreReferenceRequest $request, ReferenceRepositoryInterface $referencesRepo)
    {
        $references = $referencesRepo->postReference($request->all());

        $this->handleFiles($request, $references);

        Flash::success(trans('app.createSuccess', ['type' => '消息']));

        return redirect()->route('admin.references.index');
    }

    public function edit($id)
    {
        $data = Reference::find($id);
        $introductionTypes = self::introductionTypes();
        return view('admin.references.edit', compact('data', 'introductionTypes'));
    }

    public function update(StoreReferenceRequest $request, $id)
    {
        $references = Reference::find($id);
        $references->update($request->all());

        $this->handleFiles($request, $references);

        Flash::success(trans('app.updateSuccess', ['type' => '消息']));

        return redirect()->route('admin.references.index');
    }

    /**
     * @param Reference $references
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Reference $references)
    {
        $references->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '消息']));

        return redirect()->route('admin.references.index');
    }
}
