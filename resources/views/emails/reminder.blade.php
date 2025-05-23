<!DOCTYPE html>
<html>
<head>
    <title>Medication Reminder</title>
</head>
<body>
    <h1>Hello!</h1>
    <p>This is a reminder for your medication:</p>
    <p><strong>Medication Name:</strong> {{ $medicationName }}</p>
    <p><strong>Dosage:</strong> {{ $dosage }}</p>
    <p><strong>Time:</strong> {{ $reminderTime->format('Y-m-d H:i:s') }}</p>
    @if(isset($message) && $message)
        <p><strong>Note:</strong> {{ $message }}</p>
    @endif
    <p>Please take your medication as scheduled.</p>
    <p>Thank you,</p>
    <p>Your Drug Reminder App</p>
</body>
</html>