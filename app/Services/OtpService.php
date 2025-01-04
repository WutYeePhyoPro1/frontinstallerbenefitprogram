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



          Otp::create([
               "installer_card_card_number"=>$installer_card_card_number,
               "otp"=>$randomotp,
               'type'=> $type,
               "expires_at"=> $expireset
          ]);


          return $randomotp;
     }
     public function verifyotp($installer_card_card_number,$type,$otp){
          $checkotp = Otp::where("installer_card_card_number",$installer_card_card_number)
                         ->where("otp",$otp)
                         ->where("expires_at",">",\Carbon\Carbon::now())
                         ->where("type",$type)
                         ->first();

          if($checkotp){
               // OTP valid

               $checkotp->delete(); // Delete OTP after verification.

               return true;
          }else{
               // OTP invalid
               return false;
          }
     }
}

?>
