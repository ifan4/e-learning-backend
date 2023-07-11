<?php

namespace App\Http\Controllers;

use App\Http\Resources\categoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    public function index()
    {
        $categories = Category::all();

        return categoryResource::collection($categories);
    }
    public function showDetail($category_id)
    {
        $category = Category::findOrFail($category_id);

        return new categoryResource($category);
    }

    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => "required|max:50",
            'description' => "required|max:255",
        ]);

        $newCategory = Category::create($request->all());

        return response()->json([
            "message" => "New Category Successfully Added!",
            "data" => $newCategory
        ]);
    }
    public function update(Request $request, $category_id)
    {
        $request->validate([
            'name' => "required|max:50",
            'description' => "required|max:255",
        ]);

        $category = Category::findOrFail($category_id);

        $category->update($request->all());

        return response()->json([
            "message" => $request->name .  " Category Successfully Updated!",
            "data" => $category
        ]);
    }

    public function delete($id)
    {

        $category = Category::findOrFail($id);

        $category->delete();

        return response()->json([
            'message' => $category->name . " Category Successfully Deleted!",
            'data' => $category
        ]);
    }
}
