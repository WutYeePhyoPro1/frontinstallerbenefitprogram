<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupedReturn extends Model
{
    use HasFactory;
    // protected $connection = 'centralpgsql';

    protected $table = "grouped_returns";
    protected $fillable = [
        'installer_card_point_uuid',
        'reference_return_installer_card_point_uuid',
        'maincatid',
        'category_remark',
        'category_id',
        'category_name',
        'group_id',
        'group_name',
        'return_price_amount',
        'return_point',
        'return_amount',
        'return_banner_uuid'
    ];

    public function installercardpoint(){
        return $this->belongsTo(InstallerCardPoint::class,"installer_card_point_uuid","uuid");
    }

    public function referencereturninstallercardpoint(){
        return $this->belongsTo('App\Models\ReferenceReturnInstallerCardPoint','reference_return_installer_card_point_uuid','uuid');
    }
}
