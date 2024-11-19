<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewStudentController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/grades/update-grades', [GradesController::class, 'updateGrades'])->name('grades.update-grades');
    Route::resource('students', \App\Http\Controllers\NewStudentController::class); // フルパスを使用
    Route::resource('grades', GradesController::class);
});




// メール確認用ルートの追加
Route::get('/email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify')->middleware(['signed']);
Route::post('/email/resend', 'Auth\VerificationController@resend')->name('verification.resend');


