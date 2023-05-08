<?php

namespace App\Http\Controllers;

use App\Http\Resources\materiResource;
use App\Models\Materi;
use Illuminate\Http\Request;

class MateriController extends Controller
{

    public function index($class_id)
    {

        $materis = Materi::with("class:id,name")->where('class_id', $class_id)->get();

        return materiResource::collection($materis);
    }

    public function showMateri($materi_id)
    {

        $materi = Materi::with('class:id,name')->findOrFail($materi_id);

        return new materiResource($materi);
    }
}
