<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Dispatch Advice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            border: 1px solid black;
        }
        th {
            text-align: left;
            background-color: #ffcc00;
            font-weight: bold;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
        }
        .product-table td {
            padding: 5px;
            border: 1px solid black;
        }
        .product-table th {
            text-align: left;
            background-color: #ffcc00;
            font-weight: bold;
        }
        .highlight {
            background-color: #ffcc00;
            font-weight: bold;
        }
        .section-title {
            font-weight: bold;
            text-decoration: underline;
        }
        .notes {
            font-style: italic;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <p class="section-title">Sample Dispatch Advice</p>
    <p class="notes">*Please email confirmation upon receipt of the samples.</p>

    <table>
        <tr>
            <td class="highlight">Contact Person:</td>
            <td>{{ optional($sample_requests->clientContact)->ContactName}}</td>
        </tr>
        <tr>
            <td class="highlight">Company Name:</td>
            <td>{{  optional($sample_requests->client)->Name }}</td>
        </tr>
        <tr>
            <td class="highlight">Address:</td>
            <td>{{  optional($sample_requests->clientAddress)->Address }}</td>
        </tr>
    </table>

    <br>

    <table class="product-table">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Lot No.</th>
            <th>Product Description</th>
        </tr>
        @foreach ( $sample_requests->requestProducts as $requestProducts)
            <tr>
                <td>{{  $requestProducts->ProductCode }}</td>
                <td>{{ $requestProducts->NumberOfPackages }} x {{ $requestProducts->Quantity }}  @if ( $requestProducts->UnitOfMeasureId == 1)
                    g
                    @elseif ($requestProducts->UnitOfMeasureId == 2)
                    kg
                    @endif</p></td>
                <td>{{ $sample_requests->SrfNumber}}-{{ $requestProducts->ProductIndex }}</td>
                <td>{{ $requestProducts->ProductDescription }}</td>
            </tr>
        @endforeach
    </table>

    <br>

    <table>
        <tr>
            <td class="highlight">Documents:</td>
            <td>Proforma Invoice, Certification, MSDS, Cover Letter</td>
        </tr>
        <tr>
            <td class="highlight">Courier Company:</td>
            <td>{{ $sample_requests->Courier  }}</td>
        </tr>
        <tr>
            <td class="highlight">Airway Bill No.:</td>
            <td>{{ $sample_requests->AwbNumber }}</td>
        </tr>
        <tr>
            <td class="highlight">Date of Dispatch:</td>
            <td>{{ \Carbon\Carbon::parse($sample_requests->DateDispatched)->format('F d, Y') }}
            </td>
        </tr>
        <tr>
            <td class="highlight">ETA:</td>
            <td>{{ \Carbon\Carbon::parse($sample_requests->Eta)->format('F d, Y') }}
        </tr>
    </table>

</body>
</html>
