<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\TvShowController;


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

    Route::post('logout',[AuthController::class, "logout"]);
    Route::get('user' ,[AuthController::class, "user"]);

    //movie
    Route::get('movie' ,[MoviesController::class, "movieShow"]);
    Route::post('addactormovie' ,[MoviesController::class, "addActorMovie"]);
    Route::post('moviecreate' ,[MoviesController::class, "movieCreate"]);

    //actors and directors
    Route::get('director', [OrganizationController::class, "directors"]);
    Route::get('actor', [OrganizationController::class, "actors"]);
    Route::post('directorcreate' ,[OrganizationController::class, "directorCreate"]);
    Route::post('actorcreate' ,[OrganizationController::class, "actorCreate"]);

    //tvshow
    Route::get('tvshow' ,[TvShowController::class, "tvShow"]);
    Route::post('addactortv' ,[TvShowController::class, "addActorTvShow"]);
    Route::post('tvshowcreate' ,[TvShowController::class, "tvShowCreate"]);

    //episodes
    Route::post('addepisodes' ,[TvShowController::class, "episodesCreate"]);
    Route::get('episode' ,[TvShowController::class, "episodes"]);
});
