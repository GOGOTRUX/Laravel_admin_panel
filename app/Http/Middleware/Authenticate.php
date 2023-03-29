<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Models\User;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        try{
            if(!isset($_SERVER['Authorization'])){  
                abort(401);
            }
            else{
                $authorization = explode(" ", $_SERVER['Authorization']);
                $isValid = User::findOrFail($authorization[1]);
                if(!$isValid){
                    abort(401);
                }
                else{
                    return $isValid;
                }
            } 
        }
        catch(InvalidArgumentException $e){
            return $e;
        }
    }
}
