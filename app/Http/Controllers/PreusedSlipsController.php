<?php

namespace App\Http\Controllers;

use App\Models\PointPay;
use App\Models\PreusedSlip;
use Illuminate\Http\Request;

class PreusedSlipsController extends Controller
{

    public function show($uuid)
    {
        $preusedslip = PreusedSlip::where('uuid',$uuid)->first();
        $pointpays = PointPay::where("preused_slip_uuid",$uuid)->get();

        return view("preusedslips.show",compact(
            'preusedslip',
            'pointpays'
        ));
    }
}
