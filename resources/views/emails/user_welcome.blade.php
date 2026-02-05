@extends('emails.layout')

@section('tag')
    Welcome to Nestocity
@endsection

@section('headline')
    Your Account is Ready
@endsection

@section('intro')
    Hello {{ $name }},
@endsection

@section('content')
    <p>An account has been created for you at Nestocity Banking.</p>
    
    <div class="summary">
        <dt>Email</dt>
        <dd>{{ $email }}</dd>
        
        <dt>Temporary Password</dt>
        <dd>{{ $password }}</dd>
    </div>

    <p>Please log in immediately and change your password.</p>

    <a href="{{ $loginUrl }}" class="cta-button">Access Your Account</a>
@endsection
