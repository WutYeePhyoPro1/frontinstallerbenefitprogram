@extends('layouts.admin.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-8 mx-auto">
                <div class="card-header">
                    <h4 class="mb-3 text-left"><span>Redemption Transaction Detail</span></h4>
                </div>

                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('assets/img/file.png') }}" width="100px" alt="file">
                        {{-- <h2>{{ $collectiontransaction->total_points_collected }}</h2> --}}
                        <h2 class="my-2">- {{ number_format($redemptiontransaction->total_cash_value,0,'.',',') }} <span class="ms-4">MMK</span></h2>
                    </div>
                    <hr/>

                    <div class="row">
                        <div class="col-6">
                            <span>Transaction No.</span>
                        </div>
                        <div class="col-6 text-right">
                            <span>{{ $redemptiontransaction->document_no }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <span>Transaction Time</span>
                        </div>
                        <div class="col-6 text-right">
                            <span>{{  \Carbon\Carbon::parse($redemptiontransaction->redemption_date)->format('d/m/Y h:m:s A') }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <span>Total Points Redeemed</span>
                        </div>
                        <div class="col-6 text-right">
                            <span>- {{ $redemptiontransaction->total_points_redeemed }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <span>Total Cash Value</span>
                        </div>
                        <div class="col-6 text-right">
                            <span>- {{ number_format($redemptiontransaction->total_cash_value,0,'.',',') }} <span class="ms-4">MMK</span></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <table class="tables">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th>No</th>
                                        <th>Invoice Number</th>
                                        <th>Category/ Group</th>
                                        <th style="text-align:right !important;">Points Redeemed/ Redemption Amount</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($pointsredemptions as $idx=>$pointsredemption)
                                    <tr>
                                        <td>{{ ++$idx }}</td>
                                        <td>{{ $pointsredemption->installercardpoint->collectiontransaction->invoice_number  }}</td>
                                        <td>{{ $pointsredemption->installercardpoint->category_remark }} / {{ $pointsredemption->installercardpoint->group_name }}</td>
                                        <td class="text-right">
                                            <span class="d-block">{{ $pointsredemption->points_redeemed  }}</span>
                                            <span class="d-block">{{ number_format($pointsredemption->redemption_amount,0,'.',',') }} <span class="ms-4">MMK</span></span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>

{{-- Start Modal Area --}}
<div class="modal fade show_image" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="show_image_div">
        </div>
    </div>
</div>
{{-- End Modal Area--}}
@endsection
@section('js')
<script>
    $(document).ready(function() {






        var previewimages = function(input,output){

            // console.log(input.files);

            if(input.files){
                 var totalfiles = input.files.length;
                 // console.log(totalfiles);
                 if(totalfiles > 0){
                      $('.gallery').addClass('removetxt');
                 }else{
                      $('.gallery').removeClass('removetxt');
                 }
                 console.log(input.files);

                 for(var i = 0 ; i < totalfiles ; i++){
                      var filereader = new FileReader();


                      filereader.onload = function(e){
                           // $(output).html("");
                           $($.parseHTML('<img>')).attr('src',e.target.result).appendTo(output);
                      }

                      filereader.readAsDataURL(input.files[i]);

                 }
            }

       };

        $('#images').change(function(){
                previewimages(this,'.gallery');
        });


        function showImageDetail()
        {
            var src = $(this).attr("src");
            console.log(src)
{{--
                $('#show_image_div').html('');
                $('#show_image_div').append(`
                    <img class="rounded img-fluid" id="table_image" src="" alt="profile" width="" height="" style="text-align:center;">
                `);

            // $("#table_image").prop('src', src);
            $("#table_image").attr('src', src);

            $('.show_image').modal('show'); --}}
        }

        $('.redemptiontransactionimages').click(function(){
            var src = $(this).attr("src");
            {{-- console.log(src) --}}

            $('#show_image_div').html('');
            $('#show_image_div').append(`
                <img class="rounded img-fluid" id="table_image" src="" alt="profile" width="" height="" style="text-align:center;">
            `);

            // $("#table_image").prop('src', src);
            $("#table_image").attr('src', src);

            $('.show_image').modal('show');
        });

    });
</script>
@stop
