<?php

use App\http\Controllers\AuthorController;
use App\http\Controllers\BookController;
use App\http\Controllers\CourseController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LectureDetailsController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\VideoController;
;







use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::view('test', 'test');
Route::view('course', 'course');
Route::view('usertest', 'test2');
Route::view('lecture', 'lecture');
Route::view('advertisement', 'advertisement');
Route::view('book', 'book');
// Route::prefix('Books')->group(function () {

//
// });
Route::post('/store', [CourseController::class, 'store']);
// Route::get('/destroy/{id}', [CourseController::class, 'destroy']);
// Route::put('update/{id}',[VideoController::class,'update']);
// Route::post('/store',[BookController::class,'store']);
// Route::put('update/{id}',[BookController::class,'update']);
Route::get('/index', [BookController::class, 'index']);
Route::get('/awad/{id}', [BookController::class, 'show']);
Route::get('/author', [AuthorController::class, 'index']);
// Route::post('/store', [CourseController::class, 'store']);
Route::get('/course/{id}', [CourseController::class, 'show']);
// Route::post('/store', [AuthController::class, 'register']);
Route::get('/user/{id}', [UserController::class, 'show']);
// Route::post('/store', [ProjectController::class, 'store']);
// Route::put('/u/{n}',[ProjectController::class,'update']);
// Route::post('/store', [LectureController::class, 'store']);
// Route::put('/update/{id}', [LectureController::class, 'update']);
Route::get('/lecture/{id}', [LectureController::class, 'show']);
Route::get('/subject/{id}', [SubjectController::class, 'show']);
// Route::post('/store', [LectureController::class, 'store']);





