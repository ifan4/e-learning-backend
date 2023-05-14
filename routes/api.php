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
    Route::prefix('authentication')->group(function () {
        Route::get('/logout', [UserController::class, 'logout']);
        Route::get('/me', [UserController::class, 'myData']);
    });

    Route::prefix('quizzes')->group(function () {
        Route::post('/add', [QuizController::class, 'store']);
        Route::delete('/delete/{id}', [QuizController::class, 'delete']);
        Route::patch('/update/{quiz_id}', [QuizController::class, 'update']);
    });

    Route::middleware('admin')->group(function () {
        Route::prefix('class')->group(function () {
            Route::post('/add', [ClassController::class, 'store'])->middleware('admin');
            Route::delete('/delete/{id}', [ClassController::class, 'delete']);
            Route::patch('/update/{class_id}', [ClassController::class, 'update']);
        });

        Route::prefix('materi')->group(function () {
            Route::post('/add', [MateriController::class, 'store']);
            Route::delete('/delete/{id}', [MateriController::class, 'delete']);
            Route::patch('/update/{materi_id}', [MateriController::class, 'update']);
        });
    });


    Route::prefix('quizzes')->group(function () {
        Route::get('/', [QuizController::class, 'index']);
        Route::get('/materi/{materi_id}', [QuizController::class, 'showQuizOnMateri']);
        Route::get('/{quiz_id}', [QuizController::class, 'showQuiz']);
    });

    Route::prefix('quizScores')->group(function () {
        Route::get('/', [ScoreController::class, 'index']);
        Route::get('/{quiz_id}/{user_id}', [ScoreController::class, 'showScore']);
        Route::get('/{materi_id}', [ScoreController::class, 'showScore']);
        Route::post('/add', [ScoreController::class, 'addScore']);
        Route::patch('/update/{score_id}', [ScoreController::class, 'update']);

        Route::post('/addAllAnswers/{materi_id}', [ScoreController::class, 'addAllAnswers']);
    });
});

Route::prefix('class')->group(function () {
    Route::get('/', [ClassController::class, 'index']);
    Route::get('/{id}', [ClassController::class, 'showDetail']);
});

Route::prefix('materi')->group(function () {
    Route::get('/classes/{class_id}', [MateriController::class, 'index']);
    Route::get('/{materi_id}', [MateriController::class, 'showMateri']);
});
