<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{asset('images/wgroup.png')}}" type="image/x-icon">
    <title>Customer Relationship Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<style>
body {
    font-size: 9;
}

@page {
    margin: 90px 30px 60px 30px;
}

.page-break {
    page-break-after: always;
}

table {
    page-break-inside: auto;
    width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
    margin-top: 10px;
}

thead {
    display: table-row-group;
}

tr {
    page-break-inside: auto;
}

p {
    text-align: justify;
    text-justify: inter-word;
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    padding: 0;
}

header {
    margin-top: -75px;
    margin-bottom: 0;
}

input[type="checkbox"] {
    display: inline;
}
</style>

<body>
    <header>
        <table border="1" style="width: 100%; margin-bottom: -10px !important;" cellspacing="0" cellpadding="4">
            <tr>
                <td rowspan="2">
                    <img src="{{asset('images/whi.png')}}" alt="" height="45" width="90" style="vertical-align: middle;">
                    <p style="font-size:24px;" class="d-inline-block font-weight-bold ml-1">W HYDROCOLLOIDS, INC.</p>
                </td>
                <td>
                    <p class="text-center"><b>SAMPLE REQUEST FORM</b></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="text-center">FR-S&M-04 rev 02</p>
                </td>
            </tr>
        </table>
    </header>

    {{-- <div class="page-break">
    </div> --}}
    <table border="1" style="width: 100%; font-size:9 margin-top:0; height:min-content;" cellspacing="0" cellpadding="0">
        <table border="1" style="width: 80%; font-size:9; margin: 0 auto;" class="mt-4" cellspacing="0" cellpadding="0">
            <h4 class="text-center">{{$sample_requests->client->Name}}</h4>
        </table>
        <div style="font-size: 12px; text-align: center; margin-top:20px;">
            <label style="margin-right: 35px;">
                <input type="checkbox"> Pre-Ship Sample
            </label>
            <label style="margin-right: 35px;">
                <input type="checkbox"> Regular Sample
            </label>
            <label>
                <input type="checkbox"> Co-Ship Sample
            </label>
        </div>
        <table border="0" style="width: 100%; font-size:9; height:min-content;" cellspacing="0" cellpadding="0">
            <tr>
                <td width="15%">
                    <p class="text-right">Request Date </p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-left"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{date('F d, Y', strtotime($sample_requests->DateRequested))}}</strong></p>
                </td>
                <td width="25%">
                    <p class="text-right font-weight-bold">SRF No: </p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%; font-size: 18px;">{{$sample_requests->SrfNumber}}</strong></p>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <p class="text-right font-weight-bold">Deadline </p>
                </td>
                <td width="25%">
                    <p class="ml-1 text-left"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{date('F d, Y', strtotime($sample_requests->DateRequired))}}</strong></p>
                </td>
                <td width="15%">
                    <p class="text-right font-weight-bold">Laboratory </p>
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">@if($sample_requests->RefCode == 1)
                                RND
                            @elseif($sample_requests->RefCode == 2)
                                QCD
                            @else
                                {{ $sample_requests->RefCode }}
                            @endif</strong></p>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <p class="text-right">Attention</p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-left"><span class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{ optional($sample_requests->clientContact)->ContactName}}</span></p>
                </td>
                <td width="25%">
                    <p class="text-right"></p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-left"><span class="d-inline-block"></span></p>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <p class="text-right">Address</p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-left"><span class="d-inline-block" style="border-bottom: 1px dashed black; width:100%; white-space: pre-line;">{{ optional($sample_requests->clientAddress)->Address }}</span></p>
                </td>
                <td width="25%">
                    <p class="text-right"></p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><span class="d-inline-block"></span></p>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <p class="text-right">Telephone</p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-left"><span class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{ optional($sample_requests->clientContact)->PrimaryTelephone}}</span></p>
                </td>
                <td width="25%">
                    <p class="text-right"> </p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><span class="d-inline-block" >&nbsp;</span></p>
                </td>
            </tr>
        </table>
        <div style="position: relative; ">
            <div class="div" style="font-size:12px; position: fixed; left:400px; top: 200px; width: 60%;">
            <p class="ml-1 text-left" style="color:red">No. of Days for Sample Preparation</p>
            <p class="ml-1 text-left">Pure Products - Maximum of 2 days</p>
            <p class="ml-1 text-left">Blended Products - Maximum of 4 days</p>
        </div>
        </div>
        <div class="div" style="margin-top:40px; min-height:510px !important; max-height:510px !important;">
            <table border="0.5" style="width: 100%; font-size:9px; margin-top:0;" cellspacing="0" cellpadding="0">
                <thead>
                    <tr style="text-align: center">
                        <th style="width:8%">SRF #</th>
                        <th style="width:8%">RPE #</th>
                        <th style="width:8%:">CRR #</th>
                        <th style="width:9%">PRODUCT <br> CODE</th>
                        <th style="width:9%">Label</th>
                        <th style="width:10%">No. of  <br> Packs x <br> Weight</th>
                        <th style="width:9%">Product <br> Description</th>
                        <th style="width:14%">Remarks <br> (For Internal Purposes <br> Only)</th>
                        <th style="width:0%">Disposition</th>
                        <th style="width:15%">Disposition <br> Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    @foreach ( $sample_requests->requestProducts as $requestProducts)
                        <tr>
                            <td>{{ $sample_requests->SrfNumber}}-{{ $requestProducts->ProductIndex }}</td>
                            <td>{{$requestProducts->RpeNumber}}</td>
                            <td>{{$requestProducts->CrrNumber}}</td>
                            <td>{{ $requestProducts->ProductCode }}</td>
                            <td>{{ $requestProducts->Label }}</td>
                            <td> {{ number_format($requestProducts->NumberOfPackages,2) }} x 
                                {{ $requestProducts->Quantity }} 
                                @if ( $requestProducts->UnitOfMeasureId == 1)
                                    g
                                @elseif ($requestProducts->UnitOfMeasureId == 2)
                                    kg
                                @endif
                            </td>
                            <td>{{ $requestProducts->ProductDescription }}</td>
                            <td>{{ $requestProducts->Remarks }}</td>
                            <td>
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
                            <td>{{$requestProducts->DispositionRejectionDescription}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <table style="width: 100%; font-size:9px; margin-top:0;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="border: none; font-size: 12px; padding: 10px;"><i>OTHER INSTRUCTIONS</i></td>
            </tr>
            <tr>
                <td style="font-size: 12px; text-align: center; padding:5px">
                    Please send COA and PDS
                </td>
            </tr>
        </table>
    </table>
    <table border="1" style="width: 100%; font-size:9; height:min-content; margin-top:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="5">
                <p style="width: 100%; border-bottom:10px solid black;"></p>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; font-size:9; margin-top:0; font-size: 11px;">
        <tr>
            <td width="50%">
                <strong class="text-left">DISPATCH INFORMATION:</strong>
                <table style="width:100%; border:none" >
                    <tr >
                        <td width="30%" style="border:none">
                            <p class="text-left">Date Sent</p>
                        </td>
                        <td width="70%" style="border:none">
                            <p class="ml-1 text-left">
                                <p class="d-inline-block" style="border-bottom: 1px dashed black; width:80%;">
                                    {{ $sample_requests->DateDispatched }}
                                </p>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" style="border:none">
                            <p class="text-left">Courier</p>
                        </td>
                        <td width="70%" style="border:none"> 
                            <p class="ml-1 text-left">
                                <p class="d-inline-block" style="border-bottom: 1px dashed black; width:80%;">
                                    {{ $sample_requests->Courier  }}
                                </p>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" style="border:none">
                            <p class="text-left">AWB#</p>
                        </td>
                        <td width="70%" style="border:none"> 
                            <p class="ml-1 text-left">
                                <p class="d-inline-block" style="border-bottom: 1px dashed black; width:80%;">
                                    {{ $sample_requests->AwbNumber }}
                                </p>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table style="width:100%; border:none" >
                    <tr >
                        <td width="30%" style="border:none">
                            <p class="text-left">Prepared by</p>
                        </td>
                        <td width="70%" style="border:none">
                            <p class="ml-1 text-left">
                                <span class="d-inline-block" style="border-bottom: 1px dashed black; width:80%; color: transparent;">
                                    Hidden text
                                </span>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" style="border:none">
                            <p class="text-left">Approved by</p>
                        </td>
                        <td width="70%" style="border:none">
                            <p class="ml-1 text-left">
                                <span class="d-inline-block" style="border-bottom: 1px dashed black; width:80%; color: transparent;">
                                    Hidden text
                                </span>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" style="border:none">
                            <p class="text-left">Copy Received by</p>
                        </td>
                        <td width="70%" style="border:none">
                            <p class="ml-1 text-left">
                                <span class="d-inline-block" style="border-bottom: 1px dashed black; width:80%; color: transparent;">
                                    Hidden text
                                </span>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table> 
    

    {{-- <table border="1" style="width: 100%; font-size:9; margin-top:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="4" class="bg-secondary">
                <p class="text-center font-weight-bold">Details of the Requirement</p>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; font-size:9; margin-top:0; height:min-content;" cellspacing="0" cellpadding="10">
        <tr>
            <td colspan="5">
                <p style="width:100vw;">{!! nl2br($crr->DetailsOfRequirement) !!}</p>
            </td>
        </tr>
        <tr>
            <td style="border: none;">
                <input type="checkbox"> <small>Data Sheet</small>
            </td>
            <td style="border: none;">
                <input type="checkbox"> <small>Nutritional Data</small>
            </td>
            <td style="border: none;">
                <input type="checkbox"> <small>Formula</small>
            </td>
            <td style="border: none;">
            </td>
            <td style="border: none;">
                <input type="checkbox"> <small>Others</small>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; font-size:9; margin-top:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="4" class="bg-secondary">
                <p class="text-center font-weight-bold">Remarks</p>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; font-size:9; height:min-content; margin-top:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="5" height="200" valign="top">
                <p class="ml-5 mb-0 mt-0">Recommended by: _________________</p>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <p style="width: 100%; border-bottom:10px solid black;"></p>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; font-size:9; margin-top:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td width="33.33%">
                <p class="ml-3 mb-0 mt-0"><i><small>Prepared by</small></i></p>

                <p class="text-center" style="border-top: 1px solid black; width:80%; margin: 40px auto 5px auto;"><small>(Signature Over Printed Name)</small></p>
            </td>
            <td width="33.33%">
                <p class="ml-3 mb-0 mt-0"><i><small>Approved by</small></i></p>

                <p class="text-center" style="border-top: 1px solid black; width:80%; margin: 40px auto 5px auto;"><small>(Signature Over Printed Name)</small></p>
            </td>
            <td width="33.33%">
                <p class="ml-3 mb-0 mt-0"><i><small>Date Received by</small></i></p>

                <p class="text-center" style="border-top: 1px solid black; width:80%; margin: 40px auto 5px auto;"><small>(Signature Over Printed Name)</small></p>
            </td>
        </tr>
    </table> --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>