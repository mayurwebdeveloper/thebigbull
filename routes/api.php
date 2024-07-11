<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\SliderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// authentication
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

//profile
Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'getProfile']);
Route::middleware('auth:sanctum')->post('/profile/edit', [ProfileController::class, 'editProfile']);


//quiz 
Route::middleware('auth:sanctum')->post('/quizzes/active', [QuizController::class, 'getActiveQuizzes']);
Route::middleware('auth:sanctum')->post('/quiz/details', [QuizController::class, 'getQuizDetails']);


// slider (public) apis
Route::get('/sliders',[SliderController::class,'index']);
Route::get('/slider/{code?}',[SliderController::class,'show']);


// feedback api
Route::middleware('auth:sanctum')->post('/feedback', [FeedbackController::class, 'store']);
