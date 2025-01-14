@extends('layouts.admin.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="mb-3">Installer Cards Tracking</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mb-4">
                <div class="row">
                    @foreach ($allinstallercards as $installercard)
                        <div class="col-md-4  mb-3 md-mb-0">
                            <div class="installercards {{ ($installercard->status != 1) ? "inactivecard" : '' }}">
                                <h5 class="text-center">Installer Card
                                    @if($installercard->status == 1)
                                    <span class="float-right"><i class="fas fa-check-circle text-info"></i></span>
                                    @endif
                                </h5>
                                <p><strong>Card Number:</strong> {{ $installercard->card_number }}</p>
                                <p><strong>Installer Name:</strong> {{ $installercard->fullname }}</p>
                                <p><strong>Phone Number:</strong> {{ $installercard->phone }}</p>
                                <p><strong>NRC:</strong> {{ $installercard->nrc }}</p>
                                <p><strong>Points Expiring Soon:</strong> 100 points by 2024-12-31</p>
                            </div>
                        </div>
                    @endforeach
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


        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>

@endsection


@section('js')
<script type="text/javascript">
    $(document).ready(function(){


    });
</script>
@endsection
