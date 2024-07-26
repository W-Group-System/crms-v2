@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Customer Requirement List
            <button type="button" class="btn btn-md btn-primary" name="add_customer_requirement" data-toggle="modal" data-target="#AddCustomerRequirement" class="btn btn-md btn-primary">Add Customer Requirement</button>
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
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="customer_requirement_table" width="100%">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>CRR #</th>
                            <th>Date Created</th>
                            <th>Due Date</th>
                            <th>Client Name</th>
                            <th>Application</th>
                            <th>Recommendation</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $customer_requirements as $cusomerRequirement)
                        <tr>
                            <td align="center">
                                <button type="button" class="btn btn-sm btn-warning"
                                    data-target="#editBase{{ $cusomerRequirement->id }}" data-toggle="modal" title='Edit New Base Price'>
                                    <i class="ti-pencil"></i>
                                </button>  
                                <button type="button" class="btn btn-sm btn-success approve-btn"  data-id="{{ $cusomerRequirement->Id }}">
                                    <i class="ti-thumb-up"></i>
                                </button> 
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $cusomerRequirement->Id }}" title='Delete Base Price'>
                                    <i class="ti-trash"></i>
                                </button>
                            </td>
                            <td>{{ optional($cusomerRequirement)->CrrNumber }}</td>
                            <td>{{ $cusomerRequirement->CreatedDate }}</td>
                            <td>{{ $cusomerRequirement->DueDate }}</td>
                            <td>{{ optional($cusomerRequirement->client)->Name }}</td>
                            <td>{{ optional($cusomerRequirement->product_application)->Name }}</td>
                            <td style="white-space: break-spaces; width: 100%;">{{ $cusomerRequirement->Recommendation }}</td>
                            <td>
                                @if($cusomerRequirement->Status == 10)
                                        Open
                                    @elseif($cusomerRequirement->Status == 30)
                                        Closed
                                    @else
                                        {{ $cusomerRequirement->Status }}
                                    @endif
                            </td>
                            <td>{{ optional($cusomerRequirement->progressStatus)->name }}</td>
                            
                        </tr>
                            
                        @endforeach
                    </tbody>
                </table>
                {!! $customer_requirements->appends(['search' => $search])->links() !!}
                @php
                    $total = $customer_requirements->total();
                    $currentPage = $customer_requirements->currentPage();
                    $perPage = $customer_requirements->perPage();
    
                    $from = ($currentPage - 1) * $perPage + 1;
                    $to = min($currentPage * $perPage, $total);
                @endphp
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Nature of Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px">
                <h5 style="margin: 0">Are you sure you want to delete this data?</h5>
            </div>
            <div class="modal-footer" style="padding: 0.6875rem">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" name="delete_crr_priority" id="delete_crr_priority" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 

<style>
    #natureOfRequestContainer .select2-container {
        width: 360px !important;
    }
</style>
<script>
    $(document).ready(function(){
        $('.js-example-basic-single').select2();


        $('#form_customer_requirement').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == 'Save')
            

            if($('#action').val() == 'Edit')
            {
                var formData = new FormData(this);
                formData.append('id', $('#hidden_id').val());
                $.ajax({
                    url: "{{ route('update_crr_priority', ':id') }}".replace(':id', $('#hidden_id').val()),
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success:function(data)
                    {
                        var html = '';
                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for(var count = 0; count < data.errors.length; count++)
                            {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if(data.success)
                        {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#form_customer_requirement')[0].reset();
                            setTimeout(function(){
                                $('#formCustomerRequirement').modal('hide');
                            }, 2000);
                            $('#customer_requirement_table').DataTable().ajax.reload();
                            setTimeout(function(){
                                $('#form_result').empty(); 
                            }, 2000); 
                        }
                        $('#form_result').html(html);
                    }
                });
            }
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajax({
                url: "{{ route('edit_crr_priority', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(html){
                    $('#Name').val(html.data.Name);
                    $('#Description').val(html.data.Description);
                    $('#Days').val(html.data.Days);
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text("Edit CRR Priority");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    
                    $('#formCustomerRequirement').modal('show');
                }
            });
        });
                
        $(document).on('click', '.delete', function(){
            crr_priority_id = $(this).attr('id');
            $('#confirmModal').modal('show');
            $('.modal-title').text("Delete Nature of Request");
        });    

        $('#delete_crr_priority').click(function(){
            $.ajax({
                url: "{{ url('delete_crr_priority') }}/" + crr_priority_id, 
                method: "GET",
                beforeSend:function(){
                    $('#delete_crr_priority').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#customer_requirement_table').DataTable().ajax.reload();
                    }, 2000);
                }
            })
        });
    });
</script>
@include('customer_requirements.create')
@endsection