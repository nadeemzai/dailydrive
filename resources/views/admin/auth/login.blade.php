<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — DAILYdRIVE</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #0d1117;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-wrap {
            width: 100%;
            max-width: 400px;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo-text {
            font-size: 1.8rem;
            font-weight: 900;
            letter-spacing: -0.04em;
        }
        .logo-d { color: #fff; }
        .logo-drive { color: #6366f1; }
        .login-logo-sub {
            font-size: 0.78rem;
            color: #4a5568;
            margin-top: 6px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 600;
        }
        .login-card {
            background: #161f32;
            border: 1px solid #1e2d45;
            border-radius: 16px;
            padding: 36px;
        }
        .login-title {
            font-size: 1.18rem;
            font-weight: 700;
            color: #e2e8f0;
            margin-bottom: 6px;
        }
        .login-sub {
            font-size: 0.82rem;
            color: #4a5568;
            margin-bottom: 28px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #64748b;
            margin-bottom: 7px;
        }
        .form-control {
            width: 100%;
            padding: 11px 14px;
            background: #0d1420;
            border: 1.5px solid #1e2d45;
            border-radius: 10px;
            color: #e2e8f0;
            font-size: 0.9rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s;
        }
        .form-control::placeholder { color: #2d3748; }
        .form-control:focus { border-color: #6366f1; }
        .form-error {
            font-size: 0.76rem;
            color: #f87171;
            margin-top: 5px;
        }
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        .remember-row input[type=checkbox] { accent-color: #6366f1; width: 15px; height: 15px; cursor: pointer; }
        .remember-row label { font-size: 0.82rem; color: #64748b; cursor: pointer; }
        .btn-login {
            width: 100%;
            padding: 13px;
            background: #6366f1;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.15s, transform 0.1s;
        }
        .btn-login:hover { background: #4f46e5; transform: translateY(-1px); }
        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            font-size: 0.82rem;
            margin-bottom: 20px;
            border: 1px solid;
        }
        .alert-error { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.25); color: #fca5a5; }
        .alert-success { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.25); color: #6ee7b7; }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 0.8rem;
            color: #2d3748;
            text-decoration: none;
            transition: color 0.14s;
        }
        .back-link:hover { color: #6366f1; }
    </style>
</head>
<body>

<div class="login-wrap">
    <div class="login-logo">
        <div class="login-logo-text">
            <span class="logo-d">DAILY</span><span class="logo-drive">dRIVE</span>
        </div>
        <div class="login-logo-sub">Admin Panel</div>
    </div>

    <div class="login-card">
        <div class="login-title">Welcome back</div>
        <div class="login-sub">Sign in to manage your blog</div>

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input class="form-control" type="email" name="email"
                       value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input class="form-control" type="password" name="password"
                       placeholder="••••••••••" required>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Keep me logged in</label>
            </div>

            <button class="btn-login" type="submit">
                Sign In to Admin Panel
            </button>
        </form>
    </div>

    <a class="back-link" href="{{ route('home') }}">← Back to DAILYdRIVE</a>
</div>

</body>
</html>
