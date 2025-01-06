<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\BranchUser;
use Illuminate\Http\Request;
use App\Models\InstallerCard;
use App\Models\PointsRedemption;
use App\Models\InstallerCardPoint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RedemptionTransaction;
use App\Models\RedemptionTransactionFile;
use Illuminate\Support\Facades\Notification;

class RedemptionTransactionsController extends Controller
{
    public function index(){
        $branch_id = getCurrentBranch();

        $redemptiontransactions = RedemptionTransaction::
                                where('branch_id',$branch_id)
                                ->orderBy("created_at",'desc')->paginate(10);



        // foreach($redemptiontransactions as $redemptiontransaction){
        //     $preparebyUuid = $redemptiontransaction->prepare_by; // Single value, not a collection
        //     $prepareByUser = User::where('uuid', $preparebyUuid)->first();
        //     // Assign the user model to the `prepareby` attribute
        //     $redemptiontransaction->prepareby = $prepareByUser;

        //     $approvedbyUuid = $redemptiontransaction->approved_by; // Single value, not a collection
        //     $approvedByUser = User::where('uuid', $approvedbyUuid)->first();
        //     // Assign the user model to the `prepareby` attribute
        //     $redemptiontransaction->approvedby = $approvedByUser;

        //     $paidbybyUuid = $redemptiontransaction->paid_by; // Single value, not a collection
        //     $paidbyByUser = User::where('uuid', $paidbybyUuid)->first();
        //     // Assign the user model to the `prepareby` attribute
        //     $redemptiontransaction->paidby = $paidbyByUser;
        // }


        return view("redemptiontransactions.index",compact('redemptiontransactions'));
    }

    public function show($uuid){
        $redemptiontransaction = RedemptionTransaction::where('uuid',$uuid)->first();
        // dd($redemptiontransaction);
        $pointsredemptions = PointsRedemption::where("redemption_transaction_uuid",$uuid)->get();
        $redemptiontransactionfiles = RedemptionTransactionFile::where("redemption_transaction_uuid",$uuid)->get();

        // **Preparation for multiple branch deployment
        // Fetch the user who prepared the transaction
        // $preparebyUuid = $redemptiontransaction->prepare_by; // Single value, not a collection
        // $prepareByUser = User::where('uuid', $preparebyUuid)->first();
        // // Assign the user model to the `prepareby` attribute
        // $redemptiontransaction->prepareby = $prepareByUser;

        // $approvedbyUuid = $redemptiontransaction->approved_by; // Single value, not a collection
        // $approvedByUser = User::where('uuid', $approvedbyUuid)->first();
        // // Assign the user model to the `prepareby` attribute
        // $redemptiontransaction->approvedby = $approvedByUser;

        // $paidbybyUuid = $redemptiontransaction->paid_by; // Single value, not a collection
        // $paidbyByUser = User::where('uuid', $paidbybyUuid)->first();
        // // Assign the user model to the `prepareby` attribute
        // $redemptiontransaction->paidby = $paidbyByUser;

        // dd($redemptiontransaction);
        return view("redemptiontransactions.show",compact('redemptiontransaction','pointsredemptions','redemptiontransactionfiles'));
    }

    public function approvalnotifications(){

        return view("redemptiontransactions.approvalnotifications");
    }

    public function approveRedemptionRequest($uuid,Request $request){

        $user = Auth::user();
        $user_uuid = $user->uuid;
        $transaction = RedemptionTransaction::where("uuid",$uuid)->first();
        // dd($transaction);
        $transaction->update([
            "status"=>"approved",
            "approved_by"=>$user_uuid,
            "approved_date"=>now(),
            "bm_remark"=>$request->remark
        ]);

        readIRENotification($uuid);
        sendIRENotification('Finance',$transaction);


        return redirect()->back();

    }

    public function rejectRedemptionRequest($uuid,$step,Request $request){
        // dd('hay');

        $user = Auth::user();
        $user_uuid = $user->uuid;
        $transaction = RedemptionTransaction::where("uuid",$uuid)->first();
        // dd($transaction);

        if($step == 'bm'){
            $transaction->update([
                "status"=>"rejected",
                "approved_by"=>$user_uuid,
                "approved_date"=>now(),
                "bm_remark"=>$request->remark
            ]);
        }else if($step == 'ac'){
            $transaction->update([
                "status"=>"rejected",
                "paid_by"=>$user_uuid,
                "paid_date"=>now(),
                "ac_remark"=>$request->remark
            ]);
        }

        readIRENotification($uuid);
        sendIRESingleUserNotification($transaction->prepare_by,$transaction);

        return redirect()->back();

    }

    public function paidRedemptionRequest($uuid,Request $request){

        // $request->validate([
        //     "images" => "required|array",
        //     "images.*"=>"image|mimes:jpg,jpeg,png|max:1024",
        // ]);

        \DB::beginTransaction();
        try{
            $user = Auth::user();
            $user_id = $user->id;
            $user_uuid = $user->uuid;
            $transaction = RedemptionTransaction::where("uuid",$uuid)->first();
            // dd($transaction);
            $transaction->update([
                "status"=>"paid",
                "paid_by"=>$user_uuid,
                "paid_date"=>now(),
                "ac_remark"=>$request->remark
            ]);


            $installercard = InstallerCard::where("card_number",$transaction->installer_card_card_number)->first();
            // dd($installercard);

            $reqredeempoints = $transaction->total_points_redeemed;
            // Ensure the installer has enough points
            if ($installercard->totalpoints >= $reqredeempoints) {
                // getting installerpoints
                $installerpoints = InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                    ->where("is_redeemed", "0")
                    ->where("expiry_date", ">", Carbon::now())
                    ->orderBy("created_at", "asc")
                    ->orderBy('id','asc')
                    ->get();



                $totalRedeemedPoints = 0;
                $totalRedeemedAmount = 0;
                $remainingPointsToRedeem = $reqredeempoints;

                // Deduct points from the oldest available entries first
                foreach ($installerpoints as $installerpoint) {
                    $availablePoints = $installerpoint->points_balance;
                    $availableAmount = $installerpoint->amount_balance;

                    if ($remainingPointsToRedeem > 0) {
                        if ($remainingPointsToRedeem >= $availablePoints) {
                            // Redeem all points from this entry
                            $totalRedeemedPoints += $availablePoints;
                            $totalRedeemedAmount += $availableAmount;


                            // Update this row as fully redeemed
                            $installerpoint->update([
                                'points_redeemed' => $installerpoint->points_redeemed + $availablePoints,
                                'points_balance' => 0,
                                'amount_redeemed' => $installerpoint->amount_redeemed + $availableAmount,
                                'amount_balance' => 0,
                                'is_redeemed' => 1, // Mark as redeemed
                            ]);


                            $remainingPointsToRedeem -= $availablePoints;


                        } else {
                            // Partially redeem points from this entry
                            $proportionalAmount = ($remainingPointsToRedeem / $availablePoints) * $availableAmount;

                            $totalRedeemedPoints += $remainingPointsToRedeem;
                            $totalRedeemedAmount += $proportionalAmount;


                            // Update this row with partial redemption
                            $installerpoint->update([
                                'points_redeemed' => $installerpoint->points_redeemed + $remainingPointsToRedeem,
                                'points_balance' => $installerpoint->points_balance - $remainingPointsToRedeem,
                                'amount_redeemed' => $installerpoint->amount_redeemed + $proportionalAmount,
                                'amount_balance' => $installerpoint->amount_balance - $proportionalAmount,
                            ]);



                            $remainingPointsToRedeem = 0; // Fully redeemed
                        }
                    } else {
                        break; // No more points needed for redemption
                    }
                }

                $installercard->update([
                    "totalpoints"=>  $installercard->totalpoints - $totalRedeemedPoints,
                    "totalamount"=> $installercard->totalamount - $totalRedeemedAmount
                ]);

                readIRENotification($uuid);
                sendIRESingleUserNotification($transaction->prepare_by,$transaction);

                // Multi Images Upload
                if($request->hasFile('images')){
                    foreach($request->file("images") as $image){
                         $redemptiontransactionfile = new RedemptionTransactionFile();
                         $redemptiontransactionfile->redemption_transaction_uuid = $transaction->uuid;
                         $redemptiontransactionfile->user_uuid = $user_uuid;

                         $file = $image;
                         $fname = $file->getClientOriginalName();
                         $imagenewname = uniqid($user_id).$transaction['id'].$fname;
                         $file->move(public_path('assets/img/redemptiontransactions/'),$imagenewname);


                         $filepath = 'assets/img/redemptiontransactions/'.$imagenewname;
                         $redemptiontransactionfile->image = $filepath;

                         $redemptiontransactionfile->save();
                    }
               }


                \DB::commit();
                return redirect()->back()->with('success',"Requested Points are deducted from installer card");
            } else {
                // Not enough points to redeem
                return redirect()->route('installercards.checking')
                    ->with("error", "Installer Card doesn't have sufficient points.");
            }

        }catch(Exception $err){
            \DB::rollback();

            return redirect()->route('installercardpoints.detail',$card_number)->with("error","There is an error in paying Redemption Request.");
        }
    }

    public function finishRedemptionRequest($uuid,Request $request){
         $request->validate([
            "images" => "required|array",
            "images.*"=>"image|mimes:jpg,jpeg,png|max:1024",
        ]);


        \DB::beginTransaction();
        try{

            $user = Auth::user();
            $user_id = $user->id;
            $user_uuid = $user->uuid;

            $transaction = RedemptionTransaction::where("uuid",$uuid)->first();
            $transaction->update([
                "status"=>"finished",
                "redemption_date"=>now()
            ]);


             // Multi Images Upload
             if($request->hasFile('images')){
                foreach($request->file("images") as $image){
                     $redemptiontransactionfile = new RedemptionTransactionFile();
                     $redemptiontransactionfile->redemption_transaction_uuid = $transaction->uuid;
                     $redemptiontransactionfile->user_uuid = $user_uuid;

                     $file = $image;
                     $fname = $file->getClientOriginalName();
                     $imagenewname = uniqid($user_id).$transaction['id'].$fname;
                     $file->move(public_path('assets/img/redemptiontransactions/'),$imagenewname);


                     $filepath = 'assets/img/redemptiontransactions/'.$imagenewname;
                     $redemptiontransactionfile->image = $filepath;

                     $redemptiontransactionfile->save();
                }
           }

            readIRENotification($uuid);

            \DB::commit();
            return redirect()->back();

        }catch(Exception $err){
            \DB::rollback();

            return redirect()->route('installercardpoints.detail',$card_number)->with("error","There is an error in finishing Redemption Request.");
        }

    }

    public function search(Request $request){
        $querydocno = $request->docno;
        // dd($queryname);
        $querystatus = $request->status;
        $document_from_date     = $request->from_date;
        $document_to_date       = $request->to_date;

        $results = RedemptionTransaction::query();
        // dd($results);
        if($querydocno){
            $results = $results->where('document_no','LIKE','%'.$querydocno.'%');
        }
        if($querystatus){
            $results = $results->where('status',$querystatus);
        }
        if (!empty($document_from_date) || !empty($document_to_date)) {
            if($document_from_date === $document_to_date)
            {
                $results = $results->whereDate('redemption_date', $document_from_date);
            }
            else
            {

                if($document_from_date && $document_to_date){
                    $from_date = Carbon::parse($document_from_date);
                    $to_date = Carbon::parse($document_to_date)->endOfDay();
                    $results = $results->whereBetween('redemption_date', [$from_date , $to_date]);
                }
                if($document_from_date)
                {
                    $from_date = Carbon::parse($document_from_date);
                    $results = $results->whereDate('redemption_date', ">=",$from_date);
                }
                if($document_to_date)
                {
                    $to_date = Carbon::parse($document_to_date)->endOfDay();
                    $results = $results->whereDate('redemption_date',"<=", $to_date);
                }

            }
        }


        $branch_id = getCurrentBranch();
        $redemptiontransactions = $results->where('branch_id',$branch_id)->paginate(10);
        // dd($results);

        return view('redemptiontransactions.index',compact("redemptiontransactions"));
    }


}
