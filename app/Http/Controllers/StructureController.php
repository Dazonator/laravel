<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    public function getStructure(){
        return Structure::whereNull('structure_id')
            ->with('children')
            ->get();
    }

    public function createCategory(Request $request){
        Structure::create([
            'name' => $request->name,
            'structure_id' => $request->parent_id,
        ]);
    }
    public function updateCategory(Request $request){
        $event = Structure::find($request->id);
        $event->update([
            'name' => $request->name,
        ]);

    }
    public function deleteCategory($id){
        Structure::where('id', $id)->delete();
    }
}
