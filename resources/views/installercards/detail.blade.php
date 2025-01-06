@extends('layouts.admin.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="mb-3">Installer Card Points</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mb-4">
                <div class="row">
                    <div class="col-md-4  mb-3 md-mb-0">
                        <div class="installercards">
                            <h5 class="text-center">Installer Card <span class="float-right"><i class="fas fa-check-circle text-info"></i></span></h5>
                            <p><strong>Card Number:</strong> {{ $installercard->card_number }}</p>
                            <p><strong>Installer Name:</strong> {{ $installercard->fullname }}</p>
                            <p><strong>Phone Number:</strong> {{ $installercard->phone }}</p>
                            <p><strong>NRC:</strong> {{ $installercard->nrc }}</p>
                            <p><strong>Points Expiring Soon:</strong> {{ $expiringsoonpoints }} points by {{ \Carbon\Carbon::now()->endOfMonth()->format('d-m-Y') }}</p>
                        </div>
                        @if($installercardcount >= 1)
                        <h6 class="text-center mt-2"><i class="fas fa-2x fa-info-circle text-info"></i> <a href="{{ route('installercards.track',$installercard->card_number) }}">Installer has <span class="">{{ $installercardcount }}</span> more cards.</a></h6>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="card shadow p-3">
                                    <h5>Balance Point</h5>
                                    <div class="flex">
                                        <h2>{{ intval($installercard->totalpoints) }} </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow p-3">
                                    <h5>Balance Amount</h5>
                                    <div class="d-flex">
                                        <h2 class="mr-1">{{ number_format($installercard->totalamount,0,'.',',') }} </h2>
                                        {{-- <small class="mr-2">. {{ substr($installercard->totalamount, -2) }}</small> --}}
                                        <h5 class="align-self-end">MMK</h5>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-md-3">
                                <div class="card shadow p-3">
                                    <h5>Used Point</h5>
                                    <h2>{{ $usedpoints ? $usedpoints : '0' }}</h2>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow p-3">
                                    <h5>Used Amount</h5>
                                    <div class="d-flex">
                                        <h2 class="mr-1">{{ $usedamount ? number_format($usedamount,0,'.',',') : '0'}} </h2>
                                        <h5 class="align-self-end">MMK</h5>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="col-md-3">
                                <div class="card shadow p-3">
                                    <h5>Expired Point</h5>
                                    <h2>{{ $expiredpoints ? $expiredpoints : 0 }}</h2>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow p-3">
                                    <h5>Expired Amount</h5>
                                    <div class="d-flex">
                                        <h2 class="mr-1">{{ $expiredamounts ? number_format($expiredamounts,0,'.',',') : 0 }}</h2>
                                        <h5 class="align-self-end">MMK</h5>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="card shadow p-3 bg-danger">
                                    <h5>Credit Points</h5>
                                    <div class="flex">
                                        <h2>{{ intval($installercard->credit_points) }} </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow p-3 bg-danger">
                                    <h5>Credit Amount</h5>
                                    <div class="d-flex">
                                        <h2 class="mr-1">{{ number_format($installercard->credit_amount,0,'.',',') }} </h2>
                                        {{-- <small class="mr-2">. {{ substr($installercard->totalamount, -2) }}</small> --}}
                                        <h5 class="align-self-end">MMK</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- {{dd(number_format(1728600,0,'.',','))}} --}}

                        {{-- <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('installercardpoints.check',$installercard->card_number) }}" title="Installer Point Checking" class="btn btn-warning"><img src="{{ asset('/images/Common-File-Search--Streamline-Ultimate.png')}}" width="38px" alt=""></a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
            @endif
            @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
            @endif
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif

            <div class="col-lg-12">
                <div class="row my-0 py-0">
                  <!-- Collect Point Section -->
                  <div class="col-md-6">
                    <div class="card p-4 shadow-lg collectpointcard">
                        {{-- <p>Last Activity: {{ session('lastactivity') }}</p> --}}

                        <h4 class="text-center mb-4">Collect Point</h4>

                        <h4 class="mt-2">Earnings Points:</h4>
                        <form method="GET" action="{{ route('installercards.detail') }}">
                            <div class="row border-0 my-2">
                                <div class="col-md-6 border-0">
                                    <div class="form-group border-0">
                                        <input type="text" name="collection_search" value="{{ $collectionSearch }}" placeholder="Search Collection Transactions" class="form-control form-control-sm">
                                    </div>
                                    <input type="hidden" name="redemption_search" value="{{ request('redemption_search') }}">
                                </div>
                                <div class="col">
                                    @if(count(request()->query()) > 0)
                                        <button type="button" id="btn-clear" class="btn btn-light btn-clear" title="Refresh"><i class="fas fa-sync-alt"></i></button>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <ul class="list-group list-group-flush earningpointlists">

                            @foreach ($collectiontransactions as $collectiontransaction)
                                <li class="list-group-item {{ $collectiontransaction->checkfullyredeemed() ? 'fullyredeemed' : '' }}"  onclick="window.location.href='{{ route('collectiontransactions.show',$collectiontransaction->uuid) }}'">
                                    <div class="row">
                                        <div class="col">
                                            <h6>{{ $collectiontransaction->document_no }}</h6>
                                            <p class="pb-0 mb-0">{{ $collectiontransaction->invoice_number }}</p>
                                            <small>{{ $collectiontransaction->collection_date }}</small>
                                            <small class="text-danger d-block">Expire at: {{ $collectiontransaction->getExpireDate() }}</small>
                                        </div>
                                        <div class="col-auto text-right">
                                            <h5 class="text-warning">+ {{$collectiontransaction->total_points_collected  }} pts</h5>
                                            <h5 class="text-success">+ {{ number_format($collectiontransaction->total_save_value,0,'.',',') }} MMK</h5>
                                        </div>
                                        @can('delete-collection-transaction')
                                            @if($collectiontransaction->isDeleteAuthUser() && $collectiontransaction->allowDelete())
                                            <div class="col-auto align-self-center">
                                                <form action="{{ url("/collectiontransactions/$collectiontransaction->uuid") }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button" class="close bg-danger delete-btns" title="Delete">
                                                        <span class="">&times;</span>
                                                    </button>
                                                </form>

                                            </div>
                                            @endif
                                        @endcan
                                    </div>
                                    {{-- {{dd($collectiontransaction->returnbanner)}} --}}
                                    <div class="row">
                                        <div class="col-md-12" >
                                            @if(count($collectiontransaction->returnbanners) > 0)
                                            <div style="border-top: 2px dashed silver">
                                                @foreach($collectiontransaction->returnbanners as $returnbanner)
                                                <div class="d-flex justify-content-between py-2" >
                                                    <a href="{{ route('returnbanners.show',$returnbanner->uuid) }}" class="text-underline">{{ $returnbanner->return_product_docno  }}</a>
                                                    <span>{{ number_format($returnbanner->total_return_value,0,'.',',') }} MMK</span>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif

                                        </div>
                                    </div>
                                </li>
                            @endforeach
                            {{-- <div class="d-flex justify-content-center">
                                {{ $collectiontransactions->appends(['redemption_page' => $redemptiontransactions->currentPage(),'collection_search' => $collectionSearch, 'redemption_search' => $redemptionSearch])->links() }}
                            </div> --}}
                        </ul>
                    </div>
                  </div>

                  <!-- Redeem Point Section -->
                  <div class="col-md-6">
                    <div class="card p-4 shadow-lg redeemcashcards">
                        <h4 class="text-center mb-4">Redeem Points</h4>


                        <h4 class="mt-2">Redemption Transaction:</h4>
                        <form method="GET" action="{{ route('installercards.detail') }}">
                            <div class="row border-0 align-items-center my-2">
                                <div class="col-md-6 border-0">
                                    <div class="form-group border-0">
                                        <input type="text" name="redemption_search" value="{{ $redemptionSearch }}" placeholder="Search Redemption Transactions" class="form-control form-control-sm">
                                    </div>
                                    <input type="hidden" name="collection_search" value="{{ request('collection_search') }}">
                                </div>
                                <div class="col">
                                    @if(count(request()->query()) > 0)
                                        <button type="button" id="btn-clear" class="btn btn-light btn-clear" title="Refresh"><i class="fas fa-sync-alt"></i></button>
                                    @endif
                                </div>
                            </div>
                        </form>

                        {{-- <ul class="list-group list-group-flush earningpointlists">
                            @foreach ($redemptiontransactions as $redemptiontransaction)
                                <li class="list-group-item"  onclick="window.location.href='{{ route('redemptiontransactions.show',$redemptiontransaction->uuid) }}'">
                                    <div class="row">
                                        <div class="col">
                                            <p class="px-2 pb-0 mb-0 d-flex justify-content-between {{ ($redemptiontransaction->nature == 'normal') ? 'bg-success' :  (($redemptiontransaction->nature == 'return deduct') ? 'bg-danger' : ($redemptiontransaction->nature == 'double profit deduct' ? 'bg-warning' : '' )) }}">
                                                <span>{{ $redemptiontransaction->document_no }}</span>
                                                <span>({{ ucwords($redemptiontransaction->nature) }})</span>
                                            </p>
                                            {!! $redemptiontransaction->status == "pending" ? "<span class='badge bg-warning'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "approved" ? "<span class='badge bg-success'>$redemptiontransaction->status</span>" :($redemptiontransaction->status == "rejected"? "<span class='badge bg-danger'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "paid"? "<span class='badge bg-primary'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "finished"? "<span class='badge bg-secondary'>$redemptiontransaction->status</span>" : '')))) !!}
                                            <br>
                                            <small>{{ $redemptiontransaction->redemption_date }}</small>
                                            <small class="text-danger d-block m-0 p-0">Prepare By: {{ $redemptiontransaction->prepareby->name }}</small>
                                            <small class="text-danger m-0 p-0">Prepare At: {{  $redemptiontransaction->created_at }}</small>
                                        </div>
                                        <div class="col-auto text-right">
                                            <h5 class="text-warning">- {{$redemptiontransaction->total_points_redeemed  }} pts</h5>
                                            <h5 class="text-danger">- {{ number_format($redemptiontransaction->total_cash_value,0,'.',',')  }} MMK</h5>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                            <div class="d-flex justify-content-center">
                                {{ $redemptiontransactions->appends(['collection_page' => $collectiontransactions->currentPage(),'collection_search' => $collectionSearch, 'redemption_search' => $redemptionSearch])->links() }}
                            </div>
                        </ul> --}}
                    </div>
                  </div>

                  {{-- <div class="col-md-6">
                    <div class="card p-4 shadow-lg returnproductscards">
                        <h4 class="text-center mb-4">Return Product</h4>


                        <h4 class="mt-2">Return Deduction Records:</h4>
                        <ul class="list-group list-group-flush earningpointlists">
                            @foreach ($redemptiontransactions as $redemptiontransaction)
                                <li class="list-group-item"  onclick="window.location.href='{{ route('redemptiontransactions.show',$redemptiontransaction->uuid) }}'">
                                    <div class="row">
                                        <div class="col">
                                            <p class="pb-0 mb-0">{{ $redemptiontransaction->document_no }}</p>
                                            <small>{{ $redemptiontransaction->redemption_date }}</small>
                                            <small class="text-danger d-block m-0 p-0">Prepare By: {{ $redemptiontransaction->prepareby->name }}</small>
                                            <small class="text-danger m-0 p-0">Prepare At: {{  $redemptiontransaction->created_at }}</small>
                                        </div>
                                        <div class="col-auto text-right">
                                            <h5 class="text-warning">- {{$redemptiontransaction->total_points_redeemed  }} pts</h5>
                                            <h5 class="text-danger">- {{ number_format($redemptiontransaction->total_cash_value,0,'.',',') }} MMK</h5>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                      </div>
                    </div>
                  </div> --}}
                </div>
            </div>


        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->


</div>


@endsection


@section('js')
<script type="text/javascript">
    $(document).ready(function(){



    });
</script>
@endsection
