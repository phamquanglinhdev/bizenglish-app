<?php

use App\Http\Controllers\Api\GetPrivateKey;
use App\Http\Controllers\Api\GradeCrudController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\GradeController;
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
    Route::get("/userData", [GradeController::class, "getUserData"]);
    Route::post("/grade/store", [GradeController::class, "storeNewGrade"]);
    Route::post("/grade/update", [GradeController::class, "updateGrade"]);
    Route::post("/grades", [GradeController::class, "getGrades"]);
    Route::get("/grade/delete/{id}", [GradeController::class, "deleteGrade"]);
    Route::get("/grade/{id}", [GradeController::class, "getGrade", "id"]);
});
Route::get("/app/login", [LoginController::class, "login"]);

