<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MypageController;

Route::get('/', function () {
    return view('welcome');
});

// 認証済みユーザー用ルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/books/{book}/favorite', [FavoriteController::class, 'toggle'])
        ->name('books.favorite.toggle');


    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::post('/mypage/set-goal', [MypageController::class, 'setGoal'])->name('mypage.setGoal');
    // 本一覧
    Route::get('/dashboard', [BookController::class, 'index'])->name('dashboard');
    Route::get('/books', [BookController::class, 'index'])->name('books.index');


    // 本リソースルート（indexは別で定義済み）
    Route::resource('books', BookController::class)->except(['index']);

    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
