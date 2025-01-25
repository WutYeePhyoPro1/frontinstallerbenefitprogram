@extends('layouts.admin.app')

@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h4 class="mb-3">Return Detail</h4>
                <table class="tables">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No</th>
                            <th>Category/ Group</th>
                            <th>Return Price Amount</th>
                            <th style="text-align:right !important;">Return Point</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($groupedreturns as $idx=>$groupedreturn)
                        <tr>
                            <td>{{ ++$idx }}</td>
                            <td>
                                {{ $groupedreturn->category_remark  }} / {{ $groupedreturn->group_name  }}
                            </td>
                            <td>{{ number_format($groupedreturn->return_price_amount,0,'.',',') }} <span class="ms-4">MMK</span></td>

                            <td class="text-right">
                               {{ $groupedreturn->return_point }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>
@endsection
@section('js')
<script>

</script>
@stop
