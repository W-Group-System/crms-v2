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
    margin: 90px 50px 80px 50px;
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
    margin-top: -75pxs;
    margin-bottom: 0;
}

input[type="checkbox"] {
    display: inline;
}
</style>

<body>
    <header>
        <table border="1" style="width: 100%;" cellspacing="0" cellpadding="4">
            <tr>
                <td rowspan="2">
                    <img src="{{asset('images/whi.png')}}" alt="" height="45" width="90" style="vertical-align: middle;">
                    <p style="font-size:15;" class="d-inline-block font-weight-bold ml-1">W HYDROCOLLOIDS, INC.</p>
                </td>
                <td>
                    <p class="text-center"><b>CUSTOMER REQUIREMENT REPORT</b></p>
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
            <h4 class="text-center">{{$crr->client->Name}}</h4>
        </table>
        <table border="0" style="width: 100%; font-size:9; height:min-content; padding:5;" cellspacing="0" cellpadding="0">
            <tr>
                <td width="15%">
                    <p class="text-right">Request Date </p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{date('F d, Y', strtotime($crr->DateCreated))}}</strong></p>
                </td>
                <td width="25%">
                    <p class="text-right font-weight-bold">Crr No: </p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{$crr->CrrNumber}}</strong></p>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <p class="text-right">Deadline </p>
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{date('F d, Y', strtotime($crr->DueDate))}}</strong></p>
                </td>
                <td width="25%">
                    <p class="text-right"><i><small>Consolidate with</small></i></p>
                </td>
                <td width="25%">
                    <table border="0" style="width: 100%; font-size:9;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <p class="ml-4 mb-0"><strong>RPE No.:</strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="ml-4 mb-0"><strong>CRR No.:</strong></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <p class="text-right">Application</p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{$crr->product_application->Name}}</strong></p>
                </td>
                <td width="25%">
                    <p class="text-right">Target Price: </p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{$crr->TargetPrice}}</strong></p>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <p class="text-right">Country</p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{$crr->client->clientcountry->Name}}</strong></p>
                </td>
                <td width="25%">
                    <p class="text-right">Competitor: </p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">@if($crr->Competitor){{$crr->Competitor}}@else &nbsp; @endif</strong></p>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <p class="text-right">Region</p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{$crr->client->clientregion->Name}}</strong></p>
                </td>
                <td width="25%">
                    <p class="text-right">Attachment: </p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">&nbsp;</strong></p>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <p class="text-right">Potential Volume</p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{$crr->PotentialVolume}}</strong></p>
                </td>
                <td width="25%">
                    {{-- <p class="text-right">Target Price: </p>  --}}
                </td>
                <td width="25%">
                    {{-- <p class="ml-1"><strong style="border-bottom: 1px dashed black;">{{$crr->TargetPrice}}</strong></p> --}}
                </td>
            </tr>
        </table>
    </table>
    <table border="1" style="width: 100%; font-size:9; margin-top:0;" cellspacing="0" cellpadding="0">
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
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>