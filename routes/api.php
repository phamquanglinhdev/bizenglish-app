<?php

use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\FileApiController;
use App\Http\Controllers\Api\GradeApiController;
use App\Http\Controllers\Api\LogApiController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\StaffApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\TeacherApiController;
use App\Http\Controllers\PushNotificationController;
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
    Route::prefix("/")->group(function () {
        Route::post("/grades", [GradeApiController::class, "index"]);
        Route::post("/grade/people", [GradeApiController::class, "people"]);
        Route::post("/grade/edit", [GradeApiController::class, "edit"]);
        Route::post("/grade/show", [GradeApiController::class, "show"]);
        Route::post("/grade/store", [GradeApiController::class, "store"]);
        Route::post("/grade/update", [GradeApiController::class, "update"]);
        Route::post("/grade/destroy", [GradeApiController::class, "destroy"]);
    });
    Route::prefix("/")->group(function () {
        Route::post("/logs", [LogApiController::class, "index"]);

        Route::post("/log/create", [LogApiController::class, "create"]);
        Route::post("/log/show", [LogApiController::class, "show"]);
        Route::post("/log/store", [LogApiController::class, "store"]);
        Route::post("/log/edit", [LogApiController::class, "edit"]);
        Route::post("/log/update", [LogApiController::class, "update"]);
        Route::post("/log/destroy", [LogApiController::class, "destroy"]);
    });
    Route::prefix("/")->group(function () {
        Route::post("/staffs", [StaffApiController::class, "index"]);
        Route::post("/staff/student", [StaffApiController::class, "student"]);
        Route::post("/staff/show", [StaffApiController::class, "show"]);
        Route::post("/staff/store", [StaffApiController::class, "store"]);
        Route::post("/staff/update", [StaffApiController::class, "update"]);
        Route::post("/staff/destroy", [StaffApiController::class, "destroy"]);
    });
    Route::prefix("/")->group(function () {
        Route::post("/teachers", [TeacherApiController::class, "index"]);
        Route::post("/teacher/show", [TeacherApiController::class, "show"]);
        Route::post("/teacher/store", [TeacherApiController::class, "store"]);
        Route::post("/teacher/update", [TeacherApiController::class, "update"]);
        Route::post("/teacher/destroy", [TeacherApiController::class, "destroy"]);
    });
    Route::prefix("/")->group(function () {
        Route::post("/students", [StudentApiController::class, "index"]);
        Route::post("/student/show", [StudentApiController::class, "show"]);
        Route::post("/student/store", [StudentApiController::class, "store"]);
        Route::post("/student/update", [StudentApiController::class, "update"]);
        Route::post("/student/destroy", [StudentApiController::class, "destroy"]);
    });
    Route::prefix("/")->group(function () {
        Route::post("/clients", [ClientApiController::class, "index"]);
        Route::post("/client/show", [ClientApiController::class, "show"]);
        Route::post("/client/store", [ClientApiController::class, "store"]);
        Route::post("/client/update", [ClientApiController::class, "update"]);
        Route::post("/client/destroy", [ClientApiController::class, "destroy"]);
    });
    Route::prefix("/")->group(function () {
        Route::post("/customers", [CustomerApiController::class, "index"]);
        Route::post("/customer/show", [CustomerApiController::class, "show"]);
        Route::post("/customer/store", [CustomerApiController::class, "store"]);
        Route::post("/customer/update", [CustomerApiController::class, "update"]);
        Route::post("/customer/destroy", [CustomerApiController::class, "destroy"]);
        Route::post("/customer/switch", [CustomerApiController::class, "switch"]);
    });
    Route::prefix("/")->group(function () {
        Route::post("/files", [FileApiController::class, "index"]);
        Route::post("/file/read", [FileApiController::class, "show"]);
    });
    Route::prefix("/")->group(function () {
        Route::post("/notifications", [NotificationApiController::class, "index"]);
        Route::post("/notification/show", [NotificationApiController::class, "show"]);
        Route::post("/checkin", [DeviceController::class, "register"]);
    });

});
Route::post("/login", [LoginController::class, "login"]);
Route::get("/testNotification", [PushNotificationController::class, "index"]);



