<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'Banko') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8fafc;
            min-height: 100vh;
        }
        .header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        .btn-secondary {
            background-color: transparent;
            color: #6b7280;
            border: 1px solid #d1d5db;
        }
        .btn-secondary:hover {
            background-color: #f3f4f6;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .welcome-card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        .welcome-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        .welcome-subtitle {
            color: #6b7280;
            margin-bottom: 1rem;
        }
        .account-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .info-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 0.5rem;
        }
        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
        .info-value {
            font-weight: 600;
            color: #1f2937;
        }
        .balance {
            font-size: 1.5rem;
            color: #059669;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🏦 Banko</div>
        <div class="user-info">
            <span>Welcome, {{ $user->name }}!</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">Logout</button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome-card">
            <div class="welcome-title">Welcome to your Dashboard</div>
            <div class="welcome-subtitle">Manage your banking account with ease</div>
            
            <div class="account-info">
                <div class="info-item">
                    <div class="info-label">Account Number</div>
                    <div class="info-value">{{ $user->account_number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Account Type</div>
                    <div class="info-value">{{ ucfirst($user->account_type) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $user->phone }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">{{ ucfirst($user->status) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Current Balance</div>
                    <div class="info-value balance">₦{{ number_format($user->balance / 100, 2) }}</div>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; color: #6b7280;">
            <p>More banking features coming soon!</p>
        </div>
    </div>
</body>
</html>
