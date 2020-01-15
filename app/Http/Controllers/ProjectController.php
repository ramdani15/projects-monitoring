<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Response;

use App\Projects;
use App\Tasks;

class ProjectController extends Controller
{
	public function check_finished($id){
		$task = Tasks::where('project_id', $id)
					 ->where('finished', false)->count();
		if($task < 1){
			Projects::where('_id', $id)->update([
				"finished" => true,
			]);
		}
	}

    public function index(Request $request){
    	return Projects::all();
    }

    public function store(Request $request){
    	if(app(PermissionController::class)->addProject($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'name' => 'required|string|max:255',
	        ]);

	        if($validator->fails()){
	            return Response::json($validator->errors()->toJson(), 400);
	        }

	        $project = Projects::create([
	        	"name" => $request->name,
	        	"finished" => false,
	        	"created_by" => $request->user()->username,
	        ]);

	        return $project;
    	}
    	return Response::json(["status" => 'You don\'t have permission'], 403);
    }

    public function show(Request $request, $id){
    	return Projects::find($id);
    }

    public function edit(Request $request, $id){
    	if(app(PermissionController::class)->addProject($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'name' => 'required|string|max:255',
	        ]);

	        if($validator->fails()){
	            return Response::json($validator->errors()->toJson(), 400);
	        }

	        Projects::where('_id', $id)->update([
	        	"name" => $request->name,
	        ]);

	        return Projects::find($id);
    	}
    	return Response::json(["status" => 'You don\'t have permission'], 403);
    }

    public function destroy(Request $request, $id){
    	if(app(PermissionController::class)->addProject($request->user()->role)){
    		// delete tasks
    		Tasks::where('project_id', $id)->delete();
    		
	        Projects::destroy($id);

	        return Response::json(["status" => 'Deleted'], 200);
    	}
    	return Response::json(["status" => 'You don\'t have permission'], 403);
    }
}
