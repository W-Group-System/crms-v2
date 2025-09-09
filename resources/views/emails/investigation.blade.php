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
        <b>Hi Team,</b>
        <p>I hope this message finds you well.</p>
        <p>We are writing to inform you that an investigation has been initiated regarding <b>{{ $CcNumber }}</b></p><br>
        <b>Purpose:</b>
        <p>The objective of this investigation is to thoroughly assess the issue and identify any contributing factors so that we can implement appropriate corrective and immediate actions.</p>
        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td><b>Details:</b></td>
            </tr>
            <tr>
                <td><strong>Immediate Action:</strong></td>
                <td>{{ $investigationComplaint->ImmediateAction }}</td>
            </tr>
            <tr>
                <td><strong>Objective Evidence:</strong></td>
                <td>{{ $investigationComplaint->ObjectiveEvidence }}</td>
            </tr>
            <tr>
                <td><strong>Investigation:</strong></td>
                <td>{{ $investigationComplaint->Investigation ?? '' }}</td>
            </tr>
            <!-- <tr>
                <td colspan="2"><strong>Investigation/ Root Cause Analysis:</strong></td>
            </tr>
            <tr>
                <td colspan="2">{{ $investigationComplaint->Investigation ?? '' }}</td>
            </tr> -->
            <tr>
                <td><strong>Corrective Action:</strong></td>
                <td>{{ $investigationComplaint->CorrectiveAction ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Objective Evidence:</strong></td>
                <td>{{ $investigationComplaint->ActionObjectiveEvidence ?? '' }}</td>
            </tr>
        </table>
        <p align="center">
            <a href="{{ url('customer_complaint/view/'.$investigationComplaint->id) }}" style="background: #007BFF; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                Click here to view details
            </a>
        </p>
        {{-- <p align="center">
            <a href="{{ $button_url }}" style="background: #007BFF; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                {{ $button_text }}
            </a>
        </p> --}} 
    </div>
</body>
</html>