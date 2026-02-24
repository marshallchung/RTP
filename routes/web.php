<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//首頁

use App\Http\Controllers\ReportController;
use App\Http\Controllers\CentralReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\IntroductionController;
use App\Http\Controllers\SignLocationController;
use App\Http\Controllers\QAController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DpController;
use App\Http\Controllers\DcController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResultIIIController;
use App\Http\Controllers\DpTeacherController;
use App\User;
use App\UserAlias;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Rutorika\Sortable\SortableController;
use App\Http\Controllers\Admin\Auth\Login2FAController;

// 僅供本機開發測試：免帳密/免2FA快速登入
if (app()->environment('local') && config('app.dev_auto_login')) {
    Route::prefix('__dev')->group(function () {
        Route::get('/login/admin/{id}', function (int $id) {
            $user = User::findOrFail($id);
            Auth::guard('web')->login($user);
            session(['origin_identity' => null]);

            return redirect('/admin');
        })->name('dev.login.admin');

        Route::get('/login/alias/{username}', function (string $username) {
            $alias = UserAlias::where('username', $username)->firstOrFail();
            abort_if(!$alias->user, 404, 'Alias user not found');

            Auth::guard('web')->login($alias->user);
            session(['origin_identity' => null]);

            return redirect('/admin');
        })->name('dev.login.alias');

        Route::get('/logout', function () {
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect('/');
        })->name('dev.logout');
    });
}

//首頁
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/page/{staticPage}', [HomeController::class, 'showStaticPage'])->name('static-page');   //固定頁面 (For English,隱私權政策 網站安全政策 政府網站資料開放宣告)
Route::get('/search', [HomeController::class, 'search'])->name('search');   //搜尋功能
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');   //網站導覽Sitemap

Route::post('sort', [SortableController::class, 'sort'])->name('sort');   //排序

/**
 * 最新消息 &計畫簡介
 */
Route::prefix('introduction')->group(function () {
    Route::get('/news', [IntroductionController::class, 'publicNews'])->name('introduction.public-news.index');
    Route::get('/news/{publicNews}', [IntroductionController::class, 'publicNewsShow'])->name('introduction.public-news.show');
    Route::get('/{introductionType}', [IntroductionController::class, 'index'])->name('introduction.index');
    Route::get('/search/{introductionType}', [IntroductionController::class, 'search'])->name('introduction.search');
    Route::get('/show/{introduction}', [IntroductionController::class, 'show'])->name('introduction.show');
    //Route::get('/achievements_statistics', [IntroductionController::class, 'publicNews'])->name('introduction.public-news.index');
});

Route::prefix('achievements')->group(function () {
    Route::get('/statistics', [AchievementsController::class, 'index'])->name('achievements.statistics.index');
    Route::get('/search/{introductionType}', [AchievementsController::class, 'search'])->name('achievements.statistics.search');
});

/**
 * 直轄市、縣(市)政府歷年成果統計頁面
 */
Route::prefix('report')->group(function () {
    Route::get('/', [ReportController::class, 'statistic'])->name('report.statistic');
    Route::get('/export/{year}/{county}', [ReportController::class, 'export'])->name('report.statistic.export');
});

/**
 *  二期成果資料
 */
/*Route::prefix('report')->group(function () {
    Route::get('/{topic}', [ReportController::class, 'index'])->name('report.index');
    Route::get('/search/{topic}', [ReportController::class, 'search'])->name('report.search');
});*/

Route::prefix('centralReport')->group(function () {
    Route::get('/{topic}', [CentralReportController::class, 'index'])->name('centralReport.index');
    Route::get('/search/{topic}', [CentralReportController::class, 'search'])->name('centralReport.search');
});

//二期成果資料-防災避難看板
Route::get('sign-location', [SignLocationController::class, 'index'])->name('sign-location.index');

//二期成果資料-防救災圖資
//Route::get('image-data', [ImageDataController::class, 'index'])->name('image-data.index');
//Route::get('image-data/{county}', [ImageDataController::class, 'show'])->name('image-data.show');
//Route::get('image-data/search/{county}', [ImageDataController::class, 'search'])->name('image-data.search');

/**
 *  防災士培訓
 */
Route::prefix('dp')->group(function () {
    Route::get('/intro', [DpController::class, 'intro'])->name('dp.intro');
    Route::get('/download', [DpController::class, 'download'])->name('dp.download');
    Route::get('/download/search', [DpController::class, 'search'])->name('dp.download-search');
    Route::get('/courseShow/{data}', [DpController::class, 'courseShow'])->name('dp.courseShow');
    Route::get('/course', [DpController::class, 'course'])->name('dp.course');
    Route::get('/civilShow/{data}', [DpController::class, 'civilShow'])->name('dp.civilShow');
    Route::get('/civil', [DpController::class, 'civil'])->name('dp.civil');
    Route::get('/training-institution', [DpController::class, 'trainingInstitution'])->name('dp.training-institution');
    Route::get('/statistics', [DpController::class, 'statistics'])->name('dp.statistics');
    Route::get('/advanced-statistics', [DpController::class, 'advancedStatistics'])->name('dp.advanced-statistics');
    Route::get('/statistics/export', [DpController::class, 'statisticsExport'])->name('dp.statistics.export');
    Route::get('/advanced-statistics/export', [DpController::class, 'advancedStatisticsExport'])->name('dp.advanced-statistics.export');

    //Route::get('/student-list', [DpController::class, 'studentList'])->name('dp.student-list');
    //Route::get('/advanced-student-list', [DpController::class, 'advancedStudentList'])->name('dp.advanced-student-list');
    Route::get('/student-search', [DpController::class, 'studentSearch'])->name('dp.student-search');
    Route::get('/advanced-student-search', [DpController::class, 'advancedStudentSearch'])->name('dp.advanced-student-search');
    Route::middleware('authDp')->group(function () {
        Route::get('/student', [DpController::class, 'student'])->name('dp.student');
        Route::put('/student', [DpController::class, 'studentUpdate'])->name('dp.student.update');
        Route::get('/myCourse', [DpController::class, 'myCourse'])->name('dp.myCourse');
        Route::get('/geMyCourse', [DpController::class, 'getMyCourse'])->name('dp.getMyCourse');
        Route::get('/waiver', [DpController::class, 'waiver'])->name('dp.waiver');
        Route::post('/uploadWaiverFiles/{id}', [DpController::class, 'uploadWaiverFiles'])->name('dp.uploadWaiverFiles');
        Route::get('/experience', [DpController::class, 'experience'])->name('dp.experience');
        Route::post('/uploadExperienceFiles/{id}', [DpController::class, 'uploadExperienceFiles'])->name('dp.uploadExperienceFiles');
    });
});

/**
 *  師資資料更新 , 從Email寄信給連結進入
 */
Route::prefix('dp-teacher')->group(function () {
    Route::get('profile/{dp_teacher}', [DpTeacherController::class, 'editProfile'])->name('dp-teacher.edit-profile');
    Route::put('profile/{dp_teacher}', [DpTeacherController::class, 'updateProfile'])->name('dp-teacher.update-profile');
});

// 推動韌性社區
Route::prefix('dc')->group(function () {
    Route::get('/intro', [DcController::class, 'intro'])->name('dc.intro');
    Route::get('/download', [DcController::class, 'download'])->name('dc.download');
    Route::get('/download/search', [DcController::class, 'search'])->name('dc.download-search');
    Route::get('/show-unit', [DcController::class, 'unitIndex'])->name('dc.unit.index');
    Route::get('/search-unit', [DcController::class, 'unitSearch'])->name('dc.unit.search');
    Route::get('/show-unit/{dcUnit}', [DcController::class, 'unitShow'])->name('dc.unit.show');

    Route::middleware('authDc')->group(function () {
        Route::get('/unit', [DcController::class, 'unit'])->name('dc.unit');
        Route::put('/unit', [DcController::class, 'unitUpdate'])->name('dc.unit.update');
        //Route::get('/courseShow/{data}',[DpController::class, 'courseShow'])->name('dc.courseShow');
        Route::get('/upload', [DcController::class, 'upload'])->name('dc.upload');
        Route::post('/upload', [DcController::class, 'uploadUpdate'])->name('dc.upload.update');
        Route::get('/certification', [DcController::class, 'certification'])->name('dc.certification');
        Route::post('/certification', [DcController::class, 'certificationUpdate'])->name('dc.certification.update');
    });
});

// 相關資源連結
Route::prefix('resource')->group(function () {
    Route::get('/link', [ResourceController::class, 'linkIndex'])->name('resource.linkIndex');
    Route::get('/download', [ResourceController::class, 'downloadIndex'])->name('resource.downloadIndex');
    Route::get('/search', [ResourceController::class, 'downloadSearch'])->name('resource.downloadSearch');
    Route::get('/video', [ResourceController::class, 'videoIndex'])->name('resource.videoIndex');
    Route::get('/searchvideo', [ResourceController::class, 'videoSearch'])->name('resource.videoSearch');
    Route::get('/video/{video}', [ResourceController::class, 'videoShow'])->name('resource.videoShow');
    Route::get('/map', [ResourceController::class, 'mapIndex'])->name('resource.mapIndex');
    Route::get('/advanceMap', [ResourceController::class, 'advanceMapIndex'])->name('resource.advanceMapIndex');
});

// QA專區
Route::prefix('QA')->group(function () {
    Route::get('/', [QAController::class, 'index'])->name('qa.index');
    Route::get('/search', [QAController::class, 'search'])->name('qa.search');
    Route::get('/show/{qa}', [QAController::class, 'show'])->name('qa.show');
});

//Login處理 , Password密碼重置
Route::get('/login', [UserController::class, 'loginIndex'])->name('user.loginIndex');
Route::post('/login', [UserController::class, 'login'])->name('user.login');
Route::post('/changePassword', [UserController::class, 'changePassword'])->name('user.changePassword');
Route::get('/logout', [UserController::class, 'logout'])->name('user.logout');
Route::get('/resetPassword', [UserController::class, 'resetPassword'])->name('user.resetPassword');
Route::post('/resetPassword', [UserController::class, 'execResetPassword'])->name('user.execResetPassword');

//檔案下載
Route::get('uploads/{year}/{month}/{id}', [DownloadController::class, 'get'])->name('downloads.get')->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}']);

Route::get('stream/{year}/{month}/{id}', [DownloadController::class, 'stream'])->name('downloads.stream')->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}']);

// 三期計畫成果網
Route::prefix('resultiii')->group(function () {
    Route::get('/', [ResultIIIController::class, 'index'])->name('resultIII.index'); //首頁
    Route::get('detail', [ResultIIIController::class, 'detail'])->name('resultIII.detail'); //細述深耕
    Route::get('overview/{overviewType}', [ResultIIIController::class, 'overview'])->name('resultIII.overview'); //深耕概要
    Route::get('overview/{overviewType}/{staticPageId}', [ResultIIIController::class, 'overviewShow'])->name('resultIII.overview.show'); //深耕概要
    Route::get('achievement/{topicName}', [ResultIIIController::class, 'achievement'])->name('resultIII.achievement'); //成果展示
    Route::get('highlights', [ResultIIIController::class, 'highlights'])->name('resultIII.highlights'); //深耕集錦
    Route::get('lookahead', [ResultIIIController::class, 'lookahead'])->name('resultIII.lookahead'); //遠望深耕
});

//2fa
Route::get('login', [LoginController::class, 'showLoginForm'])->name('auth.login');
Route::get('2fa', [Login2FAController::class, 'show2faForm'])->name('auth.2fa.form');
Route::post('2fa', [Login2FAController::class, 'verify2fa'])->name('auth.2fa.verify');

// 已經有的路由
Route::get('/2fa/setup', [Login2FAController::class, 'show2faSetupForm'])->name('auth.2fa.setup');
Route::post('/2fa/setup', [Login2FAController::class, 'store2faSecret'])->name('auth.2fa.setup.store');  // 這裡的路由名稱要對應到你表單的提交路徑


//會員（須完成信箱驗證）
//Route::group(['middleware' => ['auth', 'email']], function () {
//    //會員管理
//    //權限：user.manage、user.view
//    Route::resource('user', 'UserController', [
//        'except' => [
//            'create',
//            'store',
//        ],
//    ]);
//    //角色管理
//    //權限：role.manage
//    Route::group(['middleware' => 'permission:role.manage'], function () {
//        Route::resource('role', 'RoleController', [
//            'except' => [
//                'show',
//            ],
//        ]);
//    });
//    //會員資料
//    Route::group(['prefix' => 'profile'], function () {
//        //查看會員資料
//        Route::get('/',[\ProfileController::class, 'getProfile'])->name('profile');
//        //編輯會員資料
//        Route::get('edit',[\ProfileController::class, 'getEditProfile'])->name('profile.edit');
//        Route::put('update',[\ProfileController::class, 'updateProfile'])->name('profile.update');
//    });
//});

//會員系統
//將 Auth::routes() 複製出來自己命名
//Route::group(['namespace' => 'Auth'], function () {
//    // Authentication Routes...
//    Route::get('login',[\LoginController::class, 'showLoginForm'])->name('login');
//    Route::post('login',[\LoginController::class, 'login'])->name('login');
//    Route::get('logout',[\LoginController::class, 'logout'])->name('logout');
//    Route::post('logout',[\LoginController::class, 'logout'])->name('logout');
//    // Registration Routes...
//    Route::get('register',[\RegisterController::class, 'showRegistrationForm'])->name('register');
//    Route::post('register',[\RegisterController::class, 'register'])->name('register');
//    // Password Reset Routes...
//    Route::get('password/reset',[\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
//    Route::post('password/email',[\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
//    Route::get('password/reset/{token}',[\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
//    Route::post('password/reset',[\ResetPasswordController::class, 'reset']);
//    //修改密碼
//    Route::get('password/change',[\PasswordController::class, 'getChangePassword'])->name('password.change');
//    Route::put('password/change',[\PasswordController::class, 'putChangePassword'])->name('password.change');
//    //驗證信箱
//    Route::get('resend',[\RegisterController::class, 'resendConfirmMailPage'])->name('confirm-mail.resend');
//    Route::post('resend',[\RegisterController::class, 'resendConfirmMail'])->name('confirm-mail.resend');
//    Route::get('confirm/{confirmCode}',[\RegisterController::class, 'emailConfirm'])->name('confirm');
//});
