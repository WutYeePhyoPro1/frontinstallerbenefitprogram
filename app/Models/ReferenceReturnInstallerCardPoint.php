<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceReturnInstallerCardPoint extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "reference_return_installer_card_points";
    protected $fillable = [
        'uuid',
        'installer_card_point_uuid',
        'installer_card_card_number',
        'maincatid',
        'category_remark',
        'category_id',
        'category_name',
        'group_id',
        'group_name',
        'saleamount',
        'points_earned',
        'points_redeemed',
        'points_balance',
        'point_based',
        'amount_earned',
        'amount_redeemed',
        'amount_balance',
        'expiry_date',
        'is_redeemed',
        'is_returned',
        'collection_transaction_uuid',
        'expire_deduction_date',
        'reference_return_collection_transaction_uuid'
    ];

    public function installercardpoint(){
        return $this->belongsTo(InstallerCardPoint::class,"installer_card_point_uuid","uuid");
    }

    public function collectiontransaction(){
        return $this->belongsTo(CollectionTransaction::class,"collection_transaction_uuid","uuid");
    }
}
