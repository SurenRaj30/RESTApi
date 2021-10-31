<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//auth

Route::post('register', [AuthController::class, 'register'])
->name('register');
Route::post('login', [AuthController::class, 'login'])
->name('login');


//crud functions after auth
Route::middleware('auth:api')->group(function () {
    //Route::resource('users', UserController::class);
    Route::get('users', [UserController::class, 'index']);
    Route::post('users/store', [UserController::class, 'store']);
    Route::get('users/{id}/show', [UserController::class, 'show']);
    Route::put('users/{id}/update', [UserController::class, 'update']);
    Route::delete('users/{id}/delete', [UserController::class, 'destroy']);
});

//Excel::import(new UserImport,request()->file('file'));
Route::post('file-import', [UserController::class, 'fileImport'])->name('file-import');