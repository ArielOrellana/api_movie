<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MoviesController;

/*use App\Http\Controllers\UserController;*/

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

Route::post('register',[AuthController::class, "register"]);
Route::post('login',[AuthController::class, "login"]);

Route::middleware('jwt.verify')->group(function(){
    /* test JWT */
    /*Route::get('user' ,[UserController::class, "index"]);*/
    Route::post('logout',[AuthController::class, "logout"]);
    Route::get('user' ,[AuthController::class, "user"]);

    //movie
    Route::get('movie/{name}' ,[MoviesController::class, "movieShow"]);
    Route::get('movie' ,[MoviesController::class, "movieShow"]);
    Route::post('addactormovie' ,[MoviesController::class, "addActorMovie"]);
    Route::post('moviecreate' ,[MoviesController::class, "movieCreate"]);

    //actors and directors

});
