<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SavingsAccount;

class UserSavingsController extends Controller
{
    private const PRODUCTS = [
        'hysa' => [
            'name' => 'High-Yield Savings',
            'shortName' => 'HYSA',
            'rate' => '4.00%',
            'description' => 'Earn more on your everyday savings with instant access to your funds, no lock-in required.',
        ],
        'mma' => [
            'name' => 'Money Market Account',
            'shortName' => 'MMA',
            'rate' => '3.57%',
            'description' => 'Combine a competitive return with the flexibility of a checking-style account.',
        ],
    ];

    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        $accounts = SavingsAccount::where('user_id', $user->id)->get()->keyBy('product');

        $products = collect(self::PRODUCTS)->map(function ($product, $key) use ($accounts) {
            return array_merge($product, [
                'key' => $key,
                'balance' => isset($accounts[$key]) ? (int) round((float) $accounts[$key]->balance) : 0,
            ]);
        })->values();

        return inertia('Savings', [
            'wallet' => $wallet,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        $validated = $request->validate([
            'product' => 'required|string|in:' . implode(',', array_keys(self::PRODUCTS)),
            'amount' => 'required|numeric|min:10',
        ]);

        if (!$wallet || !$wallet->isActive()) {
            return redirect()->back()->with('error', 'Your wallet is not available for savings right now.');
        }

        $amountInCents = (int) round($validated['amount'] * 100);
        $availableCents = (int) round((float) $wallet->balance);

        if ($amountInCents > $availableCents) {
            return redirect()->back()->with('error', 'Insufficient available balance.');
        }

        try {
            $wallet->debit($amountInCents);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Insufficient available balance.');
        }

        $account = SavingsAccount::firstOrCreate(
            ['user_id' => $user->id, 'product' => $validated['product']],
            ['balance' => 0]
        );
        $account->credit($amountInCents);

        $productName = self::PRODUCTS[$validated['product']]['name'];

        return redirect()->route('savings')->with('success', "Added to your {$productName} balance!");
    }
}
