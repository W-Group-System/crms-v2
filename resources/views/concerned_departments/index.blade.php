@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card border border-1 border-primary rounded-0">
        <div class="card-header bg-primary rounded-0">
            <p class="text-white font-weight-bold m-0">Department Concerned List</p>
        </div>
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-end align-items-center">
            <button type="button" class="btn btn-md btn-primary" name="add_concern_department" id="add_concern_department">Add Concerned Department</button>
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex ">
                    <div class="col-md-5 mt-2">
                        <a href="#" id="copy_btn" class="btn btn-md btn-info mb-1">Copy</a>
                        <a href="#" id="excel_btn" class="btn btn-md btn-success mb-1">Excel</a>
                    </div>
                    <div class="offset-md-2 col-md-5 mt-2">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Concerned Department" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="concern_department_table" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Action</th>
                            <th>
                                Department
                                <a href="{{ route('concern_department.index', [
                                    'sort' => 'Name', 
                                    'direction' => request('sort') == 'Name' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Name' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th >
                                Description
                                <a href="{{ route('concern_department.index', [
                                    'sort' => 'Description', 
                                    'direction' => request('sort') == 'Description' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Description' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Email
                            </th>
                            <!-- <th>Cc Email</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($concernDepartments as $concern_department)
                            <tr>
                                <td>
                                <button type="button" class="edit btn btn-sm btn-warning" data-id="{{ $concern_department->id }}" title='Edit Concerned Department'>
                                    <i class="ti-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete" data-id="{{ $concern_department->id }}" title='Delete Concerned Department'>
                                    <i class="ti-trash"></i>
                                </button>
                                </td>
                                <td>{{ $concern_department->Name }}</td>
                                <td>{{ $concern_department->Description }}</td>
                                <td>{{$concern_department->email}}</td>
                                <!-- <td>
                                    @foreach ($concern_department->audit as $key=>$audit)
                                        <small>{{$audit->email}}</small> <br>
                                    @endforeach
                                </td> -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $concernDepartments->appends(['search' => $search, 'sort' => request('sort'), 'direction' => request('direction')])->links() !!}
            @php
                $total = $concernDepartments->total();
                $currentPage = $concernDepartments->currentPage();
                $perPage = $concernDepartments->perPage();

                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
        </div>
    </div>
</div>

<!-- Add -->
<div class="modal fade" id="formConcernDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Concern Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="form_concern_department">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="name">Department</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Department">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Description">
                    </div>
                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="email" class="form-control" id="Email" name="Email" placeholder="Enter Email">
                    </div>
                    <!-- <div class="form-group">
                        <label for="name">Cc Email</label>
                        <select data-placeholder="Select Email" name="audit[]" id="CcEmail" class="form-control js-example-basic-multiple" multiple>
                            <option value=""></option>
                            @foreach ($audits as $key=>$audit)
                                <option value="{{$key}}">{{$audit}}</option>
                            @endforeach
                        </select>
                    </div> -->
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        // $('#concern_department_table').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         url: "{{ route('concern_department.index') }}"
        //     },
        //     columns: [
        //         {
        //             data: 'Name',
        //             name: 'Name'
        //         },
        //         {
        //             data: 'Description',
        //             name: 'Description'
        //         },
        //         {
        //             data: 'action',
        //             name: 'action',
        //             orderable: false
        //         }
        //     ],
        //     columnDefs: [
        //         {
        //             targets: 1, // Target the Description column
        //             render: function(data, type, row) {
        //                 return '<div style="white-space: break-spaces; width: 100%;">' + data + '</div>';
        //             }
        //         }
        //     ]
        // });

        $('#add_concern_department').click(function(){
            $('#formConcernDepartment').modal('show');
            $('.modal-title').text("Add Concerned Category");
            $('#form_result').html(''); // Clear previous validation errors
            $('#form_concern_department')[0].reset(); // Clear form fields
        });

        $('#form_concern_department').on('submit', function(event){
            event.preventDefault();
            var action_url = '';

            if($('#action').val() == 'Save') {
                action_url = "{{ route('concern_department.store') }}";
            }

            if($('#action').val() == 'Edit') {
                action_url = "{{ route('update_concern_department', ':id') }}".replace(':id', $('#hidden_id').val());
            }

            $.ajax({
                url: action_url,
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(data) {
                    var html = '';
                    if(data.errors) {
                        html = '<div class="alert alert-danger">';
                        for(var count = 0; count < data.errors.length; count++) {
                            html += '<p>' + data.errors[count] + '</p>';
                        }
                        html += '</div>';
                        $('#form_result').html(html);
                    }
                    if (data.success) {
                        // Use SweetAlert2 for the success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success,
                            timer: 1500, // Auto-close after 1.5 seconds
                            showConfirmButton: false
                        }).then(() => {
                            $('#form_concern_department')[0].reset();
                            $('#formConcernDepartment').modal('hide');
                            location.reload();
                            $('#form_result').empty(); 
                        });
                    }
                }
            });
        });

        // Edit button click
        $(document).on('click', '.edit', function() {
            var id = $(this).data('id');

            $.ajax({
                url: "{{ route('edit_concern_department', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(data) {
                    $('#Name').val(data.data.Name);
                    $('#Description').val(data.data.Description);
                    $('#Email').val(data.data.email);
                    $('#hidden_id').val(data.data.id);
                    $('#CcEmail').val(data.audit).trigger('change');
                    $('.modal-title').text("Edit Concerned Department");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    $('#formConcernDepartment').modal('show');
                }
            });
        });

        // Delete
        $(document).on('click', '.delete', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete_concern_department', ['id' => '_id_']) }}".replace('_id_', id),
                        method: "GET",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'The concerned department has been deleted.',
                                icon: 'success',
                                showConfirmButton: false, // Hide the OK button
                                timer: 1500 // Auto-close after 1.5 seconds
                            }).then(() => {
                                // Optionally, you can force a reload if necessary
                                location.reload();
                            });
                        },
                    });
                }
            });
        });

        $('#formConcernDepartment').on('hidden.bs.modal', function() {
            $('#form_result').html('');
            $('#form_concern_department')[0].reset();
        });

        // Copy functionality
        $('#copy_btn').click(function() {
            $.ajax({
                url: "{{ route('concern_department.index') }}",
                type: 'GET',
                data: {
                    search: "{{ request('search') }}",
                    sort: "{{ request('sort') }}",
                    direction: "{{ request('direction') }}",
                    fetch_all: true
                },
                success: function(data) {
                    var tableData = '';

                    // Add the table header
                    $('#concern_department_table thead tr').each(function(rowIndex, tr) {
                        $(tr).find('th').each(function(cellIndex, th) {
                            tableData += $(th).text().trim() + '\t'; // Add a tab space
                        });
                        tableData += '\n'; // New line after each row
                    });

                    // Add the table body from the fetched data
                    $(data).each(function(index, item) {
                        tableData += item.Name + '\t' + item.Description + '\n'; // Append each row's data
                    });

                    // Create a temporary textarea element to hold the text
                    var tempTextArea = $('<textarea>');
                    $('body').append(tempTextArea);
                    tempTextArea.val(tableData).select();
                    document.execCommand('copy');
                    tempTextArea.remove(); // Remove the temporary element

                    // Notify the user
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: 'Table data has been copied to the clipboard.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });

        // Export functionality
        $('#excel_btn').click(function() {
            $.ajax({
                url: "{{ route('export_concerned_department') }}", // URL for exporting all data
                method: "GET",
                data: {
                    search: "{{ $search }}", // Pass current search parameters if needed
                    sort: "{{ request('sort', 'Name') }}", // Use default 'Name' if not provided
                    direction: "{{ request('direction', 'asc') }}" // Use default 'asc' if not provided
                },
                success: function(data) {
                    // Ensure data is in array format
                    if (Array.isArray(data)) {
                        // Create a new workbook and worksheet
                        var wb = XLSX.utils.book_new();
                        var ws = XLSX.utils.json_to_sheet(data.map(item => ({
                            Name: item.Name,
                            Description: item.Description
                        })));

                        // Append the worksheet to the workbook
                        XLSX.utils.book_append_sheet(wb, ws, "Concerned Department");

                        // Write the workbook to a file
                        XLSX.writeFile(wb, "Concerned Department.xlsx");
                    }
                }
            });
        });
    });
</script>
@endsection