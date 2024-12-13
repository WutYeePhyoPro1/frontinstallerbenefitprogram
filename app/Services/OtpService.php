<?php

namespace App\Services;

use App\Models\Otp;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use  App\Jobs\OtpMailJob;


class OtpService{
     public function generateotp($installer_card_card_number,$type){
          $randomotp = rand(100000,999999);
          $expireset = Carbon::now()->addMinute(1);



        //   Otp::create([
        //        "installer_card_card_number"=>$installer_card_card_number,
        //        "otp"=>$randomotp,
        //        'type'=> "signin",
        //        "expires_at"=> $expireset
        //   ]);

          // Send OTP via to email / sms
        //   dispatch(new OtpMailJob($user_email,$randomotp));

          return $randomotp;
     }
     public function verifyotp($userid,$otp){
          $checkotp = Otp::where("user_id",$userid)
                         ->where("otp",$otp)
                         ->where("expires_at",">",\Carbon\Carbon::now())->first();

          if($checkotp){
               // OTP valid

               $checkotp->delete(); // Delete OTP after vefirication.

               return true;
          }else{
               // OTP invalid
               return false;
          }
     }
}

?>
