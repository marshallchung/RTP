<?php

namespace App\Providers;

use App\DcUnit;
use App\DcUser;
use App\DpStudent;
use App\DpTeacher;
use App\HomePageCarouselImage;
use App\Introduction;
use App\News;
use App\PublicNews;
use App\Upload;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\SlackHandler;
use Monolog\Level;
use App\Observers\ActivityObserver;
use App\Observers\DpStudentObserver;
use App\Observers\HomePageCarouselImageObserver;
use App\Observers\IntroductionObserver;
use App\Observers\NewsObserver;
use App\Observers\PublicNewsObserver;
use App\Observers\UploadObserver;
use Spatie\Activitylog\Models\Activity;
use App\Nfa\Repositories\HomePageCarouselImageRepositoryInterface;
use App\Nfa\Repositories\HomePageCarouselImageRepository;
use App\Nfa\Repositories\NewsRepository;
use App\Nfa\Repositories\UploadRepository;
use App\Nfa\Repositories\NewsRepositoryInterface;
use App\Nfa\Repositories\UploadRepositoryInterface;
use App\Observers\DcUserObserver;
use App\Observers\DpTeacherObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HomePageCarouselImageRepositoryInterface::class, HomePageCarouselImageRepository::class);
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(UploadRepositoryInterface::class, UploadRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        //Carbon語系
        Carbon::setLocale(env('APP_LOCALE', 'en'));

        //Slack通知
        $slackEnable = env('SLACK_ENABLE', false) === true;
        $slackToken = env('SLACK_TOKEN');
        $slackChannel = env('SLACK_CHANNEL');
        if ($slackEnable && $slackToken && $slackChannel) {
            $monolog = Log::getMonolog();
            $slackHandler = new SlackHandler(
                $slackToken,
                $slackChannel,
                'Monolog',
                true,
                null,
                Level::Warning
            );
            $monolog->pushHandler($slackHandler);
        }

        Paginator::defaultView('vendor.pagination.tailwind');
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');

        // Observers
        Activity::observe(ActivityObserver::class);
        News::observe(NewsObserver::class);
        Upload::observe(UploadObserver::class);
        Introduction::observe(IntroductionObserver::class);
        PublicNews::observe(PublicNewsObserver::class);
        DpStudent::observe(DpStudentObserver::class);
        DpTeacher::observe(DpTeacherObserver::class);
        DcUser::observe(DcUserObserver::class);
        HomePageCarouselImage::observe(HomePageCarouselImageObserver::class);
    }
}
