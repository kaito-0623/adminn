<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewStudentController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/grades/update-grades', [GradesController::class, 'updateGrades'])->name('grades.update-grades');

    // 学生一覧表示 // 新しい学生の作成フォーム表示 // 学生詳細表示 // 学生編集フォーム表示
    Route::group(['prefix' => 'students', 'as' => 'students.'], function() {
        Route::get('/', [NewStudentController::class, 'index'])->name('index');
        Route::get('/create', [NewStudentController::class, 'create'])->name('create');
        Route::post('/', [NewStudentController::class, 'store'])->name('store'); 
        Route::get('/{student}', [NewStudentController::class, 'show'])->name('show');
        Route::get('/{student}/edit', [NewStudentController::class, 'edit'])->name('edit');
    });
    
    // 成績一覧表示 // 新しい成績の作成フォーム表示 // 成績詳細表示 // 成績編集フォーム表示
    Route::group(['prefix' => 'grades', 'as' => 'grades.'], function() {
        Route::get('/', [GradesController::class, 'index'])->name('index');
        Route::get('/create', [GradesController::class, 'create'])->name('create');
        Route::get('/{grade}', [GradesController::class, 'show'])->name('show');
        Route::get('/{grade}/edit', [GradesController::class, 'edit'])->name('edit');
    }); 
});

// LoginControllerのルートを追加
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
//見直し

// RegisterControllerのルートを追加
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
