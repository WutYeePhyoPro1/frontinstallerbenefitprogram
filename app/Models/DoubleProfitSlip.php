<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoubleProfitSlip extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "double_profit_slips";
    protected $fillable = [
        'uuid',
        'branch_id',
        'installer_card_card_number',
        'collection_transaction_uuid',
        'user_uuid',
        'redemption_transaction_uuid',
    ];
    public function branch(){
        return $this->belongsTo(Branch::class,"branch_id","branch_id");
    }
    public function installercard(){
        return $this->belongsTo(InstallerCard::class,"installer_card_card_number","card_number");
    }

    public function collectiontransaction(){
        return $this->belongsTo(CollectionTransaction::class,"collection_transaction_uuid","uuid");
    }

    public function redemptiontransaction(){
        return $this->belongsTo(RedemptionTransaction::class,"redemption_transaction_uuid","uuid");
    }
}
