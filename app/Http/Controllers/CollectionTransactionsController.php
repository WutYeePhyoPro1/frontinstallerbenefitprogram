<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\BranchUser;
use Illuminate\Support\Str;
use App\Models\ReturnBanner;
use Illuminate\Http\Request;
use App\Models\GroupedReturn;
use App\Models\InstallerCard;
use App\Models\PointPromotion;
use App\Models\PointsRedemption;
use App\Models\InstallerCardPoint;
use Illuminate\Support\Facades\Auth;
use App\Models\CollectionTransaction;
use App\Models\CollectionTransactionLog;
use App\Models\CollectionTransactionDeleteLog;
use App\Models\ReferenceReturnInstallerCardPoint;
use App\Models\ReferenceReturnCollectionTransaction;

class CollectionTransactionsController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('permission:view-collection-transaction', ['only' => ['index']]);
    //     $this->middleware('permission:delete-collection-transaction', ['only' => ['destroy']]);
    // }
    public function index(){
        $branch_id = getCurrentBranch();

        $collectiontransactions = CollectionTransaction::
                                where('branch_id',$branch_id)
                                ->orderBy("created_at",'desc')
                                ->paginate(10);
        return view("collectiontransactions.index",compact('collectiontransactions'));
    }

    public function show($uuid){
        $collectiontransaction = CollectionTransaction::where('uuid',$uuid)->first();

        $installercardpoints = InstallerCardPoint::where("collection_transaction_uuid",$uuid)
                                ->orderBy("created_at", "asc")
                                ->orderBy('id','asc')
                                ->get();
        // dd($installercardpoints);
        $returnbanners = ReturnBanner::where("collection_transaction_uuid",$uuid)->get();

        $total_available_points = 0;
        $total_available_amount = 0;
        foreach($installercardpoints as $installercardpoint){
            $total_available_points += $installercardpoint['points_balance'];
            $total_available_amount += $installercardpoint['amount_balance'];
        }

        // Get the previous URL
        $previousUrl = url()->previous();

        // Attempt to match the previous URL to a route
        $previousRoute = app('router')->getRoutes()->match(app('request')->create($previousUrl));

        // Check if the previous route has a name
        $previousRouteName = $previousRoute->getName();
        // dd($previousRouteName);


        return view("collectiontransactions.show",compact(
            'collectiontransaction',
            'installercardpoints',
            'total_available_points',
            'total_available_amount',
            'returnbanners',
            'previousRouteName'
        ));
    }



    public function search(Request $request){
        $querydocno = $request->docno;
        $queryinvoice_number = $request->invoice_number;
        // dd($queryname);
        $document_from_date     = $request->from_date;
        $document_to_date       = $request->to_date;

        $results = CollectionTransaction::query();
        // dd($results);
        if($querydocno){
            $results = $results->where('document_no','LIKE','%'.$querydocno.'%');
        }
        if($queryinvoice_number){
            $results = $results->where('invoice_number','LIKE','%'.$queryinvoice_number.'%');
        }
        if (!empty($document_from_date) || !empty($document_to_date)) {
            if($document_from_date === $document_to_date)
            {
                $results = $results->whereDate('collection_date', $document_from_date);
            }
            else
            {

                if($document_from_date && $document_to_date){
                    $from_date = Carbon::parse($document_from_date);
                    $to_date = Carbon::parse($document_to_date)->endOfDay();
                    $results = $results->whereBetween('collection_date', [$from_date , $to_date]);
                }
                if($document_from_date)
                {
                    $from_date = Carbon::parse($document_from_date);
                    $results = $results->whereDate('collection_date', ">=",$from_date);
                }
                if($document_to_date)
                {
                    $to_date = Carbon::parse($document_to_date)->endOfDay();
                    $results = $results->whereDate('collection_date',"<=", $to_date);
                }

            }
        }


        $branch_id = getCurrentBranch();
        $collectiontransactions = $results->where('branch_id',$branch_id)->paginate(10);
        // dd($results);

        return view('collectiontransactions.index',compact("collectiontransactions"));
    }


}
