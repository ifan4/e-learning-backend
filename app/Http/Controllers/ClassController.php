<?php

namespace App\Http\Controllers;

use App\Http\Resources\classResource;
use App\Models\Class_model;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {

        $classes = Class_model::all()->loadMissing('Category:id,name');
        $withMateris = $request->with_materis;

        return classResource::collection(($withMateris === 'true') ? $classes->loadMissing('materis') : $classes);
    }

    public function showDetail(Request $request, $id)
    {

        $class = Class_model::findOrFail($id)->loadMissing('Category:id,name');
        $withMateris = $request->with_materis;

        // return response()->json($class);
        return new classResource(($withMateris === 'true') ? $class->loadMissing('materis') : $class);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:App\Models\Category,id'
        ]);


        $class = Class_model::create($request->all());

        return new classResource($class->loadMissing('Category:id,name'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:App\Models\Category,id'
        ]);

        $class = Class_model::findOrFail($id);

        $class->update($request->all());

        return new classResource($class->loadMissing('Category:id,name'));
    }

    public function delete($id)
    {

        $class = Class_model::findOrFail($id);

        $class->delete();

        return new classResource($class->loadMissing('Category:id,name'));
    }
}
