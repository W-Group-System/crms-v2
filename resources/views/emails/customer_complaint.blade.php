<!-- <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Customer Complaint Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="max-width: 600px; margin: auto; padding: 20px; border-collapse: collapse;">
        <tr>
            <td align="center">
                <img src="{{ url('images/whi.png') }}" style="width: 100px; margin-top: 10px; margin-bottom: 10px;">
                <h2 style="color: #333;">Customer Complaint Form Submission</h2>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td><strong>CC Number:</strong></td>
                        <td>{{ $customerComplaint->CcNumber }}</td>
                    </tr>
                    <tr>
                        <td><strong>Company Name:</strong></td>
                        <td>{{ $customerComplaint->CompanyName }}</td>
                    </tr>
                    <tr>
                        <td><strong>Contact Name:</strong></td>
                        <td>{{ $customerComplaint->ContactName }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $customerComplaint->Email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Telephone:</strong></td>
                        <td>{{ $customerComplaint->Telephone }}</td>
                    </tr>
                    <tr>
                        <td><strong>Country:</strong></td>
                        <td>{{ $ComplaintCountry }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Customer Remarks:</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2">{{ $customerComplaint->CustomerRemarks }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html> -->


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
        @if($showButton)
            <p align="center"><img src="{{ url('images/whi.png') }}" style="width: 100px; margin-top: 10px; margin-bottom: 10px;"></p>
            <b>Dear Team,</b>
            <p>A new customer complaint form has been submitted. Please see the details below:</p>

            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td><strong>CC Number:</strong></td>
                    <td>{{ $customerComplaint->CcNumber }}</td>
                </tr>
                <tr>
                    <td><strong>Company Name:</strong></td>
                    <td>{{ $customerComplaint->CompanyName }}</td>
                </tr>
                <tr>
                    <td><strong>Contact Name:</strong></td>
                    <td>{{ $customerComplaint->ContactName }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $customerComplaint->Email }}</td>
                </tr>
                <tr>
                    <td><strong>Telephone:</strong></td>
                    <td>{{ $customerComplaint->Telephone }}</td>
                </tr>
                <tr>
                    <td><strong>Country:</strong></td>
                    <td>{{ $ComplaintCountry }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Customer Remarks:</strong></td>
                </tr>
                <tr>
                    <td colspan="2">{{ $customerComplaint->CustomerRemarks }}</td>
                </tr>
            </table>
            <p align="center">
                <a href="{{ $button_url }}" style="background: #007BFF; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                    {{ $button_text }}
                </a>
            </p>
        @else 
            <p align="center"><img src="{{ url('images/whi.png') }}" style="width: 100px; margin-top: 10px; margin-bottom: 10px;"></p>
            <b>Dear {{ $ContactName }},</b>
            <p>Thank you for bringing your concern. We truly value your feedback and sincerely apologize for any inconvenience caused.<br><br>Please rest assured that we are carefully reviewing the matter and will take the necessary steps to resolve it promptly. Your satisfaction is very important to us, and your input helps us improve our service.</p>

            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td><strong>CC Number:</strong></td>
                    <td>{{ $customerComplaint->CcNumber }}</td>
                </tr>
                <tr>
                    <td><strong>Company Name:</strong></td>
                    <td>{{ $customerComplaint->CompanyName }}</td>
                </tr>
                <tr>
                    <td><strong>Contact Name:</strong></td>
                    <td>{{ $customerComplaint->ContactName }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $customerComplaint->Email }}</td>
                </tr>
                <tr>
                    <td><strong>Telephone:</strong></td>
                    <td>{{ $customerComplaint->Telephone }}</td>
                </tr>
                <tr>
                    <td><strong>Country:</strong></td>
                    <td>{{ $ComplaintCountry }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Customer Remarks:</strong></td>
                </tr>
                <tr>
                    <td colspan="2">{{ $customerComplaint->CustomerRemarks }}</td>
                </tr>
            </table>
        @endif

        <p class="footer">This message was sent from Our Company. Please do not reply to this email.</p>
    </div>
</body>
</html>
