{{-- <h2>Customer Satisfaction Form Submission</h2>
<p><strong>Cs Number:</strong> {{ $CsNumber }}</p>
<p><strong>Company Name:</strong> {{ $CompanyName }}</p>
<p><strong>Contact Name:</strong> {{ $ContactName }}</p>
<p><strong>Concerned:</strong> {{ $Concerned }}</p> 
<p><strong>Description:</strong> {{ $Description }}</p>
<p><strong>Category:</strong> {{ $CategoryName ?? 'N/A' }}</p> 
<p><strong>Contact Number:</strong> {{ $ContactNumber }}</p>
<p><strong>Email:</strong> {{ $Email }}</p> --}}


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
            <img src="{{ url('images/whi.png') }}" style="width: 100px; margin-top: 10px; margin-bottom: 10px;">
            <h2>Customer Satisfaction Regarding {{ $CategoryName }}</h2>
            <b>Hi Team,</b>
            <p>A new customer satisfaction feedback form has been submitted. Please see the details below:</p>

            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td><strong>CS Number:</strong></td>
                    <td>{{ $CsNumber }}</td>
                </tr>
                <tr>
                    <td><strong>Customer Name:</strong></td>
                    <td>{{ $ContactName }}</td>
                </tr>
                <tr>
                    <td><strong>Company Name:</strong></td>
                    <td>{{ $CompanyName }}</td>
                </tr>
                <tr>
                    <td><strong>Contact Number:</strong></td>
                    <td>{{ $ContactNumber }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $Email }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Customer Feedback:</strong></td>
                </tr>
                <tr>
                    <td colspan="2">{{ $Description }}</td>
                </tr>
            </table>
            <p align="center">
                <a href="{{ $button_url }}" style="background: #007BFF; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                    {{ $button_text }}
                </a>
            </p>
        @else 
            <img src="{{ url('images/whi.png') }}" style="width: 100px; margin-top: 10px; margin-bottom: 10px;" align="center">
            <h2 align="center">Customer Satisfaction Regarding {{ $CategoryName }}</h2>
            <b>Dear Valued Partner.</b>
            <p>Thank you for your feedback.<br><br>We are delighted to hear your positive comments.<br><br>Should you have further concerns, please contact marketing@rico.com.ph or you may https://crms-v2.wgroup.space/customer_service</p>

            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td><strong>CS Number:</strong></td>
                    <td>{{ $CsNumber }}</td>
                </tr>
                <tr>
                    <td><strong>Customer Name:</strong></td>
                    <td>{{ $ContactName }}</td>
                </tr>
                <tr>
                    <td><strong>Company Name:</strong></td>
                    <td>{{ $CompanyName }}</td>
                </tr>
                <tr>
                    <td><strong>Contact Number:</strong></td>
                    <td>{{ $ContactNumber }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $Email }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Customer Feedback:</strong></td>
                </tr>
                <tr>
                    <td colspan="2">{{ $Description }}</td>
                </tr>
            </table>
        @endif
        <p class="footer">This message was sent from Our Company. Please do not reply to this email.</p>
    </div>
</body>
</html>
