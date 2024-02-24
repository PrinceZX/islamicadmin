<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\AudioController;
use App\Http\Controllers\Admin\AiaudiobookController;
use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\AiaudioTransactionController;



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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [AdminController::class, 'get_login'])->name('admin.home');
Route::get('admin/login', [AdminController::class, 'get_login'])->name('admin.index');
Route::post('admin/login', [AdminController::class, 'post_login'])->name('admin.login');
Route::get('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::get('admin/signup', [AdminController::class, 'get_signup'])->name('admin.signup');
Route::post('admin/signup', [AdminController::class, 'post_signup'])->name('admin.signup');

// Pages
Route::get('send-mail', [MailController::class,'sendMail'])->name('send.mail');
Route::get('about-us', [AdminController::class, 'Page'])->name('AboutUS');
Route::get('privacy-policy', [AdminController::class, 'Page'])->name('privacyPolicy');
Route::get('terms-and-conditions', [AdminController::class, 'Page'])->name('TermsAndConditions');
Route::get('refund-policy', [AdminController::class, 'Page'])->name('RefundPolicy');
Route::get('remaining', [AdminController::class, 'Page'])->name('remaining');


Route::group(['prefix' => 'admin','middleware' => 'adminauth'], function()  {

    Route::group(['middleware' => 'checkadmin'], function () {

        // ADMIN //
        Route::resource('admins', AdminController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        // User //
        Route::resource('user', UserController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        // Artist //
        Route::resource('artist', ArtistController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        // Album //
        Route::resource('album', AlbumController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        // Category //
        Route::resource('category', CategoryController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        // Video //
        Route::resource('video', VideoController::class)->only(['create', 'store', 'edit', 'update', 'show', 'destroy']);
        Route::post('/video/approve', [VideoController::class, 'approvevideo'])->name('video.approvevideo');
        Route::resource('audio', AudioController::class)->only(['create', 'store', 'edit', 'update', 'show', 'destroy']);
        Route::post('/audio/approve', [AudioController::class, 'approveaudio'])->name('audio.approveaudio');
        // AI Audio Book
        Route::resource('aiaudiobook', AiaudiobookController::class)->only(['create', 'store', 'edit', 'update', 'show', 'destroy']);
        Route::post('/aiaudiobook/getconvertedaudio', [AiaudiobookController::class, 'getconvertedaudio'])->name('aiaudiobook.getconvertedaudio');
        

        Route::any('saveChunk', [VideoController::class, 'saveChunk']);

        // Language //
        Route::resource('language', LanguageController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        // Package
        Route::resource('package', PackageController::class)->only(['create', 'store', 'edit', 'update', 'show', 'destroy']);
        // Page //
        Route::resource('page', PageController::class)->only(['create', 'store', 'edit', 'update', 'show']);
        // Notification //
        Route::resource('notification', NotificationController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::get('notifications/setting', [NotificationController::class, 'setting'])->name('notification.setting');
        Route::post('notifications/setting', [NotificationController::class, 'settingsave'])->name('notification.settingsave');
        // Payment
        Route::resource('payment', PaymentController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        
        Route::any('search_user', [UserController::class, 'searchUser'])->name('searchUser');
       
    });
    
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('multi/language/{id}', [DashboardController::class,'language'])->name('language');
    //  User  //
    Route::resource('admins', AdminController::class)->only(['index']);
    Route::resource('user', UserController::class)->only(['index']);
    // Category //
    Route::resource('category', CategoryController::class)->only(['index']);
    // Artist //
    Route::resource('artist', ArtistController::class)->only(['index']);
    // Album //
    Route::resource('album', AlbumController::class)->only(['index']);
    // Video //
    Route::resource('video', VideoController::class)->only(['index']);
    Route::resource('audio', AudioController::class)->only(['index']);
    Route::resource('aiaudiobook', AiaudiobookController::class)->only(['index']);
    Route::get('video/details/{id}', [VideoController::class,'detail'])->name('videoDetail');
    Route::get('audio/details/{id}', [AudioController::class,'detail'])->name('audioDetail');
    Route::get('aiaudiobook/details/{id}', [AiaudiobookController::class,'detail'])->name('aiAudioBookDetail');
    // Language //
    Route::resource('language', LanguageController::class)->only(['index']);

    // Package
    Route::resource('package', PackageController::class)->only(['index']);
    // Page //
    Route::resource('page', PageController::class)->only(['index']);
    // Comment
    Route::resource('comment', CommentController::class);
    // Notification
     Route::resource('notification', NotificationController::class)->only(['index']);
    // Transaction //
    Route::resource('transaction', TransactionController::class);
    Route::resource('aiaudio_transaction', AiaudioTransactionController::class);
    Route::get('aiaudio_transaction/invoice/{id}', [AiaudioTransactionController::class,'aiaudio_invoice']);
    // Setting //
    
    Route::get('setting', [SettingController::class, 'index'])->name('setting');
    Route::post('setting/app', [SettingController::class, 'app'])->name('settingapp');
    Route::post('setting/currency', [SettingController::class, 'currency'])->name('settingcurrency');
    Route::post('setting/changepassword', [SettingController::class, 'changepassword'])->name('settingchangepassword');
    Route::post('setting/admob', [SettingController::class, 'admob_android'])->name('settingadmob_android');
    Route::post('setting/admob-ios', [SettingController::class, 'admob_ios'])->name('settingadmob_ios');
    Route::post('setting/facebookad', [SettingController::class, 'facebookad'])->name('settingfacebookad');
    Route::post('setting/facebookad-ios', [SettingController::class, 'facebookad_ios'])->name('settingfacebookad_ios');
    // Setting (SMTP)
    Route::get('setting/smtp', [SettingController::class, 'smtpindex'])->name('settingsmtpindex');
    Route::post('setting/smtp', [SettingController::class, 'smtp'])->name('settingsmtp');
    Route::any('check_smtp', [SettingController::class,'check_smtp'])->name('check_smtp');

    // Payment
    Route::resource('payment', PaymentController::class)->only(['index']);

});

