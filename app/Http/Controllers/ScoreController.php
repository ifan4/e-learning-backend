<?php

namespace App\Http\Controllers;

use App\Http\Resources\scoreResource;
use App\Models\quiz;
use App\Models\quizScore;
use Illuminate\Http\Request;

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

    //showing scores based on materi_id and 
}
