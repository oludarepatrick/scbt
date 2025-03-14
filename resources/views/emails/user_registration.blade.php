<!DOCTYPE html>
<html>
<head>
    <title>Your Account Login Details</title>
</head>
<body>
    <h2>Welcome to Our Platform, {{ $name }}!</h2>
    
    <p>Your account has been successfully created. Below are your login details:</p>

    <p><strong>Username:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>

    <p>Please keep it safe.</p>

    <p>Best regards,<br> Schooldrive CBT Team</p>
</body>
</html>
