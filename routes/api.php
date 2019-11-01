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

//login
Route::post('/login', 'AuthController@login');

//logout
Route::middleware('auth:api')->get('/logout', 'AuthController@logout');

//Team test hasOne relation
Route::middleware('auth:api')->get('/team', "AuthController@team");

//Create Team
Route::middleware('auth:api')->post('/create_team', 'AuthController@create_team');

//Get all players from DB
Route::middleware('auth:api')->get('/get_players', 'AuthController@getPlayers');

//saves players to users team
Route::middleware('auth:api')->post('/edit_players', 'AuthController@edit_players');

//retrieves players from users team
Route::middleware('auth:api')->get('/retrieve_players', 'AuthController@retrieve_players');



