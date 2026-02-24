<?php

namespace App\Http\Middleware;

use App\Nfa\Repositories\ReportRepositoryInterface;
use App\Nfa\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Closure;
use DateTimeImmutable;
use Flash;
use Illuminate\Http\Request;

class RedirectIfReportsNotPublic
{
    /**
     * @var ReportRepositoryInterface
     */
    protected $reportRepo;
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepo;

    public function __construct(ReportRepositoryInterface $reportRepo, UserRepositoryInterface $userRepo)
    {
        $this->reportRepo = $reportRepo;

        $this->userRepo = $userRepo;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isReportOwner = $this->isReportOwner($request);
        $isAfterPublicDate = $this->isAfterPublicDate($request);
        $hasPermissions = $this->userHasPermissions($request);
        $isParentOfDistrct = $this->isParentOfDistrct($request);

        if ($isReportOwner || $hasPermissions || $isAfterPublicDate === true || $isParentOfDistrct) {
            return $next($request);
        } else {
            $date = new DateTimeImmutable($isAfterPublicDate);
            Flash::error(trans('app.report.notPublic', ['date' => $date->format('Y年m月d日h:i')]));

            return redirect()->back();
        }
    }

    /**
     * Does the requested report belong to the logged in user?
     * @param Request $request
     * @return bool
     */
    private function isReportOwner($request)
    {
        $reportId = $request->route('id');

        return $reportId == $request->user()->id;
    }

    /**
     * Are we after the "public date" for the requested year?
     * @param Request $request
     * @return bool|null
     */
    private function isAfterPublicDate($request)
    {
        $year = $request->route('year');

        $publicDate = $this->reportRepo->getPublicDateByYear($year);

        $isAfterPublicDate = $publicDate === null ?: Carbon::now()->gte($publicDate);

        return ($publicDate === null || $isAfterPublicDate) ? true : $publicDate;
    }

    /**
     * Does the user have elevated permissions to view all reports?
     * @param Request $request
     * @return mixed
     */
    private function userHasPermissions($request)
    {
        return $request->user()->hasPermission('view-all-reports');
    }

    /**
     * If the logged in user is a county, does the requested report
     * belong to one of its districts?
     * @param Request $request
     * @return bool
     */
    private function isParentOfDistrct($request)
    {
        $user = $request->user();
        $reportId = $request->route('id');

        $reportUser = $this->userRepo->findById($reportId);

        if ($user->type !== 'county') {
            return false;
        }

        return $reportUser && $reportUser->county_id === $user->id;
    }
}
