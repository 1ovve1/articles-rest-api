<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\UserAuthController;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\RubricController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('/',  [UserAuthController::class, 'index'])
        ->middleware('auth:sanctum');
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
});

Route::apiResources([
    'authors' => AuthorController::class,
    'rubrics' => RubricController::class,
    'articles' => ArticleController::class
]);
