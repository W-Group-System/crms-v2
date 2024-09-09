<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: right;
            margin-bottom: 10px;
        }

        .section-title {
            background-color: #d3d3d3;
            padding: 5px;
            margin-top: 10px;
            font-weight: bold;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            border: none;
            vertical-align: middle; 
        }

        .label {
            font-weight: bold;
            text-align: right;
            width: 50%;
        }

        .detail {
            text-align: left; 
            padding-left: 10px;
            width: 50%; 
        }
        .signatures {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding-top: 50px;
            text-align: center;
        }

        .signature-space {
            width: 33%;
            display: inline-block;
            text-align: center;
            margin-top: 50px;
        }

        .page {
            position: relative;
            min-height: 100vh; 
            padding-bottom: 100px; 
        }

        .two-columns {
            display: table;
            width: 100%;
        }

        .column {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        .border {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 10px;
        overflow: hidden; 
        }
        .form-divider{
        border-top: 3px solid rgb(0, 13, 255);
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <p>{{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
        </div>
        <h3>Sample Request Details</h3>
        <hr class="form-divider">

        <div class="section-title">Customer Details</div>
        <div class="two-columns">
            <div class="column">
                <table class="details-table">
                    <tr>
                        <td class="label"><span>Client Name:</span></td>
                        <td class="detail">{{ $sample_requests->client->Name }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Client Trade Name:</span></td>
                        <td class="detail">{{ $sample_requests->client->trade_name }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Region:</span></td>
                        <td class="detail">{{ optional(optional($sample_requests->client)->clientregion)->Name }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Country:</span></td>
                        <td class="detail">{{ optional(optional($sample_requests->client)->clientcountry)->Name }}</td>
                    </tr>
                </table>
            </div>

            <div class="column">
                <table class="details-table">
                    <tr>
                        <td class="label"><span>Contact:</span></td>
                        <td class="detail">{{ optional($sample_requests->clientContact)->ContactName}}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Telephone:</span></td>
                        <td class="detail">{{ optional($sample_requests->clientContact)->PrimaryTelephone}}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Mobile:</span></td>
                        <td class="detail">{{ optional($sample_requests->clientContact)->PrimaryMobile}}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Email:</span></td>
                        <td class="detail">{{ optional($sample_requests->clientContact)->EmailAddress}}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Skype:</span></td>
                        <td class="detail">{{ optional($sample_requests->clientContact)->Skype}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="section-title">Requirement Details</div>
        <div class="two-columns">
            <div class="column">
                <table class="details-table">
                    <tr>
                        <td class="label"><span>SRF #:</span></td>
                        <td class="detail">{{ $sample_requests->SrfNumber }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Manual SRF #:</span></td>
                        <td class="detail">{{ $sample_requests->ManualSrfNumber }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Date Requested:</span></td>
                        <td class="detail">{{ $sample_requests->DateRequested }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Date Required:</span></td>
                        <td class="detail">{{ $sample_requests->DateRequired }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Date Started:</span></td>
                        <td class="detail">{{ $sample_requests->DateStarted }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>REF CODE:</span></td>
                        <td class="detail">
                            @if($sample_requests->RefCode == 1)
                                RND
                            @elseif($sample_requests->RefCode == 2)
                                QCD
                            @else
                                {{ $sample_requests->RefCode }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><span>Type:</span></td>
                        <td class="detail">
                            @if($sample_requests->SrfType == 1)
                                Regular
                             @elseif($sample_requests->SrfType == 2)
                                PSS
                            @elseif($sample_requests->SrfType == 3)
                                CSS
                            @else
                                {{ $sample_requests->InternalRemarks }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><span>Remarks:</span></td>
                        <td class="detail">{{ $sample_requests->DateStarted }}</td>
                    </tr>
                </table>
            </div>

            <div class="column">
                <table class="details-table">
                    <tr>
                        <td class="label"><span>Primary Sales Person:</span></td>
                        <td class="detail">{{ optional($sample_requests->primarySalesPerson)->full_name}}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Secondary Sales Person:</span></td>
                        <td class="detail">{{ optional($sample_requests->secondarySalesPerson)->full_name}}</td>
                    </tr>
                </table>
            </div>
        </div>
        @foreach ( $sample_requests->requestProducts as $requestProducts)
            <div class="border">
                <div class="two-columns">
                    <div class="column">
                        <table class="details-table">
                            <tr>
                                <td class="label"><span>Index #:</span></td>
                                <td class="detail">{{ $sample_requests->SrfNumber}}-{{ $requestProducts->ProductIndex }}</td>
                            </tr>
                            <tr>
                                <td class="label"><span>Product Type:</span></td>
                                <td class="detail">
                                    @if($requestProducts->ProductType == 1)
                                        Pure
                                    @elseif($requestProducts->ProductType == 2)
                                        Blend
                                    @else
                                    {{ $requestProducts->ProductType }}
                                    @endif
                                </td>
                            </tr>        
                            <tr>
                                <td class="label"><span>Application:</span></td>
                                <td class="detail">{{ $requestProducts->productApplicationsId->Name }}</td>
                            </tr>
                            <tr>
                                <td class="label"><span>Product Code:</span></td>
                                <td class="detail">{{ $requestProducts->ProductCode }}</td>
                            </tr>
                            <tr>
                                <td class="label"><span>Product Description:</span></td>
                                <td class="detail">{{ $requestProducts->ProductDescription }}</td>
                            </tr>
                            <tr>
                                <td class="label"><span>Number of Packages:</span></td>
                                <td class="detail">{{ $requestProducts->NumberOfPackages }}</td>
                            </tr>
                            <tr>
                                <td class="label"><span>Quantity:</span></td>
                                <td class="detail">
                                    {{ $requestProducts->Quantity }} 
                                    @if ( $requestProducts->UnitOfMeasureId == 1)
                                    g
                                    @elseif ($requestProducts->UnitOfMeasureId == 2)
                                    kg
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><span>Label:</span></td>
                                <td class="detail">{{ $requestProducts->Label }}</td>
                            </tr>
                            <tr>
                                <td class="label"><span>Remarks:</span></td>
                                <td class="detail">{{ $requestProducts->Remarks }}</td>
                            </tr>
                        </table>
                    </div>
        
                    <div class="column">
                        <table class="details-table">
                            <tr>
                                <td class="label"><span>RPE #:</span></td>
                                <td class="detail">{{$requestProducts->RpeNumber}}</td>
                            </tr>
                            <tr>
                                <td class="label"><span>CRR #:</span></td>
                                <td class="detail">{{$requestProducts->CrrNumber}}</td>
                            </tr>
                            <tr>
                                <td class="label"><span>Disposition:</span></td>
                                <td class="detail">
                                    @if ($requestProducts->Disposition == '1')
                                        No Feedback
                                    @elseif ($requestProducts->Disposition == '10')
                                        Accepted
                                    @elseif ($requestProducts->Disposition == '20')
                                        Rejected
                                    @else
                                        NA
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><span>Disposition Remarks:</span></td>
                                <td class="detail">{{$requestProducts->DispositionRejectionDescription}}</td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="signatures">
        <div class="signature-space">[Signature over printed name]</div>
        <div class="signature-space">[Signature over printed name]</div>
        <div class="signature-space">[Signature over printed name]</div>
    </div>
</body>
</html>