<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;

class OtpsController extends Controller
{
    protected $otpservice;
    public function __construct(OtpService $otpservice){
        $this->otpservice = $otpservice;
    }

    public function index(){
        return view('otps.index');
    }


    public function generate(Request $request,$type){
        // $userid = Auth::id();

        // $installer_card_card_number = $request->session()->get('installer_card_card_number');
        $installer_card_card_number = "3000000018";
        $getotp = $this->otpservice->generateotp($installer_card_card_number,$type);

        return response()->json(["message"=>"OTP generated successfully","otp"=>$getotp]);
    }
    // public function verify(Request $request){
    //     $userid = $request->input("otpuser_id");
    //     $otp = $request->input("otpcode");
    //     $isvalidotp = $this->otpservice->verifyotp($userid,$otp);

    //     if($isvalidotp){
    //         return response()->json(["message"=>"OTP is valid"]);
    //     }else{
    //         return response()->json(["message"=>"OTP is Invalid"],400);
    //     }
    // }
}
