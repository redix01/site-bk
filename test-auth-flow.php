<?php

/**
 * Test script for the new multi-step authentication flow
 * Run this script to test OTP generation and verification
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Testing Multi-Step Authentication Flow\n";
echo "========================================\n\n";

// Test 1: OTP Generation
echo "1. Testing OTP Generation...\n";
$testEmail = 'test@example.com';
$otpCode = OtpCode::generateForEmail($testEmail);

echo "   ✅ OTP generated: {$otpCode->code}\n";
echo "   ✅ Expires at: {$otpCode->expires_at}\n";
echo "   ✅ Email: {$otpCode->email}\n\n";

// Test 2: OTP Verification (Valid)
echo "2. Testing OTP Verification (Valid Code)...\n";
$isValid = $otpCode->verify($otpCode->code);
echo "   " . ($isValid ? "✅" : "❌") . " Verification result: " . ($isValid ? "Valid" : "Invalid") . "\n\n";

// Test 3: OTP Verification (Invalid)
echo "3. Testing OTP Verification (Invalid Code)...\n";
$newOtpCode = OtpCode::generateForEmail('test2@example.com');
$isInvalid = $newOtpCode->verify('123456');
echo "   " . ($isInvalid ? "❌" : "✅") . " Verification result: " . ($isInvalid ? "Valid (should be invalid)" : "Invalid (correct)") . "\n\n";

// Test 4: Check User Exists
echo "4. Testing User Lookup...\n";
$user = User::where('email', 'admin@example.com')->first();
if ($user) {
    echo "   ✅ Admin user found: {$user->name} ({$user->email})\n";
} else {
    echo "   ❌ Admin user not found\n";
}

$testUser = User::where('email', 'test@example.com')->first();
if ($testUser) {
    echo "   ✅ Test user found: {$testUser->name} ({$testUser->email})\n";
} else {
    echo "   ❌ Test user not found\n";
}

echo "\n";

// Test 5: Email Notification (Mock)
echo "5. Testing Email Notification...\n";
try {
    Mail::fake();
    $user = User::where('email', 'admin@example.com')->first();
    if ($user) {
        Mail::to($user->email)->send(new OtpCodeMail('123456', $user->name));
        echo "   ✅ Email notification sent successfully\n";
    } else {
        echo "   ❌ User not found for email notification\n";
    }
} catch (Exception $e) {
    echo "   ❌ Email notification failed: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 6: Database Cleanup
echo "6. Testing Database Operations...\n";
$otpCount = OtpCode::count();
echo "   📊 Total OTP codes in database: {$otpCount}\n";

// Clean up test OTPs
OtpCode::where('email', 'test@example.com')->delete();
OtpCode::where('email', 'test2@example.com')->delete();
echo "   🧹 Cleaned up test OTP codes\n";

echo "\n";
echo "🎉 Authentication Flow Test Complete!\n";
echo "====================================\n";
echo "\n";
echo "Next Steps:\n";
echo "1. Run 'php artisan migrate' to create the OTP table\n";
echo "2. Start the development server: 'php artisan serve'\n";
echo "3. Visit http://localhost:8000/login\n";
echo "4. Test with admin@example.com or test@example.com\n";
echo "5. Check storage/logs/laravel.log for OTP emails\n";
