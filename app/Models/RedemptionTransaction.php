<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class RedemptionTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;


    // protected $connection = 'centralpgsql';

    protected $table = "redemption_transactions";
    protected $fillable = [
        'uuid',
        'branch_id',
        'document_no',
        'installer_card_card_number',
        'total_points_redeemed',
        'total_cash_value',
        'status',
        'redemption_date',
        'requester',
        'prepare_by',
        'approved_by',
        'paid_by',
        'approved_date',
        'paid_date',
        'remark',
        'bm_remark',
        'ac_remark',
        'nature',
    ];


    public function prepareby() {
        return $this->belongsTo(User::class, "prepare_by", "uuid");
    }
    public function approvedby(){
        return $this->belongsTo(User::class,"approved_by","uuid");
    }
    public function paidby(){
        return $this->belongsTo(User::class,"paid_by","uuid");
    }

    public function branch(){
        return $this->belongsTo(Branch::class,"branch_id","branch_id");
    }

    public function preusedslip(){
        return $this->hasOne(PreusedSlip::class,"redemption_transaction_uuid","uuid");
    }
    public function doubleprofitslip(){
        return $this->hasOne(DoubleProfitSlip::class,"redemption_transaction_uuid","uuid");
    }
    public function isApproveAuthUser(){

        $user = Auth::user();

        // dd($user->roles);
        // Check if the user's branch matches the transaction's branch
        $belongsToBranch = BranchUser::where('user_uuid', $user->uuid)
                            ->where('branch_id', $this->branch_id)
                            ->exists();

        // Check if the user has the Branch Manager role
        $isBranchManager = $user->roles()->where('name', 'Branch Manager')->exists();
         // Return true if both conditions are met
         return $belongsToBranch && $isBranchManager;

        //  Method 2
        // *firstly find Aughorized user
        // *compare with current user
    }

    public function isPaidAuthUser(){

        $user = Auth::user();

        // dd($user->roles);
        // Check if the user's branch matches the transaction's branch
        $belongsToBranch = BranchUser::where('user_uuid', $user->uuid)
                            ->where('branch_id', $this->branch_id)
                            ->exists();

        // Check if the user has the Branch Manager role
        $isBranchManager = $user->roles()->where('name', 'Finance')->exists();
         // Return true if both conditions are met
        return $belongsToBranch && $isBranchManager;

    }


    public function isFinishedAuthUser(){

        $user = Auth::user();

        $prepareby_user = User::where("uuid",$this->prepare_by)->first();


        return ($user == $prepareby_user);

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
}
