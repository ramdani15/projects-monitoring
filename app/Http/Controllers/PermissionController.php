<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class PermissionController extends Controller
{
    public function isSuper($role) {
        if($role == 'super'){
            return true;
        }
        return false;
    }

    public function isPm($role){
    	if($role == 'pm'){
    		return true;
    	}
    	return false;
    }

    public function isProgrammer($role){
    	if($role == 'programmer'){
    		return true;
    	}
    	return false;
    }

    public function addProject($role){
    	if(Self::isSuper($role) || Self::isPm($role)){
    		return true;
    	}
    	return false;
    }

    public function usersPermission($username, $user_id, $action){
        $user = User::find($user_id);
        $owner = User::where('username', $username)->first();
        if($user != null || $owner != null){
        	if($action == 'destroy'){
        		if(Self::isSuper($owner->role)){
	                if($user->_id != $owner->_id){
	                    return true;
	                }
	            } else if(Self::isPm($user->role)){
	                if(Self::isSuper($owner->role)){
	                    return true;
	                }
	            } else if(Self::isProgrammer($user->role)){
	                if(Self::addProject($owner->role)){
	                    return true;
	                }
	            }
        	} else {
        		if(Self::isSuper($owner->role)){
        			return true;
	            } else if(Self::isPm($user->role)){
	                if(Self::isSuper($owner->role) || $user->_id == $owner->_id){
	                    return true;
	                }
	            } else if(Self::isProgrammer($user->role)){
	                if(Self::addProject($owner->role) || $user->_id == $owner->_id){
	                    return true;
	                }
	            }
        	}
            return false;
        }
        return abort(404);
    }
}
