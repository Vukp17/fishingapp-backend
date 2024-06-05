<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//contooller
use App\Http\Controllers\Api\V1\SpotController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\AuthController;
use Google\Service\Adsense\Row;

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
    //Auth
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::post('google-login', [AuthController::class, 'googleLogin']);

    /**Routes protected with aut token */
    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('spots', SpotController::class);
        /** Get all spots by user */
        Route::get('users/{user}/spots', [UserController::class, 'spots']);
        /** Save spot  */
        Route::post('users/{user}/spots', [UserController::class, 'storeSpot']);
        /** Get image */
        Route::get('images/{filename}', [UserController::class, 'getImage']);
        /**List of all spots   with pagination */
        Route::get('spots', [SpotController::class, 'index']);
       /**Update user */
       Route::put('users/{user}', [UserController::class, 'update']);
 
    });
});
