<?php

use App\Controllers\ChangePasswordController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\NoteController;
use App\Controllers\ProfileController;
use App\Controllers\RegisterController;
use App\Controllers\SearchController;
use App\Controllers\ShowProductController;
use Src\Http\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/product', [ShowProductController::class, 'index']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/profile', [ProfileController::class, 'index']);
Route::post('/logout', [LogoutController::class, 'logout']);
Route::get('/search', [SearchController::class, 'index']);
Route::post('/change-password', [ChangePasswordController::class, 'update']);
Route::post('/notes/add', [NoteController::class, 'created']);
Route::post('/notes/delete', [NoteController::class, 'delete']);
Route::get('/notes/edit', [NoteController::class, 'edit']);
Route::post('/notes/update', [NoteController::class, 'Update']);
