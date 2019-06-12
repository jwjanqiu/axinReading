<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'checkToken'], function () {
    /**
     * 获取书籍内容
     */
    Route::any('getReading', 'BookController@getReading');
    /**
     * 获取所有书籍
     */
    Route::any('getAllBook', 'BookController@getAllBook');
    /**
     * 获取书籍章节目录
     */
    Route::any('getCategory', 'BookController@getCategory');
    /**
     * 退出登录
     */
    Route::any('logout', 'LoginController@logout');
    /**
     * 修改用户信息
     */
    Route::any('modifyUserInfo', 'CustomerController@modifyUserInfo');
    /**
     * 书桌书籍
     */
    Route::any('desktop', 'BookController@desktop');
    /**.
     * 获取daily_beauty列表
     */
    Route::any('dailyBeautyList', 'LeadingController@getDailyBeauty');
    /**
     * daily_beauty详情
     */
    Route::any('getBeautyDetail', 'LeadingController@getBeautyDetail');
    /**
     * 添加新房租信息
     */
    Route::any('insertNewRent', 'CalRentController@insertNewRent');
    /**
     * 添加房租基础信息
     */
    Route::any('addRentBaseInfo', 'CalRentController@addRentBaseInfo');
    /**
     * 查询基础房租信息
     */
    Route::any('getRentBaseInfo', 'CalRentController@getRentBaseInfo');
    /**
     * 获取房租明细
     */
    Route::any('getAllRent', 'CalRentController@getAllRent');
    /**
     * 发送邮件
     */
    Route::any('sendEmail', 'EmailController@sendMail');
    /**
     * 获取定位信息
     */
    Route::any('getLocation', 'LocationController@getLocation');
});
/**
 * 登录
 */
Route::any('login', 'LoginController@login');
/**
 * 上传定位信息
 */
Route::any('uploadLocation', 'LocationController@uploadLocation');
/**
 * 小程序获取加密数据
 */
Route::any('wxDataDecrypt', 'WxAuth\WxDecryptController@wxDataDecrypt');
