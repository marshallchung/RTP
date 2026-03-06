<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreNewsRequest;
use App\Nfa\Repositories\QaRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Qa;
use Flash;
use Illuminate\Http\Request;

class QaController extends Controller
{
    use FileUploadTrait;

    /**
     * 列表顯示QA專區。
     *
     * @param QaRepositoryInterface $QaRepo
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(QaRepositoryInterface $QaRepo, Request $request)
    {
        $qas = $QaRepo->getQas($request->input('keyWord'), $request->input('sort'));
        //dd(compact('qas'));
        $sorts = self::sorts();
        return view('admin.qas.index', compact('qas', 'sorts'));
    }

    /**
     * QA專區的分類選項。
     *
     */
    public static function sorts($hidden = false)
    {
        if ($hidden) {

            return [
                ''     => '-',
                '工作項目' => '工作項目',
                '整體計畫' => '整體計畫',
                '其他'   => '其他',
            ];
        } else {

            return [
                ''     => '-',
                '經費核銷' => '經費核銷',
                '工作項目' => '工作項目',
                '整體計畫' => '整體計畫',
                '其他'   => '其他',
            ];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sorts = self::sorts();
        return view('admin.qas.create', compact('sorts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNewsRequest $request
     * @param QaRepositoryInterface $qasRepo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNewsRequest $request, QaRepositoryInterface $qasRepo)
    {
        $qas = $qasRepo->postQa($request->all());

        $this->handleFiles($request, $qas);

        Flash::success(trans('app.createSuccess', ['type' => 'QA專區 - 新增']));

        return redirect()->route('admin.qas.index');
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
        $qa = Qa::find($id);
        $sorts = self::sorts();
        return view('admin.qas.edit', compact('qa', 'sorts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $qas = Qa::find($id);
        $qas->update($request->all());

        $this->handleFiles($request, $qas);

        Flash::success(trans('app.updateSuccess', ['type' => '消息']));

        return redirect()->route('admin.qas.index');
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
        $qas = Qa::find($id);
        $qas->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => 'QA專區']));

        return redirect()->route('admin.qas.index');
    }
}
