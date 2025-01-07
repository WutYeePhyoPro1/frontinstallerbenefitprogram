<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointPromotion extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "point_promotions";
    protected $fillable = [
        'uuid',
        'name',
        'pointperamount',
        'start_date',
        'end_date',
        'status',
        'discon_status',
        'remark',
        'user_uuid',
    ];

    public function users(){
        return $this->belongsTo('App\Models\User','user_uuid','uuid');
    }

    public function pointpromotionbranches() {
        return $this->hasMany(PointPromotionBranch::class, 'point_promotion_uuid','uuid');
    }

    public function pointrules(){
        return $this->hasMany(PointRule::class, 'point_promotion_uuid','uuid');
    }

    public function branches(){
        return $this->belongsToMany(Branch::class,"point_promotion_branches",'point_promotion_uuid','branch_id','uuid','branch_id');
    }
}
