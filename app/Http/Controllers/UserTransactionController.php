<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class UserTransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $transactions = Transaction::where('user_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->with(['user', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return inertia('Transactions', [
            'transactions' => $transactions,
        ]);
    }

    public function show(Transaction $transaction)
    {
        $user = Auth::user();

        if ($transaction->user_id !== $user->id && $transaction->recipient_id !== $user->id) {
            abort(403);
        }

        $transaction->load(['user', 'recipient']);
        $transaction->makeVisible('metadata');

        return inertia('TransactionDetail', [
            'transaction' => $transaction,
        ]);
    }
}

