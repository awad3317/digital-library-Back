<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BookController,
    CategoryController,
    AuthorController,
    PublisherController,
    AuthorBookController,
    ProgramController,
    CourseController,
    AuthController,
    UserController,
    TeamController,
    AcademicYearController,
    DepartmentController,
    TeamProjectController,
    ProjectController,
    LectureController,
    SubjectController,
    SubjectDepartmentController,
    LectureDetailsController,
    AcademicyearSubjectController,
    AdvertisementController,
    VideoController,
    SuperAdminController,
    SuggestionController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('/BooK')->group(function(){
    Route::get('/',[BookController::class,'index']);
    Route::post('/store',[BookController::class,'store']);
    Route::get('/show/{id}',[BookController::class,'show']);
    Route::get('/edit/{id}',[BookController::class,'edit'])->middleware(['auth','CheckAdmin']);
    Route::put('/update/{id}',[BookController::class,'update'])->middleware(['auth','CheckAdmin']);
    Route::delete('/destroy/{id}',[BookController::class,'destroy'])->middleware(['auth','CheckAdmin']);
});


Route::prefix('/Program')->group(function(){
    Route::get('/',[ProgramController::class,'index']);
    Route::post('/store',[ProgramController::class,'store']);
    Route::get('/show/{id}',[ProgramController::class,'show']);
    Route::get('/edit/{id}',[ProgramController::class,'edit'])->middleware(['auth','CheckAdmin']);
    Route::put('/update/{id}',[ProgramController::class,'update'])->middleware(['auth','CheckAdmin']);
    Route::delete('/destroy/{id}',[ProgramController::class,'destroy'])->middleware(['auth','CheckAdmin']);
});


Route::prefix('/Course')->group(function(){
    Route::get('/',[CourseController::class,'index']);
    Route::post('/store',[CourseController::class,'store']);
    Route::get('/show/{id}',[CourseController::class,'show']);
    Route::get('/edit/{id}',[CourseController::class,'edit'])->middleware(['auth','CheckAdmin']);
    Route::put('/update/{id}',[CourseController::class,'update'])->middleware(['auth','CheckAdmin']);
    Route::delete('/destroy/{id}',[CourseController::class,'destroy'])->middleware(['auth','CheckAdmin']);
    Route::get('/DeleteZip/{zip_file}',[CourseController::class,'Delete_zip_file']);
});

Route::prefix('/Video')->group(function(){
    Route::post('/store/{id}',[VideoController::class,'store']);
    Route::put('/update/{id}',[VideoController::class,'update'])->middleware(['auth','CheckAdmin']);
    Route::delete('/destroy/{id}',[VideoController::class,'destroy'])->middleware(['auth','CheckAdmin']);
});

Route::prefix('/Category')->group(function(){
    Route::get('/',[CategoryController::class,'index']);
    Route::post('/store',[CategoryController::class,'store'])->middleware(['auth','CheckAdmin']);
    Route::put('/update/{id}',[CategoryController::class,'update'])->middleware(['auth','CheckAdmin']);
    Route::delete('/destroy/{id}',[CategoryController::class,'destroy'])->middleware(['auth','CheckAdmin']);
});


Route::prefix('/Publisher')->group(function(){
    Route::get('/',[PublisherController::class,'index']);
    Route::post('/store',[PublisherController::class,'store']);
});


Route::prefix('/Author')->group(function(){
    Route::get('/',[AuthorController::class,'index']);
    Route::post('/store',[AuthorController::class,'store']);
});


Route::prefix('/AuthorBook')->group(function(){
    Route::post('/store',[AuthorBookController::class,'store']);
});


Route::prefix('/')->group(function(){
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('Logout',[AuthController::class,'Logout'])->middleware(['auth','CheckToken']);
});


Route::prefix('/SuperAdmin')->group(function(){
    Route::delete('/deleteAdmin/{id}',[SuperAdminController::class,'DeleteAdmin'])->middleware(['auth','CheckSuperAdmin']);
    Route::put('/updateAdmin/{id}',[SuperAdminController::class,'UpdateAdmin'])->middleware(['auth','CheckSuperAdmin']);
});


Route::prefix('/Users')->group(function(){
    Route::get('/',[UserController::class,'index']);
    Route::get('/show/{id}',[UserController::class,'show'])->middleware(['auth','CheckAdmin']);
    Route::post('/edit/{id}',[UserController::class,'edit'])->middleware(['auth','CheckAdmin']);
    Route::put('/update',[UserController::class,'update'])->middleware(['auth','CheckToken']);
    Route::delete('/destroy/{id}',[UserController::class,'destroy'])->middleware(['auth','CheckAdmin']);
    Route::post('/changePassword',[UserController::class,'changePassword'])->middleware(['auth','CheckToken']);
    Route::post('/change_Password/{id}',[UserController::class,'change_Password_by_admin'])->middleware(['auth']);
    Route::put('/active_user/{id}',[UserController::class,'active_user'])->middleware(['auth','CheckAdmin']);
});


Route::prefix('/Team')->group(function(){
    Route::get('/',[TeamController::class,'index'])->middleware(['auth','CheckAdmin']);
    Route::post('/store',[TeamController::class,'store'])->middleware(['auth','CheckAdmin']);
});


Route::prefix('/AcademicYear')->group(function(){
    Route::get('/',[AcademicYearController::class,'index']);
    Route::post('/store',[AcademicYearController::class,'store'])->middleware(['auth','CheckToken']);
    Route::put('/update/{id}',[AcademicYearController::class,'update'])->middleware(['auth','CheckAdmin']);
    Route::delete('/destroy/{id}',[AcademicYearController::class,'destroy'])->middleware(['auth','CheckAdmin']);
});


Route::prefix('/Department')->group(function(){
    Route::get('/',[DepartmentController::class,'index']);
    Route::post('/store',[DepartmentController::class,'store'])->middleware(['auth','CheckToken']);
    Route::get('/edit/{id}',[DepartmentController::class,'edit'])->middleware(['auth','CheckAdmin']);
    Route::put('/update/{id}',[DepartmentController::class,'update'])->middleware(['auth','CheckAdmin']);
    Route::delete('/destroy/{id}',[DepartmentController::class,'destroy'])->middleware(['auth','CheckAdmin']);
});


Route::prefix('/TeamProject')->group(function(){
    Route::post('/store',[TeamProjectController::class,'store'])->middleware(['auth','CheckAdmin']);
});


Route::prefix('/Project')->group(function(){
    Route::get('/',[ProjectController::class,'index']);
    Route::post('/store',[ProjectController::class,'store'])->middleware(['auth','CheckAdmin']);
    Route::get('/show/{number}',[ProjectController::class,'show']);
    Route::get('/edit/{number}',[ProjectController::class,'edit'])->middleware(['auth','CheckAdmin']);
    Route::put('/update/{number}',[ProjectController::class,'update'])->middleware(['auth','CheckAdmin']);
    Route::delete('/destroy/{number}',[ProjectController::class,'destroy'])->middleware(['auth','CheckAdmin']);
});


Route::prefix('/Lecture')->group(function(){
    Route::get('/',[LectureController::class,'index']);
    Route::post('/store',[LectureController::class,'store'])->middleware(['auth','CheakStaffEducational']);
    Route::get('/show/{id}',[LectureController::class,'show']);
    Route::get('/edit/{id}',[LectureController::class,'edit'])->middleware(['auth','CheakStaffEducational']);
    Route::put('/update/{id}',[LectureController::class,'update'])->middleware(['auth','CheakStaffEducational']);
    Route::delete('/destroy/{id}',[LectureController::class,'destroy'])->middleware(['auth','CheakStaffEducational']);

});


Route::prefix('/Subject')->group(function(){
    Route::get('/',[SubjectController::class,'index']);
    Route::post('/store',[SubjectController::class,'store'])->middleware(['auth','CheakStaffEducational']);
    Route::get('/show/{id}',[SubjectController::class,'show']);
    Route::put('/update/{id}',[SubjectController::class,'update'])->middleware(['auth','CheckAdmin']);
    Route::delete('/destroy/{id}',[SubjectController::class,'destroy'])->middleware(['auth','CheckAdmin']);
});


Route::prefix('/SubjectDepartment')->group(function(){
    Route::post('/store',[SubjectDepartmentController::class,'store'])->middleware(['auth','CheckToken']);
});


Route::prefix('/AcademicyearSubject')->group(function(){
    Route::post('/store',[AcademicyearSubjectController::class,'store'])->middleware(['auth','CheckToken']);
});


Route::prefix('/LectureDetails')->group(function(){
    Route::get('/',[LectureDetailsController::class,'index']);
    Route::post('/store',[LectureDetailsController::class,'store'])->middleware(['auth','CheakStaffEducational']);
    Route::get('/edit/{id}',[LectureDetailsController::class,'edit'])->middleware(['auth','CheakStaffEducational']);
    Route::put('/update/{id}',[LectureDetailsController::class,'update'])->middleware(['auth','CheakStaffEducational']);
    Route::delete('/destroy/{id}',[LectureDetailsController::class,'destroy'])->middleware(['auth','CheakStaffEducational']);
});


Route::prefix('/Advertisement')->group(function(){
    Route::get('/',[AdvertisementController::class,'index']);
    Route::post('/store',[AdvertisementController::class,'store'])->middleware(['auth','CheckAdmin']);
    Route::get('/show/{id}',[AdvertisementController::class,'show']);
    Route::get('/edit/{id}',[AdvertisementController::class,'edit'])->middleware(['auth','CheckAdmin']);
    Route::put('/update/{id}',[AdvertisementController::class,'update'])->middleware(['auth','CheckAdmin']);
    Route::delete('/destroy/{id}',[AdvertisementController::class,'destroy'])->middleware(['auth','CheckAdmin']);
});


Route::prefix('/Suggestion')->group(function(){
    Route::get('/',[SuggestionController::class,'index'])->middleware(['auth','CheckAdmin']);
    Route::post('/store',[SuggestionController::class,'store']);
    Route::get('/edit/{id}',[SuggestionController::class,'edit'])->middleware(['auth','CheckAdmin']);
    Route::put('/update/{id}',[SuggestionController::class,'update'])->middleware(['auth','CheckAdmin']);
    Route::delete('/destroy/{id}',[SuggestionController::class,'destroy'])->middleware(['auth','CheckAdmin']);
});

