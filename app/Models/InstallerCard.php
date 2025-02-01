<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstallerCard extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "installer_cards";
    protected $fillable = [
        'card_number',
        'branch_id',

        'fullname',
        'phone',
        'address',
        'gender',
        'dob',
        'nrc',
        'passport',
        'identification_card',
        'member_active',
        'customer_active',
        'customer_rank_id',
        'customer_barcode',

        'titlename',
        'firstname',
        'lastnanme',
        'province_id',
        'amphur_id',
        'nrc_no',
        'nrc_name',
        'nrc_short',
        'nrc_number',
        'gbh_customer_id',


        'totalpoints',
        'totalamount',
        'credit_points',
        'credit_amount',
        'expire_points',
        'expire_amount',
        'issued_at',
        'user_uuid',
        'status',

        'approved_by',
        'approved_date',
        'bm_remark',
        'stage'
    ];

    public function user(){
        return $this->belongsTo(User::class,"user_uuid","uuid");
    }

    public function approvedby(){
        return $this->belongsTo(User::class,"approved_by","uuid");
    }

    public function branch(){
        return $this->belongsTo(Branch::class,"branch_id","branch_id");
    }

    public function customers(){
        return $this->belongsTo(Pos101GbhCustomer::class,"identification_card","identification_card");
    }

    protected static function boot(){
        parent::boot();

        // static::creating(function($installercard){
        //     $installercard->card_number = self::generatecardnumber();
        // });
    }
    protected static function generatecardnumber(){
        return \DB::transaction(function(){
            $latestinscard = \DB::table("installer_cards")->orderBy("id","desc")->first();
            $latestid= $latestinscard ?  $latestinscard->id : 0;
            $branchshortname = "LAN1";
            $newinscardnumber = "INS".$branchshortname.str_pad($latestid+1,5,"0",STR_PAD_LEFT);

            while(\DB::table("installer_cards")->where("card_number",$newinscardnumber)->exists()){
                $latestid++;
                $newinscardnumber = "INS".$branchshortname.str_pad($latestid+1,5,"0",STR_PAD_LEFT);
            }
            return $newinscardnumber;
        });
    }

    public function ismembercustomer(){
        return $this->identification_card != null && $this->member_active && $this->customer_active && $this->customer_rank_id == 1013;
    }

    public function installercardfiles(){
        return $this->hasMany(InstallerCardFile::class,"installer_card_card_number","card_number");
    }

    public function homeowners(){
        return $this->belongsToMany(HomeOwner::class,"homeowner_installers","installer_card_card_number","home_owner_uuid","card_number","uuid");
    }

    public function isApproveAuthUser(){

        $user = Auth::user();

        // dd($user->roles);
        // Check if the user's branch matches the transaction's branch
        $belongsToBranch = BranchUser::where('user_uuid', $user->uuid)
                            ->where('branch_id', $this->branch_id)
                            ->exists();

        // Check if the user has the Branch Manager role
        $isBranchManager = $user->roles()->whereIn('name', ['Branch Manager',"Super Admin"])->exists();
         // Return true if both conditions are met
         return $belongsToBranch && $isBranchManager;

        //  Method 2
        // *firstly find Aughorized user
        // *compare with current user
    }


    public function isTransferrable(){
        // $installercard = InstallerCard::where('card_number',$this->card_number)->first();

        $lastusedinstallercard = InstallerCard::where('customer_barcode',$this->customer_barcode)
                            ->where('status',0)
                            ->whereIn("stage",["approved"])
                            ->orderBy('created_at','desc')->first();

        if($lastusedinstallercard){
            if($lastusedinstallercard->card_number == $this->card_number){
                return true;
            }
        }
        return false;

    }
}
