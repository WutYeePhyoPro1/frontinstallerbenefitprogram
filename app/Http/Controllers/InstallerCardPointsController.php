<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Models\BranchUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InstallerCard;
use App\Models\PointPromotion;
use App\Models\PointsRedemption;
use App\Models\InstallerCardPoint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CollectionTransaction;
use App\Models\RedemptionTransaction;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RedemptionTransactionApprovalNoti;

class InstallerCardPointsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-collection-transaction', ['only' => ['collectpoints']]);
        $this->middleware('permission:create-redemption-transaction', ['only' => ['requestredeem']]);
    }
    public function detail(Request $request,$cardnumber){

        $installercard = InstallerCard::where('card_number',$cardnumber)->first();
        // dd($installercard);
        $installercardcount = InstallerCard::where('gbh_customer_id',$installercard->gbh_customer_id)->where('card_number',"!=",$cardnumber)->count();
        // dd($installercardcount);

        $user = Auth::user();
        $user_uuid = $user->uuid;
        $userbranches = BranchUser::where("user_uuid",$user_uuid)->pluck("branch_id");
        $branches = Branch::whereIn("branch_id",$userbranches)->get();


        $collectionSearch = $request->input('collection_search');
        $redemptionSearch = $request->input('redemption_search');
        $collectiontransactions = CollectionTransaction::where('installer_card_card_number',$cardnumber)
                                    ->when($collectionSearch, function ($query, $collectionSearch) {
                                        // $query->where('document_no', 'LIKE', "%$collectionSearch%");
                                            // ->orWhere('amount', 'LIKE', "%$collectionSearch%");
                                        $query->where('invoice_number', 'LIKE', "%$collectionSearch%")
                                            ->orWhere('document_no', 'LIKE', "%$collectionSearch%");
                                    })
                                    ->orderBy("created_at",'desc')
                                    ->orderBy('id','desc')
                                    ->paginate(10, ['*'], 'collection_page');
        $redemptiontransactions = RedemptionTransaction::where('installer_card_card_number',$cardnumber)
                                    ->when($redemptionSearch, function ($query, $redemptionSearch) {
                                        $query->where('document_no', 'LIKE', "%$redemptionSearch%");
                                            // ->orWhere('transaction_id', 'LIKE', "%$redemptionSearch%")
                                    })
                                    ->orderBy("created_at",'desc')
                                    ->orderBy('id','desc')
                                    ->paginate(10, ['*'], 'redemption_page');


        // **Preparation for multiple branch deployment
        // Fetch related user UUIDs
        // $preparebyUuids = $redemptiontransactions->pluck('prepare_by')->filter();
        // // Fetch users from the local branch database
        // $users = DB::table('users')
        //     ->whereIn('uuid', $preparebyUuids)
        //     ->get()
        //     ->keyBy('uuid'); // Index users by UUID for easy mapping
        // // Map user details to installer cards
        // $redemptiontransactions->each(function ($redemptiontransaction) use ($users) {
        //     $redemptiontransaction->prepareby = $users->get($redemptiontransaction->prepare_by);
        // });


        $usedpoints = RedemptionTransaction::where('installer_card_card_number',$cardnumber)->whereIn('status',['paid','finished'])->sum('total_points_redeemed');
        $usedamount = RedemptionTransaction::where('installer_card_card_number',$cardnumber)->whereIn('status',['paid','finished'])->sum('total_cash_value');

        $installercardpointquery = InstallerCardPoint::query()
                                    ->where('installer_card_card_number',$cardnumber)
                                    ->where('is_redeemed', false)
                                    ->where('expiry_date', '<', Carbon::now());
        $expiredpoints = $installercardpointquery->sum('points_balance');
        $expiredamounts = $installercardpointquery->sum('amount_balance');

        $expiringsoonpoints = InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                                ->where("is_redeemed", "0")
                                ->where("expiry_date", "<=", Carbon::now()->endOfMonth())
                                ->sum('points_balance');

        $collectedpoints = CollectionTransaction::where('installer_card_card_number',$cardnumber)->sum('total_points_collected');
        $preusedpoints = abs(InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                            ->where('preused_points',"!=",0)
                            ->sum('preused_points'));
        $earnedpoints = $collectedpoints+$preusedpoints;


        $collectedamount = CollectionTransaction::where('installer_card_card_number',$cardnumber)->sum('total_points_collected');
        $preusedamount = abs(InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                            ->where('preused_points',"!=",0)
                            ->sum('preused_points'));
        $earnedamount = $collectedpoints+$preusedpoints;

        // dd($expiringsoonpoints);
        return view('installercardpoints.detail',compact(
            "installercard",
            "installercardcount",
            "branches",
            'collectiontransactions',
            'redemptiontransactions',
            'usedpoints',
            'usedamount',
            'expiredpoints',
            'expiredamounts',
            'expiringsoonpoints',
            'collectionSearch',
            'redemptionSearch'
        ));
    }

    public function collectpoints($card_number,Request $request){


        $request->validate([
            // 'branch_id' => 'required',
            'invoice_number' => "required"
        ]);
        \DB::beginTransaction();
        try{

            $invoice_number = $request->invoice_number;

            // Prevent Multiple Collection for one invoice
            $precollectiontransaction = CollectionTransaction::where('invoice_number',$invoice_number)->first();
            if($precollectiontransaction != null){
                return redirect()->route('installercardpoints.detail',$card_number)->with("error","This invoice is already collected by ".$precollectiontransaction->installercard->fullname);
            }

            // Get Installer Card
            $installercard = InstallerCard::where("card_number",$card_number)->first();
            $requestbranch_id = getCurrentBranch();




            // Get all active point promotions and rules
            // $activePointPromotions = PointPromotion::where('status', 1)
            //                         ->with('pointRules.pointRuleGroups')
            //                         ->get();
            $currentDate = Carbon::now();
            $activePointPromotion = PointPromotion::where('status', 1)
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->with('pointRules.pointRuleGroups')
            ->whereHas('branches',function($query) use ($requestbranch_id){
                $query->where('branches.id',$requestbranch_id);
            })->first();
            // dd($activePointPromotion);

            if(empty($activePointPromotion)){
                return redirect()->route('installercardpoints.detail',$card_number)->with("error","There is no active promotion.");
            }


            // dd(getCategoryGroupTotal($request->invoice_number));
            $user = Auth::user();
            $user_uuid = $user->uuid;
            // $inv_cat_grp_totals = getCategoryGroupTotal($invoice_number);
            if($user->can("collect-returned-collection-transaction")){
                $inv_cat_grp_totals = getCategoryGroupPointTotalReturned($requestbranch_id,$invoice_number,$activePointPromotion->pointperamount);
            }else{
                $inv_cat_grp_totals = getCategoryGroupPointTotal($requestbranch_id,$invoice_number,$activePointPromotion->pointperamount);
            }

            if(empty($inv_cat_grp_totals)){
                return redirect()->route('installercardpoints.detail',$card_number)->with("error","Invalid Invoice Voucher.");
            }
            // dd($inv_cat_grp_totals);
            $inv_cat_grp_totals_collection = collect($inv_cat_grp_totals);
            $totalCollectedPoints =  floor($inv_cat_grp_totals_collection->sum('net_point'));
            if($totalCollectedPoints == 0){
                return redirect()->route('installercardpoints.detail',$card_number)->with("error","This invoice number got 0 point. We do not record zero point collection.");
            }


            foreach($inv_cat_grp_totals as $inv_cat_grp_total){
                // dd($inv_cat_grp_total->category_name);
                $inv_cat_grp_total->maincatid = getMaincatidByName($inv_cat_grp_total->category_name);
                $inv_cat_grp_total->category_remark = getCategoryRemarkByName($inv_cat_grp_total->category_name);
            }
            // dd($inv_cat_grp_totals);


            $buy_date = $inv_cat_grp_totals[0]->date;
            $gbh_customer_id = $inv_cat_grp_totals[0]->gbh_customer_id;
            $sale_cash_document_id = $inv_cat_grp_totals[0]->sale_cash_document_id;
            $branch_code = $inv_cat_grp_totals[0]->branch_code;





            $collectiontransaction  = CollectionTransaction::create([
                'uuid' => (string) Str::uuid(),
                'point_promotion_uud'=>$activePointPromotion->uuid,
                'points_award_rate'=>$activePointPromotion->pointperamount,
                'branch_id'=>$requestbranch_id,
                // 'document_no' => $this->generate_doc_no($requestbranch_id),
                'installer_card_card_number'=> $card_number,
                'invoice_number'=>$invoice_number,
                'total_sale_cash_amount'=>0,
                'total_points_collected'=> 0,
                'total_save_value'=> 0,
                'collection_date'=>now(),
                'user_uuid'=> $user_uuid,
                'buy_date'=>$buy_date,
                'gbh_customer_id'=>$gbh_customer_id,
                'sale_cash_document_id'=>$sale_cash_document_id,
                'branch_code'=>$branch_code
            ]);

            $totalCollectedPoints = 0;
            $totalSavedAmount = 0;
            $totalSaleCashAmount = 0;
            foreach ($inv_cat_grp_totals as $item) {
                $category_id = $item->category_id;
                $category_name = $item->category_name;
                $group_id = $item->group_id;
                $group_name = $item->group_name;
                $amount = $item->saleamount;                           // the total amount for that category/group
                $maincatid = $item->maincatid;
                $category_remark = $item->category_remark;
                $saleamount = $item->saleamount;

                $totalSaleCashAmount += $saleamount;

                // Find a matching point rule for the category and grouip
                $matching_rule = null;
                $saving_promotion = null;
                // foreach ($activePointPromotions as $promotion) {
                    foreach ($activePointPromotion->pointrules as $rule) {
                        if ($rule->category_id == $maincatid) {
                            // Check if the group is defined in the rule
                            // dd(($rule->pointrulegroups->contains('group_id', $group_id)) || ($category_name == "Marketing Supply"));
                            if (($rule->pointrulegroups->contains('group_id', $group_id)) || ($category_name == "Marketing Supply" && $saleamount <= $activePointPromotion->pointperamount * -1) ) {

                                $matching_rule = $rule;
                                $saving_promotion = $activePointPromotion;
                                break;
                            }
                        }
                    }
                // }

                // If a matching rule is found, calculate points
                if ($matching_rule && $saving_promotion) {

                    // Calculate points based on points per currency unit spent
                    $points_per_amount = $saving_promotion->pointperamount;
                    $redemption_value = $matching_rule->redemption_value;

                    // $points_earned = floor($amount / $points_per_amount);
                    $points_earned = floor($item->net_point);
                    $amount_earned = $points_earned * $redemption_value;
                    // if($category_name == "Marketing Supply" && $saleamount < -1){
                    //     dd($points_earned);
                    // }

                    InstallerCardPoint::create([
                        'uuid' => (string) Str::uuid(),
                        'installer_card_card_number'=>$card_number,
                        'maincatid' => $maincatid,
                        'category_remark'=>$category_remark,
                        'category_id'=>$category_id,
                        'category_name'=>$category_name,
                        'group_id'=>$group_id ? $group_id: '0',
                        'group_name'=>$group_name ? $group_name : ' ',
                        'saleamount'=> $saleamount,
                        'points_earned'=> $points_earned,
                        'points_redeemed'=>0,
                        'points_balance'=> ($points_earned >= 0) ? $points_earned: 0,
                        'point_based'=> $matching_rule->redemption_value,
                        'amount_earned'=> $amount_earned,
                        'amount_redeemed'=>0,
                        'amount_balance'=>$amount_earned ,
                        'expiry_date'=> Carbon::now()->addMonths(6),
                        'is_redeemed'=> 0,
                        'is_returned'=> 0,
                        'collection_transaction_uuid' => $collectiontransaction->uuid
                    ]);

                    $totalCollectedPoints += $points_earned;
                    $totalSavedAmount += $amount_earned;
                } else {
                    // If no matching rule is found, skip the points calculation for this category/group
                    continue;
                }
            }


            // Update the Total Collected Value to Collection Transaction
            $collectiontransaction->update([
                "total_sale_cash_amount"=>$totalSaleCashAmount,
                "total_points_collected"=>$totalCollectedPoints,
                "total_save_value"=>$totalSavedAmount
            ]);

            // Added Collected Value to Installer Card
            $installercard->update([
                "totalpoints"=>  $installercard->totalpoints + $totalCollectedPoints,
                "totalamount"=> $installercard->totalamount + $totalSavedAmount
            ]);

            if($installercard->totalpoints > 0){
                // Deduct the credit point and amount from the new imcomming points
                deductPreUsedPointsFromCard($installercard->card_number);

                if(checkDoubleProfit($installercard->card_number,$collectiontransaction)){
                    deductDoubleProfit($installercard->card_number,$collectiontransaction);
                }
            }


            \DB::commit();
            return redirect()->route('installercardpoints.detail',$card_number)->with("success","Installer Points are saved and ready for redemption");

        }catch(Exception $err){
            \DB::rollback();

            return redirect()->route('installercardpoints.detail',$card_number)->with("error","There is an error in saving installer points");
        }
    }

    public function requestredeem($card_number,Request $request){
        // dd($card_number,$request->reqredeemamount);
        // dd($this->generate_doc_no($request->branch_id));


        $request->validate([
            'reqredeempoints' => "required|numeric"
        ]);


        \DB::beginTransaction();
        try{

            // Check for ongoing redemption transaction to block duplicates
            $curredemptiontransaction = RedemptionTransaction::where('installer_card_card_number',$card_number)->whereNotIn('status',['finished','rejected'])->exists();
            if($curredemptiontransaction){
                return redirect()->route('installercardpoints.detail',$card_number)->with("error","This installer has current redemption transaction and have to wait until it is completed.");
            }

            // Retrieve the installer's card and points balance
            $installercard = InstallerCard::where("card_number", $card_number)->first();
            $reqredeempoints = $request->reqredeempoints;
            $requestbranch_id = getCurrentBranch();


            // Ensure the installer has enough points
            if ($installercard->totalpoints >= $reqredeempoints) {
                // Retrieve available points (sorted by expiry date)
                $installerpoints = InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                    ->where("is_redeemed", "0")
                    ->where("expiry_date", ">", Carbon::now())
                    ->orderBy("created_at", "asc")
                    ->orderBy('id','asc')
                    ->get();
                $user = Auth::user();
                $transaction = RedemptionTransaction::create([
                    'uuid' => (string) Str::uuid(),
                    'branch_id' => $requestbranch_id,
                    'document_no' => $this->generate_doc_no($requestbranch_id),
                    'installer_card_card_number' => $card_number,
                    'total_points_redeemed' => 0,
                    'total_cash_value' => 0,
                    'status' => 'pending',
                    'redemption_date' => now(),
                    'requester' => $installercard->fullname,
                    'prepare_by' => $user->uuid,
                    'nature'=>"normal"
                ]);


                $totalRedeemedPoints = 0;
                $totalRedeemedAmount = 0;
                $remainingPointsToRedeem = $reqredeempoints;

                // Deduct points from the oldest available entries first
                foreach ($installerpoints as $installerpoint) {
                    $availablePoints = $installerpoint->points_balance;
                    $availableAmount = $installerpoint->amount_balance;

                    if($availableAmount <= 0){
                        $installerpoint->update([
                            'is_redeemed' => 1, // Mark as redeemed
                        ]);
                        continue;
                    }

                    if ($remainingPointsToRedeem > 0) {
                        if ($remainingPointsToRedeem >= $availablePoints) {
                            // Redeem all points from this entry
                            $totalRedeemedPoints += $availablePoints;
                            $totalRedeemedAmount += $availableAmount;

                            PointsRedemption::create([
                                'installer_card_point_uuid'=>$installerpoint->uuid,
                                'points_redeemed' => $availablePoints,
                                'point_accumulated' => $installerpoint->point_based,
                                'redemption_amount' => $availableAmount, // Full amount redeemed
                                'redemption_transaction_uuid' => $transaction->uuid,
                            ]);

                            $remainingPointsToRedeem -= $availablePoints;


                        } else {
                            // Partially redeem points from this entry
                            $proportionalAmount = ($remainingPointsToRedeem / $availablePoints) * $availableAmount;

                            $totalRedeemedPoints += $remainingPointsToRedeem;
                            $totalRedeemedAmount += $proportionalAmount;


                            PointsRedemption::create([
                                'installer_card_point_uuid'=>$installerpoint->uuid,
                                'points_redeemed' => $remainingPointsToRedeem,
                                'point_accumulated' => $installerpoint->point_based,
                                'redemption_amount' =>  $proportionalAmount,
                                'redemption_transaction_uuid' => $transaction->uuid,
                            ]);


                            $remainingPointsToRedeem = 0; // Fully redeemed
                        }
                    } else {
                        break; // No more points needed for redemption
                    }
                }

                // Update the transaction with the total redeemed points and equivalent cash value
                $transaction->update([
                    "total_points_redeemed" => $totalRedeemedPoints,
                    "total_cash_value" => $totalRedeemedAmount
                ]);

                // Notify the Branch Manager
                sendIRENotification('Branch Manager', $transaction);

                \DB::commit();
                return redirect()->route('installercardpoints.detail',$card_number)
                    ->with("success", "Redemption request is waiting for Branch Manager approval.");
            } else {
                // Not enough points to redeem
                return redirect()->route('installercards.checking')
                    ->with("error", "Installer Card doesn't have sufficient points.");
            }

        }catch(Exception $err){
            \DB::rollback();

            return redirect()->route('installercardpoints.detail',$card_number)->with("error","There is an error in creation Redemption Request.");

        }

    }

    public static function generate_doc_no($branch_id)
    {
        $prefix = 'IRE';
        $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $branch_id)->first()->branch_short_name;
        $todayDate = Carbon::now()->format('Ymd'); // Format as YYYYMMDD


        // Query the latest document for the branch from today
        $lasttransaction = RedemptionTransaction::where('branch_id', $branch_id)
                                ->whereDate('created_at', Carbon::today())
                                ->orderBy('document_no', 'desc')
                                ->first();
        // dd($lasttransaction);

        // Set initial suffix
        $newSuffix = '0001';

        if ($lasttransaction) {
            // Extract the numeric suffix from the last document number
            $lastSuffix = (int) substr($lasttransaction->document_no, -4);

            // Increment the suffix by 1 and pad with zeros
            $newSuffix = str_pad($lastSuffix + 1, 4, '0', STR_PAD_LEFT);
        }
        // Combine parts to create the new document number
        $documentNumber = $prefix . $branch_prefix . $todayDate . '-' . $newSuffix;
        // dd($documentNumber);

        return $documentNumber;
    }

    public function index(){
        return view('installercardpoints.index');
    }

    public function find(Request $request){

        $card_number = $request->card_number;
        $installercard = InstallerCard::where('card_number',$card_number)->first();
        if($installercard){
            $installercardpoints = InstallerCardPoint::where('installer_card_card_number',$installercard->card_number)
            ->with('collectiontransaction')
            ->orderBy('created_at','asc')
            ->orderBy('id','asc')
            ->get();
            return response()->json(['installercard'=>$installercard,"installercardpoints"=>$installercardpoints]);
        }else{
            return response()->json(["title"=>"Oops, Installer Card Not Found","message"=>"Installer Card Number Incorrect!!"]);
        }
    }


    public function calculateEquivalentAmount(Request $request,$card_number){
        // dd($request->redeempoints);

        $request->validate([
            'redeempoints' => "required|numeric"
        ]);


        // Retrieve the installer's card and points balance
        $installercard = InstallerCard::where("card_number", $card_number)->first();
        $redeempoints = $request->redeempoints;
        $requestbranch_id = getCurrentBranch();

        // Ensure the installer has enough points
        if ($installercard->totalpoints >= $redeempoints) {
            // Retrieve available points (sorted by expiry date)
            $installerpoints = InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                ->where("is_redeemed", "0")
                ->where("expiry_date", ">", Carbon::now())
                ->orderBy("created_at", "asc")
                ->orderBy('id','asc')
                ->get();

            $user = Auth::user();

            $totalRedeemedPoints = 0;
            $totalRedeemedAmount = 0;
            $remainingPointsToRedeem = $redeempoints;

            // Deduct points from the oldest available entries first
            foreach ($installerpoints as $installerpoint) {
                $availablePoints = $installerpoint->points_balance;
                $availableAmount = $installerpoint->amount_balance;

                if($availableAmount <= 0){
                    // $installerpoint->update([
                    //     'is_redeemed' => 1, // Mark as redeemed
                    // ]);
                    continue;
                }

                if ($remainingPointsToRedeem > 0) {
                    if ($remainingPointsToRedeem >= $availablePoints) {
                        // Redeem all points from this entry
                        $totalRedeemedPoints += $availablePoints;
                        $totalRedeemedAmount += $availableAmount;

                        $remainingPointsToRedeem -= $availablePoints;


                    } else {
                        // Partially redeem points from this entry
                        $proportionalAmount = ($remainingPointsToRedeem / $availablePoints) * $availableAmount;

                        $totalRedeemedPoints += $remainingPointsToRedeem;
                        $totalRedeemedAmount += $proportionalAmount;


                        $remainingPointsToRedeem = 0; // Fully redeemed
                    }
                } else {
                    break; // No more points needed for redemption
                }
            }

            // dd($totalRedeemedAmount);
            return response()->json(['equivalentamount'=>$totalRedeemedAmount]);

        } else {
            // Not enough points to redeem
            return redirect()->route('installercards.checking')
                ->with("error", "Installer Card doesn't have sufficient points.");
        }
    }


    public function check($card_number,Request $request){
        $installercard = InstallerCard::where('card_number',$card_number)->first();
        if($installercard){
            $installercardpoints = InstallerCardPoint::where('installer_card_card_number',$installercard->card_number)
            ->with('collectiontransaction')
            ->orderBy('created_at','asc')
            ->orderBy('id','asc')
            ->paginate(10);
            return view('installercardpoints.check',compact('installercard','installercardpoints'));
        }
    }

    public function search($card_number,Request $request){
        // dd($request);


        $query_invoice_number = $request->invoice_number;
        $document_from_date     = $request->from_date;
        $document_to_date       = $request->to_date;

        $results = InstallerCardPoint::query();
        // dd($results);
        if($query_invoice_number){
            $results = $results->whereHas('collectiontransaction',function($query) use ($query_invoice_number){
                $query->where('invoice_number',$query_invoice_number);
            });
        }

        if (!empty($document_from_date) || !empty($document_to_date)) {
            if($document_from_date === $document_to_date)
            {
                $results = $results->whereDate('created_at', $document_from_date);
            }
            else
            {

                if($document_from_date && $document_to_date){
                    $from_date = Carbon::parse($document_from_date);
                    $to_date = Carbon::parse($document_to_date)->endOfDay();
                    $results = $results->whereBetween('created_at', [$from_date , $to_date]);
                }
                if($document_from_date)
                {
                    $from_date = Carbon::parse($document_from_date);
                    $results = $results->whereDate('created_at', ">=",$from_date);
                }
                if($document_to_date)
                {
                    $to_date = Carbon::parse($document_to_date)->endOfDay();
                    $results = $results->whereDate('created_at',"<=", $to_date);
                }

            }
        }

        $installercard = InstallerCard::where('card_number',$card_number)->first();
        $results = $results->where('installer_card_card_number',$card_number)
                    ->with('collectiontransaction')
                    ->orderBy('created_at','asc')->orderBy('id','asc');
        // dd($installercardpoints);

        if(request()->ajax()){
            $installercardpoints = $results->get();
            return response()->json(["installercard"=>$installercard,"installercardpoints"=>$installercardpoints]);
        }else{
            $installercardpoints = $results->paginate(10);
            return view('installercardpoints.check',compact('installercard','installercardpoints'));
        }

    }
}
