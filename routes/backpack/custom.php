<?php

use App\Http\Controllers\Admin\GradeCrudController;
use App\Http\Controllers\Admin\LogCrudController;
use App\Http\Controllers\Admin\StaffCrudController;
use App\Http\Controllers\Admin\StudentCrudController;
use App\Http\Controllers\Admin\TeacherCrudController;
use App\Http\Controllers\Admin\TimeCrudController;
use App\Http\Controllers\Admin\UserCrudController;
use App\Http\Controllers\Api\SlackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;
use App\Models\Log;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array)config('backpack.base.web_middleware', 'web'),
        (array)config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('grade', 'GradeCrudController');
    Route::get('meet/{id}', [GradeCrudController::class,"meeting","id"]);
    Route::crud('log', 'LogCrudController');
//    Route::post("slack/send", [SlackController::class, "send"])->name("slack-send");
//    Route::get("slack", [SlackController::class, "show"])->name("slack-show");
    Route::get("/files", [UserCrudController::class, "file"])->name("user.file");
    Route::get('log/detail/{id}', [LogCrudController::class, "detail"])->name("admin.log.detail");
    Route::get('log/report/{id}', function ($id) {
        return view("report", ['log' => Log::find($id)]);
    })->name("admin.log.report");
    Route::post('log/exercise/', [LogCrudController::class, "acceptByStudent"])->name("admin.log.accept");
    Route::crud('exercise', 'ExerciseCrudController');
    Route::crud('comment', 'CommentCrudController');
    Route::post('comment/push', 'CommentCrudController@store')->name("admin.comment.store");
    Route::crud('student', 'StudentCrudController');
    Route::get("student/detail/{id}", [StudentCrudController::class, "detail"])->name("admin.student.detail");
    Route::crud('teacher', 'TeacherCrudController');
    Route::get("teacher/detail/{id}", [TeacherCrudController::class, "detail"])->name("admin.teacher.detail");
    Route::crud('client', 'ClientCrudController');
    Route::crud('customer', 'CustomerCrudController');
    Route::get('customer/switcher/{id}', 'CustomerCrudController@switcher')->name("admin.customer.switch");
    Route::crud('staff', 'StaffCrudController');
    Route::get("staff/detail/{id}", [StaffCrudController::class, "detail"])->name("admin.staff.detail");
    Route::crud('lesson', 'LessonCrudController');
    Route::get("lesson/detail/{id}", [\App\Http\Controllers\Admin\LessonCrudController::class, "detail"])->name("admin.lesson.detail");
    Route::crud('book', 'BookCrudController');
    Route::get('book/index', [\App\Http\Controllers\Admin\BookCrudController::class, "index"]);
    Route::crud('teaching', 'TeachingCrudController');
    Route::crud('notification', 'NotificationCrudController');
    Route::crud('time', 'TimeCrudController');
    Route::get("/time/show/{id}", [TimeCrudController::class, "showDetail", "id"])->name("time-show");
    Route::post("/time/update/", [TimeCrudController::class, "update"])->name("update-time");
    Route::crud('device', 'DeviceCrudController');
    Route::get("/manager/send-notification", [NotificationController::class, "index"])->name("manager.camp");
    Route::post("/manager/send-notification", [NotificationController::class, "send"])->name("manager.send");
    Route::get("/maintain", [SettingController::class, "maintainList"]);
    Route::get("/maintain/switch", [SettingController::class, "switchMaintain"])->name("maintain");
    Route::get("/maintaining", [SettingController::class, "maintaining"])->name("maintaining");
    Route::get("/account/info", [SettingController::class, "userAccount"])->name("account-info");
    Route::crud('post', 'PostCrudController');
    Route::crud('menu', 'MenuCrudController');
    Route::crud('demo', 'DemoCrudController');
    Route::crud('partner', 'PartnerCrudController');
}); // this should be the absolute last line of this file