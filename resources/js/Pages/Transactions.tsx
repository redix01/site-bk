import { useState } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';

import { Button } from '@/Components/ui/button';
import MobileLayout from '@/Layouts/MobileLayout';
import { PageProps, Transaction } from '@/types';

interface TransactionsPageProps extends PageProps {
    transactions?: Transaction[];
}

export default function Transactions({ auth, transactions = [] }: TransactionsPageProps) {
    const [filter, setFilter] = useState<'all' | 'deposit' | 'withdrawal' | 'transfer'>('all');

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
        }).format(amount / 100);
    };

    const formatDate = (dateString: string) => {
        return new Intl.DateTimeFormat('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        }).format(new Date(dateString));
    };

    const getTransactionIcon = (type: string) => {
        switch (type) {
            case 'deposit':
                return (
                    <div className="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center">
                        <svg className="w-5 h-5 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                );
            case 'withdrawal':
                return (
                    <div className="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center">
                        <svg className="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 12H4" />
                        </svg>
                    </div>
                );
            case 'transfer':
                return (
                    <div className="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center">
                        <svg className="w-5 h-5 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                );
            default:
                return null;
        }
    };

    const getStatusBadge = (status: string) => {
        const styles: Record<string, string> = {
            completed: 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/40',
            pending: 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/20 dark:text-amber-400 dark:border-amber-500/40',
            failed: 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/40',
            cancelled: 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-500/20 dark:text-slate-400 dark:border-slate-500/40',
        };

        return (
            <span className={`inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold ${styles[status] || styles.cancelled}`}>
                {status.charAt(0).toUpperCase() + status.slice(1)}
            </span>
        );
    };

    const filteredTransactions = filter === 'all' 
        ? transactions 
        : transactions.filter(t => t.type === filter);

    return (
        <MobileLayout user={auth.user} title="Transactions" currentRoute="transactions">
            <div className="px-4 py-6 space-y-6">
                {/* Filter Tabs */}
                <Card className="bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800">
                    <CardContent className="p-2">
                        <div className="grid grid-cols-4 gap-2">
                            <Button
                                variant={filter === 'all' ? 'default' : 'ghost'}
                                size="sm"
                                onClick={() => setFilter('all')}
                                className={filter === 'all' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-50 dark:hover:bg-slate-800'}
                            >
                                All
                            </Button>
                            <Button
                                variant={filter === 'deposit' ? 'default' : 'ghost'}
                                size="sm"
                                onClick={() => setFilter('deposit')}
                                className={filter === 'deposit' ? 'bg-green-600 hover:bg-green-700 text-white' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-50 dark:hover:bg-slate-800'}
                            >
                                Deposit
                            </Button>
                            <Button
                                variant={filter === 'withdrawal' ? 'default' : 'ghost'}
                                size="sm"
                                onClick={() => setFilter('withdrawal')}
                                className={filter === 'withdrawal' ? 'bg-red-600 hover:bg-red-700 text-white' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-50 dark:hover:bg-slate-800'}
                            >
                                Withdraw
                            </Button>
                            <Button
                                variant={filter === 'transfer' ? 'default' : 'ghost'}
                                size="sm"
                                onClick={() => setFilter('transfer')}
                                className={filter === 'transfer' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-50 dark:hover:bg-slate-800'}
                            >
                                Transfer
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                {/* Transactions List */}
                <div className="space-y-3">
                    {filteredTransactions.length > 0 ? (
                        filteredTransactions.map((transaction) => (
                            <Card key={transaction.id} className="bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800">
                                <CardContent className="p-4">
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center space-x-3">
                                            {getTransactionIcon(transaction.type)}
                                            <div>
                                                <p className="text-sm font-medium text-slate-900 dark:text-slate-50 capitalize">
                                                    {transaction.type}
                                                </p>
                                                <p className="text-xs text-slate-500 dark:text-slate-400">
                                                    {transaction.reference}
                                                </p>
                                                <p className="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                                    {formatDate(transaction.created_at)}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="text-right space-y-1">
                                            <p className={`text-sm font-semibold ${
                                                transaction.type === 'deposit' ||
                                                (transaction.type === 'transfer' && transaction.recipient_id === auth.user.id)
                                                    ? 'text-green-600 dark:text-green-500'
                                                    : 'text-red-600 dark:text-red-500'
                                            }`}>
                                                {transaction.type === 'deposit' ||
                                                (transaction.type === 'transfer' && transaction.recipient_id === auth.user.id)
                                                    ? '+'
                                                    : '-'}
                                                {formatCurrency(transaction.amount)}
                                            </p>
                                            {getStatusBadge(transaction.status)}
                                        </div>
                                    </div>
                                    {transaction.description && (
                                        <p className="text-xs text-slate-400 dark:text-slate-500 mt-3 pl-13">
                                            {transaction.description}
                                        </p>
                                    )}
                                </CardContent>
                            </Card>
                        ))
                    ) : (
                        <Card className="bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800">
                            <CardContent className="py-12">
                                <div className="text-center">
                                    <div className="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                        <svg className="w-8 h-8 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p className="text-sm text-slate-500 dark:text-slate-400">No transactions found</p>
                                    <p className="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                        {filter === 'all'
                                            ? 'Your transactions will appear here'
                                            : `No ${filter} transactions yet`
                                        }
                                    </p>
                                </div>
                            </CardContent>
                        </Card>
                    )}
                </div>
            </div>
        </MobileLayout>
    );
}

