<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            $REQUEST_URI = strtolower($_SERVER['REQUEST_URI']);
            $user = Auth::user();
            //check if user is not logged in then redirect to admin login page
            if (strpos($REQUEST_URI, '/') !== false) {
                if (is_null($user)) {
                    return route('userlogin');
                }
            }
            //check if user is not logged in then redirect to admin login page
            //check if admin is not logged in then redirect to admin login page
            if (strpos($REQUEST_URI, '/admin') !== false) {
                if (is_null($user)) {
                    return route('adminlogin');
                }
            }
            //check if admin is not logged in then redirect to admin login page
        }
    }
}
