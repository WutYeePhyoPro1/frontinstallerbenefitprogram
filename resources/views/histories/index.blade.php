@extends('layouts.admin.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            {{-- <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="mb-3">Histories</h4>
                    </div>
                </div>
            </div> --}}

            <div class="col-lg-8 mx-auto">
                <h4 class="mb-3">Histories</h4>
                <form action="" method="">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-3">
                            <label for="startdate">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="startdate" id="startdate" class="form-control form-control-sm rounded-0" value="{{ request()->startdate ?? $startDate }}"/>
                       </div>

                       <div class="col-md-4 mb-3">
                            <label for="enddate">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="enddate" id="enddate" class="form-control form-control-sm rounded-0"  value="{{ request()->enddate ??  $gettoday }}"/>
                        </div>
                        <div class="col-auto">
                            <button type="submit" id="search-btn" class="btn btn-primary rounded">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(count(request()->query()) > 0)
                                <button type="button" id="btn-clear" class="btn btn-light" onclick="window.location.href = window.location.href.split('?')[0];"><i class="fas fa-sync-alt"></i></button>
                            @endif
                        </div>
                    </div>
                </form>
                <div id="earnings" class="transactions">

                    @foreach ($transactions as $transaction)
                        @if (class_basename($transaction) == "CollectionTransaction")
                        <div class="transaction" onclick="window.location.href='{{ route('collectiontransactions.show',$transaction->uuid) }}'">
                        @else
                        <div class="transaction" onclick="window.location.href='{{ route('redemptiontransactions.show',$transaction->uuid) }}'">
                        @endif
                            {{-- {{ dd(class_basename($transaction)) }} --}}
                            <div>
                                <p>{{ class_basename($transaction) == "CollectionTransaction" ? $transaction->invoice_number : $transaction->document_no }}</p>
                                @if (class_basename($transaction) == "CollectionTransaction")
                                    <p>{{  \Carbon\Carbon::parse($transaction->collection_date)->format('d/m/Y h:m:s A') }}</p>
                                @else
                                    <p>{{  \Carbon\Carbon::parse($transaction->redemption_date)->format('d/m/Y h:m:s A') }}</p>
                                @endif
                            </div>

                            @if (class_basename($transaction) == "CollectionTransaction")
                                <div>
                                    <p class="text-success mr-2 mb-0">+ {{ number_format($transaction->total_save_value, 0, '.', ',') }} MMK</p>
                                </div>
                            @else
                                <div>
                                    <p class="text-danger mr-2 mb-0">- {{ number_format($transaction->total_cash_value, 0, '.', ',') }} MMK</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center">
                    {{ $transactions->links('pagination::bootstrap-4') }}
                </div>
            </div>


        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>


@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#startdate,#enddate").flatpickr({
            dateFormat: "Y-m-d",
            {{-- minDate: "today", --}}
            {{-- maxDate: new Date().fp_incr(30) --}}
       });
    });
</script>
@endsection
