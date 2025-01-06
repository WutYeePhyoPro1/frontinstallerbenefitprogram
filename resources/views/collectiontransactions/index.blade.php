@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">Collection Transaction Lists</h4>
                    </div>
                </div>
            </div>
            {{-- {{ dd(request()->query() ) }} --}}
            <div class="col-lg-12 mb-2">
                <form action="{{ route('collectiontransactions.search') }}" method="GET">
                    <div class="row justify-content-end align-items-end">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" id="" class="form-control form-control-sm" name="docno" placeholder="Enter Document No" value="{{ request()->get('docno') }}"/>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" id="" class="form-control form-control-sm" name="invoice_number" placeholder="Enter Invoice Number" value="{{ request()->get('invoice_number') }}"/>
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
                    <table class="table table-warning table-hover mb-0 tbl-server-info" id="lucky_draw_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                <th>Document No.</th>
                                <th>Branch</th>
                                <th>Card Number</th>
                                <th>Invoice Number</th>
                                <th>Total Points Collected</th>
                                <th>Total Save Value</th>
                                <th>Collection Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tabledata" class="ligth-body">
                            @foreach ($collectiontransactions as $idx=>$collectiontransaction)
                                <tr style="cursor: pointer" onclick="window.location.href='{{ route('collectiontransactions.show',$collectiontransaction->uuid) }}'" >
                                    <td>{{$idx + $collectiontransactions->firstItem()}}</td>
                                    <td>{{ $collectiontransaction->document_no }}</td>
                                    <td>{{ $collectiontransaction->branch->branch_name_eng }}</td>
                                    <td>{{ $collectiontransaction->installer_card_card_number  }}</td>
                                    <td>{{ $collectiontransaction->invoice_number  }}</td>
                                    <td>{{ $collectiontransaction->total_points_collected  }}</td>
                                    <td>{{ number_format($collectiontransaction->total_save_value,0,'.',',') }} <span class="ms-4">MMK</span></td>
                                    <td>{{ Carbon\Carbon::parse($collectiontransaction->collection_date)->format('M d Y') }}</td>
                                    <td>
                                        @if($collectiontransaction->isDeleteAuthUser() && $collectiontransaction->allowDelete())
                                            <form action="{{ route('collectiontransactions.destroy',$collectiontransaction->uuid) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a class="text-danger delete-btns"><i class="fas fa-trash"></i></a>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $collectiontransactions->appends(request()->all())->links('pagination::bootstrap-4') }}
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
            e.stopPropagation();
            {{-- console.log('hay'); --}}

            Swal.fire({
                title: "Are you sure you want to delete collection transaction?",
                text: "All the collected will be removed recursively.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, send it!"
                }).then((result) => {
                if (result.isConfirmed) {

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
