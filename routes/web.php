<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpsController;

Route::get('/', function () {
    return view('welcome');
});


Route::get("/otps",[OtpsController::class,'index'])->name("otps.index");
Route::get("/generateotps/{type}",[OtpsController::class,"generate"])->name("otps.generateotps");
Route::post("/verifyotps",[OtpsController::class,"verify"]);

