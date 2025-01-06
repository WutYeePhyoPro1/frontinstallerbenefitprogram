<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AutoLogoutMid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(getAuthCard()){
            // $dueinactivetime = config('session.lifetime') * 60; // Convert minute to seconds
            $dueinactivetime = 10 * 60; // Convert minute to seconds

            $lastactivity = Session::get('lastactivity',now());

            if(abs(now()->diffInSeconds($lastactivity)) >= $dueinactivetime){
                Session::flush();

                return redirect()->route('welcome')->with("message","You have been logged out.");
            }

            // Update the last activity time
            Session::put('lastactivity',now());
        }

        return $next($request);
    }
}
