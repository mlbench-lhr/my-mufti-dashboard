<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8" />
</head>

<body>
    <h2 style="font-family: Arial, sans-serif; color: #333;">Your OTP Code for My Mufti</h2>
    <p style="font-family: Arial, sans-serif; color: #555;">
        Dear User,
    </p>
    <p style="font-family: Arial, sans-serif; color: #555;">
        Use the following One-Time Password (OTP) to complete your process:
    </p>
    <h3 style="font-family: Arial, sans-serif; color: #007bff;">
        {{ $data['message'] }}
    </h3>
    <p style="font-family: Arial, sans-serif; color: #555;">
        This code is valid for the next 10 minutes. Please do not share it with anyone.
    </p>
    <p style="font-family: Arial, sans-serif; color: #555;">
        Thank you, <br>
        The My Mufti Team
    </p>
</body>

</html>
