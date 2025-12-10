<!DOCTYPE html>
<html>
<head>
    <title>New Bill Created</title>
</head>
<body>
    <h1>Hello, {{ $bill->flat->renter->name }}</h1>
    <p>A new bill has been created for your flat {{ $bill->flat->number }}.</p>
    <p><strong>Amount:</strong> {{ $bill->amount }}</p>
    <p><strong>Due Date:</strong> {{ $bill->due_date->format('Y-m-d') }}</p>
    <p>Please pay before the due date.</p>
    <p>Thank you.</p>
</body>
</html>
