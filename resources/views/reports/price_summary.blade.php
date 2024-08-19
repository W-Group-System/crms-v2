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
                    <a href="#" id="excel_btn" class="btn btn-md btn-success mb-1">Excel</a>
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
                            <th>Date (Y-M-D)</th>
                            <th>Account Manager</th>
                            <th>Client</th>
                            <th>Product</th>
                            <th>RMC</th>
                            <th>Offered Price</th>
                            <th>Shipment Term</th>
                            <th>Payment Term</th>
                            <th>Quantity Required</th>
                            <th>Margin</th>
                            <th>% Margin</th>
                            <th>Total Margin</th>
                            <th>Accepted?</th>
                            <th>Remarks?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($price_requests->count() > 0)
                            @foreach($price_requests as $price_request)
                                <tr>
                                    <td>{{ $price_request->DateRequested }}</td>
                                    <td>{{ $price_request->primarySalesPerson->full_name ?? $price_request->userById->full_name ?? 'N/A' }} </td>
                                    <td>{{ $price_request->client->Name ?? 'N/A' }} </td>
                                    <td>
                                        @if($price_request->products->isNotEmpty())
                                            @foreach($price_request->products as $product)
                                                {{ $product->code }}<br>
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $price_request->priceRequestProduct->ProductRmc ?? 'N/A' }}</td>
                                    <td>{{ $price_request->priceRequestProduct->IsalesOfferedPrice ?? 'N/A' }}</td>
                                    <td>{{ $price_request->ShipmentTerm ?? 'N/A' }}</td>
                                    <td>{{ $price_request->paymentterms->Name ?? 'N/A' }}</td>
                                    <!-- <td>{{ $price_request->priceRequestProduct->QuantityRequired ?? 'N/A' }}</td> -->
                                    <td>{{ $price_request->priceRequestProduct->IsalesMargin ?? 'N/A' }}</td>
                                    <td>{{ $price_request->priceRequestProduct->IsalesMarginPercentage ?? 'N/A' }}</td>
                                    <td>{{ number_format($price_request->priceRequestProduct->QuantityRequired * $price_request->priceRequestProduct->IsalesMargin),2 }} </td>
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
                </table>
            </div>
            {!! $price_requests->appends(['search' => $search, 'sort' => request('sort'), 'direction' => request('direction')])->links() !!}
            @php
                $total = $price_requests->total();
                $currentPage = $price_requests->currentPage();
                $perPage = $price_requests->perPage();
                
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

        // $('#copy_price_btn').click(function() {
        //     $.ajax({
        //         url: "{{ route('reports.price_request') }}",
        //         type: 'GET',
        //         data: {
        //             search: "{{ request('search') }}",
        //             sort: "{{ request('sort') }}",
        //             direction: "{{ request('direction') }}",
        //             fetch_all: true
        //         },
        //         success: function(data) {
        //             var tableData = '';

        //             // Add the table header
        //             $('#price_request_table thead tr').each(function(rowIndex, tr) {
        //                 $(tr).find('th').each(function(cellIndex, th) {
        //                     tableData += $(th).text().trim() + '\t'; // Add a tab space
        //                 });
        //                 tableData += '\n'; // New line after each row
        //             });

        //             // Add the table body from the fetched data
        //             $(data).each(function(index, item) {
        //                 tableData += item.DateRequested + '\t' +
        //                             (item.primarySalesPerson.full_name) + '\t' +
        //                             (item.client ? item.client.Name : 'N/A') + '\t' +
        //                             (item.products ? item.products.map(p => p.code).join(', ') : 'N/A') + '\t' +
        //                             (item.priceRequestProduct ? item.priceRequestProduct.ProductRmc : 'N/A') + '\t' +
        //                             (item.priceRequestProduct ? item.priceRequestProduct.IsalesOfferedPrice : 'N/A') + '\t' +
        //                             item.ShipmentTerm + '\t' +
        //                             (item.paymentterms ? item.paymentterms.Name : 'N/A') + '\t' +
        //                             (item.priceRequestProduct ? item.priceRequestProduct.QuantityRequired : 'N/A') + '\t' +
        //                             (item.priceRequestProduct ? item.priceRequestProduct.IsalesMargin : 'N/A') + '\t' +
        //                             (item.priceRequestProduct ? item.priceRequestProduct.IsalesMarginPercentage : 'N/A') + '\t' +
        //                             (item.priceRequestProduct ? (item.priceRequestProduct.QuantityRequired * item.priceRequestProduct.IsalesMargin).toFixed(2) : 'N/A') + '\t' +
        //                             (item.IsAccepted == 1 ? 'YES' : 'NO') + '\t' +
        //                             (item.Remarks ? item.Remarks : 'N/A') + '\n';
        //             });

        //             // Create a temporary textarea element to hold the text
        //             var tempTextArea = $('<textarea>');
        //             $('body').append(tempTextArea);
        //             tempTextArea.val(tableData).select();
        //             document.execCommand('copy');
        //             tempTextArea.remove(); // Remove the temporary element

        //             // Notify the user
        //             Swal.fire({
        //                 icon: 'success',
        //                 title: 'Copied!',
        //                 text: 'Table data has been copied to the clipboard.',
        //                 timer: 1500,
        //                 showConfirmButton: false
        //             });
        //         }
        //     });
        // });

        // Excel export functionality
        // $('#excel_btn').click(function() {
        //     $.ajax({
        //         url: "{{ route('export_price_request') }}",
        //         method: "GET",
        //         data: {
        //             search: "{{ $search }}",
        //             sort: "{{ request('sort', 'DateRequested') }}",
        //             direction: "{{ request('direction', 'desc') }}"
        //         },
        //         success: function(data) {
        //             if (Array.isArray(data)) {
        //                 var wb = XLSX.utils.book_new();
        //                 var ws = XLSX.utils.json_to_sheet(data.map(item => ({
        //                     DateRequested: item.DateRequested,
        //                     AccountManager: item.primarySalesPerson ? item.primarySalesPerson.full_name : 'N/A',
        //                     Client: item.client ? item.client.Name : 'N/A',
        //                     Product: item.products ? item.products.map(p => p.code).join(', ') : 'N/A',
        //                     RMC: item.priceRequestProduct ? item.priceRequestProduct.ProductRmc : 'N/A',
        //                     OfferedPrice: item.priceRequestProduct ? item.priceRequestProduct.IsalesOfferedPrice : 'N/A',
        //                     ShipmentTerm: item.ShipmentTerm,
        //                     PaymentTerm: item.paymentterms ? item.paymentterms.Name : 'N/A',
        //                     QuantityRequired: item.priceRequestProduct ? item.priceRequestProduct.QuantityRequired : 'N/A',
        //                     Margin: item.priceRequestProduct ? item.priceRequestProduct.IsalesMargin : 'N/A',
        //                     MarginPercentage: item.priceRequestProduct ? item.priceRequestProduct.IsalesMarginPercentage : 'N/A',
        //                     TotalMargin: item.priceRequestProduct ? (item.priceRequestProduct.QuantityRequired * item.priceRequestProduct.IsalesMargin).toFixed(2) : 'N/A',
        //                     Accepted: item.IsAccepted == 1 ? 'YES' : 'NO',
        //                     Remarks: item.Remarks ? item.Remarks : 'N/A'
        //                 })));
                        
        //                 XLSX.utils.book_append_sheet(wb, ws, "Price Summary");
        //                 XLSX.writeFile(wb, "Price_Summary.xlsx");
        //             }
        //         }
        //     });
        // });
    });

</script>
@endsection