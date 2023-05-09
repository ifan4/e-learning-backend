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

        return quizResource::collection($quizzez->loadMissing('materi:id,title'));
    }

    public function showQuizOnMateri($materi_id)
    {
        $quizzez = quiz::where('materi_id', $materi_id)->get();

        return quizResource::collection($quizzez->loadMissing('materi:id,title'));
    }

    public function showQuiz($quiz_id)
    {
        $quiz = quiz::findOrFail($quiz_id);

        return new quizResource($quiz->loadMissing('materi:id,title'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'materi_id' => 'required',
            'question' => 'required',
            'opsi_a' => 'required',
            'opsi_b' => 'required',
            'answer' => 'required'
        ]);

        $quiz = quiz::create($request->all());

        return new quizResource($quiz->loadMissing('materi:id,title'));
    }

    public function delete($id)
    {
        $quiz = quiz::findOrFail($id);

        $quiz->delete($id);

        return new quizResource($quiz->loadMissing("materi:id,title"));
    }
}
