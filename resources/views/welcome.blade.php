<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Banko') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.tsx'])
        @else
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
                text-align: center;
                max-width: 400px;
                width: 100%;
                margin: 1rem;
            }
            .logo {
                font-size: 2.5rem;
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 0.5rem;
            }
            .subtitle {
                color: #6b7280;
                margin-bottom: 2rem;
                font-size: 1.1rem;
            }
            .button {
                display: inline-block;
                padding: 0.75rem 2rem;
                margin: 0.5rem;
                border-radius: 0.5rem;
                text-decoration: none;
                font-weight: 500;
                font-size: 1rem;
                transition: all 0.2s;
                border: 2px solid transparent;
                min-width: 140px;
            }
            .button-primary {
                background-color: #3b82f6;
                color: white;
            }
            .button-primary:hover {
                background-color: #2563eb;
                transform: translateY(-1px);
            }
            .button-secondary {
                background-color: transparent;
                color: #3b82f6;
                border-color: #3b82f6;
            }
            .button-secondary:hover {
                background-color: #3b82f6;
                color: white;
                transform: translateY(-1px);
            }
            .features {
                margin-top: 2rem;
                text-align: left;
            }
            .feature {
                display: flex;
                align-items: center;
                margin-bottom: 1rem;
                color: #4b5563;
            }
            .feature-icon {
                width: 1.5rem;
                height: 1.5rem;
                margin-right: 0.75rem;
                color: #10b981;
            }
            </style>
        @endif
    </head>
<body>
    <div class="container">
        <div class="logo">🏦 Banko</div>
        <div class="subtitle">Your Digital Banking Solution</div>
        
        <div class="button-group">
            @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/dashboard') }}" class="button button-primary">
                            Dashboard
                        </a>
                    @else
                    <a href="{{ route('login') }}" class="button button-primary">
                        Login
                        </a>

                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="button button-secondary">
                            Create Account
                            </a>
                        @endif
                    @endauth
            @endif
        </div>
        
        <div class="features">
            <div class="feature">
                <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                Secure Banking
            </div>
            <div class="feature">
                <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                Instant Transfers
                </div>
            <div class="feature">
                <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                24/7 Support
                </div>
        </div>
    </div>
    </body>
</html>
