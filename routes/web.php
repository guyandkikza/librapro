<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\RedirectIfAuthenticated;

// หน้า Welcome
Route::get('/', [MainController::class, 'index']);



Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('auth:librarians')->group(function () {
        Route::get('/books', [BookController::class, 'index']);
        Route::get('/books/getdata', [BookController::class, 'getdata']);
        Route::delete('/books/delete', [BookController::class, 'destroy']);
        Route::post('/books/store', [BookController::class, 'store']);
        Route::post('/books/edit', [BookController::class, 'edit']);
        Route::post('/books/upload', [BookController::class, 'upload']);

        Route::get('/students', [StudentsController::class, 'index']);
        Route::get('/students/getdata', [StudentsController::class, 'getdata']);
        Route::delete('/students/delete', [StudentsController::class, 'destroy']);
        Route::post('/students/store', [StudentsController::class, 'store']);
        Route::post('/students/edit', [StudentsController::class, 'edit']);
        Route::post('/students/upload', [StudentsController::class, 'upload']);

        Route::get('/manage', [BorrowController::class, 'index']);
        Route::post('/manage/borrow', [BorrowController::class, 'borrow']);
        Route::post('/manage/return', [BorrowController::class, 'return']);
    });

    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});

