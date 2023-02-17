<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Contributor\ContributorController;
use App\Http\Controllers\News\NewsCategoryController;
use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\News\NewsSyncController;
use App\Http\Controllers\Source\SourceController;
use App\Http\Middleware\EnsureIsAdmin;
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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'v1'], function () {

        Route::group(['prefix' => "auth"], function () {
            Route::post('/personalize', [AuthController::class, "updatePersonalize"]);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/update', [AuthController::class, 'update']);
        });

        Route::middleware([EnsureIsAdmin::class])->group(function () {
            Route::group(["prefix" => "/news"], function () {
                Route::get('/sync', [NewsSyncController::class, 'sync']);
            });
        });
        Route::get("/news/personalize", [NewsController::class, "personalize"]);
    });
});

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::group(['prefix' => '/news'], function () {
        Route::get('/', [NewsController::class, 'index']);
        Route::get('/search', [NewsController::class, 'search']);
        Route::get("/category", [NewsCategoryController::class, 'index']);
    });

    Route::group(["prefix" => "/contributor"], function () {
        Route::get('/', [ContributorController::class, 'index']);
        Route::get("/random", [ContributorController::class, "getRandomAuthor"]);
        Route::get("/{id}", [ContributorController::class, "findById"]);
    });

    Route::get("/source", [SourceController::class, "index"]);
});
