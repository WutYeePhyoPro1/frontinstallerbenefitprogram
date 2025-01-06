<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnBanner extends Model
{
    use HasFactory;
    use SoftDeletes;
    // protected $connection = 'centralpgsql';

    protected $table = "return_banners";
    protected $fillable = [
        'uuid',
        'branch_id',
        'installer_card_card_number',
        'return_product_docno',
        'ref_invoice_number',
        'total_return_value',
        'total_return_points',
        'total_return_amount',
        'collection_transaction_uuid',
        'reference_return_collection_transaction_uuid',
        'return_action_date',
        'user_uuid',
        'return_date',
        'gbh_customer_id',
        'sale_cash_document_id',
        'return_product_doc_branch_code'
    ];


    public function user() {
        return $this->belongsTo(User::class, "user_uuid", "uuid");
    }

    public function branch() {
        return $this->belongsTo(Branch::class,"branch_id","branch_id");
    }

    public function collectiontransaction(){
        return $this->belongsTo(CollectionTransaction::class,"collection_transaction_uuid","uuid");
    }

    public function referencereturncollectiontransaction(){
        return $this->belongsTo('App\Models\ReferenceReturnCollectionTransaction','reference_return_collection_transaction_uuid','uuid');
    }
}
