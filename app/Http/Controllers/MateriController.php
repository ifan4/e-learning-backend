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

    public function store(Request $request)
    {

        $validate = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $materi = Materi::create($request->all());

        return new materiResource($materi->loadMissing('class:id,name,description'));
    }

    //fix needed on nested materi resource
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $materi = Materi::findOrFail($id);
        $materi->update($request->all());

        return new materiResource($materi->loadMissing('class:id,name,description'));
    }

    public function delete($id)
    {
        $materi = Materi::findOrFail($id);

        $materi->delete();

        return new materiResource($materi->loadMissing('class:id,name,description'));
    }
}
