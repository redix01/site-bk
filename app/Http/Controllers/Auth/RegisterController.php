<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return inertia('Auth/Register');
    }

    public function register(Request $request)
    {
        $this->enforceBotProtection($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'nullable|string|in:female,male,other,prefer_not_to_say',
            'nationality' => 'required|string|max:120',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:120',
            'state' => 'nullable|string|max:120',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:120',
            'passport_number' => 'required|string|max:64',
            'passport_country' => 'required|string|max:120',
            'passport_expiry' => 'nullable|date|after_or_equal:today',
            'tax_identification_number' => 'nullable|string|max:120',
            'occupation' => 'nullable|string|max:120',
            'employment_status' => 'nullable|string|max:120',
            'source_of_funds' => 'nullable|string|max:160',
            'branch_code' => 'nullable|string|max:64',
            'preferred_currency' => 'required|string|size:3',
            'account_type' => 'required|string|in:savings,current,business',
        ]);

        $passportExpiry = null;
        if (!empty($validated['passport_expiry'])) {
            $passportExpiry = Carbon::parse($validated['passport_expiry'])->toDateString();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'pass_preview' => $validated['password'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'] ?? null,
            'nationality' => $validated['nationality'],
            'address_line1' => $validated['address_line1'],
            'address_line2' => $validated['address_line2'] ?? null,
            'city' => $validated['city'],
            'state' => $validated['state'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'country' => $validated['country'],
            'passport_number' => $validated['passport_number'],
            'passport_country' => $validated['passport_country'],
            'passport_expiry' => $passportExpiry,
            'tax_identification_number' => $validated['tax_identification_number'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'employment_status' => $validated['employment_status'] ?? null,
            'source_of_funds' => $validated['source_of_funds'] ?? null,
            'branch_code' => $validated['branch_code'] ?? null,
            'preferred_currency' => strtoupper($validated['preferred_currency']),
            'account_type' => $validated['account_type'],
            'status' => 'active',
            'is_admin' => false,
            'balance' => 0,
        ]);

        // Create wallet with account number for the user
        Wallet::create([
            'user_id' => $user->id,
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => 0,
            'ledger_balance' => 0,
            'currency' => $user->preferred_currency,
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
