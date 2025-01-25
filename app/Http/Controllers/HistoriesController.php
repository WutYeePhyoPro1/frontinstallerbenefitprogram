<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CollectionTransaction;
use App\Models\RedemptionTransaction;
use Illuminate\Pagination\LengthAwarePaginator;

class HistoriesController extends Controller
{
    public function index(Request $request)
    {  $cardnumber = getAuthCard()->card_number;
        $search = $request->input('search');
        $startDate = $request->input('startdate');
        $endDate = $request->input('enddate');

        // $startDate = Carbon::parse($startDate)->format('Y-m-d');
        // $endDate = Carbon::parse($endDate)->format('Y-m-d');

        $startDate = $startDate ?? Carbon::now()->subMonths(6)->format('Y-m-d');
        // dd($startDate);

        // Fetch CollectionTransactions
        $collectionTransactions = CollectionTransaction::where('installer_card_card_number', $cardnumber)
            ->when($search, function ($query, $search) {
                $query->where('invoice_number', 'LIKE', "%$search%")
                      ->orWhere('document_no', 'LIKE', "%$search%");
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween(DB::raw('DATE(collection_date)'), [$startDate, $endDate]);
            })
            ->get();

        // dd($startDate,$endDate,$collectionTransactions);

        // Fetch RedemptionTransactions
        $redemptionTransactions = RedemptionTransaction::where('installer_card_card_number', $cardnumber)
            ->when($search, function ($query, $search) {
                $query->where('document_no', 'LIKE', "%$search%");
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween(DB::raw('DATE(redemption_date)'), [$startDate, $endDate]);
            })
            ->whereIn('status',["finished"])
            ->whereNotIn('nature',["double profit deduct"])
            ->get();

        // Merge and sort the transactions
        $mergedTransactions = $collectionTransactions->merge($redemptionTransactions)
            ->sortByDesc(function ($transaction) {
                return [$transaction->created_at, $transaction->id];
            });

            // dd($mergedTransactions);

        // Paginate the results
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        $paginatedResults = new LengthAwarePaginator(
            $mergedTransactions->forPage($currentPage, $perPage),
            $mergedTransactions->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );


       $gettoday = Carbon::today()->format("Y-m-d"); // get today // "2024-02-26"
        // dd($paginatedResults);
        return view('histories.index', [
            'transactions' => $paginatedResults,
            'gettoday'=> $gettoday,
            "startDate"=>$startDate
        ]);
    }
}
