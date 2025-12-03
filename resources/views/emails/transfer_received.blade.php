@extends('emails.layout')

@section('title', 'Money Received')

@section('tag', 'Transfer Update')

@section('headline', 'You Received a Transfer')

@section('intro')
Hello {{ $recipientName }}, funds have just landed in your account.
@endsection

@section('content')
    <div class="summary">
        <dl>
            <dt>Amount Received</dt>
            <dd>{{ $amount }}</dd>

            <dt>From</dt>
            <dd>{{ $senderName }}</dd>

            <dt>Sender Account</dt>
            <dd>{{ $senderAccount }}</dd>

            <dt>Reference</dt>
            <dd>{{ $reference }}</dd>

            <dt>Date</dt>
            <dd>{{ $date }}</dd>

            <dt>Status</dt>
            <dd>{{ $status }}</dd>
        </dl>
    </div>

    <p>Your updated available balance is <strong>{{ $availableBalance }}</strong>.</p>

    <a href="{{ url('/transactions') }}" class="cta-button">Review Transactions</a>

    <p class="muted">If you do not recognize this transfer, contact support immediately.</p>
@endsection

