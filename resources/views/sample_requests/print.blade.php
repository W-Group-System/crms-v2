{{-- <!DOCTYPE html>
<html>
<head>
    <style>
         .form-divider{
        border-top: 3px solid rgb(0, 13, 255);
        }
    </style>
</head>
<body>
    <h3>Sample Request Details</h3>
    <hr class="form-divider">

    <p><strong>SRF Number:</strong> {{ $sample_requests->SrfNumber }}</p>



</body>
</html> --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sample Request Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            margin: 20px;
        }
        .header {
            text-align: right;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 20px;
            color: #0044cc;
            border-bottom: 2px solid #0044cc;
            padding-bottom: 5px;
        }
        .details, .request-details {
            border: 1px solid #ccc;
            margin-bottom: 15px;
            padding: 10px;
        }
        .details th, .details td {
            text-align: left;
            padding: 5px;
        }
        .details th {
            width: 150px;
            background-color: #f4f4f4;
        }
        .request-details {
            margin-bottom: 30px;
        }
        .request-details h3 {
            background-color: #f4f4f4;
            padding: 5px;
            margin: 0 0 10px 0;
        }
        .request-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .request-details table, .request-details th, .request-details td {
            border: 1px solid #ccc;
            padding: 5px;
        }
        .remarks {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p>{{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
        </div>
        <h1>Sample Request Form</h1>

        <div class="details">
            <table>
                <tr>
                    <th>Client Name</th>
                    <td>{{ $sample_requests->name }}</td>
                    <th>Contact</th>
                    <td>{{ $sample_requests->contact }}</td>
                </tr>
                <tr>
                    <th>Client Trade Name</th>
                    <td>{{ $sample_requests->trade_name }}</td>
                    <th>Telephone</th>
                    <td>{{ $sample_requests->telephone }}</td>
                </tr>
                <tr>
                    <th>Region</th>
                    <td>{{ $sample_requests->region }}</td>
                    <th>Mobile</th>
                    <td>{{ $sample_requests->mobile }}</td>
                </tr>
                <tr>
                    <th>Country</th>
                    <td>{{ $sample_requests->country }}</td>
                    <th>Email</th>
                    <td>{{ $sample_requests->email }}</td>
                </tr>
            </table>
        </div>

        <div class="request-details">
            <h3>Request Details</h3>
            <table>
                <tr>
                    <th>SRF #</th>
                    <td>{{ $sample_requests->srf_number }}</td>
                    <th>Primary Sales Person</th>
                    <td>{{ $sample_requests->primary_sales_person }}</td>
                </tr>
                <tr>
                    <th>Manual SRF #</th>
                    <td>{{ $sample_requests->manual_srf_number }}</td>
                    <th>Secondary Sales Person</th>
                    <td>{{ $sample_requests->secondary_sales_person }}</td>
                </tr>
                <tr>
                    <th>Date Requested</th>
                    <td>{{ $sample_requests->date_requested }}</td>
                    <th>Date Required</th>
                    <td>{{ $sample_requests->date_required }}</td>
                </tr>
                <tr>
                    <th>Date Started</th>
                    <td>{{ $sample_requests->date_started }}</td>
                    <th>REF CODE</th>
                    <td>{{ $sample_requests->ref_code }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>{{ $sample_requests->type }}</td>
                </tr>
            </table>

            <div class="remarks">
                <p><strong>Remarks:</strong> {{ $sample_requests->remarks }}</p>
            </div>
        </div>

        {{-- @foreach($sample_requests->products as $product)
        <div class="request-details">
            <h3>Product Details</h3>
            <table>
                <tr>
                    <th>Index</th>
                    <td>{{ $product->index }}</td>
                    <th>RPE #</th>
                    <td>{{ $product->rpe_number }}</td>
                </tr>
                <tr>
                    <th>Product Type</th>
                    <td>{{ $product->type }}</td>
                    <th>CRR #</th>
                    <td>{{ $product->crr_number }}</td>
                </tr>
                <tr>
                    <th>Application</th>
                    <td>{{ $product->application }}</td>
                    <th>Disposition</th>
                    <td>{{ $product->disposition }}</td>
                </tr>
                <tr>
                    <th>Product Code</th>
                    <td>{{ $product->code }}</td>
                    <th>Disposition Remarks</th>
                    <td>{{ $product->disposition_remarks }}</td>
                </tr>
                <tr>
                    <th>Product Description</th>
                    <td colspan="3">{{ $product->description }}</td>
                </tr>
                <tr>
                    <th>Number of Packages</th>
                    <td>{{ $product->packages }}</td>
                    <th>Quantity</th>
                    <td>{{ $product->quantity }}</td>
                </tr>
                <tr>
                    <th>Label</th>
                    <td colspan="3">{{ $product->label }}</td>
                </tr>
            </table>

            <div class="remarks">
                <p><strong>Remarks:</strong> {{ $product->remarks }}</p>
            </div>
        </div>
        @endforeach --}}

    </div>
</body>
</html>
