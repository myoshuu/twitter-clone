<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\TweetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
  Route::post('login', 'login');
  Route::post('register', 'register');
  Route::put('profile/{id}', 'update')->middleware('auth');
  Route::get('me', 'me')->middleware('auth');
  Route::post('logout', 'logout')->middleware('auth');
});

Route::group(['middleware' => 'auth'], function () {
  Route::apiResource('tweet', TweetController::class);
  Route::post('tweet/filter', [FilterController::class, 'filter']);
  Route::apiResource('comment', CommentController::class);
});
