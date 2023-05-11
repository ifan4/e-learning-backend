<?php

namespace App\Http\Controllers;

use App\Http\Resources\classResource;
use App\Models\Class_model;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {

        $classes = Class_model::all();
        $withMateris = $request->with_materis;

        return classResource::collection(($withMateris === 'true') ? $classes->loadMissing('materis') : $classes);
    }

    public function showDetail(Request $request, $id)
    {

        $class = Class_model::findOrFail($id);
        $withMateris = $request->with_materis;

        // return response()->json($class);
        return new classResource(($withMateris === 'true') ? $class->loadMissing('materis') : $class);
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

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required'
        ]);

        $class = Class_model::findOrFail($id);

        $class->update($request->all());

        return new classResource($class);
    }

    public function delete($id)
    {

        $class = Class_model::findOrFail($id);

        $class->delete();

        return new classResource($class);
    }
}
