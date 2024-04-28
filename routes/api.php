<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//contooller
use App\Http\Controllers\Api\V1\SpotController;
use App\Http\Controllers\Api\V1\UserController;

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
Route::middleware('auth:api')->group(function () {
    Route::resource('users', 'UserController');
});

//api/v1/users
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::resource('users', UserController::class);
    Route::resource('spots', SpotController::class);
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
}); 

// Route::group(['prefix' => 'v1'], function () {
//     Route::post('register', [UserController::class, 'register']);
//     // other v1 routes...
// });