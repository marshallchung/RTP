<?php

namespace App\Http\Controllers\Admin;

use App\StaticPage;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaticPageController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(StaticPage::class);
    }

    private function canManageAll(): bool
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->hasPermission(['modify-static-page', 'resultiii-manage']);
    }

    private function getUserOptions(): array
    {
        return [null => ''] + User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        $canManageAll = $this->canManageAll();

        $staticPageQuery = StaticPage::with('user');
        if (!$canManageAll) {
            $staticPageQuery->where('user_id', $user->id);
        }
        $staticPages = $staticPageQuery->orderBy('id')->paginate(20);

        return view('admin.static-page.index', compact('staticPages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $canManageAll = $this->canManageAll();
        $userOptions = $this->getUserOptions();

        return view('admin.static-page.create', compact('canManageAll', 'userOptions'));
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
            'id'      => ['required', Rule::unique('static_pages')],
            'title'   => 'required',
            'content' => 'required',
        ]);

        $validFillFields = ['id', 'title', 'content'];
        if ($this->canManageAll()) {
            $validFillFields[] = 'user_id';
        }

        StaticPage::create($request->only($validFillFields));

        Flash::success(trans('app.createSuccess', ['type' => '靜態頁面']));

        return redirect()->route('admin.static-page.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\StaticPage $staticPage
     * @return \Illuminate\Http\Response
     */
    public function show(StaticPage $staticPage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\StaticPage $staticPage
     * @return \Illuminate\Http\Response
     */
    public function edit(StaticPage $staticPage)
    {
        $canManageAll = $this->canManageAll();
        $userOptions = $this->getUserOptions();

        return view('admin.static-page.edit', compact('staticPage', 'canManageAll', 'userOptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\StaticPage $staticPage
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, StaticPage $staticPage)
    {
        $this->validate($request, [
            'id'      => ['required', Rule::unique('static_pages')->ignoreModel($staticPage)],
            'title'   => 'required',
            'content' => 'required',
        ]);

        $validFillFields = ['id', 'title', 'content'];
        if ($this->canManageAll()) {
            $validFillFields[] = 'user_id';
        }

        $staticPage->update($request->only($validFillFields));

        Flash::success(trans('app.updateSuccess', ['type' => '靜態頁面']));

        return redirect()->route('admin.static-page.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\StaticPage $staticPage
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(StaticPage $staticPage)
    {
        $staticPage->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '靜態頁面']));

        return redirect()->route('admin.static-page.index');
    }
}
