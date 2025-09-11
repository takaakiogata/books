<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('welcome');
});

// 認証済みユーザー用ルート
Route::middleware(['auth', 'verified'])->group(function () {

    // 本一覧
    Route::get('/dashboard', [BookController::class, 'index'])->name('dashboard');
    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 本リソースルート
    Route::resource('books', BookController::class)->except(['index']);
});

require __DIR__.'/auth.php';
