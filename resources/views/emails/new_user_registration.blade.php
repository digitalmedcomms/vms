<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { background: #2c3e50; color: #fff; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
        .button { display: inline-block; padding: 10px 20px; background: #3498db; color: #fff; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New User Registration</h1>
        </div>
        <div class="content">
            <p>Hello Admin,</p>
            <p>A new user has registered on the Vendor Management System and is awaiting your approval.</p>
            
            <hr>
            <p><strong>User Details:</strong></p>
            <ul>
                <li><strong>Name:</strong> {{ $user->name }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Designation:</strong> {{ $user->designation }}</li>
                <li><strong>Mobile:</strong> {{ $user->mobile }}</li>
            </ul>
            <hr>

            <p>To approve or reject this registration, please log in to the admin panel:</p>
            <p style="text-align: center;">
                <a href="{{ backpack_url('user') }}" class="button">Go to User Management</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MIMS VMS. All rights reserved.
        </div>
    </div>
</body>
</html>
