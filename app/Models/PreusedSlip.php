<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PreusedSlip extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "preused_slips";
    protected $fillable = [
        'uuid',
        'branch_id',
        'installer_card_card_number',
        'before_pay_total_points',
        'before_pay_total_amount',
        'before_pay_credit_points',
        'before_pay_credit_amount',
        'total_points_paid',
        'total_accept_value',
        'user_uuid',
        'redemption_transaction_uuid',
    ];
    public function branch(){
        return $this->belongsTo(Branch::class,"branch_id","branch_id");
    }
    public function installercard(){
        return $this->belongsTo(InstallerCard::class,"installer_card_card_number","card_number");
    }
}
