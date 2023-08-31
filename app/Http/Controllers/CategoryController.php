<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $category = Category::all();
        return response()->json($category);
    }

    public function show($id){
        $category = Category::findOrFail($id);
        return response()->json($category);

    }

    public function store(Request $request){

        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required|max:500'
        ]);

        $category = Category::create($data);
        return response()->json($category, 201);
    }

    public function update(Request $request, $id){
        $category = Category::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required|max:500'
        ]);

        $category->update($data);
        return response()->json($category, 200);
    }

    public function destroy($id){
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(null, 204);
    }
}
