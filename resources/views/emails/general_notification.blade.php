@extends('emails.layout')

@section('title', $title)

@section('content')
    <div style="padding: 20px; text-align: left;">
        <h2 style="color: #333; margin-bottom: 20px;">{{ $title }}</h2>
        <p style="color: #555; line-height: 1.6; font-size: 16px;">
            {!! nl2br(e($messageContent)) !!}
        </p>

        @if($actionUrl)
            <div style="margin-top: 30px; text-align: center;">
                <a href="{{ $actionUrl }}" 
                   style="background-color: #4F46E5; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                    {{ $actionText }}
                </a>
            </div>
        @endif
        
        <p style="margin-top: 30px; color: #777; font-size: 14px;">
            If you have any questions, please contact our support team.
        </p>
    </div>
@endsection
