<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUploadRequest;
use App\Nfa\Repositories\UploadRepository;
use App\Nfa\Repositories\UploadRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Upload;
use Auth;
use Flash;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    use FileUploadTrait;

    protected $validation = [
        'name'    => 'required',
        'files.0' => 'required',
    ];

    public function index(UploadRepositoryInterface $uploadRepo)
    {
        $uploads = $uploadRepo->getUploads();
        $routeName = (Auth::user()->hasPermission('create-uploads')) ? 'edit' : 'show';

        return view('admin.upload.index', compact('uploads', 'routeName'));
    }

    public function create()
    {
        return view('admin.upload.create');
    }

    public function store(StoreUploadRequest $request, UploadRepositoryInterface $uploadRepo)
    {
        $upload = $uploadRepo->addUpload($request->all());

        $this->handleFiles($request, $upload);

        Flash::success(trans('app.createSuccess', ['type' => 'upload']));

        return redirect()->route('admin.uploads.index');
    }

    public function edit(Upload $upload)
    {
        return view('admin.upload.edit', compact('upload'));
    }

    public function show(Upload $data)
    {
        return view('admin.upload.show', compact('data'));
    }


    public function update(StoreUploadRequest $request, Upload $upload)
    {
        if ($request->has('fromPosition') && $request->has('toPosition')) {
            $fromId = $request->get('fromId');
            $fromPosition = $request->get('fromPosition');
            $toPosition = $request->get('toPosition');
            if ($fromPosition && $toPosition && $fromPosition !== $toPosition) {
                DB::select('call uploads_exange_position(?,?,?)', [$fromId, $fromPosition, $toPosition]);
            }
        } else {
            $upload->update($request->all());

            $this->handleFiles($request, $upload);
        }
        if ($request->has('response_json') && $request->get('response_json')) {
            $uploadsRepo = new UploadRepository();
            $uploads = $uploadsRepo->getUploads()->items();
            return response()->json($uploads);
        } else {
            Flash::success(trans('app.updateSuccess', ['type' => 'upload']));

            return redirect()->route('admin.uploads.index');
        }
    }

    /**
     * @param Upload $upload
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Upload $upload)
    {
        $upload->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => 'upload']));

        return redirect()->route('admin.uploads.index');
    }

    public function view(UploadRepositoryInterface $uploadRepo)
    {
        $uploads = $uploadRepo->getViewUploads();
        //dd($uploads);
        return view('admin.upload.view', compact('uploads'));
    }
}
