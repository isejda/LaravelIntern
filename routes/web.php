<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('users', [UsersController::class, 'index'])->name('users.index');
    Route::post('users', [UsersController::class, 'store'])->name('users.store');
    Route::get('users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::patch('users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('users/delete/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/fetch-years', [UsersController::class, 'fetchUserYears'])->name('users.fetch_years');
});

//Route::resource('users',\App\Http\Controllers\UsersController::class )->middleware(['auth', 'verified']);

/*
Route::get('/users', function () {
    return view('users');
})->middleware(['auth', 'verified'])->name('users');*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('purchases',\App\Http\Controllers\PurchaseController::class );

require __DIR__.'/auth.php';

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
