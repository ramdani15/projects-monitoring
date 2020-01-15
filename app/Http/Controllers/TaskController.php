<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Response;

use App\Projects;
use App\Tasks;

class TaskController extends Controller
{
    public function index(Request $request){
    	return Tasks::all();
    }

    public function store(Request $request){
    	if(app(PermissionController::class)->addProject($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'project_id' => 'required',
	            'keterangan' => 'required|string|max:255',
	        ]);

	        if($validator->fails()){
	            return Response::json($validator->errors()->toJson(), 400);
	        }

	        // Check project
	        $project = Projects::where('_id', $request->project_id)->get();
	        if($project->isEmpty()){
	        	return Response::json(["status" => "Invalid Project"], 400);
	        }

	        $task = Tasks::create([
	        	"project_id" => $request->project_id,
	        	"keterangan" => $request->keterangan,
	        	"finished" => false,
	        	"develop_by" => null,
	        	"created_by" => $request->user()->username,
	        ]);

	        return $task;
    	}
    	return Response::json(["status" => 'You don\'t have permission'], 403);
    }

    public function show(Request $request, $id){
    	return Tasks::find($id);
    }

    public function edit(Request $request, $id){
    	if(app(PermissionController::class)->addProject($request->user()->role)){
    		$validator = Validator::make($request->all(), [
	            'project_id' => 'required',
	            'keterangan' => 'required|string|max:255',
	        ]);

	        if($validator->fails()){
	            return response()->json($validator->errors()->toJson(), 400);
	        }

	        // Check project
	        $project = Projects::where('_id', $request->project_id)->get();
	        if($project->isEmpty()){
	        	return Response::json(["status" => "Invalid Project"], 400);
	        }

	        Tasks::where('_id', $id)->update([
	        	"project_id" => $request->project_id,
	        	"keterangan" => $request->keterangan,
	        ]);

	        return Tasks::find($id);
    	}
    	return Response::json(["status" => 'You don\'t have permission'], 403);
    }

    public function destroy(Request $request, $id){
    	if(app(PermissionController::class)->addProject($request->user()->role)){
	        Tasks::destroy($id);

	        return Response::json(["status" => 'Deleted'], 200);
    	}
    	return Response::json(["status" => 'You don\'t have permission'], 403);
    }

    public function pull(Request $request, $id){
    	$task = Tasks::find($id);
    	if(app(PermissionController::class)->isProgrammer($request->user()->role) && 
    		!$task->finished){
    		// check task developer
    		if($task->develop_by){
    			return Response::json(["status" => 'Project have been Pulled by '.$task->develop_by], 400);
    		}

    		Tasks::where('_id', $id)->update([
    			"develop_by" => $request->user()->username,
    		]);

    		return Tasks::find($id);
    	}
    	return Response::json(["status" => 'You don\'t have permission'], 403);
    }

    public function finish(Request $request, $id){
    	$task = Tasks::find($id);
    	if(app(PermissionController::class)->isProgrammer($request->user()->role) &&
    		$task->develop_by == $request->user()->username && 
    		!$task->finished){
    		
    		Tasks::where('_id', $id)->update([
    			"finished" => true,
    		]);

    		// check finsihed project
    		app(ProjectController::class)->check_finished($task->project_id);

    		return Tasks::find($id);
    	}
    	return Response::json(["status" => 'You don\'t have permission'], 403);
    }
}
