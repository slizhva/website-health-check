<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/link/add', [App\Http\Controllers\DashboardController::class, 'add'])->name('dashboard.link.add');
Route::post('/dashboard/link/delete', [App\Http\Controllers\DashboardController::class, 'delete'])->name('dashboard.link.delete');
Route::post('/dashboard/link/update', [App\Http\Controllers\DashboardController::class, 'update'])->name('dashboard.link.update');
