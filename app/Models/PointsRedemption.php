<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsRedemption extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "points_redemptions";
    protected $fillable = [
        'installer_card_point_uuid',
        'points_redeemed',
        'point_accumulated',
        'redemption_amount',
        'redemption_transaction_uuid'
    ];

    public function pointpromotion(){
        return $this->belongsTo('App\Models\PointPromotion','point_promotion_uuid','uuid');
    }

    public function installercardpoint(){
        return $this->belongsTo('App\Models\InstallerCardPoint','installer_card_point_uuid','uuid');
    }

    public function redemptiontransaction(){
        return $this->belongsTo('App\Models\RedemptionTransaction','redemption_transaction_uuid','uuid');
    }

}
