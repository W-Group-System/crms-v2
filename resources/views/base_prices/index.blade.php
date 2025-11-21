@extends('layouts.header')
@section('title', 'Products - CRMS')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card border border-1 border-primary rounded-0">
        <div class="card-header bg-primary rounded-0">
            <p class="m-0 text-white font-weight-bold">List of Current Product Material Base Price</p>
        </div>
        <div class="card-body">
            {{-- <h4 class="card-title d-flex justify-content-between align-items-center">
            Current Base Price List
            </h4> --}}
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
            <table class="table table-striped table-bordered table-hover" id="base_price_table" width="100%">
                <thead>
                    <tr>
                        <th width="8%">Action</th>
                        <th width="32%">Material</th>
                        <th width="20%">Price</th>
                        <th width="20%">Approved By</th>
                        <th width="20%">Effective</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $currentBasePrice as $currentBase)
                    <tr>
                        <td align="center">
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                data-target="#viewBase{{ $currentBase->Id }}" data-toggle="modal" title='View Base Price'>
                                <i class="ti-eye"></i>
                            </button>    
                        </td>
                        <td>{{ optional($currentBase->productMaterial)->Name }}</td>
                        <td>{{ number_format($currentBase->Price, 2) }}</td>
                        <td>{{ ($currentBase->userApproved->full_name) ?? ($currentBase->userApprovedById->full_name) ?? 'N/A'}}</td>
                        <td>{{ $currentBase->EffectiveDate }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $currentBasePrice->appends(['search' => $search])->links() !!}
            @php
              $total = $currentBasePrice->total();
              $currentPage = $currentBasePrice->currentPage();
              $perPage = $currentBasePrice->perPage();

              $from = ($currentPage - 1) * $perPage + 1;
              $to = min($currentPage * $perPage, $total);
          @endphp
          <div class="d-flex justify-content-between align-items-center mt-3">
              <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
          </div>
        </div>
    </div>
</div>

<div class="modal fade" id="formBusinessType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_business_type" enctype="multipart/form-data" action="{{ route('business_type.store') }}">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action" value="Save">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" class="btn btn-success" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Business Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to delete this data?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" name="yes_button" id="yes_button" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

<script>
    // $(document).ready(function(){
    //     $('#base_price_table').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         ajax: {
    //             url: "{{ route('base_price.index') }}"
    //         },
    //         columns: [
    //             {
    //                 data: 'MaterialId',
    //                 name: 'MaterialId'
    //             },
    //             {
    //                 data: 'Price',
    //                 name: 'Price'
    //             },
    //             {
    //                 data: 'ApprovedBy',
    //                 name: 'ApprovedBy'
    //             },
    //             {
    //                 data: 'EffectiveDate',
    //                 name: 'EffectiveDate'
    //             },
    //             {
    //                 data: 'action',
    //                 name: 'action',
    //                 orderable: false
    //             }
    //         ],
    //         columnDefs: [
    //             {
    //                 targets: 1, // Target the Description column
    //                 render: function(data, type, row) {
    //                     return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
    //                 }
    //             }
    //         ]
    //     });

        // $('#add_base_price').click(function(){
        //     $('#formBusinessType').modal('show');
        //     $('.modal-title').text("Add New Business Type");
        // });

        // $('#form_business_type').on('submit', function(event){
        //     event.preventDefault();
        //     if($('#action').val() == 'Save')
        //     {
        //         $.ajax({
        //             url: "{{ route('business_type.store') }}",
        //             method: "POST",
        //             data: new FormData(this),
        //             contentType: false,
        //             cache: false,
        //             processData: false,
        //             dataType: "json",
        //             success: function(data)
        //             {
        //                 var html = '';
        //                 if(data.errors)
        //                 {
        //                     html = '<div class="alert alert-danger">';
        //                     for(var count = 0; count < data.errors.length; count++)
        //                     {
        //                         html += '<p>' + data.errors[count] + '</p>';
        //                     }
        //                     html += '</div>';
        //                 }
        //                 if(data.success)
        //                 {
        //                     html = '<div class="alert alert-success">' + data.success + '</div>';
        //                     $('#form_business_type')[0].reset();
        //                     setTimeout(function(){
        //                         $('#formBusinessType').modal('hide');
        //                     }, 2000);
        //                     $('#company_table').DataTable().ajax.reload();
        //                     setTimeout(function(){
        //                         $('#form_result').empty(); 
        //                     }, 2000); 
        //                 }
        //                 $('#form_result').html(html);
        //             }
        //         })
        //     }

        //     if($('#action').val() == 'Edit')
        //     {
        //         var formData = new FormData(this);
        //         formData.append('Id', $('#hidden_id').val());
        //         $.ajax({
        //             url: "{{ route('update_business_type', ':Id') }}".replace(':Id', $('#hidden_id').val()),
        //             method: "POST",
        //             data: new FormData(this),
        //             contentType: false,
        //             cache: false,
        //             processData: false,
        //             dataType: "json",
        //             success:function(data)
        //             {
        //                 var html = '';
        //                 if(data.errors)
        //                 {
        //                     html = '<div class="alert alert-danger">';
        //                     for(var count = 0; count < data.errors.length; count++)
        //                     {
        //                         html += '<p>' + data.errors[count] + '</p>';
        //                     }
        //                     html += '</div>';
        //                 }
        //                 if(data.success)
        //                 {
        //                     html = '<div class="alert alert-success">' + data.success + '</div>';
        //                     $('#form_business_type')[0].reset();
        //                     setTimeout(function(){
        //                         $('#formBusinessType').modal('hide');
        //                     }, 2000);
        //                     $('#base_price_table').DataTable().ajax.reload();
        //                     setTimeout(function(){
        //                         $('#form_result').empty(); 
        //                     }, 2000); 
        //                 }
        //                 $('#form_result').html(html);
        //             }
        //         });
        //     }
        // });

        // $(document).on('click', '.edit', function(){
        //     var id = $(this).attr('Id');
        //     $('#form_result').html('');
        //     $.ajax({
        //         url: "{{ route('edit_business_type', ['Id' => '_id_']) }}".replace('_id_', id),
        //         dataType: "json",
        //         success: function(html){
        //             $('#Name').val(html.data.Name);
        //             $('#Description').val(html.data.Description);
        //             $('#hidden_id').val(html.data.Id);
        //             $('.modal-title').text("Edit Business Type");
        //             $('#action_button').val("Update");
        //             $('#action').val("Edit");
        //             $('#formBusinessType').modal('show');
        //         }
        //     });
        // });

        // var business_type_id;
        // $(document).on('click', '.delete', function(){
        //     business_type_id = $(this).attr('Id');
        //     $('#confirmModal').modal('show');
        //     $('.modal-title').text("Delete Business Type");
        // }); 

        // $('#yes_button').click(function(){
        //     $.ajax({
        //         url: "{{ url('delete_business_type') }}/" + business_type_id, 
        //         method: "GET",
        //         beforeSend:function(){
        //             $('#yes_button').text('Deleting...');
        //         },
        //         success:function(data)
        //         {
        //             setTimeout(function(){
        //                 $('#confirmModal').modal('hide');
        //                 $('#base_price_table').DataTable().ajax.reload();
        //             }, 2000);
        //         }
        //     })
        // });
    // });
</script>
@foreach ($currentBasePrice as $currentBase)
    @include('base_prices.view_current_base_price')
@endforeach
@endsection