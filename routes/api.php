<?php

use App\Http\Controllers\Api\GetPrivateKey;
use App\Http\Controllers\Api\GradeCrudController;
use App\Http\Controllers\Api\LoginController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});
Route::get("app/grade", [GradeCrudController::class, "index"])->name("app.grade");
Route::get("app/filter", [GradeCrudController::class, "filter"])->name("app.grade.filter");
Route::post("/private", [GetPrivateKey::class, "getKey"]);
Route::get("/app/login", [LoginController::class, "login"]);
