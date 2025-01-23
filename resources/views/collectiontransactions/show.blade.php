@extends('layouts.admin.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-8 mx-auto">
                {{-- <div class="card"> --}}
                    <div class="card-header">
                        <h4 class="mb-3 text-left"><span>Collection Transaction Detail</span></h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ asset('assets/img/file.png') }}" width="100px" alt="file">
                            {{-- <h2>{{ $collectiontransaction->total_points_collected }}</h2> --}}
                            <h2 class="my-2">+ {{ number_format($collectiontransaction->total_save_value,0,'.',',') }} <span class="ms-4">MMK</span></h2>
                        </div>
                        <hr/>

                        <div class="row">
                            <div class="col-6">
                                <span>Transaction No.</span>
                            </div>
                            <div class="col-6 text-right">
                                <span>{{ $collectiontransaction->document_no }}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <span>Transaction Time</span>
                            </div>
                            <div class="col-6 text-right">
                                <span>{{  \Carbon\Carbon::parse($collectiontransaction->collection_date)->format('d/m/Y h:m:s A') }}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <span>Total Points Collected</span>
                            </div>
                            <div class="col-6 text-right">
                                <span>{{ $collectiontransaction->total_points_collected }}</span>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-6">
                                <span>Total Save Amount</span>
                            </div>
                            <div class="col-6 text-right">
                                <span>{{ number_format($collectiontransaction->total_save_value,0,'.',',') }} <span class="ms-4">MMK</span></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <table class="tables">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>No</th>
                                            <th>Category/ Group</th>
                                            <th>Sale Amount</th>
                                            <th style="text-align:right !important;">Point Earned/ Amount Earned</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($installercardpoints as $idx=>$installercardpoint)
                                        <tr>
                                            <td>{{ ++$idx }}</td>
                                            <td>
                                                {{ $installercardpoint->category_remark  }} / {{ $installercardpoint->group_name  }}
                                            </td>
                                            <td>
                                                {{ number_format($installercardpoint->saleamount,0,'.',',') }} <span class="ms-4">MMK</span>
                                            </td>
                                            <td class="text-right">
                                                <span class="d-block">{{ $installercardpoint->points_earned  }}</span>
                                                <span class="d-block">{{ number_format($installercardpoint->amount_earned,0,'.',',') }} <span class="ms-4">MMK</span></span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                         @if(count($returnbanners) > 0)

                        <div class="my-2" style="border-top: 2px dashed silver"></div>

                        <div class="row">
                            <div class="col-lg-12"  >
                                <h5>Return Product Record</h5>
                                <table class="tables">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>No</th>
                                            <th>Document No.</th>
                                            <th>Total Return Value</th>
                                            <th style="text-align:right !important;">Total Return Point</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($returnbanners as $idx=>$returnbanner)
                                        <tr>
                                            <td>{{ ++$idx }}</td>
                                            <td>
                                                <a href="{{ route('returnbanners.show',$returnbanner->uuid) }}"  class="text-underline" style="text-underline-offset: 5px;">{{ $returnbanner->return_product_docno  }}</a>
                                            </td>
                                            <td>
                                                {{  number_format($returnbanner->total_return_value,0,'.',',') }} <span class="ms-4">MMK</span>
                                            </td>
                                            <td class="text-right">
                                                {{ $returnbanner->total_return_points }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif


                    </div>
                {{-- </div> --}}
            </div>


        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>
@endsection

@section('css')
<style type="text/css">

</style>
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
