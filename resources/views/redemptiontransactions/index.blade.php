@extends('layouts.admin.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">Redemption Transaction Lists</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-2">
                <form action="{{ route('redemptiontransactions.search') }}" method="GET">
                    <div class="row justify-content-end align-items-end">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" id="" class="form-control form-control-sm" name="docno" placeholder="Enter Document No" value="{{ request()->get('docno') }}"/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="status" id="status" class="status form-control form-control-sm">
                                    <option value="">Choose Status</option>
                                    <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request()->get('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request()->get('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="paid" {{ request()->get('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="finished" {{ request()->get('status') == 'finished' ? 'selected' : '' }}>Finished</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="{{ request()->get('from_date') ? 'date' : 'text'  }}" name="from_date" id="from_date" class="from_date form-control form-control-sm" placeholder="From Date: mm/dd/yyyy" onfocus="(this.type='date')" onchange='changeHandler(this)' value="{{ request()->get('from_date')}}"/>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="{{ request()->get('to_date') ? 'date' : 'text'  }}" name="to_date" id="to_date" class="to_date form-control form-control-sm" placeholder="To Date: mm/dd/yyyy" onfocus="(this.type='date')" onchange="changeHandler(this)" value="{{ request()->get('to_date') }}">
                            </div>
                        </div>

                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>

                            @if(count(request()->query()) > 0)
                                <button type="button" id="btn-clear" class="btn btn-light"><i class="fas fa-sync-alt"></i></button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
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
            </div>


            <div class="col-lg-12">
                <div class="rounded mb-3 table-container">
                    <table class="table table-success table-hover mb-0 tbl-server-info" id="lucky_draw_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                <th>Document No.</th>
                                <th>Branch</th>
                                <th>Card Number</th>
                                <th>Total Points Redeemed</th>
                                <th>Total Cash Value</th>
                                <th>Status</th>
                                <th>Requester</th>
                                <th>Prepare By</th>
                                {{-- <th>Approved By</th> --}}
                                {{-- <th>Redemption Date</th> --}}
                            </tr>
                        </thead>
                        <tbody id="tabledata" class="ligth-body">
                            @foreach ($redemptiontransactions as $idx=>$redemptiontransaction)
                                <tr style="cursor: pointer" onclick="window.location.href='{{ route('redemptiontransactions.show',$redemptiontransaction->uuid) }}'" >
                                    <td>{{ ++$idx }}</td>
                                    <td>{{ $redemptiontransaction->document_no }}</td>
                                    <td>{{ $redemptiontransaction->branch->branch_name_eng }}</td>
                                    <td>{{ $redemptiontransaction->installer_card_card_number  }}</td>
                                    <td>{{ $redemptiontransaction->total_points_redeemed  }}</td>
                                    <td>{{ number_format($redemptiontransaction->total_cash_value,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                    <td>
                                        {!! $redemptiontransaction->status == "pending" ? "<span class='badge bg-warning'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "approved" ? "<span class='badge bg-success'>$redemptiontransaction->status</span>" :($redemptiontransaction->status == "rejected"? "<span class='badge bg-danger'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "paid"? "<span class='badge bg-primary'>$redemptiontransaction->status</span>" : ($redemptiontransaction->status == "finished"? "<span class='badge bg-secondary'>$redemptiontransaction->status</span>" : '')))) !!}
                                    </td>
                                    <td>{{ $redemptiontransaction->requester  }}</td>
                                    <td>{{ $redemptiontransaction->prepareby->name  }}</td>
                                    {{-- <td>{{ $redemptiontransaction->approvedby ? $redemptiontransaction->approvedby->name : 'N/A' }}</td> --}}
                                    {{-- <td>{{  \Carbon\Carbon::parse($redemptiontransaction->redemption_date)->format('d-m-Y') }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $redemptiontransactions->appends(request()->all())->links('pagination::bootstrap-4') }}
                    </div>
                    <div class="myloader">
                        <div class="loader-item"></div>
                        <div class="loader-item"></div>
                        <div class="loader-item"></div>
                    </div>
                </div>
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
        $('.delete-btns').click(function(e){
            {{-- console.log('hi'); --}}
            e.preventDefault();

            Swal.fire({
                title: "Are you sure you want to remove point promotion?",
                text: "Your point promotion will be permanently deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
              }).then((result) => {
                if (result.isConfirmed) {
                    {{-- console.log($(this).closest('form')); --}}
                    $(this).closest('form').submit();
                }
              });

        });
    });
    function changeHandler(input){
        if(input.value){
            input.type = 'date'
        }else{
            input.type = 'text'
            input.blur();
        }
    }

    // Start Clear btn
    document.getElementById("btn-clear").addEventListener("click",function(){
        window.location.href = window.location.href.split("?")[0];
   });
   // End Clear btn
</script>
@stop
