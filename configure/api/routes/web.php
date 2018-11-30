<?php
/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */
use App\Constants\ErrorCodeConst;
use App\Constants\CommonConst;

Route::get('login', 'LoginController@index');
Route::post(CommonConst::APP_LOGIN, 'LoginController@doLogin');
Route::get(CommonConst::APP_APPROVAL, 'RegisterUserController@doApproval');
Route::get(CommonConst::APP_SEND_PASS, 'ForgotPasswordController@index');
Route::post(CommonConst::APP_SEND_PASS, 'ForgotPasswordController@doSendPass');

Route::get(CommonConst::APP_REGIST, 'RegisterUserController@index');
Route::post(CommonConst::APP_REGIST, 'RegisterUserController@doRegist')->middleware('csrf');

Route::match([
    'get',
    'post'
], CommonConst::APP . CommonConst::APP_CHECK . '/{pass_query}', 'MaintainAppController@doAppCheck')->middleware('csrf');
Route::group([
    'middleware' => [
        'web',
        'guest'
    ]
], function () {
    Route::match([
        'get',
        'post'
    ], CommonConst::APP_MYPAGE, 'MypageController@createMyPage');
    Route::post(CommonConst::APP_CHANGE, 'ChangeUserController@doChange')->middleware('csrf');
    Route::post(CommonConst::APP_UNSUBSCRIBE, 'UnsubscribeController@doUnsub')->middleware('csrf');
    Route::post(CommonConst::APP . CommonConst::APP_DELETE, 'MaintainAppController@doAppDelete')->middleware('csrf');
    Route::post(CommonConst::APP . CommonConst::APP_REGIST, 'RegisterAppController@doAppRegist')->middleware('csrf');
    Route::post(CommonConst::APP . CommonConst::APP_CHANGE, 'ChangeAppController@doAppChange')->middleware('csrf');
});

Route::any('{undefined_route}', function () {
    return view('error', [
        'msg' => ErrorCodeConst::ERR_UNDEFINED_ROUTE,
        'title' => ErrorCodeConst::ERR_TITLE_00
    ]);
})->where('undefined_route', '.*');
