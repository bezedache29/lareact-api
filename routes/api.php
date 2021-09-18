<?php

use App\Http\Controllers\AuthentificationController;
use App\Http\Controllers\PictureController;
use App\Http\Middleware\CreateImg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::resource('/pictures', PictureController::class)->only('index');
Route::resource('/pictures', PictureController::class)->middleware(CreateImg::class)->except('index');