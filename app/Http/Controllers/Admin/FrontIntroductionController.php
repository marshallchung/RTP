<?php

namespace App\Http\Controllers\Admin;

use App\FrontIntroduction;
use App\Nfa\Traits\FileUploadTrait;
use App\Upload;
use Flash;
use Illuminate\Http\Request;

class FrontIntroductionController extends Controller
{
    use FileUploadTrait;


    public function edit($id)
    {
        $data = FrontIntroduction::find($id);

        return view('admin.front-introduction.edit', compact('data'));
    }

    public function show(Upload $data)
    {
        return view('admin.upload.show', compact('data'));
    }


    public function update(Request $request, $id)
    {
        /** @var FrontIntroduction $data */
        $data = FrontIntroduction::find($id);
        $data->update($request->all());
        $this->handleFiles($request, $data);

        Flash::success($data->title . ' 編輯成功');

        return redirect()->route('admin.front-introduction.edit', $data->id);
    }
}
