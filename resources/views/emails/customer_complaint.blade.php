<!DOCTYPE html>
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
</html>
