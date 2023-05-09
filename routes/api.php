<?php

use App\Http\Controllers\ClassController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

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

Route::post('/authentication/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/authentication/logout', [UserController::class, 'logout']);
    Route::get('/authentication/me', [UserController::class, 'myData']);

    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::get('/quizzes/{materi_id}', [QuizController::class, 'showQuizOnMateri']);
    Route::get('/quiz/{quiz_id}', [QuizController::class, 'showQuiz']);
    Route::post('/quiz/add', [QuizController::class, 'store']);
    Route::delete('/quiz/delete/{id}', [QuizController::class, 'delete']);

    Route::get('/quizScores', [ScoreController::class, 'index']);
    Route::get('/quizScores/{quiz_id}/{user_id}', [ScoreController::class, 'showScore']);
    Route::get('/quizScores/{materi_id}', [ScoreController::class, 'showScore']);
    Route::post('/quizScores/add', [ScoreController::class, 'addScore']);
    Route::patch('/quizScores/update/{score_id}', [ScoreController::class, 'update']);

    Route::post('/addClass', [ClassController::class, 'store']);
    Route::delete('/class/delete/{id}', [ClassController::class, 'delete']);

    Route::post('/materi/add', [MateriController::class, 'store']);
    Route::delete('/materi/delete/{id}', [QuizController::class, 'delete']);
});


Route::get('/class', [ClassController::class, 'index']);
Route::get('/class/{id}', [ClassController::class, 'showDetail']);

Route::get('/{class_id}/materi', [MateriController::class, 'index']);
Route::get('/materi/{materi_id}', [MateriController::class, 'showMateri']);
