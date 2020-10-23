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

//Route::get('/', ['as' => 'admin.index', 'uses' => 'Admin\AdminController@index']);
//Route::get('/viewClone', ['as' => 'admin.clone-facebook.cloneFacebook', 'uses' => 'Admin\CloneFacebookController@index']);
//Route::get('/viewAction/{uid}', ['as' => 'admin.action-facebook.ActionFacebook', 'uses' => 'Admin\ActionFacebookController@viewAction']);
//
//Route::get('/login', ['as' => 'admin.login', 'uses' => 'Admin\AdminController@login']);
//Route::post('/login', ['as' => 'admin.login', 'uses' => 'Admin\AdminController@login']);
Route::any('{all}', function(){
    return [];
})->where('all', '.*');