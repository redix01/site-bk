<?php

/**
 * Quick test script to verify OTP email functionality
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Testing OTP Email Functionality\n";
echo "==================================\n\n";

// Test with admin user (should exist)
$testEmail = 'admin@obsidianwealths.com';
$user = User::where('email', $testEmail)->first();

if (!$user) {
    echo "❌ User not found: {$testEmail}\n";
    echo "Please ensure the admin user exists.\n";
    exit(1);
}

echo "✅ User found: {$user->name} ({$user->email})\n\n";

// Generate OTP
echo "📧 Generating OTP code...\n";
try {
    $otpCode = OtpCode::generateForEmail($testEmail);
    echo "✅ OTP Generated: {$otpCode->code}\n";
    echo "   Expires at: {$otpCode->expires_at}\n\n";
} catch (Exception $e) {
    echo "❌ Failed to generate OTP: " . $e->getMessage() . "\n";
    exit(1);
}

// Send email notification
echo "📬 Sending OTP email to Mailtrap...\n";
try {
    Mail::to($testEmail)->send(new OtpCodeMail($otpCode->code, $user->name));
    echo "✅ Email sent successfully!\n\n";
} catch (Exception $e) {
    echo "❌ Failed to send email: " . $e->getMessage() . "\n";
    exit(1);
}

echo "🎉 Test Complete!\n";
echo "==================\n\n";
echo "Next steps:\n";
echo "1. Check your Mailtrap inbox at: https://mailtrap.io/inboxes\n";
echo "2. Look for an email to: {$testEmail}\n";
echo "3. The OTP code should be: {$otpCode->code}\n";
echo "4. Test the login flow at: https://obsidianwealths.com/login\n\n";

