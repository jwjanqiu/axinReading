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
});
/**
 * 登录
 */
Route::any('login', 'LoginController@login');

