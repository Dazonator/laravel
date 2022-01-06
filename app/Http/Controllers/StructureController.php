<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Structure;
use App\Models\Tasks;
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


    public function tasksByStructure($id){
        $structure = Structure::where('id', $id)->with('children')->first();

        $ids = $this->getIds($structure->children);
        array_push($ids, $structure->id);

        $status = Status::with(['statusTasks' => function($query) use ($ids) {
            $query->with('initiator', 'status', 'responsibles', 'priority')->whereIn('structure_id', $ids);
        }])->get();

//        return Tasks::whereIn('structure_id', $ids)->get();
        return $status;
    }

    public function getIds($items){
        $ids = array();
        foreach ($items as $item){
            array_push($ids, $item->id);
            if(count($item->children) > 0){
                $ids = array_merge($ids, $this->getIds($item->children));
            }
        }
        return $ids;
    }
}
