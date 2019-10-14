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

// AUTH
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'API\UserController@login');
    Route::post('register', 'API\UserController@register');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
    	// LOGOUT
        Route::get('logout', 'API\UserController@logout');
    });
});

// AUTHENTICATED API
Route::group([
  'middleware' => 'auth:api'
], function() {
	// ROBOT CRUD
	Route::get('robots', 'API\RobotController@getRobots');
	Route::post('robots', 'API\RobotController@create');
	Route::put('robots/{id}', 'API\RobotController@update');
	Route::delete('robots/{id}', 'API\RobotController@delete');
	// CSV IMPORT
	Route::post('robots/import', 'API\ImportController@import');
	// FIGHT API
	Route::post('fight', 'API\FightController@fight');
});

// UNAUTHENTICATED API (FOR GUEST VIEW)
//Route::get('robots/all', 'API\RobotController@getAll'); // comment out to view all robots
Route::get('robots/ranking', 'API\RobotController@getTopRobots');
Route::get('fights', 'API\FightController@getLatestRobotFights');



