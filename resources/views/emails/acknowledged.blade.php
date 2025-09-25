<!DOCTYPE html>
<html>
<head>
    <title>New Customer Satisfaction Assignment</title>
</head>
<body>
    <div class="email-container">
        <p align="center"><img src="{{ url('images/whi.png') }}" style="width: 100px; margin-top: 10px; margin-bottom: 10px;"></p>
        <h2>Customer Satisfaction Regarding {{ $CategoryName }}</h2>
        <b>Hi Team,</b>
        <p>The Sales Manager has acknowledged the recent customer satisfaction feedback. Below are the details for your record and reference:</p>

        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;margin-bottom:20px">
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
                <td colspan="2" style="padding-bottom: 30px">{{ $Description }}</td>
            </tr>
            <tr>
                <td><strong>Acknowledged By:</strong></td>
                <td>{{ $ApprovedBy }}</td>
            </tr>
            <tr>
                <td><strong>Acknowledgement Date & Time:</strong></td>
                <td>{{ $ApprovedDate }}</td>
            </tr>
        </table>
        <p align="center">
            <a href="{{ url('customer_satisfaction/view/'.$customerSatisfaction->id) }}" style="background: #007BFF; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                Click here to view details
            </a>
        </p>
    </div>
</body>
</html>
