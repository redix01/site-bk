<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction Receipt - {{ $transaction->reference }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1e293b;
            line-height: 1.6;
            padding: 40px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        
        .header h1 {
            font-size: 28px;
            color: #2563eb;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #64748b;
            font-size: 14px;
        }
        
        .success-badge {
            background-color: #dcfce7;
            color: #166534;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #64748b;
            font-weight: 600;
        }
        
        .info-value {
            color: #1e293b;
            font-weight: 500;
            text-align: right;
        }
        
        .amount-highlight {
            background-color: #eff6ff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 25px 0;
            border: 2px solid #2563eb;
        }
        
        .amount-highlight .label {
            color: #64748b;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .amount-highlight .amount {
            color: #2563eb;
            font-size: 32px;
            font-weight: bold;
        }
        
        .reference-box {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
            margin: 20px 0;
        }
        
        .reference-box .label {
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .reference-box .value {
            color: #1e293b;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 10px;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-completed {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .total-row {
            background-color: #f8fafc;
            padding: 12px;
            margin-top: 10px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .grid-container {
            display: block;
            width: 100%;
        }
        
        .grid-row {
            display: block;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>APP</h1>
        <p>Transaction Receipt</p>
    </div>

    <div class="success-badge">
        ✓ Transaction {{ $transaction->status === 'completed' ? 'Completed' : 'Initiated' }}
    </div>

    <div class="amount-highlight">
        <div class="label">Amount {{ $transaction->status === 'completed' ? 'Transferred' : 'Initiated' }}</div>
        <div class="amount">${{ number_format($transaction->amount / 100, 2) }}</div>
    </div>

    <div class="reference-box">
        <div class="label">Reference Number</div>
        <div class="value">{{ $transaction->reference }}</div>
    </div>

    <div class="section">
        <div class="section-title">Transaction Details</div>
        <div class="grid-container">
            <div class="info-row">
                <span class="info-label">Date & Time:</span>
                <span class="info-value">{{ $transaction->created_at->format('F d, Y - h:i A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    <span class="status-badge status-{{ $transaction->status }}">
                        {{ strtoupper($transaction->status) }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Transaction Type:</span>
                <span class="info-value">
                    @if($transaction->recipient_id)
                        Internal Transfer
                    @else
                        Wire Transfer
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Sender Information</div>
        <div class="grid-container">
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value">{{ $user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Account Number:</span>
                <span class="info-value">{{ $user->wallet->account_number ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $user->email }}</span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Recipient Information</div>
        <div class="grid-container">
            @if($transaction->recipient)
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $transaction->recipient->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Account Number:</span>
                    <span class="info-value">{{ $transaction->recipient->wallet->account_number ?? 'N/A' }}</span>
                </div>
            @elseif($transaction->metadata && isset($transaction->metadata['beneficiary_name']))
                <div class="info-row">
                    <span class="info-label">Beneficiary Name:</span>
                    <span class="info-value">{{ $transaction->metadata['beneficiary_name'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Bank Name:</span>
                    <span class="info-value">{{ $transaction->metadata['bank_name'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Account Number:</span>
                    <span class="info-value">{{ $transaction->metadata['account_number'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Routing Number:</span>
                    <span class="info-value">{{ $transaction->metadata['routing_number'] }}</span>
                </div>
                @if(isset($transaction->metadata['swift_code']) && $transaction->metadata['swift_code'])
                    <div class="info-row">
                        <span class="info-label">SWIFT/BIC Code:</span>
                        <span class="info-value">{{ $transaction->metadata['swift_code'] }}</span>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Amount Breakdown</div>
        <div class="grid-container">
            <div class="info-row">
                <span class="info-label">Transfer Amount:</span>
                <span class="info-value">${{ number_format($transaction->amount / 100, 2) }}</span>
            </div>
            @if($transaction->fee > 0)
                <div class="info-row">
                    <span class="info-label">Transaction Fee:</span>
                    <span class="info-value">${{ number_format($transaction->fee / 100, 2) }}</span>
                </div>
            @endif
            <div class="total-row">
                <div class="info-row" style="border: none;">
                    <span class="info-label">Total Amount:</span>
                    <span class="info-value">${{ number_format(($transaction->amount + $transaction->fee) / 100, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($transaction->description)
        <div class="section">
            <div class="section-title">Description</div>
            <p style="color: #64748b; padding: 10px 0;">{{ $transaction->description }}</p>
        </div>
    @endif

    <div class="footer">
        <p><strong>APP - Your Trusted Banking Partner</strong></p>
        <p>This is an official transaction receipt generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>For any inquiries, please contact our support team.</p>
        <p style="margin-top: 15px; color: #94a3b8; font-size: 9px;">
            This document is computer-generated and does not require a signature.
        </p>
    </div>
</body>
</html>


