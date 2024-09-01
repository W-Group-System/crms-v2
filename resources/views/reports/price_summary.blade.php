@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
            Price Request Summary
            </h4>
            <div class="row height d-flex ">
                <div class="col-md-5 mt-2 mb-2">
                    <a href="#" id="copy_price_btn" class="btn btn-md btn-info mb-1">Copy</a>
                    <a href="#" id="excel_price_btn" class="btn btn-md btn-success mb-1">Excel</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <span>Show</span>
                    <form method="GET" class="d-inline-block">
                        <select name="number_of_entries" class="form-control">
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
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-lg-9">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Price Request" name="search" value=""> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- <form method="GET" class="custom_form mb-3" enctype="multipart/form-data">
                <div class="row height d-flex ">
                    <div class="col-md-5 mt-2">
                        <a href="#" id="copy_issue_btn" class="btn btn-md btn-info mb-1">Copy</a>
                        <a href="#" id="excel_btn" class="btn btn-md btn-success mb-1">Excel</a>
                    </div>
                    <div class="offset-md-2 col-md-5 mt-2">
                        <div class="search">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" placeholder="Search Issue Category" name="search" value=""> 
                            <button class="btn btn-sm btn-info">Search</button>
                        </div>
                    </div>
                </div>
            </form> -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="price_request_table" width="100%">
                    <thead>
                        <tr>
                            <th>
                                Date
                                <a href="{{ route('reports.price_request', [
                                    'sort' => 'DateRequested', 
                                    'direction' => request('sort') == 'DateRequested' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ]) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'DateRequested' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Account Manager
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'PrimarySalesPersonId', 'direction' => request('sort') == 'PrimarySalesPersonId' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'PrimarySalesPersonId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Client
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'ClientId', 'direction' => request('sort') == 'ClientId' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ClientId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Product
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'PriceRequestFormId', 'direction' => request('sort') == 'PriceRequestFormId' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'PriceRequestFormId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                RMC
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'ProductRmc', 'direction' => request('sort') == 'ProductRmc' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ProductRmc' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Offered Price
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'IsalesOfferedPrice', 'direction' => request('sort') == 'IsalesOfferedPrice' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'IsalesOfferedPrice' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Shipment Term
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'ShipmentTerm', 'direction' => request('sort') == 'ShipmentTerm' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'ShipmentTerm' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Payment Term
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'PaymentTermId', 'direction' => request('sort') == 'PaymentTermId' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'PaymentTermId' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Quantity Required
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'QuantityRequired', 'direction' => request('sort') == 'QuantityRequired' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'QuantityRequired' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Margin
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'IsalesMargin', 'direction' => request('sort') == 'IsalesMargin' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'IsalesMargin' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                % Margin
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'IsalesMarginPercentage', 'direction' => request('sort') == 'IsalesMarginPercentage' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'IsalesMarginPercentage' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Total Margin
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'IsalesMargin', 'direction' => request('sort') == 'IsalesMargin' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'IsalesMargin' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Accepted?
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'IsAccepted', 'direction' => request('sort') == 'IsAccepted' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'IsAccepted' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                            <th>
                                Remarks?
                                <a href="{{ route('reports.price_request', ['search' => $search, 'sort' => 'Remarks', 'direction' => request('sort') == 'Remarks' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    <i class="ti ti-arrow-{{ request('sort') == 'Remarks' && request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($priceRequests->count() > 0)
                            @foreach($priceRequests as $price_request)
                                <tr>
                                    <td>{{date('M. d, Y', strtotime($price_request->DateRequested))}}</td>
                                    <td>{{ $price_request->primarySalesPerson->full_name ?? $price_request->userById->full_name ?? 'N/A' }} </td>
                                    <td>{{ $price_request->client->name ?? 'N/A' }} </td>
                                    <td>{{ $price_request->code }}</td>
                                    <td>{{ $price_request->ProductRmc ?? 'N/A' }}</td>
                                    <td>{{ $price_request->IsalesOfferedPrice ?? 'N/A' }}</td>
                                    <td>{{ $price_request->ShipmentTerm ?? 'N/A' }}</td>
                                    <td>{{ $price_request->paymentterms->Name ?? 'N/A' }}</td>
                                    <td>{{ $price_request->QuantityRequired ?? 'N/A' }}</td>
                                    <td>{{ $price_request->IsalesMargin ?? 'N/A' }}</td>
                                    <td>{{ $price_request->IsalesMarginPercentage ?? 'N/A' }}</td>
                                    <td>{{ $price_request ? number_format($price_request->QuantityRequired * $price_request->IsalesMargin, 2) : '0' }}</td>
                                    <td>
                                        @if($price_request->IsAccepted == 1)
                                            YES
                                        @else
                                            NO 
                                        @endif
                                    </td>
                                    <td>{{ $price_request->Remarks ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        @else 
                            <tr>
                                <td colspan="14" class="text-center">No matching records found</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>
                                <select id="filter-date" class="form-control js-example-basic-single">
                                    <option value="">Select Date</option>
                                    @foreach($allDates as $date)
                                        <option value="{{ $date }}" {{ request('filter_date') == $date ? 'selected' : '' }}>{{ $date }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-account-manager" class="form-control js-example-basic-single">
                                    <option value="">Select Account Manager</option>
                                    @foreach($allPrimarySalesPersons as $id => $name)
                                        <option value="{{ $id }}" {{ request('filter_account_manager') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-client" class="form-control js-example-basic-single">
                                    <option value="">Select Client</option>
                                    @foreach($allClients as $id => $name)
                                        <option value="{{ $id }}" {{ request('filter_client') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-product" class="form-control js-example-basic-single">
                                    <option value="">Select Product</option>
                                    @foreach($allProducts as $id => $name)
                                        <option value="{{ $id }}" {{ request('filter_product') == $id ? '' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-rmc" class="form-control js-example-basic-single">
                                    <option value="">Select RMC</option>
                                    @foreach($allProductRmc as $rmc)
                                        <option value="{{ $rmc }}" {{ request('filter_rmc') == $rmc ? 'selected' : '' }}>{{ $rmc }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-offered" class="form-control js-example-basic-single">
                                    <option value="">Select Offered</option>
                                    @foreach($allOfferedPrice as $offered)
                                        <option value="{{ $offered }}" {{ request('filter_offered') == $offered ? 'selected' : '' }}>{{ $offered }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-shipment" class="form-control js-example-basic-single">
                                    <option value="">Select Shipment</option>
                                    @foreach($allShipments as $shipment)
                                        <option value="{{ $shipment }}" {{ request('filter_shipment') == $shipment ? '' : '' }}>{{ $shipment ?? 'N/A'}}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-payment" class="form-control js-example-basic-single">
                                    <option value="">Select Payment</option>
                                    @foreach($allPayments as $id => $name)
                                        <option value="{{ $id }}" {{ request('filter_payment') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-quantity" class="form-control js-example-basic-single">
                                    <option value="">Select Quantity</option>
                                    @foreach($allQuantity as $quantity)
                                        <option value="{{ $quantity }}" {{ request('filter_quantity') == $quantity ? '' : '' }}>{{ $quantity }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-margin" class="form-control js-example-basic-single">
                                    <option value="">Select Margin</option>
                                    @foreach($allMargin as $margin)
                                        <option value="{{ $margin }}" {{ request('filter_margin') == $margin ? '' : '' }}>{{ $margin }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-percent-margin" class="form-control js-example-basic-single">
                                    <option value="">Select % Margin</option>
                                    @foreach($allPercentMargin as $percent_margin)
                                        <option value="{{ $percent_margin }}" {{ request('filter_margin') == $percent_margin ? '' : '' }}>{{ $percent_margin }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-total-margin" class="form-control js-example-basic-single">
                                    <option value="">Select Total Margin</option>
                                    @foreach($totalMargins as $total_margin)
                                        <option value="{{ $total_margin }}" {{ request('filter_total_margin') == $total_margin ? '' : '' }}>{{ $total_margin }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-accepted" class="form-control js-example-basic-single">
                                    <option value="">Select</option>
                                    @foreach($allAccepted as $accept)
                                        <option value="{{ $accept }}" {{ request('filter_accepted') == $accept ? '' : '' }}>
                                            {{ $accept == 0 ? 'NO' : 'YES' }}
                                        </option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select id="filter-remarks" class="form-control js-example-basic-single">
                                    <option value="">Select Remarks</option>
                                    @foreach($allRemarks as $remark)
                                        <option value="{{ $remark }}" {{ request('filter_remarks') == $remark ? '' : '' }}>{{ $remark ?? 'N/A '}}</option>
                                    @endforeach
                                </select>
                            </th>
                            <!-- Add other filters as needed -->
                        </tr>
                    </tfoot>
                </table>
            </div>
            {!! $priceRequests->appends(['search' => $search, 'sort' => request('sort'), 'direction' => request('direction')])->links() !!}
            @php
                $total = $priceRequests->total();
                $currentPage = $priceRequests->currentPage();
                $perPage = $priceRequests->perPage();
                
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
<style>
    #filter-rmc, .select2-container {
        width: 100px;
    }
</style>
<script>
    $(document).ready(function() {
        $("[name='number_of_entries']").on('change', function() {
            var form = $(this).closest('form');
            form.submit();
        });

        $('.js-example-basic-single').select2();

        function formatDate(dateString) {
            const date = new Date(dateString);
            if (isNaN(date)) return ''; // Return empty string if date is invalid

            const options = { year: 'numeric', month: 'short', day: '2-digit' };
            return date.toLocaleDateString('en-US', options).replace(',', '');
        }

        $('#copy_price_btn').click(function() {
            $.ajax({
                url: "{{ route('export_price_request') }}",
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
                    $('#price_request_table thead tr').each(function() {
                        $(this).find('th').each(function() {
                            tableData += $(this).text().trim() + '\t'; // Add a tab space between columns
                        });
                        tableData += '\n'; // New line after the header row
                    });

                    // Add the table body from the fetched data
                    $(data).each(function(index, item) {
                        tableData += (item.DateRequested ? formatDate(item.DateRequested) : '') + '\t' +
                                    (item.primary_sales_person.full_name || '') + '\t' +
                                    (item.client.name || '') + '\t' +
                                    (item.code || '') + '\t' +
                                    (item.ProductRmc || '') + '\t' +
                                    (item.IsalesOfferedPrice || '') + '\t' +
                                    (item.ShipmentTerm || '') + '\t' +
                                    (item.paymentterms.Name || '') + '\t' +
                                    (item.QuantityRequired || '') + '\t' +
                                    (item.IsalesMargin || '') + '\t' +
                                    (item.IsalesMarginPercentage || '') + '\t' +
                                    ((item.QuantityRequired * item.IsalesMargin) ? (item.QuantityRequired * item.IsalesMargin).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '') + '\t' +
                                    ((item.IsAccepted !== null && item.IsAccepted !== undefined) ? (item.IsAccepted ? 'YES' : 'NO') : '') + '\t' +
                                    (item.Remarks || '') + '\n'; // Append each row's data
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

        $('#excel_price_btn').click(function() {
            $.ajax({
                url: "{{ route('export_price_request') }}", // URL for exporting all data
                method: "GET",
                data: {
                    search: "{{ $search }}", // Pass current search parameters if needed
                    sort: "{{ request('sort', 'DateRequested') }}", // Use default 'DateRequested' if not provided
                    direction: "{{ request('direction', 'asc') }}" // Use default 'asc' if not provided
                },
                success: function(data) {
                    // Ensure data is in array format
                    if (Array.isArray(data)) {
                        // Create a new workbook and worksheet
                        var wb = XLSX.utils.book_new();
                        var ws = XLSX.utils.json_to_sheet(data.map(item => ({
                            DateRequested: formatDate(item.DateRequested),
                            PrimarySalesPerson: item.primary_sales_person?.full_name || 'N/A',
                            Client: item.client.name || 'N/A',
                            ProductCode: item.products?.map(product => product.code).join(', ') || 'N/A', // Handle products as an array
                            ProductRmc: item.ProductRmc,
                            OfferedPrice: item.IsalesOfferedPrice || '',
                            ShipmentTerm: item.ShipmentTerm || '',
                            PaymentTerm: item.paymentterms?.Name || 'N/A',
                            QuantityRequired: item.QuantityRequired || '',
                            Margin: item.IsalesMargin || '',
                            MarginPercentage: item.IsalesMarginPercentage || '',
                            TotalMargin: item.total_margin || '',
                            Accepted: item.IsAccepted ? 'YES' : 'NO',
                            Remarks: item.Remarks || ''
                        })));

                        // Append the worksheet to the workbook
                        XLSX.utils.book_append_sheet(wb, ws, "Price Requests");

                        // Write the workbook to a file
                        XLSX.writeFile(wb, "price_requests.xlsx");
                    }
                }
            });
        });

        $('#filter-date, #filter-account-manager, #filter-client, #filter-product, #filter-rmc, #filter-shipment, #filter-payment, #filter-accepted, #filter-quantity, #filter-offered, #filter-margin, #filter-percent-margin, #filter-total-margin').on('change', function() {
            var filters = {
                filter_date: $('#filter-date').val(),
                filter_account_manager: $('#filter-account-manager').val(),
                filter_client: $('#filter-client').val(),
                filter_product: $('#filter-product').val(),
                filter_offered: $('#filter-offered').val(),
                filter_margin: $('#filter-margin').val(),
                filter_percent_margin: $('#filter-percent-margin').val(),
                filter_total_margin: $('#filter-total-margin').val(),
                filter_rmc: $('#filter-rmc').val(),
                filter_shipment: $('#filter-shipment').val(),
                filter_payment: $('#filter-payment').val(),
                filter_quantity: $('#filter-quantity').val(),
                filter_remarks: $('#filter-remarks').val(),
                filter_accepted: $('#filter-accepted').val(),
            };

            // Build the query string and redirect
            window.location.href = "{{ route('reports.price_request') }}?" + $.param(filters);
        });

    });

</script>
@endsection