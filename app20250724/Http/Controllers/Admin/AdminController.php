<?php

namespace App\Http\Controllers\Admin;

use App\IntroductionType;
use App\RootTopic;
use App\Topic;
use App\User;
use Flash;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function reportTermsIndex(Request $request)
    {
        $data = RootTopic::with('topics.author')->get();
        switch ($request->user()->origin_role) {
            case 1:
            case 2:
                $types = [
                    ''          => '-',
                    'rootTopic' => '根項目',
                    'topic'     => '子項目',
                ];
                break;
            case 4:
            case 5:
            default:
                $types = [
                    ''      => '-',
                    'topic' => '子項目',
                ];
                break;
        }

        return view('admin.admins.reportTermsIndex', compact('data', 'types'));
    }

    public function editReportTerms(Request $request)
    {
        switch ($request->input('work_type')) {
            case 'rootTopic':
                /** @var RootTopic $topic */
                $topic = RootTopic::find($request->input('id'));
                break;

            case 'topic':
                /** @var Topic $topic */
                $topic = Topic::find($request->input('id'));
                break;
        }


        $topic->title = $request->input('title');
        $topic->save();

        Flash::success(trans('app.updateSuccess', ['type' => '工作項目']));

        return redirect()->route('admin.admin.reportTerms', ['year' => $request->input('year', 2018)]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delReportTerms(Request $request)
    {
        switch ($request->input('type')) {
            case 'rootTopic':
                Topic::where('category', $request->input('id'))->delete();
                RootTopic::find($request->input('id'))->delete();
                break;
            case 'topic':
                Topic::find($request->input('id'))->delete();
                break;
        }

        Flash::success(trans('app.updateSuccess', ['type' => '工作項目']));

        return redirect()->route('admin.admin.reportTerms', ['year' => $request->input('year', 2018)]);
    }


    public function publicTermsIndex(Request $request)
    {
        $data = IntroductionType::get();

        return view('admin.admins.publicTermsIndex', compact('data'));
    }

    public function editPublicTerms(Request $request)
    {
        /** @var IntroductionType $topic */
        $topic = IntroductionType::find($request->input('id'));
        $topic->name = $request->input('title');
        $topic->save();

        Flash::success(trans('app.updateSuccess', ['type' => '民眾版簡介分類項目']));

        return redirect()->route('admin.admin.publicTerms', ['year' => $request->input('year', date('year'))]);
    }

    public function createPublicTerms(Request $request)
    {
        $topic = new IntroductionType;
        $topic->name = $request->input('title');
        $topic->save();

        Flash::success(trans('app.createSuccess', ['type' => '民眾版簡介分類項目']));

        return redirect()->route('admin.admin.publicTerms', ['year' => $request->input('year', date('year'))]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delPublicTerms(Request $request)
    {
        $topic = IntroductionType::find($request->input('id'));
        $topic->delete();

        Flash::success(trans('app.updateSuccess', ['type' => '民眾版簡介分類項目']));

        return redirect()->route('admin.admin.publicTerms', ['year' => $request->input('year', date('year'))]);
    }

    public function publicUrlsIndex(Request $request)
    {
        $user = $request->user();
        if ($user->origin_role < 4) {
            $data = User::where('origin_role', 4)->get();
        } else {
            $data = User::where([
                'origin_role' => 4,
                'id'          => $user->id,
            ])->get();
        }

        return view('admin.admins.publicUrlsIndex', compact('data'));
    }

    public function editPublicUrls(Request $request)
    {
        /** @var User $topic */
        $topic = User::find($request->input('id'));
        $topic->url = $request->input('url');
        $topic->save();

        Flash::success(trans('app.updateSuccess', ['type' => '縣市網址']));

        return redirect()->route('admin.admin.publicUrls');
    }

    public function countyOrder(Request $request)
    {
        $user = $request->user();
        if ($user->origin_role < 4) {
            $data = User::where('origin_role', 4)->orderBy('sort_order', 'asc')->get();
        } else {
            $data = User::where([
                'origin_role' => 4,
                'id'          => $user->id,
            ])->orderBy('sort_order', 'asc')->get();
        }

        return view('admin.admins.usersCountyIndex', compact('data'));
    }

    public function editCountyOrder(Request $request)
    {
        /** @var User $topic */

        $countyList = $request['countyList'];
        foreach ($countyList as $key => $value) {
            print "The name is " . $key . " and email is " . $countyList[$key] . ", thank you\n";
            $topic = User::find($key);
            $topic->sort_order = $value;
            $topic->save();

            /*Flash::success(trans('app.updateSuccess', ['type' => '縣市網址']));*/
        }
        return redirect()->route('admin.admin.countyOrder');
    }

    public function createReportTerms(Request $request)
    {
        $user = $request->user();

        if ($request->input('type') == 'rootTopic') {
            if ($user->origin_role > 2) { // 縣市及鄉鎮不能加
                return response()->json([
                    'error' => '權限不正確',
                ]);
            }
            foreach ($request->input('titles') as $title) {
                $year = $request->input('year');
                $work_type = $request->input('work_type');
                if ($work_type == 'centralReports') {
                    $year = 2017;
                }
                if (str_replace(' ', '', $title) == '') {
                    continue;
                }
                RootTopic::create([
                    'year'      => $year,
                    'title'     => $title,
                    'work_type' => $work_type,
                ]);
            }
        } else {
            if (!$request->input('rootTopic_id')) {
                return response()->json(['error' => '請選擇所屬根項目']);
            }
            switch ($user->origin_role) {
                case 1:
                case 2:
                    $unit_id = 0;
                    $levels = '1.2';
                    $type = $request->input('topicType');
                    $class = '0.1.2';
                    break;
                case 4:
                case 5:
                    $unit_id = $user->id;
                    $levels = $user->level;
                    $type = $user->type;
                    $class = '0.1.2';
                    break;
            }
            foreach ($request->input('titles') as $title) {
                if (str_replace(' ', '', $title) == '') {
                    continue;
                }
                $category = $request->input('rootTopic_id');
                $work_type = $request->input('work_type');
                /*if ($work_type == 'centralReports') {
                    $category = 149;
                }*/
                Topic::create([
                    'title'      => $title,
                    'levels'     => $levels,
                    'type'       => $type,
                    'class'      => $class,
                    'exclude'    => null,
                    'category'   => $category,
                    'unit_id'    => $unit_id, //縣市才需要, 否則為0
                    'user_id'    => $user->id,
                    'work_type'  => $work_type,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return response()->json([
            'success' => 1,
            'msg'     => '新增成功',
        ]);
    }

    public function getRootTopics(Request $request)
    {
        return response()->json(RootTopic::where([
            'year'      => $request->input('year'),
            'work_type' => $request->input('work_type'),
        ])->orWhere('work_type', 'centralReports')->get());
    }
}
