<?php
/*
    Permissions：
    admin-permissions：系統管理員權限
    NFA-permissions：消防署權限
    County-permissions：縣市權限
    DEP-permissions：社團法人臺灣防災教育訓練學會權限
    DP-Training-permissions：防災士培訓機構權限
*/

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Nfa\IntroductionController;
use App\Http\Controllers\Admin\DpScoreController;
use App\Http\Controllers\Admin\DpStudentController;
use App\Http\Controllers\Admin\DpAdvancedStudentController;
use App\Http\Controllers\Admin\DpExperienceController;
use App\Http\Controllers\Admin\DpResourceController;
use App\Http\Controllers\Admin\Nfa\CommitteeController;
use App\Http\Controllers\Admin\DpCourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DcCertificationController;
use App\Http\Controllers\Admin\Nfa\DashboardController;
use App\Http\Controllers\Admin\DpWaiverReviewController;
use App\Http\Controllers\Admin\DcScheduleController;
use App\Http\Controllers\Admin\DpCivilController;
use App\Http\Controllers\Admin\FrontIntroductionController;
use App\Http\Controllers\Admin\ReferenceController;
use App\Http\Controllers\Admin\Nfa\PublicNewsController;
use App\Http\Controllers\Admin\Nfa\NewsController;
use App\Http\Controllers\Admin\Nfa\ResetController;
use App\Http\Controllers\Admin\Nfa\UploadController;
use App\Http\Controllers\Admin\Nfa\ReportController;
use App\Http\Controllers\Admin\Nfa\ReportFixController;
use App\Http\Controllers\Admin\Nfa\ResultIIIController;
use App\Http\Controllers\Admin\Nfa\SampleReportController;
use App\Http\Controllers\Admin\Nfa\SeasonalReportController;
use App\Http\Controllers\Admin\Nfa\PresentationController;
use App\Http\Controllers\Admin\Nfa\PlanController;
use App\Http\Controllers\Admin\Nfa\CentralReportController;
use App\Http\Controllers\Admin\Nfa\ShowingController;
use App\Http\Controllers\Admin\Nfa\DownloadController;
use App\Http\Controllers\Admin\Nfa\IdentityController;
use App\Http\Controllers\Admin\Nfa\AddressController;
use App\Http\Controllers\Admin\Nfa\ImageDataController;
use App\Http\Controllers\Admin\Nfa\SignLocationController;
use App\Http\Controllers\Admin\DpTeacherController;
use App\Http\Controllers\Admin\DpWaiverController;
use App\Http\Controllers\Admin\QaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DcUnitController;
use App\Http\Controllers\Admin\DcCertificationReviewController;
use App\Http\Controllers\Admin\DcStageController;
use App\Http\Controllers\Admin\DcStageListController;
use App\Http\Controllers\Admin\DpTrainingInstitutionController;
use App\Http\Controllers\Admin\Nfa\HomePageCarouselImageController;
use App\Http\Controllers\Admin\Nfa\PasswordController;
use App\Http\Controllers\Admin\Nfa\VideoController;
use App\Http\Controllers\Admin\QuestionnaireController;
use App\Http\Controllers\Admin\StaticPageController;
use App\User;
use Illuminate\Support\Facades\DB;


Route::view('samples', 'admin.samples');
/**
 * Auth 認證部分
 */
Route::get('login', [LoginController::class, 'showLoginForm'])->name('auth.login');
Route::post('login', [LoginController::class, 'login'])->name('auth.auth');
Route::get('2fa', [Login2FAController::class, 'show2faForm'])->name('auth.2fa.form');
Route::post('2fa', [Login2FAController::class, 'verify2fa'])->name('auth.2fa.verify');
//FIXME: 可能要限 POST
Route::any('logout', [LoginController::class, 'logout'])->name('auth.logout');

Route::middleware('authAdmin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/export', [DashboardController::class, 'export'])->name('dashboard.export');


    Route::as('users.')->group(function () {
        Route::resource('users/password', PasswordController::class)->only(['index', 'store']);
    });

    /**
     * Admin routes (右上帳號管理區塊)
     */

    //帳號管理
    Route::prefix('users')->middleware('permission:reset-password')->group(function () {
        Route::as('users.')->group(function () {
            Route::resource('reset', ResetController::class)->only(['index', 'update']);
        });
        //新增帳號
        Route::get('createUser', [ResetController::class, 'createUser'])->name('users.create-user');
        Route::post('createUser', [ResetController::class, 'postCreateUser'])->name('users.create-user');
        Route::get('createAliasUser/{user}', [ResetController::class, 'createAliasUser'])->name('users.create-alias-user');
        Route::post('createAliasUser/{user}', [ResetController::class, 'postCreateAliasUser'])->name('users.create-alias-user');
        Route::delete('deleteAliasUser/{user_alias}', [ResetController::class, 'postDeleteAliasUser'])->name('users.delete-alias-user');
    });
    Route::get('users/getDistricts', [ResetController::class, 'getDistricts'])->name('users.getDistricts');

    Route::middleware('permission:create-news')->group(function () {
        Route::resource('news', NewsController::class)->except(['show', 'index', 'edit']);
    });
    Route::resource('news', NewsController::class)->only(['index', 'edit', 'show']);

    //近期重點工作
    Route::middleware('permission:create-public-news')->group(function () {
        Route::resource('public-news', PublicNewsController::class)->except(['show', 'index', 'edit']);
    });
    Route::resource('public-news', PublicNewsController::class)->only(['index', 'edit', 'show']);


    Route::get('uploads/view', [UploadController::class, 'view'])->name('uploads.view');
    Route::middleware('permission:create-uploads')->group(function () {
        Route::resource('uploads', UploadController::class)->except(['show', 'index', 'edit']);
    });
    Route::resource('uploads', UploadController::class)->only(['index', 'edit', 'show']);

    /**
     *三期計畫執行成果 & 參考資料
     */
    Route::prefix('reports')->middleware('permission:create-report-public-date|create-reports')->group(function () {
        Route::get('dates', [ReportController::class, 'getPublicDates'])->name('reports.date.index'); //資料公開時間列表index
        Route::post('dates/{id}', [ReportController::class, 'updatePublicDate'])->name('reports.date.update'); //資料公開時間更新update
        Route::get('inquire/{year?}', [ReportController::class, 'inquire'])->name('reports.inquire')->where(['year' => '[0-9]{4}']);   //管理與查詢功能(分類及縣市查詢)
        Route::get('fix', [ReportFixController::class, 'index'])->name('reports.fix'); // FIXME: 維修用
        Route::post('fix', [ReportFixController::class, 'post'])->name('reports.fix'); // FIXME: 維修用
        Route::get('distribute', [ReportController::class, 'getDistribute'])->name('reports.distribute.index'); //編輯縣市政府歷年統計民眾版靜態頁面
        Route::post('distribute', [ReportController::class, 'updateDistribute'])->name('reports.distribute.update'); //儲存縣市政府歷年統計民眾版靜態頁面
        Route::delete('distribute', [ReportController::class, 'deleteDistribute'])->name('reports.distribute.delete'); //刪除縣市政府歷年統計民眾版靜態頁面

        Route::get('downloadXlsx', [ReportController::class, 'downloadXlsx'])->name('reports.downloadXlsx');   //管理與查詢功能(分類及縣市查詢)

        //管考作業
        Route::get('evaluationCommission/{year?}', [ReportController::class, 'evaluationCommission'])
            ->name('reports.evaluationCommission.index')->where(['year' => '[0-9]{4}']);
        Route::get('evaluationCommission/{id}/{year?}', [ReportController::class, 'evaluationCommissionShow'])
            ->name('reports.evaluationCommission.show')->where(['year' => '[0-9]{4}']);
        Route::get('revaluationCommission/export/{id}/{year?}', [ReportController::class, 'evaluationCommissionExport'])
            ->name('reports.evaluationCommission.export')->where(['year' => '[0-9]{4}']);
        Route::get('submit/{year}', [ReportController::class, 'submit'])->name('reports.submit')->where(['year' => '[0-9]{4}']);
        Route::get('submit/{id}', [ReportController::class, 'create'])->name('reports.create');
        Route::post('submit', [ReportController::class, 'store'])->name('reports.store');
        Route::delete('delete-file-in-submit-page/{file}', [ReportController::class, 'deleteFileInSubmitPage'])->name('reports.delete-file-in-submit-page');
        Route::get('inquireByCounty/{year?}', [ReportController::class, 'inquireByCounty'])->name('reports.inquireByCounty')
            ->where(['year' => '[0-9]{4}']);   //2016 NEW 分類及縣市查詢!
        Route::get('downloadXlsxByCounty', [ReportController::class, 'downloadXlsxByCounty'])
            ->name('reports.downloadXlsxByCounty');   //2016 NEW 分類及縣市查詢!
        Route::get('downloadFilesByCounty', [ReportController::class, 'downloadFilesByCounty'])
            ->name('reports.downloadFilesByCounty');   //2016 NEW 分類及縣市查詢!
    });

    //以年分展示執行成果,網址用西元年, 顯示用民國年
    Route::get('reports/{year?}', [ReportController::class, 'index'])->name('reports.index')->where(['year' => '[0-9]{4}']);

    /**
     * 成果資料（三期）
     */
    Route::prefix('resultiii')->middleware('permission:create-report-public-date')->group(function () {
        Route::get('inquire/{year?}', [ResultIIIController::class, 'inquire'])->name('resultiii.inquire')->where(['year' => '[0-9]{4}']);   //管理與查詢功能(分類及縣市查詢)
        Route::get('downloadXlsx', [ResultIIIController::class, 'downloadXlsx'])->name('resultiii.downloadXlsx');   //管理與查詢功能(分類及縣市查詢)
    });

    /**
     * 成果資料（三期）
     */
    Route::prefix('resultiii')->middleware('permission:create-reports')->group(function () {
        Route::get('submit', [ResultIIIController::class, 'submit'])->name('resultiii.submit')->where(['year' => '[0-9]{4}']);
        Route::get('submit/{id}', [ResultIIIController::class, 'create'])->name('resultiii.create');
        Route::post('submit', [ResultIIIController::class, 'store'])->name('resultiii.store');
        Route::delete('delete-file-in-submit-page/{file}', [ResultIIIController::class, 'deleteFileInSubmitPage'])->name('resultiii.delete-file-in-submit-page');
        Route::get('inquireByCounty', [ResultIIIController::class, 'inquireByCounty'])->name('resultiii.inquireByCounty');   //2016 NEW 分類及縣市查詢!
        Route::get('downloadXlsxByCounty', [ResultIIIController::class, 'downloadXlsxByCounty'])
            ->name('resultiii.downloadXlsxByCounty');   //2016 NEW 分類及縣市查詢!
    });

    //以年分展示執行成果,網址用西元年, 顯示用民國年
    Route::get('resultiii', [ResultIIIController::class, 'index'])->name('resultiii.index');
    Route::get('resultiii/{id}', [ResultIIIController::class, 'show'])->name('resultiii.show');

    Route::get('reports/sample', [ReportController::class, 'sample'])->name('reports.sample');
    Route::middleware('permission:review-report-sample')->group(function () {
        Route::get('reports/sample-review', [ReportController::class, 'sampleReview'])->name('reports.sample-review');
        Route::post('reports/post-sample-review-update-is-sample/{file}', [ReportController::class, 'postSampleReviewUpdateIsSample'])
            ->name('reports.post-sample-review-update-is-sample');
        Route::post('reports/post-sample-review-update-memo/{file}', [ReportController::class, 'postSampleReviewUpdateMemo'])
            ->name('reports.post-sample-review-update-memo');
    });

    /**
     * 優選範本資料
     */
    Route::get('sample-report', [SampleReportController::class, 'index'])->name('sample-report.index');
    Route::get('sample-report/create/{topic}', [SampleReportController::class, 'create'])->name('sample-report.create');
    Route::post('sample-report/store/{topic}', [SampleReportController::class, 'store'])->name('sample-report.store');
    Route::delete('sample-report/{sampleReport}', [SampleReportController::class, 'destroy'])->name('sample-report.destroy');
    Route::prefix('sample-report')->middleware('permission:create-sample-report-public-date')->group(function () {
        Route::get('dates', [SampleReportController::class, 'getPublicDates'])->name('sample-report.date.index'); //資料公開時間列表index
        Route::post('dates/{id}', [SampleReportController::class, 'updatePublicDate'])->name('sample-report.date.update'); //資料公開時間更新update
    });

    Route::middleware('permission:review-report-sample')->group(function () {
        Route::post('sample-report/sample-review-update-is-sample/{sampleReport}', [SampleReportController::class, 'postSampleReviewUpdateIsSample'])
            ->name('sample-report.sample-review-update-is-sample');
        Route::post('sample-report/sample-review-update-memo/{sampleReport}', [SampleReportController::class, 'postSampleReviewUpdateMemo'])
            ->name('sample-report.sample-review-update-memo');
    });

    Route::middleware('publicReports')->group(function () {
        Route::get('reports/toggle/{id}', [ReportController::class, 'toggle'])->name('reports.toggle');  //2016 NEW 對外開放權限變更。
        Route::get('reports/toggle-is-sample/{id}', [ReportController::class, 'toggleIsSample'])->name('reports.toggle-is-sample');
        Route::get('reports/{id}/{year?}', [ReportController::class, 'show'])->name('reports.show')->where(['year' => '[0-9]{4}']);
        Route::middleware('removeTemporaryFiles')->group(function () {
            Route::get('reports/download/{name}/{year?}', [ReportController::class, 'download'])->name('reports.download')->where(['year' => '[0-9]{4}']);
        });
    });


    /**
     * 執行進度管制表
     */
    Route::prefix('seasonalReports')->group(function () {
        Route::middleware('permission:create-seasonalReports')->group(function () {
            Route::get('submit/{year}', [SeasonalReportController::class, 'submit'])->name('seasonalReports.submit')->where(['year' => '[0-9]{4}']);
            Route::get('create/{id}', [SeasonalReportController::class, 'create'])->name('seasonalReports.create');
            Route::delete('delete-file-in-submit-page/{file}', [SeasonalReportController::class, 'deleteFileInSubmitPage'])->name('seasonalReports.delete-file-in-submit-page');
            Route::post('submit', [SeasonalReportController::class, 'store'])->name('seasonalReports.store');
            Route::get('inquireByCounty', [SeasonalReportController::class, 'inquireByCounty'])->name('seasonalReports.inquireByCounty');   //2016 NEW 分類及縣市查詢!
            Route::get('downloadXlsxByCounty', [SeasonalReportController::class, 'downloadXlsxByCounty'])
                ->name('seasonalReports.downloadXlsxByCounty');   //2016 NEW 分類及縣市查詢!
        });
        Route::middleware('permission:create-seasonal-report-public-date')->group(function () {
            Route::get('dates', [SeasonalReportController::class, 'getPublicDates'])->name('seasonal-report.date.index'); //資料公開時間列表index
            Route::post('dates/{id}', [SeasonalReportController::class, 'updatePublicDate'])->name('seasonal-report.date.update'); //資料公開時間更新update
        });
        Route::get('inquire/{year?}', [SeasonalReportController::class, 'inquire'])->name('seasonalReports.inquire')
            ->where(['year' => '[0-9]{4}']);   //2016 NEW 分類及縣市查詢!
        Route::get('downloadXlsx', [SeasonalReportController::class, 'downloadXlsx'])->name('seasonalReports.downloadXlsx');   //2016 NEW 分類及縣市查詢!
        Route::get('downloadFilesByCounty', [SeasonalReportController::class, 'downloadFilesByCounty'])
            ->name('seasonalReports.downloadFilesByCounty');   //2016 NEW 分類及縣市查詢!
    });

    //以年分展示執行成果,網址用西元年, 顯示用民國年
    Route::get('seasonalReports/{year?}/{season?}', [SeasonalReportController::class, 'index'])->name('seasonalReports.index')->where([
        'year'   => '[0-9]{4}',
        'season' => '[0-9]{1}',
    ]);

    Route::get('seasonalReports/toggle/{id}', [SeasonalReportController::class, 'toggle'])->name('seasonalReports.toggle');  //2016 NEW 對外開放權限變更。
    Route::get('seasonalReports/{id}/{year?}/{season?}', [SeasonalReportController::class, 'show'])->name('seasonalReports.show')->where([
        'year'   => '[0-9]{4}',
        'season' => '[0-9]{1}',
    ]);
    Route::middleware('removeTemporaryFiles')->group(function () {
        Route::get('seasonalReports/download/{name}/{year?}', [SeasonalReportController::class, 'download'])
            ->name('seasonalReports.download')->where(['year' => '[0-9]{4}']);
    });

    /**
     * 執行計畫書
     */
    Route::prefix('plans')->middleware('permission:create-plans|create-plans-for-county')->group(function () {
        Route::get('submit', [PlanController::class, 'create'])->name('plans.create');
        Route::post('submit', [PlanController::class, 'store'])->name('plans.store');
        Route::middleware('removeTemporaryFiles')->group(function () {
            Route::get('download/{name}/{year?}', [PlanController::class, 'download'])->name('plans.download')->where(['year' => '[0-9]{4}']);
        });
        Route::get('downloadXlsx', [PlanController::class, 'downloadXlsx'])->name('plans.downloadXlsx');
        Route::middleware('permission:create-plans-public-date')->group(function () {
            Route::get('dates', [PlanController::class, 'getPublicDates'])->name('plans.date.index'); //資料公開時間列表index
            Route::post('dates/{id}', [PlanController::class, 'updatePublicDate'])->name('plans.date.update'); //資料公開時間更新update
        });
    });
    Route::get('inquire', [PlanController::class, 'inquire'])->name('plans.inquire');
    Route::middleware('permission:create-plans-for-county')->group(function () {
        Route::delete('destroy-file/{file}', [PlanController::class, 'destroyFile'])->name('plans.destroy-file');
    });

    /**
     * 期末簡報上傳
     */
    Route::prefix('presentation')->middleware('permission:create-plans|create-plans-for-county')->group(function () {
        Route::get('submit', [PresentationController::class, 'create'])->name('presentation.create');
        Route::post('submit', [PresentationController::class, 'store'])->name('presentation.store');
        Route::middleware('removeTemporaryFiles')->group(function () {
            Route::get('download/{name}/{year?}', [PresentationController::class, 'download'])->name('presentation.download')->where(['year' => '[0-9]{4}']);
        });
        Route::middleware('permission:create-presentation-public-date')->group(function () {
            Route::get('dates', [PresentationController::class, 'getPublicDates'])->name('presentation.date.index'); //資料公開時間列表index
            Route::post('dates/{id}', [PresentationController::class, 'updatePublicDate'])->name('presentation.date.update'); //資料公開時間更新update
        });
        Route::get('downloadXlsx', [PresentationController::class, 'downloadXlsx'])->name('presentation.downloadXlsx');
    });
    Route::get('presentation/inquire', [PresentationController::class, 'inquire'])->name('presentation.inquire');
    Route::get('presentation/export/{year}', [PresentationController::class, 'export'])->name('presentation.export');
    Route::middleware('permission:create-plans-for-county')->group(function () {
        Route::delete('presentation/destroy-file/{file}', [PresentationController::class, 'destroyFile'])->name('presentation.destroy-file');
    });

    /**
     * 管理者與署內專屬之成果網資料
     */
    Route::middleware('permission:create-central-reports')->group(function () {
        Route::get('centralReports/submit/{year}', [CentralReportController::class, 'submit'])->name('centralReports.submit')->where(['year' => '[0-9]{4}']);
        Route::get('centralReports/submit/{id}', [CentralReportController::class, 'create'])->name('centralReports.create');
        Route::post('centralReports/submit', [CentralReportController::class, 'store'])->name('centralReports.store');
        Route::delete('delete-file-in-submit-page/{file}', [CentralReportController::class, 'deleteFileInSubmitPage'])->name('central.delete-file-in-submit-page');
    });

    //以年分展示執行成果,網址用西元年, 顯示用民國年
    Route::get('centralReports/{year?}', [CentralReportController::class, 'index'])->name('centralReports.index')->where(['year' => '[0-9]{4}']);


    //切換顯示
    Route::get('showing/{topic}', [ShowingController::class, 'index'])->name('showing.index')
        ->where(['topic' => '\d+']);
    Route::get('showing/{topic}/{id}', [ShowingController::class, 'show'])->name('showing.show')
        ->where(['topic' => '\d+']);
    Route::get('showing/toggle/{id}', [ShowingController::class, 'toggle'])->name('showing.toggle');
    Route::get('showing/toggle-is-recommend/{id}', [ShowingController::class, 'toggleIsRecommend'])->name('showing.toggle-is-recommend');

    Route::middleware('permission:view-committee')->group(function () {
        Route::resource('committee', CommitteeController::class)->only(['index']);
    });

    //通訊錄
    Route::prefix('address')->group(function () {
        Route::get('search', [AddressController::class, 'search'])->name('address.search');
        Route::get('manage', [AddressController::class, 'manage'])->name('address.manage');
        Route::get('downloadXlsx', [AddressController::class, 'downloadXlsx'])->name('address.downloadXlsx');
        Route::get('getChildren', [AddressController::class, 'getChildren'])->name('address.getChildren');
    });
    Route::resource('address', AddressController::class)->except(['show']);

    //民眾版
    Route::middleware('permission:introduction-manage')->group(function () {
        Route::resource('introduction', IntroductionController::class)->except(['show']);
    });

    //防救災圖資
    Route::prefix('image-data')->group(function () {
        Route::get('create/{image_datum_type}/{user}', [ImageDataController::class, 'create'])->name('image-data.create');
        Route::get('edit/{image_datum_type}/{user}', [ImageDataController::class, 'edit'])->name('image-data.edit');
        Route::get('{image_datum_type}/{user}', [ImageDataController::class, 'show'])->name('image-data.show')->where([
            'image_datum_type' => '\d+',
            'user'             => '\d+',
        ]);
    });
    Route::resource('image-data', ImageDataController::class)->except(['create', 'show', 'edit']);

    //防災避難看板
    Route::get('sign-location/map', [SignLocationController::class, 'map'])->name('sign-location.map');
    Route::get('sign-location/downloadXlsx', [SignLocationController::class, 'downloadXlsx'])->name('sign-location.downloadXlsx');
    Route::resource('sign-location', SignLocationController::class);

    // 後台修正管理
    Route::prefix('admin')->group(function () {
        //Route::resource('user', 'PhotoController');
        Route::middleware('permission:switch-role')->group(function () {
            // 切換角色
            Route::get('switchRole', [UserController::class, 'switchRoleIndex'])->name('admin.switchRole.index');
            Route::post('switchRole', [UserController::class, 'switchRole'])->name('admin.switchRole');
        });
    });

    //宣導影片及文宣專區
    Route::middleware('permission:create-video')->group(function () {
        Route::resource('video', VideoController::class)->except(['show', 'index', 'edit']);
    });
    Route::resource('video', VideoController::class)->only(['index', 'edit', 'show']);

    //首頁輪播設定
    Route::middleware('permission:home-page-carousel-image.manage')->group(function () {
        Route::resource('home-page-carousel-image', HomePageCarouselImageController::class);
    });

    // 防災士培訓 - //TODO: 檔案上傳有問題
    Route::middleware('permission:DP-teachers-manage')->group(function () {
        Route::resource('dp-teachers', DpTeacherController::class)->except(['show', 'index']);
        Route::get('dp-teachers/export', [DpTeacherController::class, 'export'])->name('dp-teachers.export');
        Route::get('dp-teachers/import', [DpTeacherController::class, 'importForm'])->name('dp-teachers.import');
        Route::post('dp-teachers/import', [DpTeacherController::class, 'import'])->name('dp-teachers.import');
        Route::get('dp-teachers/summary', [DpTeacherController::class, 'summary'])->name('dp-teachers.summary');
        Route::get('dp-teachers/download-import-sample', [DpTeacherController::class, 'downloadImportSample'])->name('dp-teachers.download-import-sample');
        Route::post('dp-teachers/send-profile-update-mail/{dp_teacher}', [DpTeacherController::class, 'sendProfileUpdateMail'])
            ->name('dp-teachers.send-profile-update-mail');
    });
    Route::resource('dp-teachers', DpTeacherController::class)->only(['show', 'index']);

    Route::middleware('permission:DP-courses-manage')->group(function () {
        Route::resource('dp-courses', DpCourseController::class)->except(['show']);
    });
    Route::middleware('permission:DP-course-plan-document-manage')->group(function () {
        Route::get('dp-courses-document/create', [DpCourseController::class, 'documentCreate'])->name('dp-courses-document.create');
        Route::post('dp-courses-document/store', [DpCourseController::class, 'documentStore'])->name('dp-courses-document.store');
        Route::get('dp-courses-result/create', [DpCourseController::class, 'resultCreate'])->name('dp-courses-result.create');
        Route::post('dp-courses-result/store', [DpCourseController::class, 'resultStore'])->name('dp-courses-result.store');
    });

    Route::middleware('permission:DP-students-manage')->group(function () {
        Route::resource('dp-students', DpStudentController::class)->except(['show', 'create', 'store']);
        Route::prefix('dp-students')->group(function () {
            Route::get('search', [DpStudentController::class, 'search'])->name('dp-students.search');
            Route::get('show/{id}', [DpStudentController::class, 'show'])->name('dp-students.show');
            Route::get('certificate', [DpStudentController::class, 'certificate'])->name('dp-students.certificate');
            Route::get('statistics', [DpStudentController::class, 'statistics'])->name('dp-students.statistics');
            Route::get('export', [DpStudentController::class, 'export'])->name('dp-students.export');
            Route::get('inquire', [DpStudentController::class, 'inquire'])->name('dp-students.inquire');
            Route::post('inquire', [DpStudentController::class, 'postInquireInput'])->name('dp-students.inquire');
            Route::get('download-inquire-input-sample', [DpStudentController::class, 'downloadInquireInputSample'])->name('dp-students.download-inquire-input-sample');
            Route::get('download-imported-file/{filename}', [DpStudentController::class, 'downloadImportedFile'])
                ->name('dp-students.download-imported-file');
        });
    });
    //Route::resource('dp-students', DpStudentController::class)->only(['show', 'index']);

    Route::middleware('permission:DP-students-create')->group(function () {
        Route::resource('dp-students', DpStudentController::class)->only(['create', 'store']);
        Route::prefix('dp-students')->group(function () {
            Route::get('import', [DpStudentController::class, 'importForm'])->name('dp-students.import');
            Route::post('import', [DpStudentController::class, 'import'])->name('dp-students.import');
            Route::get('download-import-sample', [DpStudentController::class, 'downloadImportSample'])->name('dp-students.download-import-sample');
        });
    });

    // 進階防災士培訓
    Route::middleware('permission:DP-students-manage')->group(function () {
        Route::resource('dp-advanced-students', DpAdvancedStudentController::class)->except(['show', 'update', 'create', 'store']);
        Route::prefix('dp-advanced-students')->group(function () {
            Route::get('search', [DpAdvancedStudentController::class, 'search'])->name('dp-advanced-students.search');
            Route::get('history', [DpAdvancedStudentController::class, 'history'])->name('dp-advanced-students.history');
            Route::get('new-training/{id?}', [DpAdvancedStudentController::class, 'newTraining'])->name('dp-advanced-students.new-training');
            Route::get('statistics', [DpAdvancedStudentController::class, 'statistics'])->name('dp-advanced-students.statistics');
            Route::get('export', [DpAdvancedStudentController::class, 'export'])->name('dp-advanced-students.export');
            Route::get('inquire', [DpAdvancedStudentController::class, 'inquire'])->name('dp-advanced-students.inquire');
            Route::post('history/{id?}', [DpAdvancedStudentController::class, 'updateTraining'])->name('dp-advanced-students.history-update');
            Route::put('course-update/{id}', [DpAdvancedStudentController::class, 'courseUpdate'])->name('dp-advanced-students.course-update');
            Route::post('update', [DpAdvancedStudentController::class, 'update'])->name('dp-advanced-students.update');
            Route::post('inquire', [DpAdvancedStudentController::class, 'postInquireInput'])->name('dp-advanced-students.inquire');
            Route::get('download-inquire-input-sample', [DpAdvancedStudentController::class, 'downloadInquireInputSample'])->name('dp-advanced-students.download-inquire-input-sample');
            Route::get('download-imported-file/{filename}', [DpAdvancedStudentController::class, 'downloadImportedFile'])
                ->name('dp-advanced-students.download-imported-file');
        });
    });

    Route::middleware('permission:DP-students-create')->group(function () {
        Route::resource('dp-advanced-students', DpAdvancedStudentController::class)->only(['create', 'store']);
        Route::prefix('dp-advanced-students')->group(function () {
            Route::get('import', [DpAdvancedStudentController::class, 'importForm'])->name('dp-advanced-students.import');
            Route::post('import', [DpAdvancedStudentController::class, 'import'])->name('dp-advanced-students.import');
            Route::get('download-import-sample', [DpAdvancedStudentController::class, 'downloadImportSample'])->name('dp-advanced-students.download-import-sample');
        });
    });

    Route::middleware('permission:DP-scores-manage')->group(function () {
        Route::get('dp-scores/getStudents', [DpScoreController::class, 'ajaxGetCourseStudents'])->name('dp-scores.getStudents');
        Route::resource('dp-scores', DpScoreController::class)->except(['show']);
    });

    Route::middleware('permission:DP-waivers-manage')->group(function () {
        Route::get('dp-waivers/getWaivers', [DpWaiverController::class, 'ajaxGetWaivers'])->name('dp-waivers.getWaivers');
        Route::get('dp-waivers/getStudent', [DpWaiverController::class, 'ajaxGetStudent'])->name('dp-waivers.getStudent');
        Route::resource('dp-waivers', DpWaiverController::class)->except(['show', 'edit']);
    });
    Route::middleware('permission:DP-waivers-review')->group(function () {
        Route::resource('dp-waivers-review', DpWaiverReviewController::class)->only(['index', 'show', 'edit', 'update']);
    });

    Route::middleware('permission:DP-experiences-manage')->group(function () {
        Route::get('dp-experiences/getExperiences', [DpExperienceController::class, 'ajaxGetExperiences'])->name('dp-experiences.getExperiences');
        Route::get('dp-experiences/getStudent', [DpExperienceController::class, 'ajaxGetStudent'])->name('dp-experiences.getStudent');
        Route::delete('dp-experiences/delete/{id}', [DpExperienceController::class, 'destroy'])->name('dp-experiences.delete');
        Route::resource('dp-experiences', DpExperienceController::class)->except(['show', 'edit']);
    });

    Route::get('dp-resources/view', [DpResourceController::class, 'view'])->name('dp-resources.view');
    Route::resource('dp-resources', DpResourceController::class)->except(['show']);

    Route::middleware('permission:DP-resources-manage')->group(function () {
        Route::resource('dp-resources', DpResourceController::class)->except(['show']);
    });

    Route::middleware('permission:DP-training-institution-manage')->group(function () {
        Route::resource('dp-training-institution', DpTrainingInstitutionController::class)->except(['show']);
    });

    Route::middleware('permission:DP-civil-manage')->group(function () {
        Route::resource('dp-civil', DpCivilController::class)->except(['show']);
    });

    Route::middleware('permission:DC-schedules-manage')->group(function () {
        Route::resource('dc-schedules', DcScheduleController::class)->except(['show']);
    });
    //推動韌性社區
    Route::middleware('permission:DC-units-manage')->group(function () {
        Route::resource('dc-units', DcUnitController::class)->except(['show']);
        Route::prefix('dc-units')->group(function () {
            Route::get('{dc_unit}/edit-rank', [DcUnitController::class, 'editRank'])->name('dc-units.edit-rank');
            Route::put('{dc_unit}/edit-rank', [DcUnitController::class, 'updateRank'])->name('dc-units.update-rank');

            Route::get('export', [DcUnitController::class, 'export'])->name('dc-units.export');
            Route::get('exportRe', [DcUnitController::class, 'exportRe'])->name('dc-units.exportRe');
            Route::get('export-dc-user', [DcUnitController::class, 'exportDcUser'])->name('dc-units.export-dc-user');
            Route::get('import', [DcUnitController::class, 'importForm'])->name('dc-units.import');
            Route::post('import', [DcUnitController::class, 'import'])->name('dc-units.import');
            Route::get('dc-units-report',  [DcUnitController::class, 'report'])->name('dc-units.report');
            Route::post('dc-units-report',  [DcUnitController::class, 'getReport'])->name('dc-units.getReport');
            Route::get('download-import-sample', [DcUnitController::class, 'downloadImportSample'])->name('dc-units.download-import-sample');
            Route::get('create-dc-user/{dcUnit}', [DcUnitController::class, 'createDcUser'])->name('dc-units.create-dc-user');
            Route::post('create-dc-user/{dcUnit}', [DcUnitController::class, 'storeDcUser'])->name('dc-units.store-dc-user');
        });
    });

    //推動韌性社區
    Route::middleware('permission:DC-stages-manage')->group(function () {
        Route::resource('dc-stages', DcStageController::class)->except(['show']);
        Route::resource('dc-stages-list', DcStageListController::class);
    });

    //推動韌性社區
    Route::middleware('permission:DC-certifications-manage')->group(function () {
        Route::resource('dc-certifications', DcCertificationController::class)->except(['show']);
        Route::get('dc-certifications/export', [DcCertificationController::class, 'export'])->name('dc-certifications.export');
    });

    //推動韌性社區
    Route::middleware('permission:DC-certifications-review')->group(function () {
        Route::post('dc-certifications-review/{id}/download-files', [DcCertificationReviewController::class, 'downloadFiles'])
            ->name('dc-certifications-review.download-files');
        Route::resource('dc-certifications-review', DcCertificationReviewController::class)->only(['index', 'show', 'edit', 'update']);
    });


    Route::middleware('permission:create-references')->group(function () {
        Route::resource('references', ReferenceController::class)->except(['show', 'index', 'create']);
    });
    Route::resource('references', ReferenceController::class)->only(['index', 'create']);

    // 編輯民眾版網頁介紹
    Route::middleware('permission:front-introduction-manage')->group(function () {
        Route::resource('front-introduction', FrontIntroductionController::class)->except(['create']);
    });
    //    Route::group([
    //        //'middleware' => 'permission:create-reportTerms',
    //        'prefix' => 'admin',
    //    ], function () {

    /**
     * 績效評估自評表
     */
    Route::prefix('questionnaire')->group(function () {
        Route::get('panel/{questionnaire_id?}', [QuestionnaireController::class, 'panel'])->name('questionnaire.panel');

        Route::get('{account_id}/{questionnaire_id?}/answer', [QuestionnaireController::class, 'answer'])->name('questionnaire.answer');

        Route::get('{account_id}/{questionnaire_id}/show', [QuestionnaireController::class, 'show'])->name('questionnaire.show');

        Route::post('{account_id}/{questionnaire_id}/submit', [QuestionnaireController::class, 'submit'])->name('questionnaire.submit');

        Route::post('{account_id}/{questionnaire_id}/submitComments', [QuestionnaireController::class, 'submitComments'])->name('questionnaire.submitComments');

        Route::get('{account_id}/{questionnaire_id}/export', [QuestionnaireController::class, 'export'])->name('questionnaire.export');
        Route::get('batchExport', [QuestionnaireController::class, 'batchExportForm'])->name('questionnaire.batch-export-form');
        Route::get('{account_id}/{questionnaire_id}/batch-export', [QuestionnaireController::class, 'batchExport'])->name('questionnaire.batch-export');

        Route::middleware('permission:create-questionnaires')->group(function () {
            Route::get('updateStatus', [QuestionnaireController::class, 'updateStatus'])->name('questionnaire.updateStatus');
        });

        Route::get('statistic', [QuestionnaireController::class, 'statistic'])->name('questionnaire.statistic');
        Route::get('statistic-export', [QuestionnaireController::class, 'statisticExport'])->name('questionnaire.statistic.export');
    });
    Route::prefix('questions')->middleware('permission:create-questionnaires')->group(function () {
        Route::get('show/{questionnaire_id}', [QuestionnaireController::class, 'showQuestions'])->name('questions.show');
        Route::post('update', [QuestionnaireController::class, 'updateQuestions'])->name('questions.update');
    });
    Route::resource('questionnaire', QuestionnaireController::class)->except(['show']);

    /**
     * 操作教學說明文件
     */
    Route::middleware('permission:create-guidance')->group(function () {
        Route::resource('guidance', GuidanceController::class)->except(['show', 'index']);
    });
    Route::resource('guidance', GuidanceController::class)->only(['show', 'index']);

    /**
     *  後台修正管理
     */
    Route::prefix('admin')->group(function () {
        //工作項目管理
        Route::middleware('permission:topic-manage')->group(function () {
            Route::post('createReportTerms', [AdminController::class, 'createReportTerms'])->name('admin.createReportTerms');
            Route::get('reportTerms', [AdminController::class, 'reportTermsIndex'])->name('admin.reportTerms');
            Route::get('getRootTopics', [AdminController::class, 'getRootTopics'])->name('admin.getRootTopics');
            Route::post('editReportTerms', [AdminController::class, 'editReportTerms'])->name('admin.editReportTerms');
            Route::post('delReportTerms', [AdminController::class, 'delReportTerms'])->name('admin.delReportTerms');
        });

        //相關資源與連結(民眾版)
        Route::resource('frontDownload', FrontDownloadController::class);

        //民眾版簡介分類項目管理
        Route::middleware('permission:create-publicTerms')->group(function () {
            Route::get('publicTerms', [AdminController::class, 'publicTermsIndex'])->name('admin.publicTerms');
            Route::post('editPublicTerms', [AdminController::class, 'editPublicTerms'])->name('admin.editPublicTerms');
            Route::post('createPublicTerms', [AdminController::class, 'createPublicTerms'])->name('admin.createPublicTerms');
            Route::post('delPublicTerms', [AdminController::class, 'delPublicTerms'])->name('admin.delPublicTerms');
        });


        //各縣市深耕計畫網頁位址管理(民眾版)
        Route::middleware('permission:create-publicUrls')->group(function () {
            Route::get('publicUrls', [AdminController::class, 'publicUrlsIndex'])->name('admin.publicUrls');
            Route::post('editPublicUrls', [AdminController::class, 'editPublicUrls'])->name('admin.editPublicUrls');
            Route::post('delPublicUrls', [AdminController::class, 'delPublicUrls'])->name('admin.delPublicUrls');
        });

        //縣市順序管理
        Route::middleware('permission:create-publicUrls')->group(function () {
            Route::get('countyOrder', [AdminController::class, 'countyOrder'])->name('admin.countyOrder');
            Route::post('editCountyOrder', [AdminController::class, 'editCountyOrder'])->name('admin.editCountyOrder');
        });

        //後台修正管理-日誌紀錄
        Route::middleware('permission:activity-log.access')->group(function () {
            Route::resource('activity-log', ActivityLogController::class)->only(['index', 'show']);
        });
    });

    /**
     *  QA專區
     */
    Route::resource('qas', QaController::class)->except(['show', 'create']);
    Route::get('/qas/keyWord', [QaController::class, 'keyWord'])->name('qas.keyWord');

    Route::middleware('permission:create-QAs')->group(function () {
        Route::get('qas/create', [QaController::class, 'create'])->name('qas.create');
    });

    Route::resource('dpDownload', DpDownloadController::class);
    //民眾版網頁管理 check
    Route::resource('dcDownload', DcDownloadController::class);

    //固定頁面處理
    //    Route::middleware('permission:modify-static-page')->group(function () {
    Route::resource('static-page', StaticPageController::class);
    //    });

    //功能-身分切換
    Route::prefix('identity')->group(function () {
        Route::get('/', [IdentityController::class, 'index'])->name('identity.index');
        Route::post('change', [IdentityController::class, 'changeIdentity'])->name('identity.change-identity');
        Route::get('change-back', [IdentityController::class, 'changeIdentityBack'])
            ->name('identity.change-identity-back');
    });
});

/**
 * Files download 檔案下載
 */
Route::get('uploads/{year}/{month}/{id}', [DownloadController::class, 'get'])->name('downloads.get')->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}']);

Route::get('stream/{year}/{month}/{id}', [DownloadController::class, 'stream'])->name('downloads.stream')->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}']);
