@extends('layouts.header')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title d-flex justify-content-between align-items-center">
                Sample Dispatch Summary
            </h4>
            <div class="row height d-flex">
                <div class="col-md-6 mt-2 mb-2">
                    <a href="#" id="copy_sample_btn" style="margin-top: 1.5em" class="btn btn-md btn-outline-info mb-1">Copy</a>
                    {{-- <a href="#" id="excel_sample_btn" style="margin-top: 1.5em" class="btn btn-md btn-outline-success mb-1">Excel</a> --}}
                    <form method="GET" action="{{url('export_sample_request')}}" class="d-inline-block">
                        <input type="hidden" name="from" value="{{ $from }}">
                        <input type="hidden" name="to" value="{{ $to }}">
                        
                        <button type="submit" id="excel_sample_btn" style="margin-top: 1.5em" class="btn btn-md btn-outline-success mb-1">Excel</button>
                    </form>
                </div>
                <form class="form-inline col-md-6" action="{{ route('reports.sample_dispatch') }}" method="GET">
                    <div class="col-md-6 mt-2 mb-2">
                        <label style="align-items: start;justify-content: left;">From (DD/MM/YYYY):</label>
                        <input type="date" class="form-control" name="from" id="from" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}" style="width: 100%;" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-6 mt-2 mb-2">
                        <label style="align-items: start;justify-content: left;">To (DD/MM/YYYY):</label>
                        <input type="date" class="form-control" name="to" id="to" value="{{ request('to', now()->endOfMonth()->format('Y-m-d')) }}" style="width: 100%;" onchange="this.form.submit()">
                    </div>
                </form>
            </div>
            <div class="row">
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
                    <form method="GET" class="custom_form mb-3" enctype="multipart/form-data" onsubmit="show()">
                        <input type="hidden" name="from" value="{{ $from }}">
                        <input type="hidden" name="to" value="{{ $to }}">
                        <div class="row height d-flex justify-content-end align-items-end">
                            <div class="col-lg-9">
                                <div class="search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" class="form-control" placeholder="Search Sample Dispatch" name="search" value=""> 
                                    <button class="btn btn-sm btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="sample_summary_table" width="100%">
                    <thead>
                        <tr>
                            <th>Date of BDE Advise</th>
                            <th>Date of Dispatch</th>
                            <th>SRF No.</th>
                            <th>Company</th>
                            <th>Contact Person</th>
                            <th>Address</th>
                            <th>Quantity</th>
                            <th>In grams</th>
                            <th>Product</th>
                            <th>Lot Number</th>
                            <th>Product Description</th>
                            <th>Courier Company</th>
                            <th>AWB No.</th>
                            <th>ETA</th>
                            <th>Courier Cost</th>
                            <th>Sample Type</th>
                            <th>Issued To</th>
                            <th>Reason for Delayed Dispatch</th>
                            <th>Account Manager</th>
                            <th>Dispatch By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($sample_dispatch->count() > 0) 
                            @foreach($sample_dispatch as $data)
                                <tr>
                                    <td>{{ $data->sampleRequest->DateSampleReceived ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->DateDispatched ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->SrfNumber }}</td>
                                    <td>{{ $data->sampleRequest->client->Name ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->clientContact->ContactName ?? 'N/A' }}</td>
                                    <td>
                                        {{-- {{ $data->sampleRequest->clientAddress->Address ?? 'N/A' }} --}}
                                        {!! nl2br($data->sampleRequest->clientAddress->Address) !!}
                                    </td>
                                    <td>{{ $data->NumberOfPackages }} x {{$data->Quantity}} {{ $data->uom->Name }}</td>
                                    <td>{{ $data->NumberOfPackages * $data->Quantity }}</td>
                                    <td>{{ $data->ProductCode }}</td>
                                    <td>{{ $data->sampleRequest->SrfNumber}}-{{ $data->ProductIndex }}</td>
                                    <td>{{ $data->ProductDescription ?? 'N/A'}}</td>
                                    <td>{{ $data->sampleRequest->Courier ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->AwbNumber ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->Eta ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->CourierCost ?? 'N/A' }}</td>
                                    <td>
                                        @if($data->sampleRequest->SrfType == 1)
                                            Regular
                                        @elseif($data->sampleRequest->SrfType == 2)
                                            PSS
                                        @else
                                            CSS
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->sampleRequest->RefCode == 1)
                                            RND
                                        @elseif($data->sampleRequest->RefCode == 2)
                                            QCD-WHI
                                        @elseif($data->sampleRequest->RefCode == 3)
                                            QCD-PBI
                                        @elseif($data->sampleRequest->RefCode == 4)
                                            QCD-MRDC
                                        @else
                                            QCD-CCC
                                        @endif
                                    </td>
                                    <td>{{ $data->sampleRequest->Reason ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->primarySalesPerson->full_name ?? $data->sampleRequest->primarySalesById->full_name ?? 'N/A' }}</td>
                                    <td>{{ $data->sampleRequest->dispatchBy->full_name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        @else 
                            <tr>
                                <td colspan="21" class="text-center">No matching records found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            {{ $sample_dispatch->appends(['from' => $from, 'to' => $to, 'search' => $search, 'sort' => $sort, 'direction' => $direction])->links() }}
            @php
                $total = $sample_dispatch->total();
                $currentPage = $sample_dispatch->currentPage();
                $perPage = $sample_dispatch->perPage();
                
                $from = ($currentPage - 1) * $perPage + 1;
                $to = min($currentPage * $perPage, $total);
            @endphp
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
            </div>
        </div>
    </div>
</div>

<style>
    .tablesorter-bootstrap .tablesorter-header-inner {
        width: 180px;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        $('.table').tablesorter({
            theme: "bootstrap"
        });

        $('#copy_sample_btn').on('click', function() {
            var tableContent = '';
            // Extract data from the table and format it for copying
            $('#sample_summary_table thead tr').each(function() {
                $(this).find('th').each(function() {
                    tableContent += $(this).text().trim() + '\t'; // Append column headers
                });
                
                tableContent += '\n';
            });

            $('#sample_summary_table tbody tr').each(function() {
                $(this).find('td').each(function() {
                    // tableContent += $(this).text().trim() + '\t'; // Append row data
                    var cellText = $(this).text().trim().replace(/\n/g, ' '); 
                    tableContent += cellText + '\t'; // Append row data
                });
                
                tableContent += '\n';
            });

            // Copy the formatted table data to clipboard
            var tempTextarea = $('<textarea>').text(tableContent).appendTo('body');

            tempTextarea.select();
            document.execCommand('copy');
            tempTextarea.remove();
            
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Sample Dispatch data has been copied to the clipboard.',
                timer: 1500,
                showConfirmButton: false
            });
        });

        // Excel Export functionality
        // $('#excel_sample_btn').on('click', function() {
        //     var tableData = [];
        //     var headers = [];
        //     // Extract headers
        //     $('#sample_summary_table thead tr').each(function() {
        //         $(this).find('th').each(function() {
        //             headers.push($(this).text()); // Append column headers
        //         });
        //     });

        //     // Extract rows
        //     $('#sample_summary_table tbody tr').each(function() {
        //         var rowData = [];
        //         $(this).find('td').each(function() {
        //             rowData.push($(this).text()); // Append row data
        //         });
        //         tableData.push(rowData);
        //     });

        //     // Create worksheet data
        //     var ws = XLSX.utils.aoa_to_sheet([headers, ...tableData]);

        //     // Create workbook and add worksheet
        //     var wb = XLSX.utils.book_new();
        //     XLSX.utils.book_append_sheet(wb, ws, 'Sample Dispatch');

        //     // Generate the Excel file with the selected date range in the filename
        //     var filename = `Sample_Dispatch_${$('#from').val()}_to_${$('#to').val()}.xlsx`;
        //     XLSX.writeFile(wb, filename);
        // });
    });
</script>
@endsection
