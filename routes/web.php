<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpsController;
use App\Http\Controllers\InstallerCardsController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/installercards/signin',[InstallerCardsController::class,"signin"])->name("installercards.signin");

Route::group(['middleware' => ['auth.card']], function () {
    Route::get("/otps",[OtpsController::class,'index'])->name("otps.index");

    Route::get("/generateotps/{type}",[OtpsController::class,"generate"])->name("otps.generateotps");
    Route::post("/verifyotps/{type}",[OtpsController::class,"verify"]);

    Route::get('sendotp/',[OTPController::class,'send'])->name('otps.send');
});

