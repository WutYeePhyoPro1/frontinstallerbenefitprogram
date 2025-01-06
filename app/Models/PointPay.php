<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointPay extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = "point_pays";
    protected $fillable = [
        'installer_card_point_uuid',
        'before_pay_points_balance',
        'before_pay_amount_balance',
        'points_paid',
        'accept_value',
        'preused_slip_uuid',
    ];
    public function installercardpoint(){
        return $this->belongsTo('App\Models\InstallerCardPoint','installer_card_point_uuid','uuid');
    }

}
