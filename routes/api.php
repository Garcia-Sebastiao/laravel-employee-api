<?php

use App\Http\Controllers\AttendenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('reset_password', 'resetpassword')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::get('/users/{id}', 'show');
    Route::put('/users/update/{id}', 'update');
    Route::delete('users/delete/{id}', 'destroy');
});

Route::middleware('auth:sanctum')->controller(AttendenceController::class)->group(function () {
    Route::post('/attendence/clock_in', 'clock_in');
    Route::post('/attendence/clock_out', 'clock_out');
    Route::get('/attendence/reports/{id}', 'reports');
});