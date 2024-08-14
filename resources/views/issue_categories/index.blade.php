@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Issue Category List
            <button type="button" class="btn btn-md btn-primary" name="add_issue_category" id="add_issue_category">Add Issue Category</button>
            </h4>
            <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex ">
                    <div class="col-md-5 mt-2">
                        <a href="#" id="copy_issue_btn" class="btn btn-md btn-info mb-1">Copy</a>
                        <a href="#" id="excel_btn" class="btn btn-md btn-success mb-1">Excel</a>
                    </div>
                    <div class="offset-md-2 col-md-5 mt-2">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Issue Category" name="search" value="{{$search}}"> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-striped table-bordered table-hover" id="issue_category_table" width="100%">
                <thead>
                    <tr>
                        <th width="10%">Action</th>
                        <th width="40%">
                            Name
                            <a href="{{ route('issue_category.index', [
                                'sort' => 'Name', 
                                'direction' => request('sort') == 'Name' && request('direction') == 'asc' ? 'desc' : 'asc'
                            ]) }}">
                                <i class="ti ti-arrow-{{ request('sort') == 'Name' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                            </a>
                        </th>
                        <th width="50%">
                            Description
                            <a href="{{ route('issue_category.index', [
                                'sort' => 'Description', 
                                'direction' => request('sort') == 'Description' && request('direction') == 'asc' ? 'desc' : 'asc'
                            ]) }}">
                                <i class="ti ti-arrow-{{ request('sort') == 'Description' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if($issuesCategory->count() > 0)
                        @foreach ($issuesCategory as $issueCategory)
                            <tr>
                                <td>
                                    <button type="button" class="edit btn btn-sm btn-warning" data-id="{{ $issueCategory->id }}" title='Edit Issue Category'>
                                        <i class="ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete" data-id="{{ $issueCategory->id }}" title='Delete Issue Category'>
                                        <i class="ti-trash"></i>
                                    </button>
                                </td>
                                <td>{{ $issueCategory->Name }}</td>
                                <td>{{ $issueCategory->Description }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">No matching records found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {!! $issuesCategory->appends(['search' => $search, 'sort' => request('sort'), 'direction' => request('direction')])->links() !!}
            @php
                $total = $issuesCategory->total();
                $currentPage = $issuesCategory->currentPage();
                $perPage = $issuesCategory->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formIssueCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Issue Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_issue_category" enctype="multipart/form-data" action="">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="name">Issue</label>
                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Issue">
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" placeholder="Enter Cost">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        // Clear errors when the modal is shown
        $('#add_issue_category').click(function(){
            $('#formIssueCategory').modal('show');
            $('.modal-title').text("Add Issue Category");
            $('#form_result').html(''); // Clear previous validation errors
            $('#form_issue_category')[0].reset(); // Clear form fields
        });

        $('#form_issue_category').on('submit', function(event){
            event.preventDefault();
            var action_url = '';

            if($('#action').val() == 'Save') {
                action_url = "{{ route('issue_category.store') }}";
            }

            if($('#action').val() == 'Edit') {
                action_url = "{{ route('update_issue_category', ':id') }}".replace(':id', $('#hidden_id').val());
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
                            timer: 1500, // Auto-close after 2 seconds
                            showConfirmButton: false
                        }).then(() => {
                            $('#form_issue_category')[0].reset();
                            $('#formIssueCategory').modal('hide');
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
                url: "{{ route('edit_issue_category', ['id' => '_id_']) }}".replace('_id_', id),
                dataType: "json",
                success: function(data) {
                    $('#Name').val(data.data.Name);
                    $('#Description').val(data.data.Description);
                    $('#hidden_id').val(data.data.id);
                    $('.modal-title').text("Edit Issue Category");
                    $('#action_button').val("Update");
                    $('#action').val("Edit");
                    $('#formIssueCategory').modal('show');
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
                        url: "{{ route('delete_issue_category', ['id' => '_id_']) }}".replace('_id_', id),
                        method: "GET",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'The issue category has been deleted.',
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

        // Reset form and errors when modal is closed
        $('#formIssueCategory').on('hidden.bs.modal', function() {
            $('#form_result').html('');
            $('#form_issue_category')[0].reset();
        });

        $('#copy_issue_btn').click(function() {
            $.ajax({
                url: "{{ route('issue_category.index') }}",
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
                    $('#issue_category_table thead tr').each(function(rowIndex, tr) {
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

        // Excel export functionality
        $('#excel_btn').click(function() {
            $.ajax({
                url: "{{ route('export_issue_category') }}", // URL for exporting all data
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
                        XLSX.utils.book_append_sheet(wb, ws, "Issue Categories");

                        // Write the workbook to a file
                        XLSX.writeFile(wb, "Issue Category.xlsx");
                    }
                }
            });
        });
    });

</script>
@endsection