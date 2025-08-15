<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{asset('images/wgroup.png')}}" type="image/x-icon">
    <title>Customer Satisfaction</title>
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
    margin-top: -75px;
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
                    <p class="text-center"><b>CUSTOMER SATISFACTION REPORT</b></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="text-center">FR-S&M-16rev00</p>
                </td>
            </tr>
        </table>
    </header>
    <table style="font-size:9; margin-top: 0px" cellspacing="0" cellpadding="0" width="100%">
        <tr style="border:1px solid">
            <td colspan="5" align="center" style="padding: 10px;font-size: 12px"><b>{{ $cs->CsNumber }}</b></td>
        </tr>
        <tr>
            <td style="border-left: 1px solid" width="15%">&nbsp;Date Received:</td>
            <td width="20%">{{ $cs->DateReceived }}</td>
            <td width="20%"></td>
            <td width="25%">&nbsp;CSR No.:</td>
            <td style="border-right: 1px solid" width="20%">{{ $cs->CsNumber }}</td>
        </tr>
        <tr>
            <td style="border-left: 1px solid;border-right: 1px solid" width="20%" colspan="5"><b>&nbsp;Client Information</b></td>
        </tr>
        <tr>
            <td style="border-left: 1px solid" width="15%">&nbsp;Contact Person:</td>
            <td width="20%">{{ $cs->ContactName }}</td>
            <td width="20%"></td>
            <td width="25%">&nbsp;Department/s Concerned:</td>
            <td style="border-right: 1px solid" width="20%">{{ $cs->Concerned }}</td>
        </tr>
        <tr>
            <td style="border-left: 1px solid" width="15%">&nbsp;Company Name:</td>
            <td width="20%">{{ $cs->CompanyName }}</td>
            <td width="20%"></td>
            <td width="25%">&nbsp;Category:</td>
            <td style="border-right: 1px solid" width="20%">{{ $cs->category->Name }}</td>
        </tr>
        <tr>
            <td style="border-left: 1px solid" width="15%">&nbsp;Contact Number:</td>
            <td colspan="4" width="20%" style="border-right: 1px solid">{{ $cs->ContactNumber }}</td>
        </tr>
        <tr>
            <td style="border-left: 1px solid" width="15%">&nbsp;Email:</td>
            <td colspan="4" width="20%" style="border-right: 1px solid">{{ $cs->Email }}</td>
        </tr>
        <tr style="border:1px solid">
            <td colspan="5" align="center"><b>CUSTOMER REMARKS</b></td>
        </tr>
        <tr style="border:1px solid">
            <td colspan="5" align="center" style="padding: 50px"><b></b></td>
        </tr>
        <tr style="border:1px solid">
            <td colspan="5" align="center"><b>DESCRIPTION</b></td>
        </tr>
        <tr style="border:1px solid">
            <td colspan="5" align="center" style="padding: 50px">{{ $cs->Description }}</td>
        </tr>
        <tr style="border:1px solid">
            <td colspan="5" align="center"><b>INTERNAL INSTRUCTIONS AND REMARKS</b></td>
        </tr>
        <tr style="border:1px solid">
            <td colspan="5" align="center" style="padding: 50px">
                @foreach($cs->remarks->sortByDesc('created_at') as $remark)
                    {{ $remark->Remarks }} -
                @endforeach
            </td>
        </tr>
        <tr>
            <td style="border-left: 1px solid;padding-top: 20px" width="20%">&nbsp;Received By:</td>
            <td colspan="4" width="70" style="border-right: 1px solid;padding-top: 20px">{{ $cs->users->full_name }}</td>
        </tr>
        <tr>
            <td style="border-left: 1px solid;padding-top: 40px" width="20%">&nbsp;Noted By:</td>
            <td colspan="4" width="70" style="border-right: 1px solid;padding-top: 40px">{{ $cs->notedBy->full_name }}</td>
        </tr>
        <tr>
            <td style="border-left: 1px solid;padding: 40px 0px 20px 0px;border-bottom: 1px solid" width="20%">&nbsp;Approved By:</td>
            <td colspan="4" width="70" style="border-right: 1px solid;border-bottom: 1px solid;padding: 40px 0px 20px 0px;">{{ $cs->approvedBy->full_name }}</td>
        </tr>
    </table>
</body>
</html>