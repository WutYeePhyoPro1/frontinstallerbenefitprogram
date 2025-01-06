<?php

namespace App\Http\Controllers;

use App\Models\ReturnBanner;
use Illuminate\Http\Request;
use App\Models\GroupedReturn;

class ReturnBannersController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show($uuid)
    {
        $returnbanner = ReturnBanner::where('uuid',$uuid)->first();
        $groupedreturns = GroupedReturn::where("return_banner_uuid",$uuid)->get();

        return view("returnbanners.show",compact(
            'returnbanner',
            'groupedreturns'
        ));
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
