<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'Email from Our Company' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333333;
        }
        p {
            color: #555555;
            line-height: 1.5;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #999999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <p align="center"><img src="{{ url('images/whi.png') }}" style="width: 100px; margin-top: 10px; margin-bottom: 10px;"></p>
        <b>Hi {{ $ConcernedName }},</b>
        <p>I hope this message finds you well.</p>
        <p>This is to formally forward a verified customer complaint for your immediate attention regarding <b>{{ $verifiedComplaint->CcNumber }}</b></p><br>
        
        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td><b>Details:</b></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Client Feedback/ Acceptance:</strong></td>
            </tr>
            <tr>
                <td colspan="2">{{ $verifiedComplaint->Acceptance ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>With Claims/Credit Note:</strong></td>
                <td>
                    @if($Claims == 1)
                        Yes
                    @else
                        No 
                    @endif
                </td>
                <td><strong>For Shipment Return:</strong></td>
                <td>
                    @if($Shipment == 1)
                        Yes
                    @else
                        No 
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Credit Note Number:</strong></td>
                <td>{{ $verifiedComplaint->CnNumber ?? '' }}</td>
                <td><strong>Return Shipment Date:</strong></td>
                <td>{{ $verifiedComplaint->ShipmentDate ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Total Amount Incurred:</strong></td>
                <td>{{ $verifiedComplaint->AmountIncurred ?? '' }}</td>
                <td><strong>Return Shipment Cost:</strong></td>
                <td>{{ $verifiedComplaint->ShipmentCost ?? '' }}</td>
            </tr>
        </table>
        {{-- <p align="center">
            <a href="{{ url('customer_complaint/view/'.$verifiedComplaint->id) }}" style="background: #007BFF; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                Click here to view details
            </a>
        </p> --}}
        {{-- <p align="center">
            <a href="{{ $button_url }}" style="background: #007BFF; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                {{ $button_text }}
            </a>
        </p> --}} 
    </div>
</body>
</html>