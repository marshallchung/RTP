<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NfaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Nfa\Repositories\VideoRepositoryInterface', 'App\Nfa\Repositories\VideoRepository');
        $this->app->bind('App\Nfa\Repositories\FileRepositoryInterface', 'App\Nfa\Repositories\FileRepository');
        $this->app->bind('App\Nfa\Repositories\NewsRepositoryInterface', 'App\Nfa\Repositories\NewsRepository');
        $this->app->bind('App\Nfa\Repositories\PublicNewsRepositoryInterface', 'App\Nfa\Repositories\PublicNewsRepository');
        $this->app->bind('App\Nfa\Repositories\FrontDownloadRepositoryInterface', 'App\Nfa\Repositories\FrontDownloadRepository');
        $this->app->bind('App\Nfa\Repositories\DcDownloadRepositoryInterface', 'App\Nfa\Repositories\DcDownloadRepository');
        $this->app->bind('App\Nfa\Repositories\DcCertificationReviewRepositoryInterface', 'App\Nfa\Repositories\DcCertificationReviewRepository');
        $this->app->bind('App\Nfa\Repositories\DpDownloadRepositoryInterface', 'App\Nfa\Repositories\DpDownloadRepository');
        $this->app->bind('App\Nfa\Repositories\QaRepositoryInterface', 'App\Nfa\Repositories\QaRepository');
        $this->app->bind('App\Nfa\Repositories\GuidanceRepositoryInterface', 'App\Nfa\Repositories\GuidanceRepository');
        $this->app->bind('App\Nfa\Repositories\DpTeacherRepositoryInterface', 'App\Nfa\Repositories\DpTeacherRepository');
        $this->app->bind('App\Nfa\Repositories\DpCourseRepositoryInterface', 'App\Nfa\Repositories\DpCourseRepository');
        $this->app->bind('App\Nfa\Repositories\DpStudentRepositoryInterface', 'App\Nfa\Repositories\DpStudentRepository');
        $this->app->bind('App\Nfa\Repositories\DpAdvancedStudentRepositoryInterface', 'App\Nfa\Repositories\DpAdvancedStudentRepository');
        $this->app->bind('App\Nfa\Repositories\DpScoreRepositoryInterface', 'App\Nfa\Repositories\DpScoreRepository');
        $this->app->bind('App\Nfa\Repositories\DpWaiverRepositoryInterface', 'App\Nfa\Repositories\DpWaiverRepository');
        $this->app->bind('App\Nfa\Repositories\DpWaiverReviewRepositoryInterface', 'App\Nfa\Repositories\DpWaiverReviewRepository');
        $this->app->bind('App\Nfa\Repositories\DpExperienceRepositoryInterface', 'App\Nfa\Repositories\DpExperienceRepository');
        $this->app->bind('App\Nfa\Repositories\DpResourceRepositoryInterface', 'App\Nfa\Repositories\DpResourceRepository');
        $this->app->bind('App\Nfa\Repositories\DpTrainingInstitutionRepositoryInterface', 'App\Nfa\Repositories\DpTrainingInstitutionRepository');
        $this->app->bind('App\Nfa\Repositories\DpCivilRepositoryInterface', 'App\Nfa\Repositories\DpCivilRepository');
        $this->app->bind('App\Nfa\Repositories\DcScheduleRepositoryInterface', 'App\Nfa\Repositories\DcScheduleRepository');
        $this->app->bind('App\Nfa\Repositories\DcUnitRepositoryInterface', 'App\Nfa\Repositories\DcUnitRepository');
        $this->app->bind('App\Nfa\Repositories\DcStageRepositoryInterface', 'App\Nfa\Repositories\DcStageRepository');
        $this->app->bind('App\Nfa\Repositories\ReferenceRepositoryInterface', 'App\Nfa\Repositories\ReferenceRepository');
        $this->app->bind('App\Nfa\Repositories\PlanRepositoryInterface', 'App\Nfa\Repositories\PlanRepository');
        $this->app->bind('App\Nfa\Repositories\PresentationRepositoryInterface', 'App\Nfa\Repositories\PresentationRepository');
        $this->app->bind('App\Nfa\Repositories\ReportRepositoryInterface', 'App\Nfa\Repositories\ReportRepository');
        $this->app->bind('App\Nfa\Repositories\SampleReportRepositoryInterface', 'App\Nfa\Repositories\SampleReportRepository');
        $this->app->bind('App\Nfa\Repositories\ResultIIIRepositoryInterface', 'App\Nfa\Repositories\ResultIIIRepository');
        $this->app->bind('App\Nfa\Repositories\SeasonalReportRepositoryInterface', 'App\Nfa\Repositories\SeasonalReportRepository');
        $this->app->bind('App\Nfa\Repositories\UploadRepositoryInterface', 'App\Nfa\Repositories\UploadRepository');
        $this->app->bind('App\Nfa\Repositories\UserRepositoryInterface', 'App\Nfa\Repositories\UserRepository');
        $this->app->bind('App\Nfa\Repositories\IntroductionRepositoryInterface', 'App\Nfa\Repositories\IntroductionRepository');
        $this->app->bind('App\Nfa\Repositories\ImageDatumRepositoryInterface', 'App\Nfa\Repositories\ImageDatumRepository');
        $this->app->bind('App\Nfa\Repositories\SignLocationRepositoryInterface', 'App\Nfa\Repositories\SignLocationRepository');
        $this->app->bind('App\Nfa\Repositories\QuestionRepositoryInterface', 'App\Nfa\Repositories\QuestionRepository');
        $this->app->bind('App\Nfa\Repositories\HomePageCarouselImageRepositoryInterface', 'App\Nfa\Repositories\HomePageCarouselImageRepository');
    }
}
