<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { background: #27ae60; color: #fff; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
        .button { display: inline-block; padding: 10px 20px; background: #2c3e50; color: #fff; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Account Approved!</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            <p>We are pleased to inform you that your account on the <strong>Vendor Management System</strong> has been approved by the administrator.</p>
            
            <p>You can now log in to your account and start using the system:</p>
            
            <p style="text-align: center;">
                <a href="{{ route('login') }}" class="button">Log In Now</a>
            </p>

            <p>If you have any questions, please contact the support team.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MIMS VMS. All rights reserved.
        </div>
    </div>
</body>
</html>
