<?php

namespace App\Providers;

use App\Http\Middleware\AuthOTPMid;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AutoLogoutMid;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('auth.card', \App\Http\Middleware\AuthCardMid::class);
        Route::aliasMiddleware('auth.otp', AuthOTPMid::class);
        Route::aliasMiddleware('auth.autologout', AutoLogoutMid::class);
    }
}
