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

Route::get('home', 'HomeController@index');
Route::get('tour_detail', 'TourDetailController@index');
Route::get('hotel_detail', 'HotelDetailController@index');
Route::get('transportation_detail', 'TransportationDetailController@index');
Route::get('tour_list', 'ListToursController@index');
Route::get('hotel_list', 'ListHotelsController@index');
Route::get('transportation_list', 'ListTransportationController@index');

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
    Route::post(CommonConst::APP . CommonConst::APP_CHANGE, 'ChangeAppController@doAppChange')->middleware('csrf');
});

Route::any('{undefined_route}', function () {
    return view('error', [
        'msg' => ErrorCodeConst::ERR_UNDEFINED_ROUTE,
        'title' => ErrorCodeConst::ERR_TITLE_00
    ]);
})->where('undefined_route', '.*');
