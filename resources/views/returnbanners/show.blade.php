@extends('layouts.admin.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 logo-container">
                    <div class="pro1logo">
                        <img src="{{ asset('images/PRO-1-Global-Logo.png') }}" width="160px" style="object-fit: cover;" alt="pro1logo" />
                    </div>
                    <div>
                        <h4 class="mb-3 text-center text-danger"><span>Return Product Record</span></h4>
                        <h5 class="text-center">Installer Card - {{ $returnbanner->installer_card_card_number }}</h5>
                    </div>
                    <div class="d-flex justify-content-between font-weight-bold">
                        <div class="d-flex flex-column">
                            <span>Branch - {{ $returnbanner->branch->branch_name_eng }}</span>
                        </div>
                        <span>Date: {{  \Carbon\Carbon::parse($returnbanner->return_action_date)->format('d-m-Y') }}</span>
                    </div>
                    <h6 class="font-weight-bold text-mute mt-2">{{ $returnbanner->return_product_docno }} <span class="text-primary mx-2 fw-bolder">----></span> {{ $returnbanner->ref_invoice_number }}</h6>
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
                                <th>Category</th>
                                <th>Group</th>
                                <th>Sale Amount</th>
                                <th>Return Price Amount</th>
                                {{-- <th>Actual Sale Amount</th> --}}
                                <th>Before Return Point</th>
                                <th>Return Point</th>
                                <th>After Return Point</th>

                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach ($groupedreturns as $idx=>$groupedreturn)
                            <tr>
                                <td>{{ ++$idx }}</td>
                                <td>{{ $groupedreturn->category_remark  }}</td>
                                <td>{{ $groupedreturn->group_name  }}</td>
                                <td>{{  number_format($groupedreturn->referencereturninstallercardpoint->saleamount,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                <td>{{ number_format($groupedreturn->return_price_amount,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                {{-- <td>{{ number_format($groupedreturn->referencereturninstallercardpoint->saleamount - $groupedreturn->return_price_amount,0,'.',',') }} <span class="ms-4">MMK</span></td> --}}
                                <td>{{ $groupedreturn->referencereturninstallercardpoint->points_earned }}</td>
                                <td>{{ $groupedreturn->return_point }}</td>
                                <td>{{ $groupedreturn->installercardpoint->points_earned }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="font-weight-bold">Total Return Value</td>
                                <td>{{ number_format($returnbanner->total_return_value,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                <td></td>
                                <td></td>
                            </tr>
                            {{-- <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="font-weight-bold">Total Actual Sale Amount</td>
                                <td>{{ number_format($returnbanner->referencereturncollectiontransaction->total_sale_cash_amount -  $returnbanner->total_return_value,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                <td></td>
                            </tr> --}}

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <button type="button" id="back-btn" class="btn btn-light mr-2" onclick="window.history.back();">Back</button>
            </div>


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
    });
</script>
@stop
