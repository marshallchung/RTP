<?php

namespace App\Http\Controllers\Admin;

use App\Exports\QuestionnaireStatisticExport;
use App\Http\Requests\StoreQuestionnaireRequest;
use App\Nfa\Repositories\QuestionRepositoryInterface;
use App\Nfa\Repositories\UserRepositoryInterface;
use App\Nfa\Traits\ExcelTrait;
use App\Nfa\Traits\FileUploadTrait;
use App\Question;
use App\Questionnaire;
use App\Services\QuestionnaireExportService;
use App\User;
use Excel;
use Flash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionnaireController extends Controller
{
    use ExcelTrait;
    use FileUploadTrait;

    public function index()
    {
        $q = Questionnaire::with('author', 'users');
        if (Auth::user()->origin_role > 2) {
            $q->where('type', 'like', '%' . Auth::user()->origin_role . '%');
        }

        $data = $q->get();

        return view('admin.questionnaires.index', compact('data'));
    }

    public function create()
    {
        return view('admin.questionnaires.create');
    }

    public function edit($id)
    {
        $data = Questionnaire::find($id);

        return view('admin.questionnaires.edit', compact('data'));
    }

    public function store(StoreQuestionnaireRequest $request, QuestionRepositoryInterface $questionRepository)
    {
        /*$file = $request->file('file');
        $fileName = $file->getClientOriginalName() . '_' . date('Y-m-d_H_i_s');

        $data = self::excelToArray($file, [
            'column_from' => 'A',
            'column_to'   => 'I',
        ]);

        $questions = $this->insertQuestions($data);*/

        //if (!empty($questions)) {
        $questionnaire = Questionnaire::create([
            'title'           => $request->input('title'),
            'type'            => $request->input('type'),
            'date_from'       => $request->input('date_from'),
            'date_to'         => $request->input('date_to'),
            'expire_soon_date' => $request->input('expire_soon_date'),
            'basic_weight'    => $request->input('basic_weight'),
            'advanced_weight' => $request->input('advanced_weight'),
            'user_id'         => Auth::user()->id,
        ]);
        //$questionnaire->questions()->saveMany($questions);

        //$this->attachFiles([$request->file('file')], $questionnaire, '');

        $questionRepository->updateScoreType($questionnaire->id, true);

        Flash::success('問卷新增成功');

        return redirect()->route('admin.questionnaire.index');
        /*} else {
            Flash::error('檔案有誤，問卷新增失敗。');

            return redirect()->route('admin.questionnaire.create');
        }*/
    }

    private function insertQuestions($data)
    {
        $typesTable = [
            '標題' => 'title',
            '單選' => 'radio',
            '複選' => 'checkbox',
            '填充' => 'text',
            '作文' => 'textarea',
            '標頭' => 'bigger',
        ];

        $filesTable = [
            '是' => 1,
            '否' => 0,
            ''  => 0,
            '1' => 1,
            '0' => 0,
        ];

        $questions = [];
        foreach ($data as $key => $row) {
            if ($key === 0) {
                continue;
            }

            if (strlen(strval($row[0])) === 0) {
                break;
            }

            if (!isset($filesTable[str_replace(' ', '', $row[6])])) {
                dd('excel檔有誤');
            }

            $questions[] = new Question([
                'code'        => (string) $row[0],
                'seq'         => $key,
                'indent'      => count(explode('.', (string) $row[0])),
                'type'        => $typesTable[$row[1]],
                'content'     => (string) $row[2],
                'options'     => (string) $row[3],
                'gain'        => (float) $row[4],
                'extra_gain'  => (float) $row[5],
                'upload'      => $filesTable[str_replace(' ', '', $row[6])],
                'score_limit' => (float) $row[7],
                'comment'     => $filesTable[str_replace(' ', '', $row[8])],
            ]);
        }

        return $questions;
    }

    public function submit(Request $request, $account_id, $questionnaire_id)
    {
        /** @var User $user */
        $user = User::find($account_id);

        $data = $request->all();
        $status = $data['status'];
        $files = [];
        foreach ($data as $key => $value) {
            if (!is_numeric($key)) {
                if (strpos($key, 'files_') !== false) {
                    $files[$key] = $value;
                }
                unset($data[$key]);
            }
        }
        $answers = json_encode($data);

        /** @var Questionnaire $questionnaire */
        $questionnaire = $user->questionnaires()->where('questionnaires.id', $questionnaire_id)->first();
        if ($questionnaire === null) {
            $user->questionnaires()->attach($questionnaire_id, [
                'status'  => $status,
                'answers' => $answers,
            ]);
            $questionnaire = $user->questionnaires()->where('questionnaires.id', $questionnaire_id)->first();
            Flash::success('評估表提交成功。');
        } else {
            if ($status == 0 && $questionnaire->pivot->status > 0) {
                Flash::error('已提交之評估表無法暫存。');

                return redirect()->route('admin.questionnaire.index');
            }

            $updateData = [
                'status'     => $status,
                'answers'    => $answers,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            // 原本是暫存狀態，現改完正式提交的情況必須更新created_at
            if ($questionnaire->pivot->status == 0 && $status > 0) {
                $updateData['created_at'] = $updateData['updated_at'];
            }

            DB::table('questionnaire_user')->where('id', $questionnaire->pivot->id)
                ->update($updateData);
            Flash::success('評估表作答修改成功。');
        }

        // 處理檔案
        if ($request->get('removed_files')) {
            $this->removeFiles($request->get('removed_files'));
        }

        foreach ($files as $key => $value) {
            if ($request->hasFile($key)) {
                $id = str_replace('files_', '', $key);
                if (strpos($key, 'files') !== false) {
                    $this->attachFiles(
                        $request->file($key),
                        $questionnaire->questions()->where('id', $id)->first(),
                        $user->id,
                        $open = 1
                    );
                }
            }
        }

        return redirect()->route('admin.questionnaire.index');
    }

    public function submitComments(Request $request, $account_id, $questionnaire_id)
    {
        $author = Auth::user();
        $user = User::find($account_id);

        $data = $request->all();
        $comments = json_encode($data);

        $questionnaire = $user->questionnaires()->where('questionnaires.id', $questionnaire_id)->first();
        if ($questionnaire !== null) {
            $updateData = [
                'comments' => $comments,
            ];
            // 原本是暫存狀態，現改完正式提交的情況必須更新created_at
            if ($questionnaire->pivot->status == 0 && $status > 0) {
                $updateData['created_at'] = $updateData['updated_at'];
            }

            DB::table('questionnaire_user')->where('id', $questionnaire->pivot->id)
                ->update($updateData);
        }

        return response()->json([
            'ok'  => 1,
            'msg' => '評估表審查意見修改成功。',
        ]);
    }

    public function update(StoreQuestionnaireRequest $request, $qustionnaire_id, QuestionRepositoryInterface $questionRepository)
    {
        $data = [];

        if ($file = $request->file('file')) {
            $fileName = $file->getClientOriginalName() . '_' . date('Y-m-d_H_i_s');

            $data = self::excelToArray($file, [
                'column_from' => 'A',
                'column_to'   => 'I',
            ]);
        }

        $newQuestions = $this->insertQuestions($data);
        /** @var Questionnaire $questionnaire */
        $questionnaire = Questionnaire::find($qustionnaire_id);

        $files = [];
        if (!empty($newQuestions)) {
            // get old file ids
            $user = $request->user();
            foreach ($questionnaire->questions as $question) {
                $old_files = $question->files;
                if ($old_files) {
                    foreach ($old_files as $old_file) {
                        $files[$question->seq][] = $old_file->id;
                    }
                }
            }

            // update questions
            $questionnaire->questions()->delete();
            $questionnaire->questions()->saveMany($newQuestions);

            // update file ids
            foreach ($questionnaire->questions as $question) {
                if (isset($files[$question->seq])) {
                    foreach ($files[$question->seq] as $file_id) {
                        DB::table('files')
                            ->where('id', $file_id)
                            ->update(['post_id' => $question->id]);
                    }
                }
            }
        }

        $questionnaire->title = $request->input('title');
        $questionnaire->type = $request->input('type');
        $questionnaire->date_from = date('Y-m-d H:i:s', strtotime($request->input('date_from')));
        $questionnaire->expire_soon_date = date('Y-m-d H:i:s', strtotime($request->input('expire_soon_date')));
        $questionnaire->date_to = date('Y-m-d H:i:s', strtotime($request->input('date_to')));
        $questionnaire->basic_weight = $request->input('basic_weight');
        $questionnaire->advanced_weight = $request->input('advanced_weight');
        $questionnaire->save();

        if (isset($questionnaire->files[0])) {
            $this->removeFiles(json_encode([$questionnaire->files[0]->id]));
        }
        $this->attachFiles([$request->file('file')], $questionnaire, '');

        $questionRepository->updateScoreType($questionnaire->id, true);

        Flash::success('問卷編輯成功');

        return redirect()->route('admin.questionnaire.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        Questionnaire::find($id)->delete();
        Flash::success(trans('app.deleteSuccess', ['type' => '績效評估自評表']));

        return redirect()->route('admin.questionnaire.index');
    }

    public function panel(UserRepositoryInterface $userRepo, $questionnaire_id = null)
    {
        $availableYears = Questionnaire::orderByDesc('created_at')->select(DB::raw('YEAR(date_from) as year'))
            ->distinct()->pluck('year')->toArray();
        //$availableYears = [null => '全部'] + array_combine($availableYears, $availableYears);

        $filteredQuestionnaire = Questionnaire::find(request('questionnaire_id'));
        view()->share(compact('filteredQuestionnaire'));

        $accounts = $userRepo->getCountyDistrictAccountsByClassThenArea(true);

        //dd($accounts[0]['accounts'][0]);

        $questionnaires = Questionnaire::select('id', 'title', DB::raw('YEAR(date_from) as year'))->get()->keyBy('id');


        return view('admin.questionnaires.panel', compact('accounts', 'availableYears', 'questionnaire_id', 'questionnaires', 'filteredQuestionnaire'));
    }

    static function parseBtn($unit, $updateStatus = false, $filteredQuestionnaire = null)
    {
        $content = "";
        if ($filteredQuestionnaire) {
            $questionnaires = $unit->questionnaires->where('id', $filteredQuestionnaire->id);
        } else {
            $questionnaires = $unit->questionnaires;
        }

        foreach ($questionnaires as $questionnaire) {
            if ($questionnaire->pivot->status == 1) {
                $status = '已繳交';
                $changingStatus = 2;
                $color = 'default';
            } else {
                $status = '特別開放中';
                $changingStatus = 1;
                $color = 'warning';
            }

            if ($questionnaire->pivot->status > 0) {
                $content .= '<div class="flex flex-row flex-wrap items-center justify-start p-4 space-x-2 border text-content text-mainAdminTextGrayDark">' .
                    '<a class="pl-4 text-base text-mainBlueDark" href="' . route('admin.questionnaire.show', [
                        'account_id'       => $unit->id,
                        'questionnaire_id' => $questionnaire->id
                    ]) . '">' . $questionnaire->title . '</a> ' .
                    '<small class="whitespace-nowrap">(最初 </small><strong class="whitespace-nowrap">' .  date('Y-m-d', strtotime($questionnaire->pivot->created_at)) . '</strong>，' .
                    '<small class="whitespace-nowrap"> 最後 </small><strong class="whitespace-nowrap">' . date('Y-m-d', strtotime($questionnaire->pivot->updated_at)) . '</strong>) ';
                if ($updateStatus) {
                    $content .= '<button @click="statusClick" data-rel="' . $questionnaire->pivot->id . '" data-id="' . $unit->id . '" data-status="' . $changingStatus .
                        '" class="px-4 text-sm rounded cursor-pointer py-1.5 bg-gray-100 hover:bg-gray-50 border border-gray-300" :class="{\'bg-gray-100 hover:bg-gray-50 text-mainAdminTextGrayDark\':$el.dataset.status==\'2\',\'bg-orange-600 hover:bg-orange-500 text-white\':$el.dataset.status==\'1\'}" x-text="$el.dataset.status==\'2\'?\'已繳交\':\'特別開放中\'"></button>';
                }
                $content .= '<a href="' . route('admin.questionnaire.export', ['account_id' => $unit->id, 'questionnaire_id' => $questionnaire->id]) .
                    '" class="px-4 text-sm rounded cursor-pointer py-1.5 bg-gray-100 hover:bg-gray-50 border border-gray-300">打包匯出</a>';
                $content .= '</div>';
            }
        }
        return $content;
    }

    public function showQuestions(Request $request, $questionnaire_id)
    {
        $questionnaire = self::getQuestions($questionnaire_id);
        $can_edit = false;
        if ($questionnaire['date_from'] >= date("Y-m-d H:i:s")) {
            $can_edit = true;
        }
        return view('admin.questionnaires.questions', compact('questionnaire', 'can_edit'));
    }

    public function updateQuestions(Request $request)
    {
        $data = $request->json()->all();
        $id_list = [];
        self::saveQuestion($id_list, $data['questions']);
        Question::whereNotIn('id', $id_list)->where('questionnaire_id', $data['id'])->delete();
        $questionnaire = self::getQuestions($data['id']);
        return response()->json(['msg' => "問卷 {$data['title']} 儲存成功", 'questionnaire' => $questionnaire]);
    }

    static public function saveQuestion(&$id_list, $questions)
    {
        foreach ($questions as $question) {
            $question['type'] = ($question['type'] == 'basic' || $question['type'] == 'advanced') ? 'bigger' : $question['type'];
            if ($question['type'] == 'checkbox' || $question['type'] == 'radio') {
                $options = "";
                $line_list = preg_split('/\n/', $question['options']);
                foreach ($line_list as $one_option) {
                    $option = explode('==', $one_option);
                    if (count($option) >= 2) {
                        $options .= $option[0] . "[" . $option[1] . "]";
                    }
                }
                $question['options'] = $options;
            }
            $new_data = $question;
            unset($new_data['files']);
            unset($new_data['created_at']);
            unset($new_data['updated_at']);
            unset($new_data['deleted_at']);
            unset($new_data['child']);
            if ($new_data['id'] && intval($new_data['id']) > 0) {
                $id_list[] = $new_data['id'];
                Question::where('id', $new_data['id'])->update($new_data);
            } else {
                unset($new_data['id']);
                $id = Question::insertGetId($new_data);
                $id_list[] = $id;
            }
            if (array_key_exists('child', $question) && count($question['child'])) {
                self::saveQuestion($id_list, $question['child']);
            }
        }
    }

    static public function getQuestions($questionnaire_id)
    {
        $questionnaire = Questionnaire::with(['questions', 'questions.files'])->where('id', $questionnaire_id)->first();
        $questionnaire = $questionnaire->toArray();
        if ($questionnaire['questions'] && count($questionnaire['questions']) > 0) {
            $nesty_questions = [];
            foreach ($questionnaire['questions'] as $question) {
                if ($question['type'] === 'radio' || $question['type'] === 'checkbox') {
                    $options = '';
                    preg_match_all('/([^\[]+)\[([\d]+)\]/', $question['options'], $matches);
                    if ($matches && count($matches) >= 3) {
                        foreach ($matches[1] as $opt_key => $opt_value) {
                            $options .= $options === '' ? ($opt_value . '==' . $matches[2][$opt_key]) : ("\n" . $opt_value . '==' . $matches[2][$opt_key]);
                        }
                    }
                    $question['options'] = $options;
                } elseif ($question['type'] === 'bigger' && ($question['score_type'] === 'advanced' || $question['score_type'] === 'basic')) {
                    $question['type'] = $question['score_type'];
                }
                $level = $question['indent'];
                $parent = null;
                while ($level > 1) {
                    if ($parent) {
                        if (array_key_exists('child', $parent)) {
                            $last_key = array_key_last($parent['child']);
                            $parent = &$parent['child'][$last_key];
                        }
                    } else {
                        $last_key = array_key_last($nesty_questions);
                        $parent = &$nesty_questions[$last_key];
                    }
                    $level--;
                }
                if ($parent) {
                    if (!array_key_exists('child', $parent)) {
                        $parent['child'] = [];
                    }
                    $parent['child'][] = $question;
                } else {
                    $nesty_questions[] = $question;
                }
                unset($parent);
            }
            $questionnaire['questions'] = $nesty_questions;
        } else {
            $questionnaire['questions'] = [
                [
                    "id" => null,
                    "questionnaire_id" => $questionnaire_id,
                    "seq" => 1,
                    "code" => "1",
                    "indent" => 1,
                    "type" => "bigger",
                    "score_type" => null,
                    "content" => "1. ",
                    "options" => "",
                    "upload" => 0,
                    "gain" => 1,
                    "extra_gain" => 1,
                    "score_limit" => 0,
                    "comment" => 1,
                ]
            ];
        }
        return $questionnaire;
    }

    public function show(Request $request, $account_id, $questionnaire_id)
    {
        $account = User::find($account_id);
        $has_create_permission = Auth::user()->hasPermission('create-questionnaires');
        $questionnaire = self::getQuestionnaire($account, $questionnaire_id);
        $disableAll = true;


        return view('admin.questionnaires.answer', compact('questionnaire', 'disableAll', 'account', 'account_id', 'has_create_permission'));
    }

    public function answer($account_id, $questionnaire_id)
    {
        $account = User::find($account_id);
        $has_create_permission = Auth::user()->hasPermission('create-questionnaires');
        $questionnaire = self::getQuestionnaire($account, $questionnaire_id);
        $disableAll = false;
        return view('admin.questionnaires.answer', compact('questionnaire', 'disableAll', 'account', 'account_id', 'has_create_permission'));
    }

    static public function getQuestionnaire(User $account = null, $questionnaire_id)
    {
        $answers = [];
        $comments = [];
        //if (!$account || !$questionnaire = $account->questionnaires()->with(['questions', 'questions.files'])->where('questionnaires.id', $questionnaire_id)->first()) {
        //if (!$questionnaire = Auth::user()->questionnaires()->where('questionnaires.id', $questionnaire_id)->first()) {
        $questionnaire = $account->questionnaires()->with(['questions', 'questions.files' => function ($q) use ($account) {
            $q->where('files.memo', $account->id);
        }])->where('questionnaires.id', $questionnaire_id)->first();
        if (!$questionnaire) {
            $questionnaire = Questionnaire::with(['questions', 'questions.files' => function ($q) use ($account) {
                $q->where('files.memo', $account->id);
            }])->where('id', $questionnaire_id)->first();
        } else {
            if ($questionnaire->pivot->answers) {
                $answers = json_decode($questionnaire->pivot->answers, true);
            }
            if ($questionnaire->pivot->comments) {
                $comments = json_decode($questionnaire->pivot->comments, true);
            }
        }
        if ($questionnaire->questions) {

            /*$questionnaire->questions = $questionnaire->questions;
            foreach ($questionnaire->questions as $key => $question) {
                $question->files = $question->files;
            }*/
            $questionnaire = $questionnaire->toArray();
            if (!array_key_exists('pivot', $questionnaire)) {
                $questionnaire['pivot'] = ['status' => 0];
            }
            $questionnaire['basic_total_score'] = 0;
            $questionnaire['advance_total_score'] = 0;
            $questionnaire['has_answer'] = $answers ? true : false;
            $questionnaire['has_comment'] = $comments ? true : false;
            foreach ($questionnaire['questions'] as $key => $question) {
                if ($question['type'] == 'bigger' || $question['type'] == 'title') {
                    $questionnaire['questions'][$key]['comment'] = 0;
                }
                $answer = null;
                $seq = strval($question['id']);
                $questionnaire['questions'][$key]['answer'] = "";
                $questionnaire['questions'][$key]['basic_score'] = 0;
                $questionnaire['questions'][$key]['advance_score'] = 0;
                if (array_key_exists($seq, $answers)) {
                    $answer = $answers[$seq];
                    $questionnaire['questions'][$key]['answer'] = $answer;
                }
                if ($questionnaire['questions'][$key]['comment'] != 0 && array_key_exists($seq, $comments)) {
                    $questionnaire['questions'][$key]['comment_content'] = $comments[$seq];
                }
                if ($question['type'] === 'radio') {
                    $options = [];
                    $get_score = 0;

                    //要處理分數問題
                    preg_match_all('/([^\[]+)\[([\d]+)\]/', $question['options'], $matches);
                    if ($matches && count($matches) >= 3) {
                        foreach ($matches[1] as $opt_key => $opt_value) {
                            $options[$opt_value] = ['score' => floatval($matches[2][$opt_key])];
                            if (is_array($answer)) {
                                $options[$opt_value]['selected'] = in_array($opt_value, $answer) ? true : false;
                            } else {
                                $options[$opt_value]['selected'] = $opt_value === $answer ? true : false;
                            }
                            if ($options[$opt_value]['selected']) {
                                $get_score += ($options[$opt_value]['score'] * ($question['gain'] > 0 ? $question['gain'] : 1));
                                $get_score = $question['score_limit'] > 0 ? ($get_score > $question['score_limit'] ? $question['score_limit'] : $get_score) : $get_score;
                            }
                        }
                        if ($get_score > 0) {
                            if ($question['score_type'] === 'basic') {
                                $questionnaire['questions'][$key]['basic_score'] += $get_score;
                                $questionnaire['basic_total_score'] += $get_score;
                            } elseif ($question['score_type'] === 'advanced') {
                                $questionnaire['questions'][$key]['advance_score'] += $get_score;
                                $questionnaire['advance_total_score'] += $get_score;
                            }
                        }
                    }
                    $questionnaire['questions'][$key]['options'] = $options;
                } elseif ($question['type'] === 'checkbox') {
                    $options = [];
                    $get_score = 0;

                    //要處理分數問題
                    preg_match_all('/([^\[]+)\[([\d]+)\]/', $question['options'], $matches);
                    if ($matches && count($matches) >= 3) {
                        foreach ($matches[1] as $opt_key => $opt_value) {
                            $options[$opt_value] = ['score' => floatval($matches[2][$opt_key])];
                            if (is_array($answer)) {
                                $options[$opt_value]['selected'] = in_array($opt_value, $answer) ? true : false;
                            } else {
                                $options[$opt_value]['selected'] = $opt_value === $answer ? true : false;
                            }
                            if ($options[$opt_value]['selected']) {
                                $get_score += ($options[$opt_value]['score'] * ($question['gain'] > 0 ? $question['gain'] : 1));
                                $get_score = $question['score_limit'] > 0 ? ($get_score > $question['score_limit'] ? $question['score_limit'] : $get_score) : $get_score;
                            }
                        }
                        if ($get_score > 0) {
                            if ($question['score_type'] === 'basic') {
                                $questionnaire['questions'][$key]['basic_score'] += $get_score;
                                $questionnaire['basic_total_score'] += $get_score;
                            } elseif ($question['score_type'] === 'advanced') {
                                $questionnaire['questions'][$key]['advance_score'] += $get_score;
                                $questionnaire['advance_total_score'] += $get_score;
                            }
                        }
                    }
                    $questionnaire['questions'][$key]['options'] = $options;
                } elseif (($question['type'] === 'text' || $question['type'] === 'textarea') && floatval($question['options']) > 0 && strlen($answer) > 0) {
                    $get_score = floatval($question['options']);

                    if ($get_score > 0) {
                        if ($question['score_type'] === 'basic') {
                            $questionnaire['questions'][$key]['basic_score'] += $get_score;
                            $questionnaire['basic_total_score'] += $get_score;
                        } elseif ($question['score_type'] === 'advanced') {
                            $questionnaire['questions'][$key]['advance_score'] += $get_score;
                            $questionnaire['advance_total_score'] += $get_score;
                        }
                    }
                }
            }
        }
        unset($questionnaire['pivot']['answers']);
        unset($questionnaire['pivot']['comments']);
        return $questionnaire;
    }

    public function updateStatus(Request $request)
    {
        $questionnaire = Questionnaire::find($request->input('questionnaire_id'));
        DB::table('questionnaire_user')->where('id', $request->input('questionnaire_user_id'))
            ->update([
                'status' => $request->input('status'),
            ]);
    }

    /**
     * @param QuestionnaireExportService $questionnaireExportService
     * @param $account_id
     * @param $questionnaire_id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(QuestionnaireExportService $questionnaireExportService, $account_id, $questionnaire_id)
    {
        ini_set('max_execution_time', 1800);

        /** @var User $account */
        $account = User::find($account_id);
        /** @var Questionnaire $questionnaire */
        //$questionnaire = $account->questionnaires()->where('questionnaires.id', $questionnaire_id)->first();
        $questionnaire = QuestionnaireController::getQuestionnaire($account, $questionnaire_id);

        $zipFile = $questionnaireExportService->export($account, $questionnaire);

        return response()->download($zipFile);
    }

    public function batchExportForm()
    {
        /** @var User $user */
        $user = Auth::user();
        //根據權限過濾user
        $accountQuery = User::with('county', 'questionnaires.questions')->has('questionnaires')
            ->where('type', 'county');
        $questionnaireQuery = Questionnaire::query();
        if ($user->type == 'county') {
            $accountQuery->where('id', $user->id);
        } elseif ($user->type == 'district') {
            Flash::error('限縣市帳號使用');

            return redirect()->back();
        }
        $accountQueryCloned = clone $accountQuery;
        $accountOptions = $accountQueryCloned->get()->pluck('full_county_name', 'id')->toArray();
        $questionnaireOptions = Questionnaire::pluck('title', 'id')->toArray();

        //過濾
        /** @var User $searchAccount */
        if ($searchAccount = User::find(\request('account_id'))) {
            $accountQuery->where('id', $searchAccount->id);
        }
        /** @var Questionnaire $searchQuestionnaire */
        if ($searchQuestionnaire = Questionnaire::find(\request('questionnaire_id'))) {
            $questionnaireQuery->where('id', $searchQuestionnaire->id);
        }

        /** @var Collection|User[] $accounts */
        $accounts = $accountQuery->get();
        $questionnaires = $questionnaireQuery->get();

        return view('admin.questionnaires.batch-export', compact(
            'accountOptions',
            'questionnaireOptions',
            'accounts',
            'questionnaires'
        ));
    }

    /**
     * @param QuestionnaireExportService $questionnaireExportService
     * @param $account_id
     * @param $questionnaire_id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function batchExport(QuestionnaireExportService $questionnaireExportService, $account_id, $questionnaire_id)
    {
        ini_set('max_execution_time', 1800);

        /** @var User $account */
        $account = User::find($account_id);
        /** @var Questionnaire $questionnaire */
        $questionnaire = Questionnaire::where('questionnaires.id', $questionnaire_id)->first();

        $zipFile = $questionnaireExportService->batchExport($account, $questionnaire);

        return response()->download($zipFile);
    }

    protected static function countScore($question, $rawAnswer)
    {
        $score = 0;
        if ($rawAnswer === '') {
            return 0;
        }

        $rawAnswer = self::fixOptStr($rawAnswer);

        switch ($question->type) {
            case 'radio':
                $question->options = self::fixOptStr($question->options);
                $exploded = explode(']', $question->options);
                $options = [];
                foreach ($exploded as $key => $item) {
                    if (str_replace(' ', '', $item) === '') {
                        continue;
                    }

                    $info = explode('[', $item);
                    $key = self::fixOptStr($info[0]);
                    $options[$key] = $info[1];
                }
                $score = 0;

                if (!isset($options[$rawAnswer])) {
                    break;
                }

                $score = floatval($options[$rawAnswer]);
                break;
            case 'title':
                if (strlen($rawAnswer) > 0) {
                    $score = empty($question->options) ? 0 : (is_numeric($question->options) ? floatval($question->options) : 0);
                }
                break;
            case 'text':
                if (strlen($rawAnswer) > 0) {
                    $score = empty($question->options) ? 0 : (is_numeric($question->options) ? floatval($question->options) : 0);
                }
                break;
            case 'textarea':
                if (strlen($rawAnswer) > 0) {
                    $score = empty($question->options) ? 0 : (is_numeric($question->options) ? floatval($question->options) : 0);
                }
                break;

            case 'checkbox':
                $exploded = explode(']', $question->options);
                $options = [];
                foreach ($exploded as $key => $item) {
                    if (str_replace(' ', '', $item) === '') {
                        continue;
                    }

                    $info = explode('[', $item);
                    $idx = self::fixOptStr($info[0]);
                    $options[$idx] = $info[1];
                }
                $score = 0;

                foreach ($rawAnswer as $key => $answer) {
                    $answer = self::fixOptStr($answer);
                    if (!isset($options[$answer])) {
                        continue;
                    }

                    $score += floatval($options[$answer]);
                }
                break;

            default:
                $score = 0;
        }

        // 加成 & 權重
        $score *= empty($question->gain) ? 1 : $question->gain;
        if ($question->extra_gain > 0) {
            $score *= $question->extra_gain;
        }

        // 分數上限
        if ($question->score_limit != 0 && $score > $question->score_limit) {
            $score = $question->score_limit;
        }

        return $score;
    }

    public static function fixOptStr($str)
    {
        return str_replace(["\n", "\r"], '', $str);
    }

    public function statistic()
    {
        /** @var User $user */
        $user = Auth::user();
        //根據權限過濾user
        $accountQuery = User::with('county', 'questionnaires.questions')->has('questionnaires');
        if ($user->type == 'county') {
            $accountQuery->where('id', $user->id)->orWhere('county_id', $user->id);
        } elseif ($user->type == 'district') {
            $accountQuery->where('id', $user->id);
        }
        $accountQueryCloned = clone $accountQuery;
        $accountOptions = $accountQueryCloned->get()->pluck('full_county_name', 'id')->toArray();
        $questionnaireOptions = Questionnaire::pluck('title', 'id')->toArray();

        //過濾
        /** @var User $searchAccount */
        if ($searchAccount = User::find(\request('account_id'))) {
            $accountQuery->where('id', $searchAccount->id)->orWhere('county_id', $searchAccount->id);
        }
        /** @var Questionnaire $searchQuestionnaire */
        if ($searchQuestionnaire = Questionnaire::find(\request('questionnaire_id'))) {
            $searchClosure = function ($questionnaire) use ($searchQuestionnaire) {
                /** @var Questionnaire $questionnaire */
                $questionnaire->where('questionnaires.id', $searchQuestionnaire->id);
            };
            $accountQuery->whereHas('questionnaires', $searchClosure)->with(['questionnaires' => $searchClosure]);
        }

        /** @var Collection|User[] $accounts */
        $accounts = $accountQuery->get();

        $data = $this->countStatisticData($accounts);

        return view('admin.questionnaires.statistic', compact('data', 'accountOptions', 'questionnaireOptions'));
    }

    /**
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function statisticExport()
    {
        /** @var User $user */
        $user = Auth::user();
        //根據權限過濾user
        $accountQuery = User::with('county', 'questionnaires.questions')->has('questionnaires');
        if ($user->type == 'county') {
            $accountQuery->where('id', $user->id)->orWhere('county_id', $user->id);
        } elseif ($user->type == 'district') {
            $accountQuery->where('id', $user->id);
        }

        //過濾
        /** @var User $searchAccount */
        if ($searchAccount = User::find(\request('account_id'))) {
            $accountQuery->where('id', $searchAccount->id)->orWhere('county_id', $searchAccount->id);
        }
        /** @var Questionnaire $searchQuestionnaire */
        if ($searchQuestionnaire = Questionnaire::find(\request('questionnaire_id'))) {
            $searchClosure = function ($questionnaire) use ($searchQuestionnaire) {
                /** @var Questionnaire $questionnaire */
                $questionnaire->where('questionnaires.id', $searchQuestionnaire->id);
            };
            $accountQuery->whereHas('questionnaires', $searchClosure)->with(['questionnaires' => $searchClosure]);
        }

        /** @var Collection|User[] $accounts */
        $accounts = $accountQuery->get();

        $data = $this->countStatisticData($accounts);

        $filename = '檢視績效評估自評表';
        if ($searchAccount) {
            $filename .= ' - ' . $searchAccount->full_county_name;
        }

        return Excel::download(new QuestionnaireStatisticExport($data), $filename . '.xlsx');
    }

    /**
     * @param Collection|User[] $accounts
     * @return array
     */
    private function countStatisticData($accounts)
    {
        $data = [];
        foreach ($accounts as $account) {
            $questionnaires = [];
            foreach ($account->questionnaires as $questionnaire) {
                $answers = json_decode($questionnaire->pivot->answers, true);
                $basicScoreSum = 0;
                $advancedScoreSum = 0;
                //                $scoreSum = 0;
                foreach ($questionnaire->questions as $question) {
                    $rawAnswer = isset($answers[$question->id]) ? $answers[$question->id] : '';
                    $score = self::countScore($question, $rawAnswer);
                    $scoreType = $question->score_type;
                    if ($scoreType == 'basic') {
                        $basicScoreSum += $score;
                    } elseif ($scoreType == 'advanced') {
                        $advancedScoreSum += $score;
                    }
                    //                    $scoreSum += $score;
                }
                $scoreSum = $basicScoreSum + $advancedScoreSum;
                $questionnaires[] = [
                    'id'             => $questionnaire->id,
                    'title'          => $questionnaire->title,
                    'basic_score'    => $basicScoreSum,
                    'advanced_score' => $advancedScoreSum,
                    'score'          => $scoreSum,
                    'weighted_score' => round($basicScoreSum * $questionnaire->basic_weight + $advancedScoreSum * $questionnaire->advanced_weight, 2),
                ];
            }

            $data[] = [
                'author_id'      => $account->id,
                'account'        => $account->full_county_name,
                'questionnaires' => $questionnaires,
            ];
        }

        return $data;
    }
}
