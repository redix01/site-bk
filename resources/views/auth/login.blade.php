<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Banko') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 400px;
            width: 100%;
            margin: 1rem;
        }
        .logo {
            font-size: 2rem;
            font-weight: 600;
            color: #1f2937;
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #374151;
            font-weight: 500;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
        }
        .form-checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .form-checkbox input {
            margin-right: 0.5rem;
        }
        .btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #2563eb;
        }
        .btn-secondary {
            background-color: transparent;
            color: #3b82f6;
            border: 2px solid #3b82f6;
            margin-top: 1rem;
        }
        .btn-secondary:hover {
            background-color: #3b82f6;
            color: white;
        }
        .error {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .back-link {
            text-align: center;
            margin-top: 1rem;
        }
        .back-link a {
            color: #6b7280;
            text-decoration: none;
        }
        .back-link a:hover {
            color: #3b82f6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🏦 Banko</div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-input @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-input @error('password') border-red-500 @enderror" name="password" required>
                    @error('password')
                    <div class="error">{{ $message }}</div>
                    @enderror
            </div>

            <div class="form-checkbox">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="back-link">
            <a href="{{ route('register') }}">Don't have an account? Register</a>
            </div>
        
        <div class="back-link">
            <a href="/">← Back to Home</a>
            </div>
    </div>
</body>
</html>