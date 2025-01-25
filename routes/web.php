<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OtpsController;
use App\Http\Controllers\HistoriesController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\PreusedSlipsController;
use App\Http\Controllers\ReturnBannersController;
use App\Http\Controllers\InstallerCardsController;
use App\Http\Controllers\CollectionTransactionsController;
use App\Http\Controllers\RedemptionTransactionsController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/installercards/signin',[InstallerCardsController::class,"signin"])->name("installercards.signin");
Route::post('/installercards/signout',[InstallerCardsController::class,"signout"])->name("installercards.signout");


Route::group(['middleware' => ['auth.card']], function () {
    Route::get("/otps",[OtpsController::class,'index'])->name("otps.index");

    Route::get("/generateotps/{type}",[OtpsController::class,"generate"])->name("otps.generateotps");
    Route::post("/verifyotps/{type}",[OtpsController::class,"verify"]);
    // Route::get('sendotp/',[OTPController::class,'send'])->name('otps.send');

});

// Authenticated Route
Route::group(['middleware' => ['auth.card',"auth.otp","auth.autologout"]], function () {
    Route::get('lang/{locale}', [LocalizationController::class, 'index'])->name('lang');
    // Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/installercards/{cardnumber}/track',[InstallerCardsController::class,"track"])->name("installercards.track");

    Route::get("/installercards/detail",[InstallerCardsController::class,'detail'])->name("installercards.detail");

    Route::get("/collectiontransactions/{collectiontransaction}",[CollectionTransactionsController::class,'show'])->name('collectiontransactions.show');

    Route::get("/redemptiontransactions/{redemptiontransaction}",[RedemptionTransactionsController::class,'show'])->name('redemptiontransactions.show');

    Route::get("/returnbanners/{returnbanner}",[ReturnBannersController::class,'show'])->name('returnbanners.show');

    Route::get("/preusedslips/{preusedslip}",[PreusedSlipsController::class,'show'])->name('preusedslips.show');


    Route::get("/histories",[HistoriesController::class,'index'])->name("histories.index");

});

