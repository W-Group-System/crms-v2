<!DOCTYPE html>
<html>
<head>
    <title>New Customer Satisfaction Assignment</title>
</head>
<body>
    <div class="email-container">
        <img src="{{ url('images/whi.png') }}" style="width: 100px; margin-top: 10px; margin-bottom: 10px;">
        <h2>Customer Complaint Regarding {{ $CcNumber }}</h2>
        <b>Hi IAD Team,</b>
        <p>The Sales Manager has closed the recent customer satisfaction feedback. Below are the details for your record and reference.:</p>

        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;margin-bottom:20px">
            <tr>
                <td width="25%"><strong>Date Complaint:</strong></td>
                <td width="25%">{{ $DateComplaint }}</td>
                <td width="25%"><strong>Quality Class:</strong></td>
                <td width="25%">{{ $QualityClass }}</td>
            </tr>
            <tr>
                <td width="25%"><strong>Company Name:</strong></td>
                <td width="25%">{{ $CompanyName }}</td>
                <td width="25%"><strong>Contact Name:</strong></td>
                <td width="25%">{{ $ContactName }}</td>
            </tr>
            <tr>
                <td width="25%"><strong>Date Received:</strong></td>
                <td width="25%">{{ $DateReceived }}</td>
                <td width="25%"><strong>Received By:</strong></td>
                <td width="25%">{{ $ReceivedBy }}</td>
            </tr>
            <tr>
                <td width="25%"><strong>Date Noted:</strong></td>
                <td width="25%">{{ $DateNoted }}</td>
                <td width="25%"><strong>Noted By:</strong></td>
                <td width="25%">{{ $NotedBy }}</td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td width="25%"><strong>Approved By:</strong></td>
                <td width="25%">{{ $ApprovedBy }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Customer Remarks:</strong></td>
            </tr>
            <tr>
                <td colspan="2" style="padding-bottom: 10px">{{ $CustomerRemarks }}</td>
            </tr>
        </table>
        <hr>
        <b>Investigation</b>
        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;margin-bottom:20px">
            <tr>
                <td width="25%"><strong>Immediate Action:</strong></td>
                <td width="25%">{{ $ImmediateAction }}</td>
                <td width="25%"><strong>Objective Evidence:</strong></td>
                <td width="25%">{{ $ObjectiveEvidence }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Investigation of the Problem:</strong></td>
            </tr>
            <tr>
                <td colspan="2" style="padding-bottom: 20px">{{ $Investigation }}</td>
            </tr>
            <tr>
                <td width="25%"><strong>Corrective Action:</strong></td>
                <td width="25%">{{ $CorrectiveAction }}</td>
                <td width="25%"><strong>Objective Evidence:</strong></td>
                <td width="25%">{{ $ActionObjectiveEvidence }}</td>
            </tr>
            <tr>
                <td width="25%"><strong>Action Responsible:</strong></td>
                <td width="25%">{{ $ActionResponsible }}</td>
            </tr>
        </table>
        <hr>
        <b>Verification/ Recommendation</b>
        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;margin-bottom:20px">
            <tr>
                <td colspan="2"><strong>Client Feedback/ Acceptance:</strong></td>
            </tr>
            <tr>
                <td colspan="2" style="padding-bottom: 20px">{{ $Acceptance }}</td>
            </tr>
            <tr>
                <td width="25%"><strong>With Claims/Credit Note?:</strong></td>
                <td width="25%">
                    @if($Claims == 1)
                        Yes
                    @else
                        No 
                    @endif
                </td>
                <td width="25%"><strong>For shipment Return?:</strong></td>
                <td width="25%">
                    @if($Shipment == 1)
                        Yes
                    @else
                        No 
                    @endif
                </td>
            </tr>
            <tr>
                <td width="25%"><strong>Credit Note Number:</strong></td>
                <td width="25%">{{ $CnNumber }}</td>
                <td width="25%"><strong>Return Shipment Date:</strong></td>
                <td width="25%">{{ $ShipmentDate }}</td>
            </tr>
            <tr>
                <td width="25%" style="padding-bottom: 15px;"><strong>Total Amount Incurred:</strong></td>
                <td width="25%" style="padding-bottom: 15px;">{{ $AmountIncurred }}</td>
                <td width="25%" style="padding-bottom: 15px;"><strong>Return Shipment Cost:</strong></td>
                <td width="25%" style="padding-bottom: 15px;">{{ $ShipmentCost }}</td>
            </tr>
            <tr>
                <td width="25%"><strong>Closed By:</strong></td>
                <td width="25%">{{ $ClosedBy }}</td>
                <td width="25%"><strong>Closed Date:</strong></td>
                <td width="25%">{{ $ClosedDate }}</td>
            </tr>
        </table>
        <p align="center">
            <a href="{{ url('customer_complaint/view/'.$customerComplaint->id) }}" style="background: #007BFF; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                Click here to view details
            </a>
        </p>
    </div>
</body>
</html>
