@extends('layouts.admin.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">Redemption Transaction Notifications</h4>
                    </div>
                </div>
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
                <div class="table-responsive rounded mb-3">
                    <table class="table table-hover mb-0 tbl-server-info" id="lucky_draw_list">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>No</th>
                                <th>Document No.</th>
                                <th>Branch</th>
                                <th>Card Number</th>
                                <th>Total Points Redeemed</th>
                                <th>Total Cash Value</th>
                                <th>Status</th>
                                <th>Prepare By</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                @foreach (Auth::user()->unreadNotifications as $idx=>$notification)
                                <tr style="cursor: pointer" onclick="window.location.href='{{ route('redemptiontransactions.show',$notification->data['redemption_transaction_uuid']) }}'">
                                    <td>{{ ++$idx }}</td>
                                    <td>{{ $notification->data['document_no']  }}
                                        {!! $notification->data['status'] == 'pending' ? '<i class="fas fa-comment-dots text-warning ml-2"></i>' : '' !!}
                                    </td>
                                    <td>{{ $notification->data['branchname']  }}</td>
                                    <td>{{ $notification->data['installer_card_card_number']  }}</td>
                                    <td>{{ $notification->data["total_points_redeemed"]  }}</td>
                                    <td>{{ number_format($notification->data["total_cash_value"],0,'.',',') }} <span class="ms-4">MMK</span></td>

                                    <td>
                                        <span class="badge {{ $notification->data['status'] == 'pending' ? 'bg-warning' : ($notification->data['status'] == 'approved' ? 'bg-success' : ($notification->data['status'] == 'rejected' ? 'bg-danger' : ($notification->data['status'] == 'paid' ? 'bg-primary' : ($notification->data['status'] == 'finished' ? 'bg-secondary' : '')))) }}">{{ $notification->data["status"]  }}</span>
                                    </td>
                                    <td>{{ $notification->data["prepare_byname"]  }}</td>


                                </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
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

    });
</script>
@stop
