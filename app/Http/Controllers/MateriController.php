<?php

namespace App\Http\Controllers;

use App\Http\Resources\materiResource;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{

    public function index($class_id)
    {

        $materis = Materi::with("class:id,name")->where('class_id', $class_id)->get();

        return materiResource::collection($materis);
    }

    public function downloadMateriFile($file)
    {
        $file = Storage::download('files/' . $file);

        return $file;
    }

    public function showMateri($materi_id)
    {

        $materi = Materi::with('class:id,name')->findOrFail($materi_id);

        return new materiResource($materi);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'class_id' => 'required',
            'title' => 'required|max:255',
            'description' => 'required',
            'file' => 'file|max:10000|mimes:pdf'
        ]);


        if ($request->file) {
            $file = $request->file;
            $folder_name = "files";
            $generated_fileName = $file->hashName();

            Storage::putFileAs($folder_name, $file, $generated_fileName);

            $request["file_materi"] = $generated_fileName;
        }


        $materi = Materi::create($request->all());

        return new materiResource($materi->loadMissing('class:id,name,description'));
    }

    //fix needed on nested materi resource
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'class_id' => 'required',
            'title' => 'required|max:255',
            'description' => 'required',
            'file' => 'file|max:10000|mimes:pdf'
        ]);

        if ($request->file) {
            $file = $request->file;
            $folder_name = "files";
            $generated_fileName = $file->hashName();

            $oldFile = Materi::findOrFail($id);
            if ($oldFile->file_materi) {
                Storage::delete($folder_name . "/" . $oldFile->file_materi);
            }
            Storage::putFileAs($folder_name, $file, $generated_fileName);

            $request["file_materi"] = $generated_fileName;
        }

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
