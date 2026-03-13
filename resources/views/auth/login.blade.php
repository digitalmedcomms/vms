<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | VMS</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #3498db;
            --bg: #f5f7fa;
            --text: #34495e;
            --white: #ffffff;
            --error: #e74c3c;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background: var(--bg);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .login-container {
            background: var(--white);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            margin-bottom: 30px;
        }

        .logo img {
            height: 50px;
        }

        h1 {
            color: var(--primary);
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        p.subtitle {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #dcdde1;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
            outline: none;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            margin-top: 10px;
        }

        .btn:hover {
            background: #1a252f;
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(0);
        }

        .error-message {
            color: var(--error);
            font-size: 12px;
            margin-top: 5px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        .remember-me input {
            width: auto;
            margin-right: 8px;
        }

        .footer-links {
            margin-top: 30px;
            font-size: 13px;
            color: #95a5a6;
        }

        .footer-links a {
            color: var(--accent);
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="/images/mimsLogo-mini.png" alt="MIMS Logo">
        </div>
        <h1>Welcome Back</h1>
        <p class="subtitle">Please enter your details to sign in.</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="margin-bottom: 0;">Remember me</label>
            </div>

            <button type="submit" class="btn">Sign In</button>
        </form>

        <div class="footer-links">
            &copy; {{ date('Y') }} MIMS VMS.
        </div>
    </div>
</body>
</html>
