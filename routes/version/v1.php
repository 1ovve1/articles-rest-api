<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AuthorArticles;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\PublicationController;
use App\Http\Controllers\Api\V1\RubricController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('/',  [AuthController::class, 'index'])->name('user');
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');
    Route::post('/register', [AuthController::class, 'register'])->name('user.register');
});

Route::apiResources([
    'authors' => AuthorController::class,
    'rubrics' => RubricController::class,
    'articles' => ArticleController::class
]);

Route::apiResource('authors.articles', AuthorArticles::class)
    ->only('index');

Route::apiResource('publications', PublicationController::class)
    ->except('update');
