<?php

namespace App\Http\Controllers;

use App\Http\Resources\quizResource;
use App\Http\Resources\scoreResource;
use App\Models\quiz;
use App\Models\quizScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{

    public function index(Request $request)
    {
        $scores = [];

        //If there's no query params, so return all data
        if (
            !$request->query()
        ) {
            $scores = quizScore::all();
        }

        //if there are correct query params, so return based on queries
        elseif (
            $request->query("user_id") ||
            $request->query("materi_id") ||
            $request->query("quiz_id")
        ) {
            //Declaration first
            $scores = quizScore::query();
            $user_id = ($request->user_id) ? $request->user_id : null;
            global $materi_id;
            $materi_id = ($request->materi_id) ? $request->materi_id : null;
            $quiz_id = ($request->quiz_id) ? $request->quiz_id : null;

            //Filtering based on materi_id
            if ($materi_id) {
                $scores->whereHas('quiz', function ($query) {
                    global $materi_id;

                    $query->where('materi_id', '=', $materi_id);
                });
            }

            //Filtering based on user_id
            if ($user_id) $scores->where("user_id", $user_id);

            //Filtering based on quiz_id
            if ($quiz_id) $scores->where('quiz_id', $quiz_id);

            $scores = $scores->get();
        }

        return ($scores->count() > 0) ? scoreResource::collection($scores) : response([
            'message' => 'Data not found',
            'status' => 404
        ], 404);
    }

    //showing score based on quiz_id and also user_id 
    public function showScore($quiz_id, $user_id)
    {
        $score = quizScore::where([
            ['quiz_id', $quiz_id],
            ['user_id', $user_id]
        ])->get()->first();


        return (isset($score)) ? new scoreResource($score) : response([
            'message' => 'Data not found',
            'status' => 404
        ], 404);
    }

    //add score to user who have done with their quiz
    public function addScore(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required'
        ]);

        $correct_answer = quiz::findOrFail($request->quiz_id)->answer;
        $user_answer = $request->answer;

        ($correct_answer === $user_answer)
            ? $request['score'] = 25
            : $request['score'] = 0;

        $request['user_id'] = Auth::user()->id;

        $score = quizScore::create($request->all());

        return new scoreResource($score->loadMissing(['user:id,first_name', 'quiz']));
    }

    //update score when user changed the quiz answer  
    public function update(Request $request, $score_id)
    {
        $validated = $request->validate([
            'quiz_id' => 'required'
        ]);

        $correct_answer = quiz::findOrFail($request->quiz_id)->answer;
        $user_answer = $request->answer;

        ($correct_answer === $user_answer)
            ? $request['score'] = 25
            : $request['score'] = 0;

        $score = quizScore::findOrFail($score_id);
        $score->update($request->all());

        return new scoreResource($score->loadMissing(['user:id,first_name', 'quiz']));
    }
}
