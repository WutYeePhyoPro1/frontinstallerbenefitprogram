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
}
