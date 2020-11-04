<?php

use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

Route::get('/', [AuthController::class, 'registration'])->name('auth.registration');
Route::get('/email/confirm/{id}/{hash}', [AuthController::class, 'confirmEmail'])->name('auth.email.confirm');
Route::get('/email/confirmed', [AuthController::class, 'confirmed'])->name('auth.email.confirmed');
