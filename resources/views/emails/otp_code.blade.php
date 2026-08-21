@extends('emails.layout')

@section('title', 'Your BANKO Login Code')

@section('tag', 'Security Check')

@section('headline', 'Your One-Time Login Code')

@section('intro')
Hello {{ $userName }}, use the code below to complete your BANKO login. This code expires in {{ $expiresInMinutes }} minutes.
@endsection

@section('content')
    <div class="code-display">{{ $code }}</div>

    <p>If you did not request this code, please ignore this email. Someone may have tried to access your account.</p>

    <p class="muted">For your security, never share this code with anyone. BANKO support will never ask for it.</p>
@endsection

