<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\RatingController;

 
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// ---------------- UsersController ----------------

Route::post('login', [UserController::class, 'Login'])->name('user-login');
Route::post('registration', [UserController::class, 'Registration'])->name('user-registration');

Route::post('get_profile', [UserController::class, 'Get_Profile'])->name('get-profile');
Route::post('update_profile', [UserController::class, 'Upadte_Profile'])->name('update-profile');

Route::post('send_mail', [UserController::class, 'send_mail'])->name('send_mail');

// ----------------- HomeController --------------------
Route::post('general_setting', [HomeController::class, 'GeneralSetting'])->name('general-setting');
Route::post('get_pages', [HomeController::class, 'Get_pages'])->name('Get_pages');

Route::post('user_list', [HomeController::class, 'User_List'])->name('user-list');

Route::post('get_category', [HomeController::class, 'Get_Category'])->name('category-list');

Route::post('get_language', [HomeController::class, 'Get_Language'])->name('language-list');

Route::post('get_package', [HomeController::class, 'Get_Package'])->name('package-list');
Route::post('subscription_list', [HomeController::class, 'subscription_list'])->name('subscription_list');


Route::post('latest_audio', [HomeController::class, 'latest_audio'])->name('latest_audio');
Route::post('audio_list', [HomeController::class, 'audio_list'])->name('audio_list');

Route::post('ai_audio_book_list', [HomeController::class, 'ai_audio_book_list'])->name('ai_audio_book_list');

Route::post('audio_by_id', [HomeController::class, 'audio_by_id'])->name('audio_by_id');

Route::post('latest_video', [HomeController::class, 'latest_video'])->name('latest_video');
Route::post('video_list', [HomeController::class, 'video_list'])->name('video_list');
Route::post('video_by_id', [HomeController::class, 'video_by_id'])->name('video_by_id');

Route::post('most_view_video', [HomeController::class, 'Most_viewed_Video'])->name('Most_viewed_Propertie');
Route::post('premium_video', [HomeController::class, 'Premium_Video'])->name('premium_video');

Route::post('video_by_category', [HomeController::class, 'Video_by_category'])->name('video-by-category');
Route::post('video_by_artist', [HomeController::class, 'Video_by_artist'])->name('Video_by_artist');
Route::post('artist_profile', [HomeController::class, 'artist_profile'])->name('artist_profile');

Route::post('search_video', [HomeController::class, 'Search_Video'])->name('Search_Video');

Route::post('get_artist', [HomeController::class, 'Get_Artist'])->name('Get_Artist');
Route::post('get_album', [HomeController::class, 'Get_Album'])->name('Get_Album');
Route::post('get_album_by_video', [HomeController::class, 'get_album_by_video'])->name('Get_Album_By_Video');

Route::post('get_payment_option', [HomeController::class, 'Get_payment_option'])->name('Get_payment_option');

Route::post('get_notification', [HomeController::class, 'Get_Notification'])->name('get-notification');
Route::post('read_notification', [HomeController::class, 'Read_Notification'])->name('read-notification');

Route::post('add_transaction', [HomeController::class, 'Add_transaction'])->name('Add_transaction');
Route::post('add_aiaudio_transaction', [HomeController::class, 'Add_Aiaudio_transaction'])->name('Add_Aiaudio_transaction');
Route::post('get_like_video', [HomeController::class, 'get_like_video'])->name('get_like_video');
Route::post('get_comment_video', [HomeController::class, 'get_comment_video'])->name('get_comment_video');

Route::post('get_category_list', [HomeController::class, 'get_category_list'])->name('get_category_list');
Route::post('get_artist_list', [HomeController::class, 'get_artist_list'])->name('get_artist_list');
// ------------------------- RatingCotroller ----------------------------


Route::post('edit_comment', [RatingController::class, 'edit_comment'])->name('edit_comment');
Route::post('delete_comment', [RatingController::class, 'delete_comment'])->name('delete_comment');


Route::post('follow', [RatingController::class, 'follow']);
Route::post('follow_videos', [RatingController::class, 'follow_videos']);
Route::post('following_list', [RatingController::class, 'following_list']);
Route::post('follow_list', [RatingController::class, 'follow_list']);

Route::post('add_remove_download', [RatingController::class, 'addRemoveDownload']);
Route::post('get_download_video', [RatingController::class, 'getDownloadVideo']);
Route::post('upload_audio', [HomeController::class, 'uploadAudio']);
Route::post('upload_video', [HomeController::class, 'uploadVideo']);


// New Integration
Route::post('premium_audio', [HomeController::class, 'Premium_Audio'])->name('premium_audio');
Route::post('premium_ai_audio', [HomeController::class, 'Premium_AI_Audio'])->name('premium_ai_audio');

Route::post('most_view_audio', [HomeController::class, 'Most_Viewed_Audio'])->name('most_view_audio');
Route::post('most_view_ai_audio', [HomeController::class, 'Most_Viewed_AI_Audio'])->name('most_view_ai_audio');

Route::post('audio_by_artist', [HomeController::class, 'Audio_by_artist'])->name('audio_by_artist');
Route::post('ai_audio_by_artist', [HomeController::class, 'AI_Audio_by_artist'])->name('ai_audio_by_artist');

Route::post('search_audio', [HomeController::class, 'Search_Audio'])->name('search_audio');
Route::post('search_ai_audio', [HomeController::class, 'Search_AI_Audio'])->name('search_ai_audio');

Route::post('ai_audio_by_id', [HomeController::class, 'ai_audio_by_id'])->name('ai_audio_by_id');

Route::post('audio_by_category', [HomeController::class, 'Audio_by_category'])->name('audio_by_category');
Route::post('ai_audio_by_category', [HomeController::class, 'AI_Audio_by_category'])->name('ai_audio_by_category');

Route::post('get_album_by_audio', [HomeController::class, 'Get_Album_By_Audio'])->name('get_album_by_audio');
Route::post('get_album_by_ai_audio', [HomeController::class, 'Get_Album_By_AI_Audio'])->name('get_album_by_ai_audio');

Route::post('add_favorite', [RatingController::class, 'add_favorite']);
Route::post('favorite_list', [RatingController::class, 'favorite_list']);

Route::post('like_dislike', [RatingController::class, 'like_dislike'])->name('like_dislike');
Route::post('get_like_audio', [HomeController::class, 'get_like_audio'])->name('get_like_audio');
Route::post('get_like_ai_audio', [HomeController::class, 'get_like_ai_audio'])->name('get_like_ai_audio');

Route::post('add_comment', [RatingController::class, 'add_comment'])->name('add_comment');
Route::post('view_comment', [RatingController::class, 'view_comment'])->name('view_comment');
Route::post('add_view', [RatingController::class, 'add_view'])->name('add_view');