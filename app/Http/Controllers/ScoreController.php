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
    public $scores = 0;

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

    //showing score based on quiz_id
    public function showScore($quiz_id)
    {
        $score = quizScore::where([
            ['quiz_id', $quiz_id],
            ['user_id', Auth::user()->id]
        ])->get()->first();



        return (isset($score)) ? new scoreResource($score) : response([
            'message' => 'Data not found',
            'status' => 404
        ], 404);
    }

    //This function usable to adding score for general
    public function addScoreForAll(
        $user_answer,
        $quiz_id
    ) {
        //define first
        $request = [];
        $request['answer'] = $user_answer;
        $request['quiz_id'] = $quiz_id;
        $request['score'] = 0;

        //find the right answer
        $correct_answer = quiz::findOrFail($quiz_id)->answer;

        //calculating user score based his answer
        ($correct_answer === $user_answer)
            ? $request['score'] = 25
            : $request['score'] = 0;

        $request['user_id'] = Auth::user()->id;
        $this->scores += $request['score'];

        $score = quizScore::create($request);

        return $score;
    }

    //add score to user who have done with their quiz
    public function addScore(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required'
        ]);

        //Call general function to add data
        $score = $this->addScoreForAll(
            $request->answer,
            $request->quiz_id
        );

        return new scoreResource($score->loadMissing(['user:id,first_name', 'quiz']));
    }

    //add all answers from users and it will calculate the score directly 
    public function addAllAnswers(Request $request, $materi_id)
    {
        $validated = $request->validate([
            'answers' => 'required|array|min:4',
            'answers.*.quiz_id' => 'required',
            'answers.*.answer' => 'required'
        ]);

        $scores = array();

        //need looping for input all answers (in array) 
        foreach ($request->answers as $answer) {
            //Call general function to add data
            $scores[] = $this->addScoreForAll($answer["answer"], $answer["quiz_id"]);
        }

        return response()->json([
            'message' => 'Answers Added Successfully',
            'score_result' => $this->scores,
            'data' => $scores,
        ]);
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
