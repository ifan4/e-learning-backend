<?php

namespace App\Http\Controllers;

use App\Http\Resources\classResource;
use App\Models\Class_model;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Class_model::all();

        // return response()->json(['data' => $classes]);
        return classResource::collection($classes);
    }

    public function showDetail($id)
    {

        $class = Class_model::findOrFail($id);

        // return response()->json($class);
        return new classResource($class);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required'
        ]);


        $class = Class_model::create($request->all());

        return new classResource($class);
    }

    public function delete($id)
    {

        $class = Class_model::findOrFail($id);

        $class->delete();

        return new classResource($class);
    }
}
