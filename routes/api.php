<?php

use Illuminate\Http\Request;
use App\Http\Middleware\ApiToken;
use App\Http\Middleware\CreateImg;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\AuthentificationController;

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

// Auth
Route::post('/register', [AuthentificationController::class, 'register']);
Route::post('/login', [AuthentificationController::class, 'login']);

// Photos
Route::get('/pictures', [PictureController::class, 'index']);
Route::post('/pictures/search', [PictureController::class, 'search']);


Route::middleware(ApiToken::class)->group(function() {

    // Route lister les articles que le user a liké
    Route::get('/pictures/search-liked-articles', [PictureController::class, 'likedArticles']);
    Route::post('/pictures/search-liked-articles', [PictureController::class, 'searchLikedArticles']);
    Route::get('/pictures/{picture}/users-liked-articles', [PictureController::class, 'usersLikedArticles']);

    // Photos
    Route::resource('/pictures', PictureController::class)->except('index', 'create', 'edit');
    
    Route::get('/pictures/{picture}/checklike', [PictureController::class, 'checkLike']);
    Route::get('/pictures/{picture}/handlelike', [PictureController::class, 'handleLike']);
});