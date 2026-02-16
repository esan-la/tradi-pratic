<?php

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

// Route::prefix('v1')->group(function () {

//     Route::post('/login',[AuthController::class,'login']);
//     Route::post('/register',[AuthController::class,'register']);

//     Route::middleware('auth:sanctum')->group(function () {

//         Route::apiResource('appointments',AppointmentController::class);
//         Route::apiResource('orders',OrderController::class);
//         Route::apiResource('donations',DonationController::class);
//         Route::apiResource('products',ProductController::class);

//     });
// });
