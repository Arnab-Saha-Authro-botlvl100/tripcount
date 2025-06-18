<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        body {
            background: url('{{ url('/image/hero_bg.png') }}') no-repeat center/cover, rgba(255, 255, 255, 0.3);
            background-size: cover;
            position: relative;
            background-position: 0rem 0rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .login-hero {
            flex: 1;
            background: linear-gradient(135deg, #709ffe, #1f2b53);
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .login-hero p {
            opacity: 0.9;
            line-height: 1.6;
        }

        .login-form-container {
            flex: 1;
            padding: 60px;
        }

        .login-form {
            max-width: 360px;
            margin: 0 auto;
        }

        .form-title {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: #1e293b;
            font-weight: 700;
        }

        .form-subtitle {
            color: #64748b;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .register-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #334155;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .forgot-password {
            display: block;
            margin-top: 0.5rem;
            text-align: right;
            color: #64748b;
            font-size: 0.85rem;
            text-decoration: none;
        }

        .forgot-password:hover {
            color: #2563eb;
        }

        .form-footer {
            margin-top: 2rem;
        }

        .privacy-text {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .submit-button {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .submit-button:hover {
            background-color: #1d4ed8;
        }

        .submit-button::after {
            content: "→";
            margin-left: 8px;
            font-weight: bold;
        }

        .employer-login {
            margin-top: 2.5rem;
            text-align: center;

            width: 100%;
            padding: 12px;
            background-color: #8d8d8d;
            /* color: white; */
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.8s ease-in;
        }

        .employer-login-link {
            color: #000000;
            text-decoration: underline;
            font-size: 0.9rem;
            font-weight: 700;
            transition: all 0.8s ease-in;
        }

        .employer-login:hover {
            background-color: #000000;
            color: #f3f6ff;
        }

        .employer-login:hover .employer-login-link {
            text-decoration: none;
            color: #f3f6ff;

        }


        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-hero {
                padding: 40px;
                text-align: center;
            }

            .login-form-container {
                padding: 40px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        
        <div class="login-hero">
            <div class="text-center">
                <a href="{{ url('/') }}" class="inline-block">
                    <img src="{{ url('/image/tripcount.png') }}" alt="TripCount Logo"
                        class="h-12 md:h-16 w-auto object-contain" style="max-width: 150px;">
                </a>
            </div>
            <h1>Welcome Back</h1>
            <p>Sign in to access your personalized dashboard, manage your account, and connect with our community.</p>

        </div>

        <div class="login-form-container">

            <form method="POST" class="login-form" action="{{ route('login') }}">
                @csrf
                <h2 class="form-title">Log In</h2>
                <p class="form-subtitle">Or, <a href="{{ route('register') }}" class="register-link">Register Now</a>
                </p>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" placeholder="you@company.com" id="email" name="email"
                        class="form-input">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" placeholder="*********" id="password" name="password" class="form-input">
                    <a class="forgot-password" href="{{ route('password.request') }}">Forgot Password?</a>
                </div>

                <div class="form-footer">
                    <p class="privacy-text">You agree to our friendly privacy policy.</p>
                    <button type="submit" class="submit-button">Login</button>
                </div>

                <div class="employer-login">
                    <a href="{{ route('emp_login') }}" class="employer-login-link  submit-button-emp">Employer Log
                        In</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
