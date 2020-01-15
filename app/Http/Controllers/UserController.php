<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

use JWTAuth;
use Response;

use App\User;

class UserController extends Controller
{
    public function index(Request $request){
        if(app(PermissionController::class)->isSuper($request->user()->role)){
            $user = User::all();
        } else if(app(PermissionController::class)->isPm($request->user()->role)){
            $user = User::where('role', '!=', 'super')->get();
        } else {
            $user = User::find($request->user()->_id);
        }
        return $user;
    }

    public function show(Request $request, $id){
        if(app(PermissionController::class)->addProject($request->user()->role)){
            $user = User::find($id);
        } else {
            $user = User::where('_id', $id)
                        ->where('_id', $request->user()->_id)->get();
        }
        return $user;
    }

    public function edit(Request $request, $id){
        if(app(PermissionController::class)->usersPermission($request->user()->username, $id, 'edit')){
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
                'role' => 'required|string|in:super,pm,programmer',
            ]);

            if($validator->fails()){
                return Response::json($validator->errors()->toJson(), 400);
            }

            // check username
            $username = $request->username;
            $email = $request->email;
            $user = User::where('_id', '!=', $id)
                        ->where(function($q) use ($username, $email) {
                              $q->where('username', $username)
                                ->orWhere('email', $email);
                          })->get();
            if(!$user->isEmpty()){
                return Response::json(['status' => 'Username or Email Exists'], 400);
            }

            User::where('_id', $id)->update([
                'username' => $request->get('username'),
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'role' => $request->get('role'),
                'password' => Hash::make($request->get('password')),
            ]);

            $user = User::find($id);

            return $user;
        }
        return Response::json(['status' => 'You don\'t have permission'], 403);

    }

    public function destroy(Request $request, $id){
        if(app(PermissionController::class)->usersPermission($request->user()->username, $id, 'destroy')){
            User::destroy($id);

            return Response::json(['status' => 'Deleted!'], 200);
        }
        return Response::json(['status' => 'You don\'t have permission'], 403);
    }

    public function login(Request $request){
        $credentials = $request->only('username', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return Response::json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return Response::json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json([
        	'token' => $token,
        	'id' => $request->user()->_id,
        	'username' => $request->user()->username,
        	'email' => $request->user()->email,
        	'role' => $request->user()->role,
        	'created_at' => $request->user()->created_at,
        	'updated_at' => $request->user()->updated_at
        ]);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
            'role' => 'required|string|in:super,pm,programmer',
        ]);

        if($validator->fails()){
            return Response::json($validator->errors()->toJson(), 400);
        }

        // check username
        $user = User::where('username', $request->username)
                    ->orWhere('email', $request->email)->get();
        if(!$user->isEmpty()){
            return Response::json(['status' => 'Username or Email Exists'], 400);
        }

        $user = User::create([
        	'username' => $request->get('username'),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'role' => $request->get('role'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return Response::json(compact('user','token'), 201);
    }

    public function getAuthenticatedUser(){
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return Response::json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return Response::json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return Response::json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return Response::json(['token_absent'], $e->getStatusCode());
        }
        return Response::json(compact('user'), 200);
    }
}
