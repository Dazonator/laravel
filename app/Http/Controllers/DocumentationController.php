<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function createDocumentation(Request $request){
        $notification = new Documentation();
        $notification::create([
            'title' => $request->title,
            'icon' => $request->icon,
            'text' => $request->text,
        ]);
    }

    public function updateDocumentation(Request $request){
        $id = $request->id;
        $notification = Documentation::find($id);

        $notification->update([
            'title' => $request->title,
            'icon' => $request->icon,
            'text' => $request->text,
        ]);
    }

    public function deleteDocumentation($id){
        $notification = Documentation::find($id);
        $notification->delete();
    }

    public function getAllDocumentations(){
        return Documentation::all();
    }

    public function getByIdDocumentation($id){
        return Documentation::where('id', $id)->first();
    }
}
