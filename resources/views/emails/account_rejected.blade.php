<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { background: #e74c3c; color: #fff; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Update</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            <p>Thank you for your interest in the <strong>Vendor Management System</strong>.</p>
            
            <p>After reviewing your registration, we regret to inform you that your account application has been declined at this time.</p>
            
            <p>If you believe this is a mistake or have any questions regarding this decision, please feel free to reach out to our support team.</p>

            <p>Best regards,<br>The VMS Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MIMS VMS. All rights reserved.
        </div>
    </div>
</body>
</html>
