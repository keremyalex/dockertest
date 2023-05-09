<?php

use App\Http\Controllers\ApiLoginController;
use App\Http\Controllers\ApiPhotoController;
use App\Http\Controllers\ApiRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [ApiLoginController::class, 'login']);
Route::post('/register', [ApiRegisterController::class, 'register']);

Route::middleware('auth:sanctum')->post('/logout', [ApiLoginController::class, 'logout']);


Route::middleware('auth:sanctum')->get('/apiphoto', [ApiPhotoController::class, 'getCurrentUser']);
//Route::middleware('auth:sanctum')->get('/apiphoto2', [ApiPhotoController::class, 'getCurrentUser2']);
Route::middleware('auth:sanctum')->post('/purchasedPhotos', [ApiPhotoController::class, 'purchasedPhotos']);


