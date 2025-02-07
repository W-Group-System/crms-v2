<!DOCTYPE html>
<html>
<head>
    <title>New Customer Satisfaction Assignment</title>
</head>
<body>
    <p>Dear {{ $ConcernedName }},</p>
    
    <p>A new customer satisfaction issue has been assigned to your department.</p>

    <p><strong>Details:</strong></p>
    <ul>
        <li><strong>Reference ID:</strong> {{ $customerSatisfaction->CsNumber }}</li>
        <li><strong>Description:</strong> {{ $customerSatisfaction->Description ?? 'No description provided' }}</li>
    </ul>

    <!-- @if(!empty($attachments))
        <p><strong>Attachments:</strong></p>
        <ul>
            @foreach($attachments as $attachment)
                <li><a href="{{ $attachment }}" target="_blank">View Attachment</a></li>
            @endforeach
        </ul>
    @endif -->

    <p>Please take necessary action as soon as possible.</p>

    <p>Best regards,</p>
    <p>Your Company</p>
</body>
</html>
