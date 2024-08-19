@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Price Currency List
            <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#addPriceCurrency">Add Price Currency</button>
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex justify-content-end align-items-end">
                    <div class="col-md-5">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search User" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="mb-3">
                <a href="#" id="copy_currency_btn" class="btn btn-md btn-info mb-1">Copy</a>
                <a href="{{url('export_price_currencies')}}" class="btn btn-md btn-success">Excel</a>
            </div>
            <table class="table table-striped table-bordered table-hover" id="price_currency_table" width="100%">
                <thead>
                    <tr>
                        <th width="25%">Action</th>
                        <th width="35%">Name</th>
                        <th width="40%">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($price_currencies as $currency)
                    <tr>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning btn-outline"
                                data-target="#editPriceCurrency{{ $currency->id }}" data-toggle="modal" title='Edit currency'>
                                <i class="ti-pencil"></i>
                            </button>   
                            <button type="button" class="btn btn-sm btn-danger btn-outline" onclick="confirmDelete({{ $currency->id }})" title='Delete'>
                                <i class="ti-trash"></i>
                            </button>  
                        </td>
                        <td>{{ $currency->Name }}</td>
                        <td>{{ $currency->Description }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $price_currencies->appends(['search' => $search])->links() !!}
            @php
                $total = $price_currencies->total();
                $currentPage = $price_currencies->currentPage();
                $perPage = $price_currencies->perPage();

                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
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
                    url: "{{ url('/delete_price_currency') }}/" + id, 
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
    
    $(document).ready(function() {
        $('#copy_currency_btn').on('click', function(e) {
            e.preventDefault();
            
            var tableData = 'Name\tDescription\n';
            
            $('#price_currency_table tbody tr').each(function() {
                var name = $(this).find('td').eq(1).text(); 
                var description = $(this).find('td').eq(2).text(); 
                
                tableData += name + '\t' + description + '\n';
            });
            
            var $temp = $('<textarea>');
            $('body').append($temp);
            $temp.val(tableData).select();
            
            document.execCommand('copy');
            
            $temp.remove();
            
            Swal.fire({
                title: 'Copied!',
                text: 'Table have been copied to the clipboard.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
    });
</script>
@include('price_currencies.create')
@foreach ($price_currencies as $currency)
@include('price_currencies.edit')
@endforeach
@endsection