import { Head, Link } from '@inertiajs/react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import MobileLayout from '@/Layouts/MobileLayout';
import { PageProps, Transaction, Wallet } from '@/types';

interface DashboardPageProps extends PageProps {
    wallet: Wallet;
    recentTransactions: Transaction[];
    stats: {
        total_deposits: number;
        total_withdrawals: number;
        total_transfers_sent: number;
        total_transfers_received: number;
    };
}

export default function Dashboard({ auth, wallet, recentTransactions, stats }: DashboardPageProps) {
    // Add view parameter for admins to keep them in client view
    const viewParam = auth.user.is_admin ? '?view=client' : '';
    const isLocked = auth.user.status === 'locked';

    const formatCurrency = (amount: number | string, isCents = true) => {
        const numericAmount = typeof amount === 'string' ? parseFloat(amount) : amount;
        const normalizedAmount = isNaN(numericAmount) ? 0 : (isCents ? numericAmount / 100 : numericAmount);

        try {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: wallet?.currency || auth.user.preferred_currency || 'USD',
                minimumFractionDigits: 2,
            }).format(normalizedAmount);
        } catch (error) {
            console.error('Currency formatting error:', error);
            const currency = (wallet?.currency || auth.user.preferred_currency || 'USD').toUpperCase();
            return `${currency} ${normalizedAmount.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
        }
    };

    const formatDate = (dateString: string) => {
        return new Intl.DateTimeFormat('en-US', {
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
                        <svg className="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                );
            case 'withdrawal':
                return (
                    <div className="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center">
                        <svg className="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 12H4" />
                        </svg>
                    </div>
                );
            case 'transfer':
                return (
                    <div className="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center">
                        <svg className="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                );
            default:
                return null;
        }
    };

    const getStatusBadge = (status: string) => {
        const variants: Record<string, 'default' | 'secondary' | 'destructive' | 'outline'> = {
            completed: 'default',
            pending: 'secondary',
            failed: 'destructive',
            cancelled: 'outline',
        };

        return (
            <Badge variant={variants[status] || 'outline'} className="text-xs">
                {status}
            </Badge>
        );
    };

    return (
        <MobileLayout user={auth.user} title="Dashboard" currentRoute="dashboard">
            <div className="px-4 py-6 space-y-6">
                {isLocked && (
                    <Card className="border-rose-500/40 bg-rose-950/40 text-rose-100">
                        <CardContent className="pt-6 pb-6">
                            <div className="flex items-start space-x-3">
                                <div className="mt-1">
                                    <svg className="w-5 h-5 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01M5.07 19h13.86A2 2 0 0020.9 17L13.84 4.66a2 2 0 00-3.68 0L3.1 17a2 2 0 001.97 2z" />
                                    </svg>
                                </div>
                                <div className="space-y-1">
                                    <p className="text-sm font-semibold uppercase tracking-wide text-rose-200">
                                        Account Locked
                                    </p>
                                    <p className="text-sm text-rose-100/90">
                                        Transfers and other outgoing transactions are disabled until an administrator unlocks your account. Please contact support if you believe this is a mistake.
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                )}

                {/* Wallet Balance Card */}
                <Card className="bg-gradient-to-br from-blue-600 to-purple-600 border-0 text-white shadow-xl">
                    <CardHeader className="pb-3">
                        <CardDescription className="text-blue-100 text-xs text-opacity-80 uppercase tracking-wider font-semibold">
                            Available Balance
                        </CardDescription>
                        <CardTitle className="text-4xl font-bold tracking-tight">
                            {formatCurrency(wallet?.balance || 0, false)}
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="pb-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-xs text-blue-100 italic opacity-80">Pending: {formatCurrency(wallet?.ledger_balance || 0, false)}</p>
                            </div>
                            <div className="text-right">
                                <p className="text-xs text-blue-100 opacity-70">Account Number</p>
                                <p className="text-sm font-mono font-medium tracking-wider">{wallet?.account_number}</p>
                            </div>
                            <div className="w-12 h-8 rounded bg-white/20 flex items-center justify-center">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Quick Actions */}
                <div className="grid grid-cols-3 gap-3">
                    <Link href={"/deposit" + viewParam}>
                        <Card className="bg-slate-900 border-slate-800 hover:bg-slate-800 transition-colors cursor-pointer">
                            <CardContent className="p-4 flex flex-col items-center space-y-2">
                                <div className="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center">
                                    <svg className="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <p className="text-xs font-medium text-slate-300">Deposit</p>
                            </CardContent>
                        </Card>
                    </Link>

                    <Link href={"/withdraw" + viewParam}>
                        <Card className="bg-slate-900 border-slate-800 hover:bg-slate-800 transition-colors cursor-pointer">
                            <CardContent className="p-4 flex flex-col items-center space-y-2">
                                <div className="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center">
                                    <svg className="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 12H4" />
                                    </svg>
                                </div>
                                <p className="text-xs font-medium text-slate-300">Withdraw</p>
                            </CardContent>
                        </Card>
                    </Link>

                    <Link href={"/transfer" + viewParam}>
                        <Card className={`bg-slate-900 border-slate-800 transition-colors ${isLocked ? 'cursor-not-allowed opacity-50' : 'hover:bg-slate-800 cursor-pointer'}`}>
                            <CardContent className="p-4 flex flex-col items-center space-y-2">
                                <div className={`w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center ${isLocked ? 'opacity-60' : ''}`}>
                                    <svg className="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <p className="text-xs font-medium text-slate-300">Transfer</p>
                                {isLocked && (
                                    <span className="text-[10px] uppercase tracking-wider text-rose-300 font-semibold">Locked</span>
                                )}
                            </CardContent>
                        </Card>
                    </Link>
                </div>

                {/* Transaction Statistics */}
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader className="pb-3">
                        <CardTitle className="text-slate-50 text-lg">Overview</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-1">
                                <p className="text-xs text-slate-400">Total Deposits</p>
                                <p className="text-lg font-semibold text-green-500">
                                    {formatCurrency(stats?.total_deposits || 0, true)}
                                </p>
                            </div>
                            <div className="space-y-1">
                                <p className="text-xs text-slate-400">Total Withdrawals</p>
                                <p className="text-lg font-semibold text-red-500">
                                    {formatCurrency(stats?.total_withdrawals || 0, true)}
                                </p>
                            </div>
                            <div className="space-y-1">
                                <p className="text-xs text-slate-400">Sent</p>
                                <p className="text-lg font-semibold text-blue-500">
                                    {formatCurrency(stats?.total_transfers_sent || 0, true)}
                                </p>
                            </div>
                            <div className="space-y-1">
                                <p className="text-xs text-slate-400">Received</p>
                                <p className="text-lg font-semibold text-purple-500">
                                    {formatCurrency(stats?.total_transfers_received || 0, true)}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Recent Transactions */}
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader className="pb-3">
                        <div className="flex items-center justify-between">
                            <CardTitle className="text-slate-50 text-lg">Recent Transactions</CardTitle>
                            <Link href={"/transactions" + viewParam}>
                                <Button variant="ghost" size="sm" className="text-blue-500 hover:text-blue-400 hover:bg-blue-500/10 text-xs h-8">
                                    View All
                                </Button>
                            </Link>
                        </div>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        {recentTransactions && recentTransactions.length > 0 ? (
                            recentTransactions.map((transaction) => (
                                <div
                                    key={transaction.id}
                                    className="flex items-center justify-between p-3 rounded-lg bg-slate-800/50 hover:bg-slate-800 transition-colors"
                                >
                                    <div className="flex items-center space-x-3">
                                        {getTransactionIcon(transaction.type)}
                                        <div>
                                            <p className="text-sm font-medium text-slate-50 capitalize">
                                                {transaction.type}
                                            </p>
                                            <p className="text-xs text-slate-400">
                                                {formatDate(transaction.created_at)}
                                            </p>
                                        </div>
                                    </div>
                                    <div className="text-right">
                                        <p className={`text-sm font-semibold ${transaction.type === 'deposit' ||
                                            (transaction.type === 'transfer' && transaction.recipient_id === auth.user.id)
                                            ? 'text-green-500'
                                            : 'text-red-500'
                                            }`}>
                                            {transaction.type === 'deposit' ||
                                                (transaction.type === 'transfer' && transaction.recipient_id === auth.user.id)
                                                ? '+'
                                                : '-'}
                                            {formatCurrency(transaction.amount, true)}
                                        </p>
                                        {getStatusBadge(transaction.status)}
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="text-center py-8">
                                <div className="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-800 flex items-center justify-center">
                                    <svg className="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p className="text-sm text-slate-400">No transactions yet</p>
                                <p className="text-xs text-slate-500 mt-1">Your transactions will appear here</p>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </MobileLayout >
    );
}
