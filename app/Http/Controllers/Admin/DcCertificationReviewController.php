<?php

namespace App\Http\Controllers\Admin;

use App\DcCertification;
use App\Nfa\Repositories\DcCertificationReviewRepositoryInterface;
use App\User;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;

class DcCertificationReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param DcCertificationReviewRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DcCertificationReviewRepositoryInterface $repo)
    {
        $counties = $this->getCounties();
        $termOptions = [null => ' - 工作項目 - '] + config('dc.certification.items');
        $reviewResultOptions = [
            null     => ' - 審查結果 - ',
            'none'   => '未審查',
            'pass'   => '通過',
            'failed' => '不通過',
        ];

        $data = $repo->getFilteredData();

        return view(
            'admin.dc-certifications-review.index',
            compact('data', 'counties', 'termOptions', 'reviewResultOptions')
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
        /** @var DcCertification $DcCertification */
        $DcCertification = DcCertification::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /** @var DcCertification $data */
        $data = DcCertification::find($id);

        return view('admin.dc-certifications-review.edit', compact('data'));
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

        /** @var DcCertification $DcCertification */
        $DcCertification = DcCertification::find($id);

        $DcCertification->update(array_merge($request->only(['review_result', 'review_comment']), [
            'review_at' => Carbon::now(),
        ]));
        if ($request->has('is_json')) {
            return response()->json(['ok' => 1]);
        } else {

            Flash::success(trans('app.reviewSuccess', ['type' => '參與標章申請表申請']));

            return redirect()->route('admin.dc-certifications-review.index');
        }
    }

    public function downloadFiles(Request $request, $id)
    {
        /** @var DcCertification $dcCertification */
        $dcCertification = DcCertification::find($id);

        $zipFilePath = tempnam(sys_get_temp_dir(), 'dc');

        $tmpDir = $this->tempdir();

        //FIXME: 暫時防止在 PHP 7.4 出現 Unparenthesized `a ? b : c ? d : e` is deprecated. Use either `(a ? b : c) ? d : e` or `a ? b : (c ? d : e)`
        error_reporting(E_ALL ^ E_DEPRECATED);
        foreach ($dcCertification->files as $file) {
            $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $file->path);
            if (file_exists($filePath)) {
                copy($filePath, $tmpDir . DIRECTORY_SEPARATOR . $file->name);
            }
        }
        $files = glob($tmpDir . DIRECTORY_SEPARATOR . '*');
        if (count($files) <= 0) {
            flash('無法打包下載檔案，請嘗試逐一下載', 'error');

            return redirect()->back();
        }
        $zip = \Zip::create($zipFilePath);
        $zip->add($files);
        $zip->close();

        $filename = sprintf(
            '%s_%s_%s.zip',
            $dcCertification->dcUnit->county->name,
            $dcCertification->dcUnit->name,
            config('dc.certification.items')[$dcCertification->term]
        );

        return response()->download($zipFilePath, $filename);
    }

    private function getCounties()
    {
        $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();

        return [null => '縣市'] + $countyIdNames;
    }

    /**
     * https://stackoverflow.com/a/30010928
     * Creates a random unique temporary directory, with specified parameters,
     * that does not already exist (like tempnam(), but for dirs).
     *
     * Created dir will begin with the specified prefix, followed by random
     * numbers.
     *
     * @link https://php.net/manual/en/function.tempnam.php
     *
     * @param string|null $dir Base directory under which to create temp dir.
     *     If null, the default system temp dir (sys_get_temp_dir()) will be
     *     used.
     * @param string $prefix String with which to prefix created dirs.
     * @param int $mode Octal file permission mask for the newly-created dir.
     *     Should begin with a 0.
     * @param int $maxAttempts Maximum attempts before giving up (to prevent
     *     endless loops).
     * @return string|bool Full path to newly-created dir, or false on failure.
     */
    private function tempdir($dir = null, $prefix = 'tmp_', $mode = 0700, $maxAttempts = 1000)
    {
        /* Use the system temp dir by default. */
        if (is_null($dir)) {
            $dir = sys_get_temp_dir();
        }

        /* Trim trailing slashes from $dir. */
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);

        /* If we don't have permission to create a directory, fail, otherwise we will
         * be stuck in an endless loop.
         */
        if (!is_dir($dir) || !is_writable($dir)) {
            return false;
        }

        /* Make sure characters in prefix are safe. */
        if (strpbrk($prefix, '\\/:*?"<>|') !== false) {
            return false;
        }

        /* Attempt to create a random directory until it works. Abort if we reach
         * $maxAttempts. Something screwy could be happening with the filesystem
         * and our loop could otherwise become endless.
         */
        $attempts = 0;
        do {
            $path = sprintf('%s%s%s%s', $dir, DIRECTORY_SEPARATOR, $prefix, mt_rand(100000, mt_getrandmax()));
        } while (
            !mkdir($path, $mode) &&
            $attempts++ < $maxAttempts
        );

        return $path;
    }
}
