<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Amphur;
use App\Models\Province;
use App\Models\BranchUser;
use Illuminate\Support\Str;
use App\Models\ReturnBanner;
use Illuminate\Http\Request;
use App\Models\InstallerCard;
use App\Models\CusSaleAmounts;
use App\Models\SaleAmountCheck;
use App\Models\InstallerCardFile;
use App\Models\InstallerCardPoint;
use Illuminate\Support\Facades\DB;
use App\Models\ReturnProductRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\CollectionTransaction;
use App\Models\RedemptionTransaction;
use Illuminate\Support\Facades\Session;
use App\Models\InstallerCardTransferLog;
use App\Models\POS101\Pos101GbhCustomer;
use App\Models\POS102\Pos102GbhCustomer;
use App\Models\POS103\Pos103GbhCustomer;
use App\Models\POS104\Pos104GbhCustomer;
use App\Models\POS105\Pos105GbhCustomer;
use App\Models\POS106\Pos106GbhCustomer;
use App\Models\POS107\Pos107GbhCustomer;
use App\Models\POS108\Pos108GbhCustomer;
use App\Models\POS112\Pos112GbhCustomer;
use App\Models\POS113\Pos113GbhCustomer;
use App\Models\POS114\Pos114GbhCustomer;

class InstallerCardsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('permission:view-installer-card', ['only' => ['index', 'store']]);
        // $this->middleware('permission:create-installer-card', ['only' => ['create']]);
        // $this->middleware('permission:register-installer-card', ['only' => ['register']]);
        // $this->middleware('permission:create-installer-card|register-installer-card', ['only' => ['store']]);
        // $this->middleware('permission:edit-installer-card', ['only' => ['edit','refresh']]);
        // $this->middleware('permission:delete-installer-card', ['only' => ['delete']]);
        // $this->middleware('permission:check-installer-card', ['only' => ['checking','check']]);
        // $this->middleware('permission:transfer-installer-card', ['only' => ['transfer']]);
    }

    public function signin(Request $request){
        $phone = $request->phone;
        // dd($phone);

        $installercard = InstallerCard::where('phone',$phone)
                            ->where('status',1)
                            ->first();
        // dd($installercard);
        if($installercard){
            $request->session()->put('installer_card_card_number', $installercard->card_number);

            return response()->json(["installercard"=>$installercard]);
        }else{
            return response()->json(["title"=>"Oops, Installer Card Not Found for this phone number","message"=>"Phone Number Incorrect!!"]);
        }
    }

    public function detail(Request $request,){
        $cardnumber = getAuthCard()->card_number;
        $installercard = InstallerCard::where('card_number',$cardnumber)->first();
        // dd($installercard);
        $installercardcount = InstallerCard::where('gbh_customer_id',$installercard->gbh_customer_id)->where('card_number',"!=",$cardnumber)->count();
        // dd($installercardcount);


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
        // dd($expiringsoonpoints);

        $collectedpoints = CollectionTransaction::where('installer_card_card_number',$cardnumber)->sum('total_points_collected');
        $preusedpoints = abs(InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                            ->where('preused_points',"!=",0)
                            ->sum('preused_points'));
        $earnedpoints = $collectedpoints+$preusedpoints;

        return view('installercards.detail',compact(
            "installercard",
            "installercardcount",
            'collectiontransactions',
            'redemptiontransactions',
            'usedpoints',
            'usedamount',
            'expiredpoints',
            'expiredamounts',
            'expiringsoonpoints',
            'collectionSearch',
            'redemptionSearch',
            "earnedpoints"
        ));
    }
    public function track($card_number,Request $request){
        $installercard = InstallerCard::where('card_number',$card_number)->first();

        $allinstallercards = InstallerCard::where('gbh_customer_id',$installercard->gbh_customer_id)->orderBy('created_at','desc')->get();
        // dd($allinstallercards);


        return view("installercards.track",compact("allinstallercards"))->render();
    }


    public function signout(Request $request){
        Session::flush();

        return redirect()->route('welcome')->with("message","You have been logged out.");
    }
}
