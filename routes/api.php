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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('auth', 'UserController@login');
Route::post('register', 'UserController@register');

Route::group(['middleware' => ['jwt.verify']], function(){
	Route::get('/user', 'UserController@index');
	Route::get('/user/{id}', 'UserController@show');
	Route::put('/user/{id}', 'UserController@edit');
	Route::delete('/user/{id}', 'UserController@destroy');

	Route::get('/project', 'ProjectController@index');
	Route::post('/project', 'ProjectController@store');
	Route::get('/project/{id}', 'ProjectController@show');
	Route::put('/project/{id}', 'ProjectController@edit');
	Route::delete('/project/{id}', 'ProjectController@destroy');

	Route::get('/task', 'TaskController@index');
	Route::post('/task', 'TaskController@store');
	Route::get('/task/{id}', 'TaskController@show');
	Route::put('/task/{id}', 'TaskController@edit');
	Route::post('/task/{id}/pull', 'TaskController@pull');
	Route::post('/task/{id}/finish', 'TaskController@finish');
	Route::delete('/task/{id}', 'TaskController@destroy');
});
