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
                    <p class="text-center"><b>REQUEST FOR PRODUCT EVALUATION</b></p>
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
            <h4 class="text-center">{{$product_evaluations->client->Name}}</h4>
        </table>
        <table border="0" style="width: 100%; font-size:9; height:min-content;" cellspacing="0" cellpadding="0">
            <tr>
                <td width="15%">
                    <p class="text-right">Request Date </p> 
                </td>
                <td width="30%">
                    <p class="ml-1 text-left"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{date('F d, Y', strtotime($product_evaluations->created_at))}}</strong></p>
                </td>
                <td width="25%">
                    <p class="text-right font-weight-bold">SRF No: </p> 
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%; font-size: 18px;">{{$product_evaluations->RpeNumber}}</strong></p>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <p class="text-right font-weight-bold">Deadline </p>
                </td>
                <td width="30%">
                    <p class="ml-1 text-left"><strong class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{date('F d, Y', strtotime($product_evaluations->DueDate))}}</strong></p>
                </td>
                <td width="15%">
                    <p class="text-right font-weight-bold">Priority </p>
                </td>
                <td width="25%">
                    <p class="ml-1 text-center"><p class="d-inline-block" style="text-align:center; border-bottom: 1px dashed black; width:100%;">
                            @if($product_evaluations->Priority == 1)
                                IC Application
                            @elseif($product_evaluations->Priority == 3)
                                Second Priority
                            @elseif($product_evaluations->Priority == 5)
                                First Priority
                            @else
                                {{ $product_evaluations->Priority }}
                            @endif
                    </p>
                        </p>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <p class="text-right">Region</p> 
                </td>
                <td width="30%">
                    <p class="ml-1 text-left"><span class="d-inline-block" style="border-bottom: 1px dashed black; width:100%;">{{ optional(optional($product_evaluations->client)->clientregion)->Name }}</span></p>
                </td>
                <td width="5%">
                    <p class="text-right font-weight-bold"></p>
                </td>
                <td width="35%">
                    <p class="ml-1 text-center">
                        <p class="d-inline-block" style="font-size:10px; text-align:center; width:100%;">
                            1st: Max. 7 days; 2nd: 12-18 days; IC: 15-20 days
                        </p>
                    </p>
                </td>
            </tr>
        </table>
        <div style="font-size:12px; margin-top:15px;">
            <div style="margin-bottom:5px ;margin-left:20px;">
                Nature of RPE
            </div>

            <div style="text-align:center; white-space:nowrap;">
                <label style="margin-right:25px;">
                    <input type="checkbox"> Modification of Existing Blend
                </label>
                <label style="margin-right:25px;">
                    <input type="checkbox"> Product Development
                </label>
                <label>
                    <input type="checkbox"> Duplication of Product
                </label>
            </div>

            <div style="text-align:left; margin-top:-10px; white-space:nowrap;margin-left:120px;">
                <label style="margin-right:25px;">
                    <input type="checkbox"> Continuing Project
                </label>
                <label>
                    <input type="checkbox"> Product Comparison
                </label>
            </div>
        </div>

        <div style="font-size:12px; margin-top:15px;">
            <div style="margin-bottom 2px ;margin-left:20px;">
                Sample Information
            </div>

             <div style="white-space:nowrap;">
                <table style="width:50%; font-size:12px; margin-left:auto; margin-right:auto; border-collapse:collapse;" cellspacing="0" cellpadding="0">
                    <tbody>
                            <tr>
                                <td style="width:20%">Sample Sources</td>
                                <td style="width:30%"></td>
                            </tr>
                            <tr>
                                <td style="width:20%">Manufacturer</td>
                                <td style="width:30%">{{$product_evaluations->Manufacturer}}</td>
                            </tr>
                            <tr>
                                <td style="width:20%">Product Code</td>
                                <td style="width:30%">{{$product_evaluations->SampleName}}</td>
                            </tr>
                            <tr>
                                <td style="width:20%">Volume Requirement</td>
                                <td style="width:30%">{{$product_evaluations->PotentialVolume}}</td>
                            </tr>
                            <tr>
                                <td style="width:20%">Price</td>
                                <td style="width:30%">{{$product_evaluations->TargetRawPrice}}</td>
                            </tr>
                            <tr>
                                <td style="width:20%">Previous RPE</td>
                                <td style="width:30%"></td>
                            </tr>
                            <tr>
                                <td style="width:20%">Last Product</td>
                                <td style="width:30%"></td>
                            </tr>
                            <tr>
                                <td style="width:20%">Client Requirement</td>
                                <td style="width:30%"></td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="font-size:12px; margin-top:15px;">
            <div style="margin-bottom 2px ;margin-left:20px;">
                Other Information
            </div>

             <div style="white-space:nowrap;">
                <table style="width:50%; font-size:12px; margin-left:auto; margin-right:auto; border-collapse:collapse;" cellspacing="0" cellpadding="0">
                    <tbody>
                            <tr>
                                <td style="width:20%">Sample Name</td>
                                <td style="width:30%">{{ $product_evaluations->SampleName  }}</td>
                            </tr>
                            <tr>
                                <td style="width:20%">DATA</td>
                                <td style="width:30%"></td>
                            </tr>
                            <tr>
                                <td style="width:20%">Application</td>
                                <td style="width:30%">{{ optional($product_evaluations->product_application)->Name  }}</td>
                            </tr>
                            <tr>
                                <td style="width:20%">Process Condition</td>
                                <td style="width:30%"></td>
                            </tr>
                            <tr>
                                <td style="width:20%">Usage</td>
                                <td style="width:30%"></td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>

       <div style="font-size:12px; margin-top:15px;">
            <div style="margin-bottom 2px; margin-left:20px;">
                Marketing Instructions
            </div>

            <div style="text-align:center;">
                <table style="width:50%; font-size:9px; margin:0 auto;">
                    <tbody>
                        <tr>
                            <td style="text-align:center; vertical-align:middle; padding:10px;">
                                {!! nl2br(e($product_evaluations->ObjectiveForRpeProject)) !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="font-size:12px; margin-top:20px;">

            <p style="font-weight:bold; text-decoration:underline; margin-bottom:6px;">Recommended Tests</p>

            <p style="font-weight:bold; margin-top:10px;">Chemical Analysis</p>
            <div style="text-align:center; white-space:nowrap;">
                <label style="margin-right:25px;">
                    <input type="checkbox"> Gum Content
                </label>
                <label style="margin-right:25px;">
                    <input type="checkbox"> Chloride Content
                </label>
                <label style="margin-right:25px;">
                    <input type="checkbox"> Borax test
                </label>
                <label style="margin-right:25px;">
                    <input type="checkbox"> Sugar Test
                </label>
                <label style="margin-right:25px;">
                    <input type="checkbox"> Fraction
                </label>
            </div>

            <p style="font-weight:bold; margin-top:3px;">Physical Analysis</p>
            <div style="text-align:center; white-space:nowrap;">
                <label style="margin-right:25px;">
                    <input type="checkbox"> Particle Size
                </label>
                <label style="margin-right:25px;">
                    <input type="checkbox"> Syneresis
                </label>
                <label style="margin-right:25px;">
                    <input type="checkbox"> pH
                </label>
                <label style="margin-right:25px;">
                    <input type="checkbox"> Powder Color
                </label>
            </div>
            <div style="text-align:center; white-space:nowrap;">
                <label style="margin-right:25px;">
                    <input type="checkbox"> Gel Color
                </label>
                <label style="margin-right:25px;">
                    <input type="checkbox"> Clarity
                </label>
                <label style="margin-right:25px;">
                    <input type="checkbox"> Odor
                </label>
                <label style="margin-right:25px;">
                    <input type="checkbox"> Brine Swelling
                </label>
            </div>

           <table style="width:70%; border-collapse:collapse; margin:8px auto 0 auto; border:none; font-size:8px">
                <tr>
                    <td style="width:50%; vertical-align:top; border:none; padding:0;">
                        <p style="font-weight:bold; margin-bottom:4px;">Gel Tests</p>
                        <table style="width:95%; border-collapse:collapse; margin:0 auto;" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Water Gel</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                           <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Potassium Gel</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Brine Gel</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Calcium Gel</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Dessert Gel</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Milk Gel</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                        </table>
                    </td>

                    <td style="width:50%; vertical-align:top; border:none; padding:0;">
                        <p style="font-weight:bold; margin-bottom:4px;">Viscosity</p>
                        <table style="width:95%; border-collapse:collapse; margin:0 auto;">
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Water</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                           <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox">Brine</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Choco Milk</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Ice cream</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Milk</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>



            <table style="width:70%; border-collapse:collapse; margin:8px auto 20 auto; border:none; font-size:8px">
                <tr>
                    <td style="width:50%; vertical-align:top; border:none; padding:0;">
                        <p style="font-weight:bold; margin-bottom:4px;">Micro Analysis</p>
                        <table style="width:95%; border-collapse:collapse; margin:0 auto;">
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> TPC</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                           <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> YMC</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Salmonella</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> E. Coli</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                        </table>
                    </td>

                    <td style="width:50%; vertical-align:top; border:none; padding:0;">
                        <p style="font-weight:bold; margin-bottom:4px;">Others</p>
                        <table style="width:95%; border-collapse:collapse; margin:0 auto;">
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Dissolution</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                           <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox">Solubility</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Hydration</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #000; padding:0px; width: 40%;"><input type="checkbox"> Others</td>
                                <td style="border:1px solid #000; padding:0px; width: 60%;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </div>
    <table border="1" style="width: 100%; font-size:9; height:min-content; margin-top:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="5">
                <p style="width: 100%; border-bottom:10px solid black;"></p>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; font-size:9; font-size: 11px;">
        <tr>
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
            <td width="50%">
                <table style="width:100%; border:none" >
                    <tr >
                        <td width="30%" style="border:none">
                            <p class="text-left">RPE Closed On</p>
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
                            <p class="text-left">Report Receive</p>
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
                            <p class="text-left">Analysis</p>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>