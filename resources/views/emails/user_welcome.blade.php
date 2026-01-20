@extends('emails.layout')

@section('title', 'Welcome to ' . config('app.name'))

@section('content')
    <div style="padding: 20px; text-align: left;">
        <h2 style="color: #333; margin-bottom: 20px;">Welcome, {{ $userName }}!</h2>
        <p style="color: #555; line-height: 1.6; font-size: 16px;">
            Thank you for choosing {{ config('app.name') }} for your banking needs. Your account has been successfully created.
        </p>
        
        <p style="color: #555; line-height: 1.6; font-size: 16px;">
            You can now log in to your dashboard to manage your finances, perform transfers, and more.
        </p>

        <div style="margin: 30px 0; background-color: #f9fafb; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb;">
            <p style="margin: 0; color: #374151; font-weight: bold;">Account Details:</p>
            <p style="margin: 5px 0 0; color: #6b7280;">Email: {{ $email }}</p>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ $actionUrl }}" 
               style="background-color: #4F46E5; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                Log In to Your Account
            </a>
        </div>
        
        <p style="margin-top: 30px; color: #777; font-size: 14px;">
            If you did not create this account, please contact our support team immediately.
        </p>
    </div>
@endsection
