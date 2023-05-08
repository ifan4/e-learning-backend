<?php

namespace App\Http\Controllers;

use App\Http\Resources\quizResource;
use App\Models\quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {

        $quizzez = quiz::all();

        return quizResource::collection($quizzez);
    }

    public function showQuizOnMateri($materi_id)
    {
        $quizzez = quiz::where('materi_id', $materi_id)->get();

        return quizResource::collection($quizzez);
    }

    public function showQuiz($quiz_id)
    {
        $quiz = quiz::findOrFail($quiz_id);

        return new quizResource($quiz);
    }
}
