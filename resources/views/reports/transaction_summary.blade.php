@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Summary of Transactions/Activities
            </h4>
            <div class="row height d-flex ">
                <div class="col-md-6 mt-2 mb-2">
                    <!-- <a href="#" id="copy_transaction_btn" class="btn btn-md btn-info" style="margin-top: 2em">Copy</a> -->
                    <button class="btn btn-md btn-info" style="margin-top: 2em" id="copy_transaction_btn">Copy</button>
                    <a href="{{ route('export_transaction_activity', request()->all()) }}" class="btn btn-success" style="margin-top: 2em">Excel</a>
                </div>
                <form class="form-inline col-md-6" action="{{ route('reports.transaction_activity') }}" method="GET">
                    <div class="col-md-6 mt-2 mb-2">
                        <label style="align-items: start;justify-content: left;">From (DD/MM/YYYY):</label>
                        <input type="date" class="form-control" name="from" id="from" value="{{ $from }}" onchange="this.form.submit();" style="width: 100%;">
                    </div>
                    <div class="col-md-6 mt-2 mb-2">
                        <label style="align-items: start;justify-content: left;">To (DD/MM/YYYY):</label>
                        <input type="date" class="form-control" name="to" id="to" value="{{ $to }}" onchange="this.form.submit();" style="width: 100%;">
                    </div>
                </form>                
            </div>
            <div class="row mt-2">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
                        <input type="hidden" name="from" value="{{ $from }}">
                        <input type="hidden" name="to" value="{{ $to }}">
                        <select name="number_of_entries" class="form-control" onchange="this.form.submit();">
                            <option value="10" @if($entries == 10) selected @endif>10</option>
                            <option value="25" @if($entries == 25) selected @endif>25</option>
                            <option value="50" @if($entries == 50) selected @endif>50</option>
                            <option value="100" @if($entries == 100) selected @endif>100</option> 
                        </select>
                    </form>
                    <span>Entries</span>
                </div>
                <div class="col-lg-6">
                    <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                        <input type="hidden" name="from" value="{{ $from }}">
                        <input type="hidden" name="to" value="{{ $to }}">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-lg-9">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Transactions/Activities" name="search" value="{{ $search }}"> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="transaction_table" width="100%">
                    <thead>
                        <tr>
                            <th>
                                Type
                                <a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'type', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'type' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Transaction Number
                                <a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'transaction_number', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'transaction_number' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                BDE
                                <a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'bde', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'bde' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Client
                                <a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'client', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'client' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Date Created
                                <a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'date_created', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'date_created' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Due Date
                                <a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'due_date', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'due_date' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Details
                                <a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'details', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'details' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Result
                                <a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'result', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'result' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Status
                                <a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'status', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'status' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Progress
                                <a href="{{ route('reports.transaction_activity', array_merge(request()->query(), ['sort' => 'progress', 'direction' => $direction == 'asc' ? 'desc' : 'asc'])) }}">
                                <i class="ti ti-arrow-{{ request('sort') == 'progress' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                            </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($transaction_data->count() > 0)
                            @foreach($transaction_data as $transaction)
                                <tr>
                                    <td>{{ $transaction->type }}</td>
                                    <td>{{ $transaction->transaction_number }}</td>
                                    <td>{{ $transaction->bde }}</td>
                                    <td>{{ $transaction->client }}</td>
                                    <td>{{ date('M. d, Y', strtotime($transaction->date_created)) }}</td>
                                    <td>{{ date('M. d, Y', strtotime($transaction->due_date)) }}</td>
                                    <td>{{ $transaction->details }}</td>
                                    <td>{{ $transaction->result ?? 'N/A' }}</td>
                                    <td>
                                        @if($transaction->status == 10)
                                            Open
                                        @elseif($transaction->status == 20)
                                            Closed 
                                        @else
                                            Cancelled
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->progress == 10)
                                            Open
                                        @elseif($transaction->progress == 20)
                                            Closed 
                                        @else
                                            {{ $transaction->progress }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else 
                            <tr>
                                <td colspan="10" class="text-center">No matching records found</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>
                                <select id="filter-type" name="filter_type" class="form-control js-example-basic-single">
                                    <option value="">Select Type</option>
                                    <option value="Customer Requirement">Customer Requirement</option>
                                    <option value="Sample Request">Sample Request</option>
                                    <option value="Request Product Evaluation">Request Product Evaluation</option>
                                    <option value="Price Request">Price Request</option>
                                    <option value="Activity">Activity</option>
                                </select>
                            </th>
                            <th>
                                <select id="filter-transaction-number" name="filter_transaction_number" class="form-control js-example-basic-single">
                                    <option value="">Select Transaction Number</option>
                                    @foreach($transactionNumbers as $number)
                                        <option value="{{ $number }}">{{ $number }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-bde" name="filter_bde" class="form-control js-example-basic-single">
                                    <option value="">Select BDE</option>
                                    @foreach($uniqueBde as $bde)
                                        <option value="{{ $bde }}">{{ $bde }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-client" name="filter_client" class="form-control js-example-basic-single">
                                    <option value="">Select Client</option>
                                    @foreach($uniqueClient as $client)
                                        <option value="{{ $client }}">{{ $client }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-date-created" name="filter_date_created" class="form-control js-example-basic-single">
                                    <option value="">Select Date</option>
                                    @foreach($uniqueDateCreated as $created)
                                        <option value="{{ date('Y-m-d', strtotime($created)) }}">{{ date('M. d, Y', strtotime($created)) }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-due-date" name="filter_due_date" class="form-control js-example-basic-single">
                                    <option value="">Select Date</option>
                                    @foreach($uniqueDueDate as $due_date)
                                        <option value="{{ date('Y-m-d', strtotime($due_date)) }}">{{ date('M. d, Y', strtotime($due_date)) }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                            </th>
                            <th>
                            </th>
                            <th>
                                <select id="filter-status" name="filter_status" class="form-control js-example-basic-single">
                                    <option value="">Select Status</option>
                                    @foreach($uniqueStatus as $status)
                                        <option value="{{ $status }}">
                                            @if($status == 10)
                                                Open
                                            @elseif($status == 20)
                                                Closed 
                                            @else
                                                Cancelled
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-progress" name="filter_progress" class="form-control js-example-basic-single">
                                    <option value="">Select Progress</option>
                                    @foreach($uniqueProgress as $progress)
                                        <option value="{{ $progress }}">
                                            @if($progress == 10)
                                                Open
                                            @elseif($progress == 20)
                                                Closed 
                                            @else
                                                {{ $progress }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            {{ $transaction_data->appends(['from' => $from, 'to' => $to, 'search' => $search, 'sort' => $sort, 'direction' => $direction])->links() }}
            @php
                $total = $transaction_data->total();
                $currentPage = $transaction_data->currentPage();
                $perPage = $transaction_data->perPage();
                
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        $("[name='number_of_entries']").on('change', function() {
            var form = $(this).closest('form');
            form.submit();
        });

        $('.js-example-basic-single').select2();

        $('#copy_transaction_btn').click(function() {
            $.ajax({
                url: '{{ route('copy_transaction_activity') }}', // Use Laravel's route helper
                method: 'GET',
                success: function(response) {
                    copyToClipboard(response.data); // Call a function to copy data to clipboard
                },
                error: function(xhr) {
                    console.error('An error occurred while copying the data:', xhr.responseText);
                }
            });
        });

        function copyToClipboard(data) {
            // Create a temporary textarea element to hold the data
            var textarea = document.createElement('textarea');
            textarea.value = data;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Data has been copied to clipboard.',
                showConfirmButton: false,
                timer: 1500
            });
        }

        // Function to handle filter changes
        function applyFilters() {
            const filterType = $('#filter-type').val();
            const filterTransactionNumber = $('#filter-transaction-number').val();
            const filterBDE = $('#filter-bde').val();
            const filterClient = $('#filter-client').val();
            const filterDateCreated = $('#filter-date-created').val();
            const filterDueDate = $('#filter-due-date').val();
            const filterStatus = $('#filter-status').val();
            const filterProgress = $('#filter-progress').val();
            
            // Build query string based on filters
            const queryParams = new URLSearchParams({
                filter_type: filterType,
                filter_transaction_number: filterTransactionNumber,
                filter_bde: filterBDE,
                filter_client: filterClient,
                filter_date_created: filterDateCreated,
                filter_due_date: filterDueDate,
                filter_status: filterStatus,
                filter_progress: filterProgress,
                // Add other filters here if needed
            }).toString();

            // Redirect to the filtered URL
            window.location.search = queryParams;
        }

        // Attach event handlers
        $('#filter-type, #filter-transaction-number, #filter-bde, #filter-client, #filter-date-created, #filter-due-date, #filter-status, #filter-progress').on('change keyup', function() {
            applyFilters();
        });

        // Helper function to format date
        function formatDate(dateStr) {
            var date = new Date(dateStr);
            var options = { year: 'numeric', month: 'short', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }
    });
</script>
@endsection