<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ContestController;
use App\Http\Controllers\Api\EditableController;
use App\Http\Controllers\Api\LogApiController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect("admin");
});
Route::get('upload-file', function () {
    Storage::disk('google')->put('google-drive.txt', 'AHII');
    dd('Đã upload file lên google drive thành công!');
});
Route::get("/book/{slug}", [BookController::class, "show", "slug"])->name("show-book");
//Route::get("/auto/v1/{private}", [ApplicationController::class, "autoLogin", "private"]);
Route::get("/testSlacks", [TestController::class, "index"]);
Route::get("/language/set", function () {
    Cookie::queue("language", "1", 60 * 24 * 365);
    return redirect()->back();
})->name("english-version");
Route::get("/language/remove", function () {
    Cookie::queue("language", "1", -1);
    return redirect()->back();
})->name("main-version");

Route::get("/app/upload", function () {
    return view("upload");
});
Route::post("/log/upload", [LogApiController::class, "upload"])->name("uploadApp");
Route::post("/editable", [EditableController::class, "update"])->name("editable");
Route::get("/contest/play/{contest_id}/",[ContestController::class,"playRequest"])->name("play-contest");
