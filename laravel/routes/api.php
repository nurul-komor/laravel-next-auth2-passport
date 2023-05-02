<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'store'])->name('user.register');
Route::post('/login', [UserController::class, 'login'])->name('user.login');
Route::get('/getData/{id}', [UserController::class, 'show'])->name('user.getData');
Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');
Route::get('/posts', function () {
    echo "This is post page";
})->middleware('auth:api');