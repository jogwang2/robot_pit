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

// ROBOT CRUD
Route::get('robots', 'API\RobotController@index');
Route::get('robots/{user_id}', 'API\RobotController@getAll');
Route::post('robots', 'API\RobotController@create');
Route::put('robots/{id}', 'API\RobotController@update');
Route::delete('robots/{id}', 'API\RobotController@delete');
// CSV IMPORT
Route::post('robots/import', 'API\RobotController@import');
// FIGHT API
Route::post('robots/fight', 'API\FightController@fight');


