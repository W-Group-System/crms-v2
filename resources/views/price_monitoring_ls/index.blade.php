@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Price Monitoring List
            @if(auth()->user()->role->type == 'LS')
            <button type="button" class="btn btn-md btn-outline-primary" name="add_price_monitoring" id="addPrfBtn" data-toggle="modal" data-target="#AddPriceMonitoringLs">New</button>
            @elseif (auth()->user()->role->type == 'IS')
            <button type="button" class="btn btn-md btn-outline-primary" name="add_price_monitoring" id="addPrfBtn" data-toggle="modal" data-target="#AddPriceMonitoring">Add</button>
            @endif
            </h4>
            <div class="form-group">
                <form method="GET" >
                    <label>Show : </label>
                    <label class="checkbox-inline">
                        <input name="open" class="price_monitoring" type="checkbox" value="10" @if($open == 10) checked @endif> Open
                    </label>
                    <label class="checkbox-inline">
                        <input name="close" class="price_monitoring" type="checkbox" value="30" @if($close == 30) checked @endif> Closed
                    </label>
                    <button type="submit" class="btn btn-sm btn-primary">Filter Status</button>
                </form>
            </div>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-5">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Prices Request" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            @if (auth()->user()->role->type == 'LS' || auth()->user()->role->type == 'ITD')
            <div class="table-responsive" style="overflow: auto; height: 80vh;">
                <table class="table table-striped table-bordered table-hover" id="price_monitoring_table">
                    <thead>
                        <tr>
                            <!-- <th>Action</th> -->
                            <th>Price Request #</th>
                            <th>Date Created</th>
                            <th>Client Name</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($price_monitorings) > 0)
                            @foreach ($price_monitorings as $priceMonitoring)
                            <tr>
                                <!-- <td align="center">
                                    <a href="{{ url('price_monitoring_local/view/' . $priceMonitoring->id) }}" class="btn btn-sm btn-outline-info btn-outline" title="View Price Request"><i class="ti-eye"></i></a>
                                    <button type="button" class="btn btn-sm btn-warning editBtn" data-primarysales="{{$priceMonitoring->PrimarySalesPersonId}}" data-secondarysales="{{$priceMonitoring->SecondarySalesPersonId}}"

                                        data-target="#editPriceRequest{{ $priceMonitoring->id }}" data-toggle="modal" title='Edit Price Request'>
                                        <i class="ti-pencil"></i>
                                    </button>  
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" onclick="confirmDelete({{ $priceMonitoring->id }})" title='Delete Request'>
                                        <i class="ti-trash"></i>
                                    </button>
                                </td> -->
                                <td><a href="{{ url('price_monitoring_local/view/' . $priceMonitoring->id.'/'.$priceMonitoring->PrfNumber) }}" title="View Price Request">{{ optional($priceMonitoring)->PrfNumber }}</a></td>
                                <td>{{  date('m-d-y', strtotime($priceMonitoring->DateRequested)) }}</td>
                                <td>{{ optional($priceMonitoring->client)->Name }}</td>
                                <td>
                                    <!-- @if($priceMonitoring->Status == 10)
                                        Open
                                    @elseif($priceMonitoring->Status == 30)
                                        Closed
                                    @else
                                        {{ $priceMonitoring->Status }}
                                    @endif -->
                                    @if($priceMonitoring->Status == 10)
                                        <div class="badge badge-success">Open</div>
                                    @elseif($priceMonitoring->Status == 30)
                                        <div class="badge badge-warning">Closed</div>
                                    @elseif($priceMonitoring->Status == 50)
                                        <div class="badge badge-danger">Cancelled</div>
                                    @endif
                                </td>
                                <td>{{ optional($priceMonitoring->progressStatus)->name }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No data available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <!-- {!! $price_monitorings->appends(['search' => $search, 'open' => $open, 'close' => $close])->links() !!} -->
                {{ $price_monitorings->appends(request()->query())->links() }}
                @php
                    $total = $price_monitorings->total();
                    $currentPage = $price_monitorings->currentPage();
                    $perPage = $price_monitorings->perPage();
    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
                </div>
            </div>
            @elseif (auth()->user()->role->type == 'IS')
            <div class="table-responsive" style="overflow: auto; height: 80vh;">
                <table class="table table-striped table-bordered table-hover" id="price_monitoring_table">
                    <thead>
                        <tr>
                            <!-- <th>Action</th> -->
                            <th>Price Request #</th>
                            <th>Date Created</th>
                            <th>Client Name</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $price_monitorings as $priceMonitoring)
                        <tr>
                            <!-- <td align="center">
                                <a href="{{ url('price_monitoring/view/' . $priceMonitoring->id) }}" class="btn btn-sm btn-outline-info" title="View Price Request"><i class="ti-eye"></i></a>
                                <button type="button" class="btn btn-sm btn-outline-warning"
                                    data-target="#editPriceRequest{{ $priceMonitoring->id }}" data-toggle="modal" title='Edit Price Request'>
                                    <i class="ti-pencil"></i>
                                </button>  
                                <button type="button" class="btn btn-sm btn-outline-danger delete-btn" onclick="confirmDelete({{ $priceMonitoring->id }})" title='Delete Request'>
                                    <i class="ti-trash"></i>
                                </button>
                            </td> -->
                            <td><a href="{{ url('price_monitoring_local/view/' . $priceMonitoring->id.'/'.$priceMonitoring->PrfNumber) }}"  title="View Price Request">{{ optional($priceMonitoring)->PrfNumber }}</a></td>
                            <td>{{  date('m-d-y', strtotime($priceMonitoring->DateRequested)) }}</td>
                            <td>{{ optional($priceMonitoring->client)->Name }}</td>
                            <td>
                                <!-- @if($priceMonitoring->Status == 10)
                                        Open
                                @elseif($priceMonitoring->Status == 30)
                                    Closed
                                @else
                                    {{ $priceMonitoring->Status }}
                                @endif -->
                                @if($priceMonitoring->Status == 10)
                                    <div class="badge badge-success">Open</div>
                                @elseif($priceMonitoring->Status == 30)
                                    <div class="badge badge-warning">Closed</div>
                                @elseif($priceMonitoring->Status == 50)
                                    <div class="badge badge-danger">Cancelled</div>
                                @endif
                            </td>
                            <td>{{ optional($priceMonitoring->progressStatus)->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- {!! $price_monitorings->appends(['search' => $search, 'open' => $open, 'close' => $close])->links() !!} -->
                {{ $price_monitorings->appends(request()->query())->links() }}
                @php
                    $total = $price_monitorings->total();
                    $currentPage = $price_monitorings->currentPage();
                    $perPage = $price_monitorings->perPage();
    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $(".table").tablesorter({
            theme : "bootstrap",
        })
    })
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/delete_price_request') }}/" + id, 
                    method: 'DELETE',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'The record has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload(); 
                        });
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Something went wrong.',
                            'error'
                        );
                    }
                });
            }
        });
    }   
    
    // $(document).ready(function() {
    //     $('.product-pick').on('change', function() {
    //         var selectedProduct = $(this).find('option:selected');
    //         var selectedType = selectedProduct.data('type');
    //         var selectedApplicationId = selectedProduct.data('application_id');

    //         $('.category-select').val(selectedType).change();

    //         $('.application-select').val(selectedApplicationId).change();
    //     });
    // });
    $(document).ready(function() {
    function handleProductChange(event) {
        var $productSelect = $(event.target);
        var selectedOption = $productSelect.find('option:selected');
        var selectedType = selectedOption.data('type');
        var selectedApplicationId = selectedOption.data('application_id');

        var $row = $productSelect.closest('.add_create_prf_form');
        
        $row.find('.category-select').val(selectedType).change();
        $row.find('.application-select').val(selectedApplicationId).change();
    }

    $(document).on('change', '.product-pick', handleProductChange);

    $("#addPrfBtn").on('click', function() {
            var primarySales = $('[name="PrimarySalesPersonId"]').val();
            
            refreshSecondaryApprovers(primarySales)
        })

        $('.editBtn').on('click', function() {
            var primarySales = $(this).data('primarysales')
            var secondarySales = $(this).data('secondarysales');

            console.log(primarySales);
            
            $.ajax({
                type: "POST",
                url: "{{url('refresh_secondary_persons_prf')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    ps: primarySales,
                },
                success: function(data)
                {
                    setTimeout(() => {
                        $('[name="SecondarySalesPersonId"]').html(data) 
                        // $('[name="SecondarySalesPersonId"]').val(secondarySales) 
                    }, 500);
                }
            })
        })

        $('[name="PrimarySalesPersonId"]').on('change', function() {
            var primarySales = $(this).val();

            refreshSecondaryApprovers(primarySales)
        })

        function refreshSecondaryApprovers(primarySales)
        {
            $.ajax({
                type: "POST",
                url: "{{url('refresh_secondary_persons_prf')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    ps: primarySales,
                },
                success: function(data)
                {
                    setTimeout(() => {
                        $('[name="SecondarySalesPersonId"]').html(data) 
                    }, 500);
                }
            })
        }
})

</script>
@if(auth()->user()->role->type == 'LS')
    @include('price_monitoring_ls.create')
    @foreach ( $price_monitorings as $priceMonitoring)
    @include('price_monitoring_ls.edit')
    @endforeach
@elseif ((auth()->user()->role->type == 'IS'))
    @include('price_monitoring.create')
    @foreach ( $price_monitorings as $priceMonitoring)
    @include('price_monitoring.edit')
    @endforeach
@endif
@endsection