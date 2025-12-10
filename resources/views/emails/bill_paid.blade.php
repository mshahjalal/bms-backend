<!DOCTYPE html>
<html>
<head>
    <title>Bill Paid</title>
</head>
<body>
    <h1>Hello, {{ $bill->flat->renter->name }}</h1>
    <p>This is a confirmation that your bill for flat {{ $bill->flat->number }} has been marked as <strong>PAID</strong>.</p>
    <p><strong>Amount:</strong> {{ $bill->amount }}</p>
    <p>Thank you.</p>
</body>
</html>
