<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthOTPMid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('installer_card_card_number') && ($request->session()->has('otp_verified') && $request->session()->get('otp_verified') == true)) {
            return $next($request);
        }
        return redirect()->route('welcome');
    }
}
