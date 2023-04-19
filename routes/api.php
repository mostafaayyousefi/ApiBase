<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PassportAuthController;
use App\Http\Controllers\Api\PostController;

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
Route::get('unauthentication', [PassportAuthController::class, 'unauthentication'])->name('unauthentication');

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
Route::post('verify_email', [PassportAuthController::class, 'verify_email']);
Route::post('remember', [PassportAuthController::class, 'remember']);
Route::put('confirm_password', [PassportAuthController::class, 'confirm_password']);

Route::prefix('user')->group(function () {
    Route::put('final_register', [PassportAuthController::class, 'final_register']);
    });


Route::middleware('auth:api')->group(function () {

Route::prefix('post')->group(function () {
Route::post('store', [PostController::class, 'store']);
Route::get('index', [PostController::class, 'index']);
Route::put('update', [PostController::class, 'update']);
Route::delete('destroy', [PostController::class, 'destroy']);
});

});



