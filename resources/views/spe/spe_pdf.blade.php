<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{asset('images/wgroup.png')}}" type="image/x-icon">
    <title>Supplier Product Evaluation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<style>
    body {
        font-size: 12px;
    }

    @page {
        margin: 40px;
    }
    th, td {
        padding: 3px;
    }
    .page-break {
        page-break-after: always;
    }
    input[type="checkbox"] {
        display: inline;
    }
</style>

<header>
    <div align="center">
        <img src="{{asset('images/whi.png')}}" alt="" height="45" width="90" style="vertical-align: middle;"><br>
        <label class="mt-1"><b style="font-size: 14px">SUPPLIER'S PRODUCT EVALUATION</b></label>
    </div>
    <table border="1" style="width: 100%;" cellspacing="0">
        <tr>
            <td width="60%">
                <label>COMPANY:</label>
                <div class="d-inline">
                    <input type="checkbox" value="RND" {{ $spe->AttentionTo == 'RND' ? 'checked' : '' }}>&nbsp;RND&nbsp;
                    <input type="checkbox" value="QCD-WHI" {{ $spe->AttentionTo == 'QCD-WHI' ? 'checked' : '' }}>&nbsp;WHI-C&nbsp;
                    <input type="checkbox" value="QCD-CCC" {{ $spe->AttentionTo == 'QCD-CCC' ? 'checked' : '' }}>&nbsp;CCC&nbsp;
                    <input type="checkbox" value="QCD-MRDC" {{ $spe->AttentionTo == 'QCD-MRDC' ? 'checked' : '' }}>&nbsp;MRDC&nbsp;
                    <input type="checkbox" value="QCD-PBI" {{ $spe->AttentionTo == 'QCD-PBI' ? 'checked' : '' }}>&nbsp;PBI&nbsp;
                </div>
            </td>
            <td width="40%"><label class="mb-3">SPE NO. &nbsp;&nbsp; {{ $spe->SpeNumber }}</label></td>
        </tr>
    </table>
    <label><b style="font-size: 13px">Procurement Department</b></label>
    <table border="1" style="width: 100%;" cellspacing="0" cellpadding="3">
        <tr>
            <td width="50%">
                DATE REQUESTED<p style="margin-bottom: 0px; margin-left: 10em">{{ date("m-d-Y", strtotime(preg_replace('/[^A-Za-z0-9\-]/', '', $spe->DateRequested) ))}}</p>
            </td>
            <td width="50%">
                DEADLINE<p style="margin-bottom: 0px; margin-left: 10em">{{ date("m-d-Y", strtotime(preg_replace('/[^A-Za-z0-9\-]/', '', $spe->Deadline) ))}}</p>
            </td>
        </tr>
    </table>
    <table style="width: 100%;" cellspacing="0">
        <tr style="border-top: 1px solid;border-left: 1px solid;border-right: 1px solid;">
            <td width="30%">Attachment to SPE Form:</td>
            <td width="70%">
                <div class="d-inline">
                    <input type="checkbox" value="Sample" 
                        {{ collect($spe->attachments)->contains('Name', 'Sample') ? 'checked' : '' }}>&nbsp;Sample&nbsp;
                    <input type="checkbox" value="Specifications" 
                        {{ collect($spe->attachments)->contains('Name', 'Specifications') ? 'checked' : '' }}>&nbsp;Specifications&nbsp;
                    <input type="checkbox" value="COA" 
                        {{ collect($spe->attachments)->contains('Name', 'COA') ? 'checked' : '' }}>&nbsp;COA&nbsp;
                    <input type="checkbox" value="Recipe" 
                        {{ collect($spe->attachments)->contains('Name', 'Recipe') ? 'checked' : '' }}>&nbsp;Recipe&nbsp;
                </div>
            </td>
        </tr>
        <tr style="border-left: 1px solid;border-right: 1px solid;">
            <td width="30%">Product Name:</td>
            <td width="70%" style="text-decoration: underline;">{{ $spe->ProductName }}</td>
        </tr>
        <tr style="border-left: 1px solid;border-right: 1px solid;">
            <td width="30%">Manufacturer of Sample:</td>
            <td width="70%" style="text-decoration: underline;">{{ $spe->Manufacturer }}</td>
        </tr>
        <tr style="border-left: 1px solid;border-right: 1px solid;">
            <td width="30%">Quantity:</td>
            <td width="70%" style="text-decoration: underline;">{{ $spe->Quantity }}</td>
        </tr>
        <tr style="border-left: 1px solid;border-right: 1px solid;">
            <td width="30%">Supplier/ Trader Name:</td>
            <td width="70%" style="text-decoration: underline;">{{ $spe->suppliers->Name }}</td>
        </tr>
        <tr style="border-left: 1px solid;border-right: 1px solid;">
            <td width="30%">Origin:</td>
            <td width="70%" style="text-decoration: underline;">{{ $spe->Origin }}</td>
        </tr>
        <tr style="border-left: 1px solid;border-right: 1px solid;">
            <td width="30%">Product Application:</td>
            <td width="70%" style="text-decoration: underline;">{{ $spe->ProductApplication }}</td>
        </tr>
        <tr style="border-left: 1px solid;border-right: 1px solid;">
            <td width="30%">Price:</td>
            <td width="70%" style="text-decoration: underline;">{{ $spe->Price }}</td>
        </tr>
        <tr style="border-bottom: 1px solid;border-left: 1px solid;border-right: 1px solid;">
            <td width="30%">Lot No./ Batch No.:</td>
            <td width="70%" style="text-decoration: underline;">{{ $spe->LotNo }}</td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" >
        <tr>
            <td colspan="6" style="border-left: 1px solid;border-right: 1px solid">ADDITIONAL INFORMATION OF SUPPLIER'S PRODUCT</td>
        </tr>
        <tr>
            <td colspan="6" style="border-left: 1px solid;border-right: 1px solid">Instruction to Laboratory:</td>
        </tr>
        <tr>
        <td colspan="6" style="border-left: 1px solid;border-right: 1px solid;">
            <input type="checkbox" value="Physical and Chemical Testing" 
                {{ collect($spe->supplier_instruction)->contains('Instruction', 'Physical Chemical Testing') ? 'checked' : '' }}>&nbsp;Physical and Chemical Testing&nbsp;
            <input type="checkbox" value="Microbiological Testing" 
                {{ collect($spe->supplier_instruction)->contains('Instruction', 'Microbiological Testing') ? 'checked' : '' }}>&nbsp;Microbiological Testing&nbsp;
            <input type="checkbox" value="Mesh Analysis" 
                {{ collect($spe->supplier_instruction)->contains('Instruction', 'Mesh Analysis') ? 'checked' : '' }}>&nbsp;Mesh Analysis&nbsp;
        </td>
        </tr>
        <tr>
            <td style="border-left: 1px solid">Prepared By:</td>
            <td><u>{{ optional($spe->prepared_by)->full_name }}</u></td>
            <td></td>
            <td>Checked By:</td>
            <td><u>Jimmylyn E. Dagsil</u></td>
            <td style="border-right: 1px solid"></td>
        </tr>
        <tr>
            <td style="border-left: 1px solid"></td>
            <td>Requestor</td>
            <td></td>
            <td></td>
            <td>Asst. Procurement Manager</td>
            <td style="border-right: 1px solid"></td>
        </tr>
        <tr>
            <td style="border-left: 1px solid; border-bottom: 1px solid"></td>
            <td style="border-bottom: 1px solid"><small><i>Signature over Printed Name and Date</i></small></td>
            <td style="border-bottom: 1px solid"></td>
            <td style="border-bottom: 1px solid"></td>
            <td style="border-bottom: 1px solid"><small><i>Signature over Printed Name and Date</i></small></td>
            <td style="border-right: 1px solid; border-bottom: 1px solid"></td>
        </tr>
    </table>
    <label><b style="font-size: 13px">R&D/ QC Department</b></label>
    <table style="width: 100%;" cellspacing="0">
        <tr>
            <td colspan="5" style="border-left: 1px solid;border-right: 1px solid;border-top: 1px solid">Disposition:<br>
                <input type="checkbox" value="1" {{ collect($spe->supplier_disposition)->contains('Disposition', '1') ? 'checked' : '' }}>&nbsp;Almost an exact match with the current product. The Sample works with direct replacement in the application.&nbsp;<br>
                <input type="checkbox" value="2" {{ collect($spe->supplier_disposition)->contains('Disposition', '2') ? 'checked' : '' }}>&nbsp;Has higher quality than the existing raw materials. Needs dilution or lower proportion in product applications.&nbsp;<br>
                <input type="checkbox" value="3" {{ collect($spe->supplier_disposition)->contains('Disposition', '3') ? 'checked' : '' }}>&nbsp;Has lower quality than the existing product. Needs higher proportion in product applications.&nbsp;<br>
                <input type="checkbox" value="4" {{ collect($spe->supplier_disposition)->contains('Disposition', '4') ? 'checked' : '' }}>&nbsp;Cannot be fully evaluated. The company does not have a testing capability.&nbsp;<br>
                <input type="checkbox" value="5" {{ collect($spe->supplier_disposition)->contains('Disposition', '5') ? 'checked' : '' }}>&nbsp;Rejected. Does not pass the critical parameters of the test.&nbsp;
            </td>
        </tr>
        <tr>
            <td style="border-left: 1px solid">Prepared By:</td>
            <td>
                <u>
                    @if($spe->spePersonnel->isNotEmpty() && $spe->spePersonnel->first()->crrPersonnelById)
                        {{ $spe->spePersonnel->first()->crrPersonnelById->full_name }}
                    @endif
                </u>
            </td>
            <td>Checked By:</td>
            <td>
                <u>
                    @if($spe->spePersonnel->isNotEmpty() && $spe->spePersonnel->first()->spePersonnelById)
                        {{ $spe->spePersonnel->first()->spePersonnelById->full_name }}
                    @endif
                </u>
            </td>
            <td style="border-right: 1px solid"></td>
        </tr>
        <tr>
            <td style="border-left: 1px solid"></td>
            <td>R&D Specialist/ QC Analyst</td>
            <td></td>
            <td colspan="2" style="border-right: 1px solid">R&D Supervisor/ QC Supervisor</td>
        </tr>
        <tr>
            <td style="border-left: 1px solid; border-bottom: 1px solid"></td>
            <td style="border-bottom: 1px solid"><small><i>Signature over Printed Name and Date</i></small></td>
            <td style="border-bottom: 1px solid"></td>
            <td style="border-bottom: 1px solid"><small><i>Signature over Printed Name and Date</i></small></td>
            <td style="border-bottom: 1px solid;border-right: 1px solid"></td>
        </tr>
    </table>
    <table style="width: 100%;" cellspacing="0">
        <tr>
            <td style="border-left: 1px solid">ASSESSMENT:</td>
            <td style="border-left: 1px solid">REMARKS:</td>
            <td style="border-right: 1px solid"></td>
        </tr>
        <tr>
            <td style="border-left: 1px solid">
            <input type="checkbox">&nbsp;Accepted as New Supplier<br>
            <input type="checkbox">&nbsp;Accepted as New Material<br>
            <input type="checkbox">&nbsp;Request new sample for re-evaluation
            </td>
            <td style="border-left: 1px solid">Chemicals = QCD<br>Blending Materials = R&D</td>
            <td style="border-right: 1px solid"></td>
        </tr>
        <tr>
            <td style="border-left: 1px solid;border-bottom: 1px solid">Noted by:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;______________________<p style="margin-left: 5em;">R&D Specialist/ R&D Supervisor<br>Signature over Printed Name and Date</p>
            </td>
            <td style="border-left: 1px solid;border-bottom: 1px solid"></td>
            <td style="border-right: 1px solid;border-bottom: 1px solid"></td>
        </tr>
    </table>
</header>