<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{asset('images/wgroup.png')}}" type="image/x-icon">
    <title>Customer Complaint</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<style>
body {
    font-size: 10px;
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
        <table cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td align="center"><img src="{{asset('images/whi.png')}}" width="120px"><br><b style="font-size: 16px;">CUSTOMER COMPLAINT FORM</b></td>
            </tr>
        </table>
    </header>
    <table class="mt-3" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td>Nature and Description of Complaint (Sales Responsibility)</td>
        </tr>
    </table>
    <table border="1" style="margin-top: 0px" cellspacing="0" cellpadding="4" width="100%">
        <tr>
            <td width="18%" style="vertical-align: middle"><b>Company Name:</b></td>
            <td width="40%" style="vertical-align: middle">{{ $cc->CompanyName }}</td>
            <td width="37%" colspan="2"><b>CCF No.:</b>&nbsp;{{ $cc->CcNumber }}<br><b>Date complaint was received:</b>&nbsp;{{ $cc->DateReceived }}</td>
        </tr>
        <tr>
            <td width="18%"><b>Contact Person:</b></td>
            <td width="72%" colspan="3">{{ $cc->ContactName }}</td>
        </tr>
        <tr>
            <td width="18%"><b>Address:</b></td>
            <td width="72%" colspan="3">{{ $cc->Address }}</td>
        </tr>
        <tr>
            <td width="18%"><b>Country:</b></td>
            <td width="25%">{{ $cc->country->Name }}</td>
            <td width="15%"><b>Telephone:</b></td>
            <td width="42%">{{ $cc->Telephone }}</td>
        </tr>
        <tr>
            <td width="30%"><b>Mode of Communication:</b></td>
            <td width="72%" colspan="3">
                <div class="d-inline">
                    <input type="checkbox">&nbsp;&nbsp;By Phone&nbsp;&nbsp;
                    <input type="checkbox">&nbsp;&nbsp;By Letter/ Fax&nbsp;&nbsp;
                    <input type="checkbox">&nbsp;&nbsp;Personal&nbsp;&nbsp;
                    <input type="checkbox">&nbsp;&nbsp;By Email&nbsp;&nbsp;
                </div>
            </td>
        </tr>
    </table>
    <table border="1" class="mt-3" cellspacing="0" cellpadding="4" width="100%">
        <tr>
            <td rowspan="3" colspan="2" align="center" style="vertical-align: middle"><b>Customer Complaint Category</b></td>
            <td colspan="3" align="center"><b>Definition of Quality Class (see below)</b></td>
            <td colspan="2" align="center"><b>Recurring Issue:</b></td>
        </tr>
        <tr>
            <td align="center"><b>C</b></td>
            <td align="center"><b>B</b></td>
            <td align="center"><b>A</b></td>
            <td align="center"><b>Yes&nbsp;&nbsp;<input type="checkbox" @if($cc->RecurringIssue == 1) checked @endif></b></td>
            <td align="center"><b>No&nbsp;&nbsp;<input type="checkbox" @if($cc->RecurringIssue == 2) checked @endif></b></td>
        </tr>
        <tr>
            <td align="center"><b>Critical</b></td>
            <td align="center"><b>Major/ Important</b></td>
            <td align="center"><b>Minor/ Marginal</b></td>
            <td align="center" colspan="2"><b>Previous CCF No. (If Yes):</b></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><b>COMPLAINT CATEGORY</b></td>
            <td colspan="5" align="center"><b>PRODUCT DETAILS</b></td>
        </tr>
        <tr>
            <td width="37%"><b>1. Product Quality</b></td>
            <td width="5%">Please<br>Check</td>
            <td align="center" width="18%">Product Name</td>
            <td align="center" width="10%">S/C No.</td>
            <td align="center" width="10%">SO No.</td>
            <td align="center" width="10%">Quantity</td>
            <td align="center" width="10%">Lot No.</td>
        </tr>
        <tr>
            <td class="break-spaces">1.1 Physical Hazard (contamination of product by unspecified compound e.g. hard plastics, metal flakes, rust, etc.)</td>
            <td align="center">
                <input id="check-p1" type="checkbox" @if(optional($cc->product_quality)->Pn1) checked @endif>
            </td>
            <td>{{ optional($cc->product_quality)->Pn1 }}</td>
            <td>{{ optional($cc->product_quality)->ScNo1 }}</td>
            <td>{{ optional($cc->product_quality)->SoNo1 }}</td>
            <td>{{ optional($cc->product_quality)->Quantity1 }}</td>
            <td>{{ optional($cc->product_quality)->LotNo1 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">1.2 Biological Hazard (e.g. high bacteria count, etc.)</td>
            <td align="center">
                <input id="check-p2" type="checkbox" @if(optional($cc->product_quality)->Pn2) checked @endif>
            </td>
            <td>{{ optional($cc->product_quality)->Pn2 }}</td>
            <td>{{ optional($cc->product_quality)->ScNo2 }}</td>
            <td>{{ optional($cc->product_quality)->SoNo2 }}</td>
            <td>{{ optional($cc->product_quality)->Quantity2 }}</td>
            <td>{{ optional($cc->product_quality)->LotNo2 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">1.3 Chemical Hazard (e.g. high heavy metals content, etc.)</td>
            <td align="center">
                <input id="check-p3" type="checkbox" @if(optional($cc->product_quality)->Pn3) checked @endif>
            </td>
            <td>{{ optional($cc->product_quality)->Pn3 }}</td>
            <td>{{ optional($cc->product_quality)->ScNo3 }}</td>
            <td>{{ optional($cc->product_quality)->SoNo3 }}</td>
            <td>{{ optional($cc->product_quality)->Quantity3 }}</td>
            <td>{{ optional($cc->product_quality)->LotNo3 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">1.4 Visual Defects (e.g. color change, particle size)</td>
            <td align="center">
                <input id="check-p4" type="checkbox" @if(optional($cc->product_quality)->Pn4) checked @endif>
            </td>
            <td>{{ optional($cc->product_quality)->Pn4 }}</td>
            <td>{{ optional($cc->product_quality)->ScNo4 }}</td>
            <td>{{ optional($cc->product_quality)->SoNo4 }}</td>
            <td>{{ optional($cc->product_quality)->Quantity4 }}</td>
            <td>{{ optional($cc->product_quality)->LotNo4 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">1.5 Application Problems (e.g. poor dispersion, poor distribution, poor binding property, high syneresis, etc.)</td>
            <td align="center">
                <input id="check-p5" type="checkbox" @if(optional($cc->product_quality)->Pn5) checked @endif>
            </td>
            <td>{{ optional($cc->product_quality)->Pn5 }}</td>
            <td>{{ optional($cc->product_quality)->ScNo5 }}</td>
            <td>{{ optional($cc->product_quality)->SoNo5 }}</td>
            <td>{{ optional($cc->product_quality)->Quantity5 }}</td>
            <td>{{ optional($cc->product_quality)->LotNo5 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">1.6 Physical/ Chemical Properties Out-of Specification (e.g. pH, gel strength, viscosity, syneresis and contamination with other ingredients)</td>
            <td align="center">
                <input id="check-p6" type="checkbox" @if(optional($cc->product_quality)->Pn6) checked @endif>
            </td>
            <td>{{ optional($cc->product_quality)->Pn6 }}</td>
            <td>{{ optional($cc->product_quality)->ScNo6 }}</td>
            <td>{{ optional($cc->product_quality)->SoNo6 }}</td>
            <td>{{ optional($cc->product_quality)->Quantity6 }}</td>
            <td>{{ optional($cc->product_quality)->LotNo6 }}</td>
        </tr>
        <tr>
            <td colspan="7"><b>2. Packaging</b></td>
        </tr>
        <tr>
            <td class="break-spaces">2.1 Quantity (e.g. Short-packing, under-filled bags or box, over-filled container or box, etc.)</td>
            <td align="center">
                <input id="check-pack1" type="checkbox" @if(optional($cc->packaging)->PackPn1) checked @endif>
            </td>
            <td>{{ optional($cc->packaging)->PackPn1 }}</td>
            <td>{{ optional($cc->packaging)->PackScNo1 }}</td>
            <td>{{ optional($cc->packaging)->PackSoNo1 }}</td>
            <td>{{ optional($cc->packaging)->PackQuantity1 }}</td>
            <td>{{ optional($cc->packaging)->PackLotNo1 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">2.2 Packing (e.g. leakages, corrosion, etc.)</td>
            <td align="center">
                <input id="check-pack2" type="checkbox" @if(optional($cc->packaging)->PackPn2) checked @endif>
            </td>
            <td>{{ optional($cc->packaging)->PackPn2 }}</td>
            <td>{{ optional($cc->packaging)->PackScNo2 }}</td>
            <td>{{ optional($cc->packaging)->PackSoNo2 }}</td>
            <td>{{ optional($cc->packaging)->PackQuantity2 }}</td>
            <td>{{ optional($cc->packaging)->PackLotNo2 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">2.3 Labeling (e.g. wrong or defective label, unreadable, incorrect or incomplete text, etc.)</td>
            <td align="center">
                <input id="check-pack3" type="checkbox" @if(optional($cc->packaging)->PackPn3) checked @endif>
            </td>
            <td>{{ optional($cc->packaging)->PackPn3 }}</td>
            <td>{{ optional($cc->packaging)->PackScNo3 }}</td>
            <td>{{ optional($cc->packaging)->PackSoNo3 }}</td>
            <td>{{ optional($cc->packaging)->PackQuantity3 }}</td>
            <td>{{ optional($cc->packaging)->PackLotNo3 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">2.4 Packaging material (e.g. wrong packaging (bag, pallet, etc.) material, incorrect application of packaging instructions, inadequate quality of packaging material, etc.)</td>
            <td align="center">
                <input id="check-pack4" type="checkbox" @if(optional($cc->packaging)->PackPn4) checked @endif>
            </td>
            <td>{{ optional($cc->packaging)->PackPn4 }}</td>
            <td>{{ optional($cc->packaging)->PackScNo4 }}</td>
            <td>{{ optional($cc->packaging)->PackSoNo4 }}</td>
            <td>{{ optional($cc->packaging)->PackQuantity4 }}</td>
            <td>{{ optional($cc->packaging)->PackLotNo4 }}</td>
        </tr>
        <tr>
            <td colspan="7"><b>3. Delivery and Handling</b></td>
        </tr>
        <tr>
            <td class="break-spaces">3.1 Product Handling (e.g. wrong product, pack size or quantity)</td>
            <td align="center">
                <input id="check-d1" type="checkbox" @if(optional($cc->delivery_handling)->DhPn1) checked @endif>
            </td>
            <td>{{ optional($cc->delivery_handling)->DhPn1 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhScNo1 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhSoNo1 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhQuantity1 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhLotNo1 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">3.2 Delayed Delivery (e.g. inadequate forwarder service, wrong delivery address, etc.)</td>
            <td align="center">
                <input id="check-d2" type="checkbox" @if(optional($cc->delivery_handling)->DhPn2) checked @endif>
            </td>
            <td>{{ optional($cc->delivery_handling)->DhPn2 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhScNo2 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhSoNo2 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhQuantity2 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhLotNo2 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">3.3 Product Damage during transit (e.g. leakages, corrosion, damaged label/box/carton/seal, etc.)</td>
            <td align="center">
                <input id="check-d3" type="checkbox" @if(optional($cc->delivery_handling)->DhPn3) checked @endif>
            </td>
            <td>{{ optional($cc->delivery_handling)->DhPn3 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhScNo3 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhSoNo3 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhQuantity3 }}</td>
            <td>{{ optional($cc->delivery_handling)->DhLotNo3 }}</td>
        </tr>
        <tr>
            <td colspan="7"><b>4. Others</b></td>
        </tr>
        <tr>
            <td class="break-spaces">4.1 Quality of records or documents (e.g. insufficient, inadequate, missing, etc.)</td>
            <td align="center">
                <input id="check-o1" type="checkbox" @if(optional($cc->others)->OthersPn1) checked @endif>
            </td>
            <td>{{ optional($cc->others)->OthersPn1 }}</td>
            <td>{{ optional($cc->others)->OthersScNo1 }}</td>
            <td>{{ optional($cc->others)->OthersSoNo1 }}</td>
            <td>{{ optional($cc->others)->OthersQuantity1 }}</td>
            <td>{{ optional($cc->others)->OthersLotNo1 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">4.2 Poor customer Service (e.g., courtesy, professionalism, handling, responsiveness)</td>
            <td align="center">
                <input id="check-o2" type="checkbox" @if(optional($cc->others)->OthersPn2) checked @endif>
            </td>
            <td>{{ optional($cc->others)->OthersPn2 }}</td>
            <td>{{ optional($cc->others)->OthersScNo2 }}</td>
            <td>{{ optional($cc->others)->OthersSoNo2 }}</td>
            <td>{{ optional($cc->others)->OthersQuantity2 }}</td>
            <td>{{ optional($cc->others)->OthersLotNo2 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">4.3 Payment/ Invoice (e.g. wrong price/ product details)</td>
            <td align="center">
                <input id="check-o3" type="checkbox" @if(optional($cc->others)->OthersPn3) checked @endif>
            </td>
            <td>{{ optional($cc->others)->OthersPn3 }}</td>
            <td>{{ optional($cc->others)->OthersScNo3 }}</td>
            <td>{{ optional($cc->others)->OthersSoNo3 }}</td>
            <td>{{ optional($cc->others)->OthersQuantity3 }}</td>
            <td>{{ optional($cc->others)->OthersLotNo3 }}</td>
        </tr>
        <tr>
            <td class="break-spaces">4.4 Other Issues (please specify)</td>
            <td align="center">
                <input id="check-o4" type="checkbox" @if(optional($cc->others)->OthersPn4) checked @endif>
            </td>
            <td>{{ optional($cc->others)->OthersPn4 }}</td>
            <td>{{ optional($cc->others)->OthersScNo4 }}</td>
            <td>{{ optional($cc->others)->OthersSoNo4 }}</td>
            <td>{{ optional($cc->others)->OthersQuantity4 }}</td>
            <td>{{ optional($cc->others)->OthersLotNo4 }}</td>
        </tr>
    </table>
    <label class="mt-2"><b>Quantification of Cost/s:</b></label>
    <table border="1" style="margin-top: 0px" cellspacing="0" cellpadding="4" width="100%">
        <tr>
            <td align="center" colspan="2"><b>DESCRIPTION</b></td>
            <td align="center" colspan="2"><b>PHP/ IN USD/ IN EUR</b></td>
        </tr>
        <tr>
            <td align="center" colspan="2">{{ $cc->Description }}</td>
            <td align="center" colspan="2">{{ $cc->Currency }}</td>
        </tr>
        <tr>
            <td align="center" colspan="2">TOTAL</td>
            <td align="center" colspan="2">{{ $cc->Currency }}</td>
        </tr>
        <tr>
            <td colspan="4">
                <b>Customer Remarks</b><br>
                {{ $cc->CustomerRemarks }}<br>{{ optional($cc->ccsales->first())->SalesRemarks }}<br>
                @if($cc->ccsales->count())
                    <ul>
                        @foreach($cc->ccsales as $remark)
                            @if($remark->Path)
                                <li>
                                    {{ $remark->Path }}
                                    {{-- if it's an image --}}
                                    <br>
                                    <img src="{{ public_path('storage/' . $remark->Path) }}" width="150">
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <p>No attachments found.</p>
                @endif
            </td>
        </tr>
       <tr>
            <td><b>Site Concerned:</b></td>
            <td>
                @if($cc->SiteConcerned == 1) 
                    WHI Head Office
                @elseif ($cc->SiteConcerned == 2)
                    WHI Carmona
                @elseif ($cc->SiteConcerned == 3)
                    MRDC
                @elseif ($cc->SiteConcerned == 4)
                    CCC Carmen
                @elseif ($cc->SiteConcerned == 5)
                    PBI Canlubang
                @else 
                    International Warehouse
                @endif
            </td>
            <td><b>Department:</b></td>
            <td>{{ $cc->Department }}</td>
       </tr> 
       <tr>
            <td colspan="2">For NCAR Issuance:</td>
            <td>Yes (NCAR No.: {{ $cc->IssuanceNo }})</td>
            <td>No</td>
       </tr>
    </table>
    <table border="1" class="mt-2" cellspacing="0" cellpadding="4" width="100%">
        <tr>
            <td width="46%">Customer Complaint Received By/ Date:</td>
            <td width="54%">{{ $cc->users->full_name }}/&nbsp;{{ $cc->DateReceived }}</td>
        </tr>
        <tr>
            <td width="46%">Noted By/ Date:</td>
            <td width="54%">{{ $cc->noted_by->full_name }}/&nbsp;{{ $cc->DateNoted }}</td>
        </tr>
    </table>
    <label class="mt-2"><b>II.	Investigation</b></label>
    <table border="1" class="mt-2" cellspacing="0" cellpadding="4" width="100%">
        <tr>
            <td colspan="3"><b>Immediate Action/Correction:</b></td>
        </tr>
        <tr>
            <td width="50%" align="center">Immediate Action</td>
            <td width="25%" align="center">Objective Evidence</td>
            <td width="25%" align="center">Action Date/ Action Responsible</td>
        </tr>
        <tr>
            <td>{{ $cc->ImmediateAction }}</td>
            <td>{{ $cc->ObjectiveEvidence }}</td>
            <td>{{ $cc->ActionDate }}/ {{ $cc->action_responsible->full_name }}</td>
        </tr>
        <tr>
            <td colspan="3"><b>Investigation of the Problem:</b>Root Cause Analysis/Investigation:  details on who/what contributed to the problem, how and why the non-conformity happened</td>
        </tr>
        <tr>
            <td width="75%" align="center" colspan="2">Investigation / Root Cause Analysis:</td>
            <td width="25%" align="center">Date of Investigation:</td>
        </tr>
        <tr>
            <td colspan="2">{{ $cc->Investigation }}</td>
            <td>{{ $cc->ActionDate }}</td>
        </tr>
        <tr>
            <td colspan="3"><b>Corrective Action Plan:</b>Action that will eliminate the cause of the detected nonconformity or undesirable situation</td>
        </tr>
        <tr>
            <td width="50%" align="center">Corrective Action</td>
            <td width="25%" align="center">Objective Evidence</td>
            <td width="25%" align="center">Action Date/ Action Responsible</td>
        </tr>
        <tr>
            <td>{{ $cc->CorrectiveAction }}</td>
            <td>{{ $cc->ActionObjectiveEvidence }}</td>
            <td>{{ $cc->ActionDate }}/ {{ $cc->action_responsible->full_name }}</td>
        </tr>
        <!-- <tr>
            <td width="50%" align="center">
                <div style="text-align: left;margin-bottom: 20px">
                    <label>Prepared By:</label>
                </div>
                <label></label>
                <label>Signature over Printed Name/ Date</label>
            </td>
            <td width="50%" colspan="2" align="center">
                <div style="text-align: left;margin-bottom: 20px">
                    <label>Approved By:</label>
                </div>
                <label></label>
                <label>Signature over Printed Name/ Date</label>
            </td>
        </tr> -->
    </table>
    <label class="mt-2"><b>III.	Verification/ Recommendation</b></label>
    <table border="1" class="mt-2" cellspacing="0" cellpadding="4" width="100%">
        <tr>
            <td colspan="2"><b>Client Feedback/ Acceptance:</b></td>
        </tr>
        <tr>
            <td colspan="2">{{ $cc->Acceptance }}</td>
        </tr>
        <tr>
            <td width="50%"><b>Received by/ Date:</b>{{ $cc->users->full_name }}/&nbsp;{{ $cc->DateReceived }}</td>
            <td width="50%"><b>Customer Complaint Closed Date:</b>&nbsp;{{ $cc->ClosedDate }}</td>
        </tr>
        <tr>
            <td width="50%">
                <label style="margin-bottom: 10px;"><b>With Claims/Credit Note?</b></label><br>
                <div style="text-align: left;margin-bottom: 10px">
                    <label style="margin-right: 10em;">{{ $cc->Claims == 1 ? 'Yes' : 'No' }}</label>
                </div>
                Credit Note Number:&nbsp;{{ $cc->CnNumber }}<br>
                Total Amount Incurred&nbsp;{{ $cc->AmountIncurred }}
            </td>
            <td width="50%">
                <label style="margin-bottom: 10px;"><b>For shipment Return?</b></label><br>
                <div style="text-align: left;margin-bottom: 10px">
                    <label style="margin-right: 10em;">{{ $cc->Shipment == 1 ? 'Yes' : 'No' }}</label>
                </div>
                Return Shipment Date:&nbsp;{{ $cc->ShipmentDate }}<br>
                Return Shipment Cost:&nbsp; {{ $cc->ShipmentCost }} 
            </td>
        </tr>
    </table>
    <p>Electronically generated and valid without signature.</p>
</body>
</html>