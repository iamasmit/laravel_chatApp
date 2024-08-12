<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function()
{
    Route::controller(AuthController::class)->group(function(){
        Route::post('/login', 'Login');
        Route::post('/register', 'Register');
    });
});

Route::group(['prefix' => 'chat'], function()
{
    Route::controller(UserController::class)->group(function(){
        Route::get('/user-search', 'userSearch');
        // Route::post('/register', 'Register');
    });
    Route::resource('conversations', ConversationController::class);
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
    Route::get('/messages/{message}', [MessageController::class, 'markSeen']);
});