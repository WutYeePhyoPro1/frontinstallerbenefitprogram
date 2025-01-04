<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Models\InstallerCard;
use Illuminate\Support\Facades\Http;

class OtpsController extends Controller
{
    protected $otpservice;
    public function __construct(OtpService $otpservice){
        $this->otpservice = $otpservice;
    }

    public function index(){
        return view('otps.index');
    }


    // card_number,phone number
    public function generate(Request $request,$type){
        $installer_card_card_number = $request->session()->get('installer_card_card_number');
        $installercard = InstallerCard::where('card_number',$installer_card_card_number)->first();
        $getotp = $this->otpservice->generateotp($installer_card_card_number,$type);
        // return response()->json(["message"=>"OTP generated successfully","otp"=>$getotp]);


        //  //Send OTP via to email / sms
        //   //dispatch(new OtpMailJob($user_email,$randomotp));
        $token = "9LsE1Sl3ul3I89k4WDPdps_Ssx15jrHNgniRnIq5chFn0Pzrf0yYIN8xGYVDzstb";
        $headers = [
            'Content-Type'=> 'application/json',
            'Authorization'=> "Bearer $token"
        ];
        $phoneNo = $installercard->phone;
        $body = [
            "to"=> "+95$phoneNo",
            "message"=> "Your register OTP code is $getotp for PRO 1 Installer Benefit Program."
        ];
        $response = Http::withHeaders($headers)->post('https://smspoh.com/api/v2/send', $body);

        return response()->json([
            "message"=>"OTP generated successfully",
            "otp"=>$getotp,
            'opt_response'=>$response
        ]);
    }
    public function verify(Request $request,$type){
        $installer_card_card_number = $request->session()->get('installer_card_card_number');
        $otp = $request->input("otp_number");
        // dd($otp);

        $isvalidotp = $this->otpservice->verifyotp($installer_card_card_number,$type,$otp);

        if($isvalidotp){
            return response()->json(["message"=>"OTP is valid",'valid'=>"true"]);
        }else{
            return response()->json(["message"=>"OTP is Invalid"],400);
        }
    }


    // public function sendOTP(Requst $request){
    //         $token = "9LsE1Sl3ul3I89k4WDPdps_Ssx15jrHNgniRnIq5chFn0Pzrf0yYIN8xGYVDzstb";
    //         $headers = [
    //             'Content-Type'=> 'application/json',
    //             'Authorization'=> "Bearer $token"
    //         ];

    //         $phoneNo = "09423942691";
    //         $body = [
    //             "to"=> "+95$phoneNo",
    //             "message"=> `Your register OTP code is otp for PRO 1 Mini App.`
    //         ];

    //         $response        = Http::withHeaders($headers)->post('https://smspoh.com/api/v2/send', $body);

    //         return $response;

    // }
}
