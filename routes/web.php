<?php

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

use App\Http\Controllers\Auth\LoginController;

Route::get('/', [LoginController::class, 'showLoginForm']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/admin/login', function () {
    return redirect()->route('login');
});
Route::get('/admin/logout', function () {
    return redirect()->route('logout');
});
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

