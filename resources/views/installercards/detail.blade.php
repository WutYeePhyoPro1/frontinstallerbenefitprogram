@extends('layouts.admin.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            {{-- <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="mb-3">Installer Card</h4>
                    </div>
                </div>
            </div> --}}

            <div class="col-lg-12">
                <div class="col-md-4 mx-auto mb-3 md-mb-0">
                    <div class="summary-card">
                        <div class="details">
                            <h2>Installer Card:  {{ $installercard->card_number }}</h2>
                            <p><strong>Installer Name:</strong> {{ $installercard->fullname }}</p>
                            <p><strong>Phone Number:</strong> {{ $installercard->phone }}</p>
                            <p><strong>NRC:</strong> {{ $installercard->nrc }}</p>
                            <p><strong>Points Expiring Soon:</strong> {{ $expiringsoonpoints }} points by {{ \Carbon\Carbon::now()->endOfMonth()->format('d-m-Y') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                    </div>
                    @if($installercardcount >= 1)
                    {{-- <h6 class="text-center mt-2"><i class="fas fa-2x fa-info-circle text-info"></i> <a href="{{ route('installercards.track',$installercard->card_number) }}">Installer has <span class="">{{ $installercardcount }}</span> more cards.</a></h6> --}}
                    @endif
                </div>
            </div>

            <div class="col-lg-8 mx-auto">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="card shadow p-3" data-toggle="modal" data-target="#balancemodel" style="cursor: pointer">
                            <h5 class="text-underline" style="text-underline-offset: 5px;">Balance Point</h5>
                            <div class="flex">
                                <h2>{{ intval($installercard->totalpoints) }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card shadow p-3">
                            <h5>Balance Amount</h5>
                            <div class="d-flex">
                                <h2 class="mr-1">{{ number_format($installercard->totalamount,0,'.',',') }} </h2>
                                {{-- <small class="mr-2">. {{ substr($installercard->totalamount, -2) }}</small> --}}
                                <h5 class="align-self-end">MMK</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card shadow p-3 bg-danger">
                            <h5>Credit Points</h5>
                            <div class="flex">
                                <h2>{{ intval($installercard->credit_points) }} </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
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


                {{-- Fav Menus --}}
                <div class="fav-menus">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="my-2">Popular Services</h5>
                                    <div class="menu-container">
                                        <div class="menu">
                                            <div class="menu-icon mx-auto">
                                                <i class="fas fa-history"></i>
                                                {{-- <i class="fas fa-hourglass-half"></i> --}}
                                                {{-- <i class="ri-hourglass-fill"></i> --}}
                                            </div>
                                            <h5 class="text-center">History</h5>
                                        </div>
                                        <div class="menu">
                                            <div class="menu-icon mx-auto">
                                                <i class="fas fa-qrcode"></i>
                                            </div>
                                            <h5 class="text-center">My QR</h5>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                  <!-- Tabs -->
                <div class="tabs">
                    <div class="tab active" onclick="showTab('earnings')"><h4>Earning Points</h4></div>
                    <div class="tab" onclick="showTab('redemptions')"><h4>Redemption Points</h4></div>
                </div>

                <!-- Transactions -->
                <div id="earnings" class="transactions">
                    @foreach ($collectiontransactions as $collectiontransaction)
                    <div class="transaction" onclick="window.location.href='{{ route('collectiontransactions.show',$collectiontransaction->uuid) }}'">
                        <p class="pb-0 mb-0">{{ $collectiontransaction->invoice_number }}</p>
                        <h5 class="text-success mr-2 mb-0">+ {{$collectiontransaction->total_points_collected  }}</h5>
                    </div>
                    @endforeach
                    {{-- <div class="transaction">
                        <p>LANLAN2A54-240502-0002</p>
                        <p class="amount">+3 pts</p>
                    </div> --}}
                </div>

                <div id="redemptions" class="transactions" style="display: none;">
                    @foreach ($redemptiontransactions as $redemptiontransaction)
                        <div class="transaction" onclick="window.location.href='{{ route('redemptiontransactions.show',$redemptiontransaction->uuid) }}'">
                            <p>{{ $redemptiontransaction->document_no }}</p>
                            <h5 class="text-danger mr-2 mb-0">- {{$redemptiontransaction->total_points_redeemed  }} </h5>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>



 <!-- START MODAL AREA -->
    <!-- start create modal -->

    <div id="balancemodel" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h6 class="modal-title">Balance Modal</h6>
                    <button type="" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="balanceform" action="" method="POST">
                        @csrf

                        <div class="row align-items-center px-4">
                                <div class="col-md-12">
                                    <h4 class="text-center" >Balance Summary</h4>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group d-flex justify-content-between">
                                        <div>
                                            <label for="name" class="d-block mb-0">Total Earned Points<span class="text-danger">*</span></label>
                                            <input type="hidden" id="total_earned_points" name="total_earned_points" class="form-control form-control-sm rounded-0" readonly value="1000">
                                            <span class="text-danger">(including before return points)</span>
                                        </div>

                                        <span>{{ $earnedpoints ? $earnedpoints : '0' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group d-flex justify-content-between">
                                        <label for="name">Total Used Points<span class="text-danger">*</span></label>
                                        <input type="hidden" id="total_redeemed_points" name="total_redeemed_points" class="form-control form-control-sm rounded-0" readonly value="1000">
                                        <span>{{ $usedpoints ? $usedpoints : '0' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-12 text-center" style="border-top: 1px dashed #ddd;">
                                    <div class="form-group d-flex justify-content-between">
                                        <label for="name" class="text-center w-full d-block">Balance Points<span class="text-danger">*</span></label>
                                        <input type="hidden" name="balance_points" id="balance_points" class="form-control form-control-sm rounded-0" value="0" readonly/>
                                        <span>{{ intval($installercard->totalpoints) }}</span>
                                    </div>
                                </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- end create modal -->
 <!-- END MODAL AREA -->
@endsection


@section('css')
    <style type="text/css">
         .summary-card {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border-radius: 15px;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-card .icon {
            font-size: 50px;
        }


        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab {
            flex: 1;
            padding: 10px;
            text-align: center;
            background: white;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            color: #333;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .tab.active {
            background: #4facfe;
            color: white;
        }


        .transactions {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .transaction {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .transaction:last-child {
            border-bottom: none;
        }

        .transaction p {
            margin: 0;
            font-size: 14px !important;
        }

        .transaction .amount {
            font-weight: bold;
        }

        .menu{
            width: 80px;
            cursor: pointer;
        }

        .menu-icon{
            width: 50px;
            height: 50px;
            background: #f4f4f4;
            /* color: rgba(79, 172, 254,0.8); */
            border-radius: 50%;
            font-size: 20px;

            display: flex;
            justify-content:center;
            align-items: center;
        }

        .menu-container{
            display: flex;
            justify-content: center;
            align-items: start;

            flex-wrap: wrap;
            gap: 40px;

        }
    </style>
@endsection

@section('js')
<script type="text/javascript">
    function showTab(tabId) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.transactions').forEach(tx => tx.style.display = 'none');

        document.querySelector(`#${tabId}`).style.display = 'block';
        document.querySelector(`.tab[onclick="showTab('${tabId}')"]`).classList.add('active');
    }

    $(document).ready(function(){


    });
</script>
@endsection


