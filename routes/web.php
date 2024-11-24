<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
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
    Route::resource('students', NewStudentController::class);
    Route::resource('grades', GradesController::class);
});

// LoginControllerのルートを追加
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// RegisterControllerのルートを追加
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);




// メール確認用ルートの追加
Route::get('/email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify')->middleware(['signed']);
Route::post('/email/resend', 'Auth\VerificationController@resend')->name('verification.resend');



