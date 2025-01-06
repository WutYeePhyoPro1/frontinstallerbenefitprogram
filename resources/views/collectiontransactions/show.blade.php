@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 pointpromotioninfos mb-2">
                <h2 class="text-secondary">{{ $collectiontransaction->pointpromotion->name }}</h2>
            </div>
            <div class="col-lg-12 logo-container">
                    <div class="pro1logo">
                        <img src="{{ asset('images/PRO-1-Global-Logo.png') }}" width="160px" style="object-fit: cover;" alt="pro1logo" />
                    </div>

                    <div>
                        <h4 class="mb-3 text-center text-secondary"><span>Collection Transaction</span> <span>( {{ $collectiontransaction->document_no }} )</h4>
                        <h5 class="text-center">Installer Card - {{ $collectiontransaction->installer_card_card_number }}</h5>
                    </div>
                    <div class="d-flex justify-content-between font-weight-bold">
                        <div class="d-flex flex-column">
                            <span>Branch - {{ $collectiontransaction->branch->branch_name_eng }}</span>
                            <span>Installer Name - {{ $collectiontransaction->installercard->fullname }}</span>
                        </div>
                        <span>Collection Date: {{ $collectiontransaction->collection_date }}</span>
                    </div>
                    <h6 class="font-weight-bold text-mute mt-2">{{ $collectiontransaction->invoice_number }}</h6>
                    <small class="text-danger d-block">Expire at: {{ $collectiontransaction->getExpireDate() }}</small>
            </div>

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
                <div class="table-responsive rounded">
                    <table class="table mb-0 tbl-server-info" id="lucky_draw_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                <th>Return</th>
                                <th>Category</th>
                                <th>Group</th>
                                <th>Sale Amount</th>
                                <th>Point Earned</th>
                                <th>Point Redeemed</th>
                                <th>Point Balance</th>
                                <th>Amount Rate</th>
                                <th>Amount Earned</th>
                                <th>Amount Redeemed</th>
                                <th>Amount Balance</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach ($installercardpoints as $idx=>$installercardpoint)
                            <tr class="installercardpoint {{ $installercardpoint->is_redeemed == 1 ? 'redeemed' : '' }}">
                                <td>{{ ++$idx }}</td>
                                {{-- <td class="">
                                    <div class="form-check d-flex justify-center align-items-center">
                                        <input  type="checkbox" value="" id="return" class="form-check-input returns" {{ $installercardpoint->is_returned == 1 ? 'checked' : '' }} disabled />
                                    </div>
                                </td> --}}
                                <td>
                                    {!!   $installercardpoint->is_returned == 1 ? "<i class='fas fa-check-square fa-lg text-primary'></i>" : "<i class='far fa-square fa-lg text-light'></i>"  !!}
                                </td>
                                <td>{{ $installercardpoint->category_remark  }}</td>
                                <td>{{ $installercardpoint->group_name  }}</td>
                                <td>{{ number_format($installercardpoint->saleamount,0,'.',',') }}</td>
                                <td>{{ $installercardpoint->points_earned  }}</td>
                                <td>{{ $installercardpoint->points_redeemed }}</td>
                                <td>{{ $installercardpoint->points_balance }}</td>
                                <td>{{ $installercardpoint->points_earned  }} x {{ intval($installercardpoint->point_based)  }}</td>
                                <td>{{ number_format($installercardpoint->amount_earned,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                <td>{{ number_format($installercardpoint->amount_redeemed,0,'.',',') }}  <span class="ms-4">MMK</span></td>
                                <td>{{ intval($installercardpoint->amount_balance) }}</td>
                            </tr>
                            @endforeach
                            <tr class="">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b class="text-info">{{ number_format($collectiontransaction->total_sale_cash_amount,0,'.',',') }}</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="font-weight-bold">Total Points Collected</td>
                                <td>{{ $collectiontransaction->total_points_collected }}</td>
                                <td class="font-weight-bold">Total Available Points</td>
                                <td>{{ $total_available_points }}</td>
                                <td class="font-weight-bold">Total Save Amount</td>
                                <td>{{ number_format($collectiontransaction->total_save_value,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                <td class="font-weight-bold">Total Available Amount</td>
                                <td>{{ number_format($total_available_amount,0,'.',',') }} <span class="ms-4">MMK</span></td>
                            </tr>

                            {{-- <tr class="coupon-receive-footer2">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total Points Redeemed</td>
                                <td>{{ $collectiontransaction->total_points_redeemed }}</td>
                            </tr> --}}

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-12">
                    @if(strpos($previousRouteName, 'collectiontransactions.show') === 0)
                        <a href="{{route('installercardpoints.detail',$collectiontransaction->installer_card_card_number)}}" id="back-btn" class="btn btn-light mr-2">Back</a>
                    @else
                        <button type="button" id="back-btn" class="btn btn-light mr-2" onclick="window.history.back();">Back</button>
                    @endif

                {{-- @if($collectiontransaction->checkreturn()) --}}
                    <button type="button" id="open_return_btn" class="btn btn-danger">Return Product</button>
                {{-- @endif --}}
            </div>
            <div class="col-lg-12 my-2 py-2" id="return_parts">
                <form id="return-product-form" action="{{ route('collectiontransactions.returnproduct',$collectiontransaction->uuid)}} " method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" id="return_product_docno" name="return_product_docno" class="form-control" placeholder="Scan Return Document No" readonly/>
                            </div>

                            <!-- bootstrap loader -->
                            <div class="d-flex justify-content-center mt-3">
                                <div id="returnloader" class="spinner-border spinner-border-sm d-none" role="status"></div>
                            </div>
                        </div>
                        {{-- <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Deduct Points</button>
                        </div> --}}
                    </div>
                </form>
            </div>

            @if(count($returnbanners) > 0)
            <div class="col-lg-12 mb-4 pb-4">
                <div class="table-responsive rounded">
                    <h5>Return Product Record</h5>
                    <table class="table table-danger mb-0 tbl-server-info" id="lucky_draw_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                <th>Branch</th>
                                <th>Document No.</th>
                                <th>Refference Invoice Number</th>
                                <th>Total Sale Amount</th>
                                <th>Total Return Value</th>
                                <th>Before Return Total Point</th>
                                <th>Total Return Point</th>
                                <th>After Return Total Point</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach ($returnbanners as $idx=>$returnbanner)
                            <tr>
                                <td>{{ ++$idx }}</td>
                                <td>{{ $returnbanner->branch->branch_name_eng  }}</td>
                                <td><a href="{{ route('returnbanners.show',$returnbanner->uuid) }}"  class="text-underline" style="text-underline-offset: 5px;">{{ $returnbanner->return_product_docno  }}</a></td>
                                <td>{{ $returnbanner->ref_invoice_number  }}</td>
                                <td>{{  number_format($returnbanner->referencereturncollectiontransaction->total_sale_cash_amount,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                <td>{{  number_format($returnbanner->total_return_value,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                <td>{{ $returnbanner->referencereturncollectiontransaction->total_points_collected }}</td>
                                <td>{{ $returnbanner->total_return_points }}</td>
                                <td>{{ $returnbanner->referencereturncollectiontransaction->total_points_collected +  $returnbanner->total_return_points }}</td>
                                {{-- <td>{{ $returnbanner->collectiontransaction->total_points_collected }}</td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {

        $('#bm-reject').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            Swal.fire({
                title: "Are you sure you want to reject redemption request?",
                text: "Redemption Transacation will be rejected",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, reject it!"
              }).then((result) => {
                if (result.isConfirmed) {
                    console.log($(this).closest('form'));
                    $('#irerejectform').submit();
                }
              });

        });




        {{-- Start Return Part --}}
        $('#open_return_btn').click(function () {
            $('#return_parts').toggleClass('active');
            $('#return_product_docno').focus();
        });

        var lastKeyTime = 0;
        $(document).keypress(function(event) {
            console.log(event.target);
            if(event.target.name == 'return_product_docno' && $('#return_parts').hasClass('active')){
                // Check if the input is readonly and prevent manual typing
                var inputField = $('#return_product_docno');
                if (inputField.prop('readonly')) {
                    // Append the scanned character to the input field value
                    if (event.key !== 'Enter') {
                        var currentTime = new Date().getTime();
                        if(inputField.val() != '' && !(currentTime - lastKeyTime <= 50)){
                            inputField.val('');
                        }

                        if (currentTime - lastKeyTime <= 50 || inputField.val() === '') {
                            inputField.val(inputField.val() + event.key);
                        } else {
                            inputField.val('');
                        }
                        lastKeyTime = currentTime;
                    }

                    // Prevent form submission when 'Enter' key is pressed by the scanner
                    if (event.key === 'Enter') {
                        event.preventDefault();  // Prevent form submission

                        console.log('Scanned QR Code:', inputField.val());

                        $('#return-product-form').submit();
                    }
                }
            }else{
                event.preventDefault();  // Prevent form submission
                Swal.fire({
                    icon: "warning",
                    title: "Return Box is not yet opened",
                    text: "Please open return box first.",
                });
            }
        });


        {{-- End Return Part --}}

        $('#return-product-form').submit(function(e){
            e.preventDefault();

            $('#returnloader').removeClass('d-none');
            this.submit();
        });
    });
</script>
@stop
