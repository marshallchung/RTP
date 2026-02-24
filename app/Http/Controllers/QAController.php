<?php

namespace App\Http\Controllers;

use App\DataTables\QADataTable;
use App\DataTables\Scopes\QASortScope;
use Illuminate\Http\Request;
use App\Qa;
use App\Services\CountService;

class QAController extends Controller
{
    public function index(Request $request)
    {
        // 依分類過濾
        $sorts = self::sorts();
        return view('qa.index', compact('sorts'));
    }

    public function search(Request $request)
    {
        $QASList = Qa::with(['author', 'counter'])->whereActive(true)->wherePublish(true)->where('sort', '<>', '經費核銷');
        if ($sort = request()->get('sort')) {
            $QASList->where('sort', $sort);
        }
        if ($search = request()->get('search')) {
            $QASList->where('title', 'LIKE', "%{$search}%");
        }
        $QASList = $QASList->paginate(15);
        $pagination = $QASList->links()->render();
        $QASList = $QASList->items();
        $data = compact('QASList', 'pagination');
        return response()->json($data);
    }

    private function sorts(): array
    {
        $sorts = \App\Http\Controllers\Admin\QaController::sorts(true);

        return array_filter($sorts, fn ($key) => $key !== '', ARRAY_FILTER_USE_KEY);
    }

    public function show(Qa $qa, CountService $countService)
    {
        //檢查是否公開於民眾版
        if (!$qa->publish) {
            abort(404);
        }

        $countService->increase($qa);

        return view('qa.show', compact('qa'));
    }
}
