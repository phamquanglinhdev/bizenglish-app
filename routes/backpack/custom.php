<?php

use App\Http\Controllers\Admin\LogCrudController;
use App\Http\Controllers\Admin\StudentCrudController;
use App\Http\Controllers\Admin\TeacherCrudController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('grade', 'GradeCrudController');
    Route::crud('log', 'LogCrudController');
    Route::get('log/detail/{id}', [LogCrudController::class,"detail"])->name("admin.log.detail");
    Route::crud('exercise', 'ExerciseCrudController');
    Route::crud('comment', 'CommentCrudController');
    Route::post('comment/push', 'CommentCrudController@store')->name("admin.comment.store");
    Route::crud('student', 'StudentCrudController');
    Route::get("student/detail/{id}",[StudentCrudController::class,"detail"])->name("admin.student.detail");
    Route::crud('teacher', 'TeacherCrudController');
    Route::get("teacher/detail/{id}",[TeacherCrudController::class,"detail"])->name("admin.teacher.detail");
    Route::crud('client', 'ClientCrudController');
}); // this should be the absolute last line of this file
