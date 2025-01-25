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

            {{-- <div class="col-lg-12 mb-2 bg-light p-4 card-banners" style="
                background: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)),url('{{ asset('assets/img/13295527_5153829.jpg') }}') ;
                background-repeat:no-repeat;
                background-size:cover;
            "> --}}
            <div class="col-lg-12 mb-2 bg-light p-2 mb-0 card-banners" >
                <div class="row">
                    <div class="col-md-4 mx-auto  md-mb-0">
                        <div class="card shadow mb-0" style="box-shadow: rgba(0, 0, 0, 0.16) 0px 16px 40px 0px !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6>Balance Amount</h6>
                                        <div class="d-flex align-items-center">
                                            <h1 class="balance-highlight">{{ number_format($installercard->totalamount,0,'.',',') }}</h1> <h6 class="ml-2">MMK</h6>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <h6>Balance Point</h6>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <h1 class="balance-highlight">{{ intval($installercard->totalpoints) }}</h1></h6>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-6 text-center">
                                    <span class="badge badge-labels">Income</span>
                                    <p class="text-success"><i class="fas fa-arrow-down" ></i> <span style="vertical-align: middle;">{{ number_format($earnedamount,0,'.',',') }}  MMK</span></p>
                                </div>

                                <div class="col-6 text-center">
                                    <span class="badge badge-labels">Expense</span>
                                    <p class="text-danger"><i class="fas fa-arrow-up" ></i> <span style="vertical-align: middle;">{{ $usedamount ? number_format($usedamount,0,'.',',') : '0'}} MMK</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-8 mx-auto">
                <div class="row">
                    <div class="col-12 text-center">
                        <button class="btn btn-link show-more-btn" data-toggle="collapse" data-target="#creditInfo" aria-expanded="false">
                            Show More
                          </button>
                    </div>

                    <div id="creditInfo" class="col-12 collapse">
                        <div class="row">
                            <div class="col-6">Balance Points:</div>
                            <div class="col-6 text-right"> <span>{{ intval($installercard->totalpoints) }}</span></div>
                        </div>
                        <div class="row">
                            <div class="col-6">Balance Amount:</div>
                            <div class="col-6 text-right"> <span>{{ number_format($installercard->totalamount,0,'.',',') }} MMK </span></div>
                        </div>
                        <div class="row">
                            <div class="col-6">Used Points:</div>
                            <div class="col-6 text-right"> <span>{{ $usedpoints ? $usedpoints : '0' }}</span></div>
                        </div>
                        <div class="row">
                            <div class="col-6">Used Amount:</div>
                            <div class="col-6 text-right"><span>{{ $usedamount ? number_format($usedamount,0,'.',',') : '0'}}  MMK</span></div>
                        </div>
                        <div class="row">
                            <div class="col-6">Credit Points:</div>
                            <div class="col-6 text-right"> <span>{{ intval($installercard->credit_points) }} </span></div>
                        </div>
                        <div class="row">
                            <div class="col-6">Credit Amount:</div>
                            <div class="col-6 text-right"><span>{{ number_format($installercard->credit_amount,0,'.',',') }} MMK</span></div>
                        </div>
                        <div class="row">
                            <div class="col-6">Expired Point:</div>
                            <div class="col-6 text-right"> <span>{{ $expiredpoints ? $expiredpoints : 0 }}</span></div>
                        </div>
                        <div class="row">
                            <div class="col-6">Expired Amount:</div>
                            <div class="col-6 text-right"><span>{{ $expiredamounts ? number_format($expiredamounts,0,'.',',') : 0 }} MMK</span></div>
                        </div>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-12">
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <h4>Recent Transactions</h4>
                            <a href="{{ route('histories.index') }}"><i class="fas fa-search"></i></a>
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
                        <div>
                            <p class="pb-0 mb-0">{{ $collectiontransaction->invoice_number }}</p>
                            <p>{{  \Carbon\Carbon::parse($collectiontransaction->collection_date)->format('d/m/Y h:m:s A') }}</p>
                        </div>
                        <p class="text-success mr-2 mb-0">+ {{ number_format($collectiontransaction->total_save_value,0,'.',',') }} MMK</p>
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
                            <div>
                                <p>{{ $redemptiontransaction->document_no }}</p>
                                <p>{{  \Carbon\Carbon::parse($redemptiontransaction->redemption_date)->format('d/m/Y h:m:s A') }}</p>
                            </div>
                            <p class="text-danger mr-2 mb-0">- {{ number_format($redemptiontransaction->total_cash_value,0,'.',',')  }} MMK</p>
                        </div>
                    @endforeach
                </div>


                <div class="row my-2">
                    <div class="col-12">
                        <div class="d-flex justify-content-between py-1">
                            <h4>My Cards</h4>
                            <a href="{{ route('installercards.track',$installercard->card_number) }}">View All</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="container mt-2">
                        {{-- <h5 class="mb-3">My Cards <a href="#" class="text-primary float-end">View All</a></h5> --}}
                        <div class="d-flex overflow-auto">
                          <!-- Card 1 -->
                          <div class="card me-3 mycards" style="">
                            <div class="card-body">
                              {{-- <h6>Balance</h6> --}}
                              <h5><i>Installer Card</i></h5>
                              <h3 style="word-spacing: 10px;">{{   preg_replace("/(\d{4})(\d{4})(\d{2})/", "$1 $2 $3", $installercard->card_number) }}</h3>
                              <p class="mb-2">{{ $installercard->fullname }}</p>
                              <p>Issued: {{  \Carbon\Carbon::parse($installercard->issued_at)->format('d/m/Y') }}</p>
                            </div>
                          </div>

                          {{-- {{ dd($oldinstallercards)  }} --}}
                          @foreach($oldinstallercards as $oldinstallercard)
                          <div class="card me-3 mycards olds" style="">
                            <div class="card-body">
                              {{-- <h6>Balance</h6> --}}
                              <h5 class="text-light"><i>Installer Card</i></h5>
                              <h3 style="word-spacing: 10px;">{{   preg_replace("/(\d{4})(\d{4})(\d{2})/", "$1 $2 $3", $oldinstallercard->card_number) }}</h3>
                              <p class="mb-2">{{ $oldinstallercard->fullname }}</p>
                              <p>Issued: {{  \Carbon\Carbon::parse($oldinstallercard->issued_at)->format('d/m/Y') }}</p>
                            </div>
                          </div>
                          @endforeach
                          {{-- <!-- Card 2 -->
                          <div class="card me-3" style="min-width: 300px; background: linear-gradient(135deg, #FF7F50, #FF4500); color: white;">
                            <div class="card-body">
                              <h6>Balance</h6>
                              <h3>$890.45</h3>
                              <p class="mb-2">Card Number: **** 1234</p>
                              <p>Expiry: 11/24 | CCV: 345</p>
                            </div>
                          </div>
                          <!-- Card 3 -->
                          <div class="card me-3" style="min-width: 300px; background: linear-gradient(135deg, #1E90FF, #00BFFF); color: white;">
                            <div class="card-body">
                              <h6>Balance</h6>
                              <h3>$450.00</h3>
                              <p class="mb-2">Card Number: **** 5678</p>
                              <p>Expiry: 01/26 | CCV: 789</p>
                            </div>
                          </div> --}}
                        </div>
                      </div>
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
        document.querySelectorAll('.show-more-btn').forEach(button => {
            button.addEventListener('click', function () {
              const expanded = this.getAttribute('aria-expanded') === 'true';
              this.textContent = expanded ? 'Show More' : 'Show Less';
            });
          });

    });
</script>
@endsection


