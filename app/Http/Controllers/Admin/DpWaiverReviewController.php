<?php

namespace App\Http\Controllers\Admin;

use App\DpCourse;
use App\DpWaiver;
use App\Nfa\Repositories\DpWaiverReviewRepositoryInterface;
use App\User;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class DpWaiverReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param DpWaiverReviewRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DpWaiverReviewRepositoryInterface $repo)
    {
        $courseOptions = [null => ' - 欲抵免防災士課程內容 - '] + DpCourse::get()->pluck('name', 'id')->toArray();
        $authorOptions = [null => ' - 辦理單位 - ']
            + User::has('dpScores', '>', 0)->get()->pluck('name', 'id')->toArray();
        $reviewResultOptions = [
            null     => ' - 審查結果 - ',
            'none'   => '未審查',
            'pass'   => '通過',
            'failed' => '不通過',
        ];

        $data = $repo->getFilteredData();

        return view(
            'admin.dp-waivers-review.index',
            compact('data', 'courseOptions', 'authorOptions', 'reviewResultOptions')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /** @var DpWaiver $dpWaiver */
        $dpWaiver = DpWaiver::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /** @var DpWaiver $data */
        $data = DpWaiver::find($id);

        return view('admin.dp-waivers-review.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'review_result' => 'required',
        ]);

        /** @var DpWaiver $dpWaiver */
        $dpWaiver = DpWaiver::find($id);

        $dpWaiver->update(array_merge($request->only(['review_result', 'review_comment']), [
            'review_at' => Carbon::now(),
        ]));
        $content = view('admin.email.dp-waiver-review-notification', [
            'name' => $dpWaiver->dpScore->dpStudent->name,
            'pass' => $dpWaiver->review_result,
        ])->render();
        Mail::html($content, function ($message) use ($dpWaiver) {
            $message->from('pdmcb@nfa.gov.tw', 'PDMCB')
                ->to($dpWaiver->dpScore->dpStudent->email, $dpWaiver->dpScore->dpStudent->name)
                ->subject('課程抵免審查結果');
        });

        Flash::success(trans('app.reviewSuccess', ['type' => '課程抵免申請']));

        return redirect()->route('admin.dp-waivers-review.index');
    }
}
