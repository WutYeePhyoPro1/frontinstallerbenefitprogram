<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceReturnCollectionTransaction extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "reference_return_collection_transactions";
    protected $fillable = [
        'uuid',
        'collection_transaction_uuid',
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

    public function branch(){
        return $this->belongsTo(Branch::class,"branch_id","branch_id");
    }

    public function pointpromotion(){
        return $this->belongsTo(PointPromotion::class,"point_promotion_uud","uuid");
    }

    public function installercard(){
        return $this->belongsTo(InstallerCard::class,"installer_card_card_number","card_number");
    }

    public function collectiontransaction(){
        return $this->belongsTo(CollectionTransaction::class,"collection_transaction_uuid","uuid");
    }

}
