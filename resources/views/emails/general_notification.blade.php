@extends('emails.layout')

@section('title', $title)

@section('headline', $title)

@section('content')
    <p>{!! nl2br(e($messageContent)) !!}</p>

    @if($actionUrl)
        <a href="{{ $actionUrl }}" class="cta-button">{{ $actionText }}</a>
    @endif

    <p class="muted">If you have any questions, our support team is happy to help.</p>
@endsection
