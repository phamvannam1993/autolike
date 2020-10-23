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
Route::post('/InsertUser', ['as' => 'friend.save', 'uses' => 'Api\UserController@InsertUser']);
Route::post('/SignUp', ['as' => 'friend.save', 'uses' => 'Api\UserController@SignUp']);
Route::post('/checkNumber', ['as' => 'friend.save', 'uses' => 'Api\UserController@checkNumber']);
Route::post('/getUser', ['as' => 'friend.save', 'uses' => 'Api\UserController@getUser']);
Route::post('/checkToken', ['as' => 'friend.save', 'uses' => 'Api\UserController@checkToken']);
Route::post('/checkLogin', ['as' => 'friend.save', 'uses' => 'Api\UserController@checkLogin']);
Route::post('/LoginAdmin', ['as' => 'friend.save', 'uses' => 'Api\UserController@LoginAdmin']);
Route::post('/DeleteUser', ['as' => 'friend.save', 'uses' => 'Api\UserController@DeleteUser']);
Route::post('/UpdateBalance', ['as' => 'friend.save', 'uses' => 'Api\UserController@UpdateBalance']);
Route::post('/InsertSetting', ['as' => 'friend.save', 'uses' => 'Api\UserController@InsertSetting']);
Route::post('/getSetting', ['as' => 'friend.save', 'uses' => 'Api\UserController@getSetting']);
Route::post('/ResetPassword', ['as' => 'friend.save', 'uses' => 'Api\UserController@ResetPassword']);
Route::post('/checkBalance', ['as' => 'friend.save', 'uses' => 'Api\UserController@checkBalance']);

Route::post('/InsertGiftCode', ['as' => 'friend.save', 'uses' => 'Api\GiftCodeController@InsertGiftCode']);
Route::post('/applyGiftCode', ['as' => 'friend.save', 'uses' => 'Api\GiftCodeController@applyGiftCode']);
Route::post('/GetGiftCode', ['as' => 'friend.save', 'uses' => 'Api\GiftCodeController@GetGiftCode']);
Route::post('/GetGiftCodeUsed', ['as' => 'friend.save', 'uses' => 'Api\GiftCodeController@GetGiftCodeUsed']);
Route::post('/DeleteGiftCode', ['as' => 'friend.save', 'uses' => 'Api\GiftCodeController@DeleteGiftCode']);


Route::post('/InsertPackage', ['as' => 'friend.save', 'uses' => 'Api\PackageController@InsertPackage']);
Route::post('/GetPackage', ['as' => 'friend.save', 'uses' => 'Api\PackageController@GetPackage']);
Route::post('/DeletePackage', ['as' => 'friend.save', 'uses' => 'Api\PackageController@DeletePackage']);


Route::post('/getServiceLog', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@getServiceLog']);
Route::post('/InsertService', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@InsertService']);
Route::post('/ServiceUpdate', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@ServiceUpdate']);
Route::post('/getService', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@getService']);
Route::post('/ApiService', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@ApiService']);
Route::post('/ApiServiceUpdate', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@ApiServiceUpdate']);
Route::post('/AddViplikeService', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@AddVipikeService']);

Route::post('/InsertTransaction', ['as' => 'friend.save', 'uses' => 'Api\TransactionController@InsertTransaction']);
Route::post('/GetTransaction', ['as' => 'friend.save', 'uses' => 'Api\TransactionController@GetTransaction']);
Route::post('/DeleteTransaction', ['as' => 'friend.save', 'uses' => 'Api\TransactionController@DeleteTransaction']);
Route::post('/UpdateTransaction', ['as' => 'friend.save', 'uses' => 'Api\TransactionController@UpdateTransaction']);

Route::get('/updateAllService', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@updateAllService']);
Route::post('/apiGetListService', ['as' => 'friend.save', 'uses' => 'Api\UserController@apiGetListService']);
Route::post('/apiUpdateService', ['as' => 'friend.save', 'uses' => 'Api\UserController@apiUpdateService']);

Route::get('/updateServiceDay', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@updateServiceDay']);
Route::get('/updateServiceSuccessDay', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@updateServiceSuccessDay']);
Route::get('/updateTotalDeposit', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@updateTotalDeposit']);
Route::post('/getReport', ['as' => 'friend.save', 'uses' => 'Api\ServiceController@getReport']);

Route::post('/HistoryTransaction', ['as' => 'friend.save', 'uses' => 'Api\UserController@HistoryTransaction']);

Route::post('/InsertComment', ['as' => 'comment.InsertComment', 'uses' => 'Api\ServiceController@InsertComment']);
Route::post('/GetComment', ['as' => 'comment.GetComment', 'uses' => 'Api\ServiceController@GetComment']);
Route::post('/GetCommentContent', ['as' => 'comment.GetCommentContent', 'uses' => 'Api\ServiceController@GetCommentContent']);
Route::post('/UpdateComment', ['as' => 'comment.UpdateComment', 'uses' => 'Api\ServiceController@UpdateComment']);
