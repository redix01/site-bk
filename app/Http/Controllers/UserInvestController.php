<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InvestmentAccount;

class UserInvestController extends Controller
{
    private const PRODUCTS = [
        'bitcoin' => [
            'name' => 'Bitcoin',
            'shortName' => 'BTC',
            'rate' => null,
            'description' => 'Invest directly in Bitcoin from your available balance at the current market price.',
        ],
        'equity_mutual_fund' => [
            'name' => 'Equity Mutual Fund',
            'shortName' => 'EMF',
            'rate' => '30.00%',
            'description' => 'A diversified fund of equities managed for long-term growth.',
        ],
    ];

    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        $accounts = InvestmentAccount::where('user_id', $user->id)->get()->keyBy('product');

        $products = collect(self::PRODUCTS)->map(function ($product, $key) use ($accounts) {
            return array_merge($product, [
                'key' => $key,
                'balance' => isset($accounts[$key]) ? (int) round((float) $accounts[$key]->balance) : 0,
            ]);
        })->values();

        return inertia('Invest', [
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
            return redirect()->back()->with('error', 'Your wallet is not available for investing right now.');
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

        $account = InvestmentAccount::firstOrCreate(
            ['user_id' => $user->id, 'product' => $validated['product']],
            ['balance' => 0]
        );
        $account->credit($amountInCents);

        $productName = self::PRODUCTS[$validated['product']]['name'];

        return redirect()->route('invest')->with('success', "Invested in {$productName}!");
    }
}
