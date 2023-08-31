<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(){
        $unit = Unit::all();
        return response()->json($unit);
    }

    public function store(Request $request){

        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $unit = Unit::create($data);
        return response()->json($unit, 201);
    }

    public function update(Request $request, $id){
        $unit = Unit::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $unit->update($data);
        return response()->json($data, 200);
    }

    public function destroy($id){
        $unit = Unit::findOrFail($id);
        $unit ->delete();
        return response()->json(null, 204);
    }
}
