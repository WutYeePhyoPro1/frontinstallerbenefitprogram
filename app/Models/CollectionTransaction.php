<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;


class CollectionTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    // protected $connection = 'centralpgsql';

    protected $table = "collection_transactions";
    protected $fillable = [
        'uuid',
        'point_promotion_uud',
        'points_award_rate',
        'branch_id',
        'document_no',
        'installer_card_card_number',
        'invoice_number',
        'total_sale_cash_amount',
        'total_points_collected',
        'total_save_value',
        'collection_date',
        'user_uuid',
        'buy_date',
        'gbh_customer_id',
        'sale_cash_document_id',
        'branch_code'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function($collectiontransaction){
            $collectiontransaction->document_no = self::generate_doc_no($collectiontransaction->branch_id);
        });

        static::deleting(function ($collectiontransaction) {
            // If the delete is forced, delete related installer card points permanently
            if ($collectiontransaction->isForceDeleting()) {
                $collectiontransaction->installercardpoints()->forceDelete();
            }
            // else {
            //     // Otherwise, soft delete related points
            //     $collectiontransaction->installercardpoints()->delete();
            // }
        });
    }


    public function branch(){
        return $this->belongsTo(Branch::class,"branch_id","branch_id");
    }

    public function pointpromotion(){
        return $this->belongsTo(PointPromotion::class,"point_promotion_uud","uuid");
    }

    public function installercard(){
        return $this->belongsTo(InstallerCard::class,"installer_card_card_number","card_number");
    }

    public function returnbanners(){
        return $this->hasMany(ReturnBanner::class,"collection_transaction_uuid","uuid");
    }

    public function installercardpoints(){
        return $this->hasMany(InstallerCardPoint::class,"collection_transaction_uuid","uuid");
    }



    public static function generate_doc_no($branch_id)
    {
        $prefix = 'ICL';
        $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $branch_id)->first()->branch_short_name;
        $todayDate = Carbon::now()->format('Ymd'); // Format as YYYYMMDD


        // Query the latest document for the branch from today
        $lasttransaction = CollectionTransaction::where('branch_id', $branch_id)
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

    public function allowDelete(){
        $installercardpoint_uuids = InstallerCardPoint::where('collection_transaction_uuid',$this->uuid)->pluck('uuid');
        $pointsredemptions = PointsRedemption::whereIn('installer_card_point_uuid',$installercardpoint_uuids)->get();
        // dd($pointsredemptions);
        // dd($pointsredemptions);

        $returnbanners = ReturnBanner::where('collection_transaction_uuid',$this->uuid)->get();

        if(count($pointsredemptions) > 0 || count($returnbanners) > 0){
            return false;
        }
        return true;
    }

    public function isReturnable(){
        $installercardpoint_uuids = InstallerCardPoint::where('collection_transaction_uuid',$this->uuid)->pluck('uuid');
        $pointsredemptions = PointsRedemption::whereIn('installer_card_point_uuid',$installercardpoint_uuids)
                            ->whereHas('redemptiontransaction',function($query){
                                $query->whereNotIn('status',['finished','rejected']);
                            })
                            ->get();
        if(count($pointsredemptions) > 0){
            return false;
        }
        return true;

    }

    public function isDeleteAuthUser(){

        $user = Auth::user();

        // dd($user->roles);
        // Check if the user's branch matches the transaction's branch
        $belongsToBranch = BranchUser::where('user_uuid', $user->uuid)
                            ->where('branch_id', $this->branch_id)
                            ->exists();

        // Check if the user has the Branch Manager role
        $isBranchManager = $user->roles()->where('name', 'Branch Manager')->exists();
        $isSuperAdmin = $user->roles()->where('name', 'Super Admin')->exists();
         // Return true if both conditions are met
         return $belongsToBranch && ($isBranchManager || $isSuperAdmin);
    }

    public function checkreturn(){
        $returnbanner = ReturnBanner::where('collection_transaction_uuid',$this->uuid)->first();
        if(!empty($returnbanner)){
            return false;
        }else{
            return true;
        }
    }

    public function checkreturnbanner($return_product_docno){
        return $this->returnbanners()->where('return_product_docno',$return_product_docno)->exists();
    }

    // public function checkfullyredeemed(){
    //     $installercardpoints = InstallerCardPoint::where("collection_transaction_uuid",$this->uuid)
    //                             ->where('is_redeemed',0)
    //                             ->orderBy("created_at", "asc")
    //                             ->orderBy('id','asc')
    //                             ->exists();
    //     if(count($installercardpoints) > 0){
    //         return false;
    //     }else{
    //         return true;
    //     }
    // }

    public function checkfullyredeemed(){
        return !(InstallerCardPoint::where("collection_transaction_uuid",$this->uuid)
                                ->where('is_redeemed',0)
                                ->orderBy("created_at", "asc")
                                ->orderBy('id','asc')
                                ->exists());
    }

    public function getExpireDate(){
        $installercardpoint = InstallerCardPoint::where("collection_transaction_uuid",$this->uuid)
                                ->orderBy("created_at", "asc")
                                ->orderBy('id','asc')
                                ->first();
        $expiry_date = $installercardpoint->expiry_date;

        return $expiry_date;
    }
}
