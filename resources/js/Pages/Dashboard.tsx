import { ReactNode, useState } from 'react';
import { Link } from '@inertiajs/react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import MobileLayout from '@/Layouts/MobileLayout';
import { PageProps, Transaction, Wallet } from '@/types';

interface PeriodStats {
    total_deposits: number;
    total_withdrawals: number;
    total_transfers_sent: number;
    total_transfers_received: number;
    transaction_count: number;
}

type StatsPeriod = '7d' | '30d' | '1y';

interface DashboardPageProps extends PageProps {
    wallet: Wallet;
    recentTransactions: Transaction[];
    stats: Record<StatsPeriod, PeriodStats>;
}

interface QuickAction {
    name: string;
    href: string;
    iconBg: string;
    iconColor: string;
    icon: ReactNode;
    disabled?: boolean;
}

const periodOptions: { key: StatsPeriod; label: string }[] = [
    { key: '7d', label: '7D' },
    { key: '30d', label: '30D' },
    { key: '1y', label: '1Y' },
];

export default function Dashboard({ auth, wallet, recentTransactions, stats }: DashboardPageProps) {
    // Add view parameter for admins to keep them in client view
    const viewParam = auth.user.is_admin ? '?view=client' : '';
    const isRestricted = auth.user.status === 'locked' || auth.user.status === 'suspended';
    const [statsPeriod, setStatsPeriod] = useState<StatsPeriod>('30d');
    const periodStats = stats?.[statsPeriod];

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: wallet?.currency || 'USD',
            minimumFractionDigits: 2,
        }).format(amount / 100);
    };

    const formatDate = (dateString: string) => {
        return new Intl.DateTimeFormat('en-US', {
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        }).format(new Date(dateString));
    };

    const quickActions: QuickAction[] = [
        {
            name: 'Deposit',
            href: '/deposit' + viewParam,
            iconBg: 'bg-green-500/10',
            iconColor: 'text-green-600 dark:text-green-500',
            icon: (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
            ),
            disabled: isRestricted,
        },
        {
            name: 'Transfer',
            href: '/transfer' + viewParam,
            iconBg: 'bg-blue-500/10',
            iconColor: 'text-blue-600 dark:text-blue-500',
            icon: (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            ),
            disabled: isRestricted,
        },
        {
            name: 'Savings',
            href: '/savings' + viewParam,
            iconBg: 'bg-amber-500/10',
            iconColor: 'text-amber-600 dark:text-amber-500',
            icon: (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 9l9-6 9 6M4.5 9.75V21M19.5 9.75V21M9 21v-6a3 3 0 013-3v0a3 3 0 013 3v6M3 21h18" />
                </svg>
            ),
            disabled: isRestricted,
        },
        {
            name: 'Invest',
            href: '/invest' + viewParam,
            iconBg: 'bg-purple-500/10',
            iconColor: 'text-purple-600 dark:text-purple-500',
            icon: (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.25 18L9 11.25l4.306 4.306a11.95 11.95 0 015.814-5.518l2.74-1.22m0 0l-5.94-2.281m5.94 2.28l-2.28 5.941" />
                </svg>
            ),
            disabled: isRestricted,
        },
    ];

    const getTransactionIcon = (type: string) => {
        switch (type) {
            case 'deposit':
                return (
                    <div className="w-10 h-10 shrink-0 rounded-full bg-green-500/10 flex items-center justify-center">
                        <svg className="w-5 h-5 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                );
            case 'withdrawal':
                return (
                    <div className="w-10 h-10 shrink-0 rounded-full bg-red-500/10 flex items-center justify-center">
                        <svg className="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 12H4" />
                        </svg>
                    </div>
                );
            case 'transfer':
                return (
                    <div className="w-10 h-10 shrink-0 rounded-full bg-blue-500/10 flex items-center justify-center">
                        <svg className="w-5 h-5 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                );
            default:
                return null;
        }
    };

    const getTransactionTitle = (transaction: Transaction) => {
        if (transaction.type === 'transfer') {
            const isReceived = transaction.recipient_id === auth.user.id;
            const other = isReceived ? transaction.user : transaction.recipient;
            if (other?.name) {
                return isReceived ? `From ${other.name}` : `To ${other.name}`;
            }
            return 'Transfer';
        }
        return transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1);
    };

    const isCredit = (transaction: Transaction) =>
        transaction.type === 'deposit' ||
        (transaction.type === 'transfer' && transaction.recipient_id === auth.user.id);

    const getStatusBadge = (status: string) => {
        const styles: Record<string, string> = {
            completed: 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/40',
            pending: 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/20 dark:text-amber-400 dark:border-amber-500/40',
            failed: 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/40',
            cancelled: 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-500/20 dark:text-slate-400 dark:border-slate-500/40',
        };

        return (
            <span className={`inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-semibold ${styles[status] || styles.cancelled}`}>
                {status.charAt(0).toUpperCase() + status.slice(1)}
            </span>
        );
    };

    return (
        <MobileLayout user={auth.user} title="Dashboard" currentRoute="dashboard">
            <div className="px-4 py-6 space-y-6">
                {/* Wallet Balance Card */}
                <Card className="relative overflow-hidden bg-gradient-to-br from-slate-800 via-slate-900 to-black border border-slate-800 text-white shadow-xl shadow-slate-900/20">
                    <div aria-hidden className="pointer-events-none absolute -top-20 -right-10 w-64 h-64 rounded-full bg-white/5 blur-3xl" />
                    <div aria-hidden className="pointer-events-none absolute -bottom-24 -left-16 w-64 h-64 rounded-full bg-blue-500/10 blur-3xl" />
                    <CardHeader className="relative pb-3">
                        <CardDescription className="text-slate-400 text-xs font-medium tracking-wide uppercase">
                            Available Balance
                        </CardDescription>
                        <CardTitle className="text-4xl font-bold tracking-tight">
                            {formatCurrency(wallet?.balance || 0)}
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="relative pb-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-xs text-slate-400">Account Number</p>
                                <p className="text-sm font-mono font-medium text-slate-100">{wallet?.account_number}</p>
                            </div>
                            <div className="w-12 h-8 rounded-md bg-white/10 border border-white/10 flex items-center justify-center">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Quick Actions */}
                <div className="grid grid-cols-4 gap-2.5">
                    {quickActions.map((action) => (
                        <Link key={action.name} href={action.disabled ? '#' : action.href} className={action.disabled ? 'pointer-events-none' : undefined}>
                            <div
                                className={`rounded-2xl border p-3 flex flex-col items-center space-y-2 transition-colors ${
                                    action.disabled
                                        ? 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 opacity-50 cursor-not-allowed'
                                        : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer'
                                }`}
                            >
                                <div className={`w-11 h-11 rounded-full flex items-center justify-center ${action.iconBg} ${action.iconColor}`}>
                                    {action.icon}
                                </div>
                                <p className="text-xs font-medium text-slate-700 dark:text-slate-300 text-center leading-tight">
                                    {action.name}
                                </p>
                                {action.disabled && (
                                    <span className="text-[9px] uppercase tracking-wider text-rose-500 dark:text-rose-300 font-semibold">Restricted</span>
                                )}
                            </div>
                        </Link>
                    ))}
                </div>

                {/* Transaction Statistics */}
                <Card className="bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800">
                    <CardHeader className="pb-3">
                        <div className="flex items-center justify-between gap-2">
                            <div>
                                <CardTitle className="text-slate-900 dark:text-slate-50 text-lg">Overview</CardTitle>
                                <p className="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                    {periodStats?.transaction_count ?? 0} transaction{periodStats?.transaction_count === 1 ? '' : 's'}
                                </p>
                            </div>
                            <div className="flex items-center rounded-full bg-slate-100 dark:bg-slate-800 p-0.5">
                                {periodOptions.map((option) => (
                                    <button
                                        key={option.key}
                                        type="button"
                                        onClick={() => setStatsPeriod(option.key)}
                                        className={`px-2.5 py-1 text-xs font-medium rounded-full transition-colors ${
                                            statsPeriod === option.key
                                                ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50 shadow-sm'
                                                : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-50'
                                        }`}
                                    >
                                        {option.label}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-1">
                                <p className="text-xs text-slate-500 dark:text-slate-400">Total Deposits</p>
                                <p className="text-lg font-semibold text-green-600 dark:text-green-500">
                                    {formatCurrency(periodStats?.total_deposits || 0)}
                                </p>
                            </div>
                            <div className="space-y-1">
                                <p className="text-xs text-slate-500 dark:text-slate-400">Total Withdrawals</p>
                                <p className="text-lg font-semibold text-red-600 dark:text-red-500">
                                    {formatCurrency(periodStats?.total_withdrawals || 0)}
                                </p>
                            </div>
                            <div className="space-y-1">
                                <p className="text-xs text-slate-500 dark:text-slate-400">Sent</p>
                                <p className="text-lg font-semibold text-blue-600 dark:text-blue-500">
                                    {formatCurrency(periodStats?.total_transfers_sent || 0)}
                                </p>
                            </div>
                            <div className="space-y-1">
                                <p className="text-xs text-slate-500 dark:text-slate-400">Received</p>
                                <p className="text-lg font-semibold text-purple-600 dark:text-purple-500">
                                    {formatCurrency(periodStats?.total_transfers_received || 0)}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Recent Transactions */}
                <Card className="bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 overflow-hidden">
                    <CardHeader className="pb-3">
                        <div className="flex items-center justify-between">
                            <CardTitle className="text-slate-900 dark:text-slate-50 text-lg">Recent Transactions</CardTitle>
                            <Link
                                href={"/transactions" + viewParam}
                                className="text-xs font-medium text-blue-600 dark:text-blue-500 hover:text-blue-700 dark:hover:text-blue-400 bg-blue-50 dark:bg-blue-500/10 rounded-full px-3 py-1.5 transition-colors"
                            >
                                View All
                            </Link>
                        </div>
                    </CardHeader>
                    <CardContent className="p-0">
                        {recentTransactions && recentTransactions.length > 0 ? (
                            <div className="divide-y divide-slate-100 dark:divide-slate-800">
                                {recentTransactions.map((transaction) => (
                                    <Link
                                        key={transaction.id}
                                        href={`/transactions/${transaction.id}${viewParam}`}
                                        className="flex items-center justify-between gap-3 px-6 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors"
                                    >
                                        <div className="flex items-center space-x-3 min-w-0">
                                            {getTransactionIcon(transaction.type)}
                                            <div className="min-w-0">
                                                <p className="text-sm font-medium text-slate-900 dark:text-slate-50 truncate">
                                                    {getTransactionTitle(transaction)}
                                                </p>
                                                <p className="text-xs text-slate-500 dark:text-slate-400">
                                                    {formatDate(transaction.created_at)}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="text-right shrink-0">
                                            <p className={`text-sm font-semibold ${isCredit(transaction) ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500'}`}>
                                                {isCredit(transaction) ? '+' : '-'}
                                                {formatCurrency(transaction.amount)}
                                            </p>
                                            <div className="mt-1 flex justify-end">
                                                {getStatusBadge(transaction.status)}
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        ) : (
                            <div className="text-center py-10 px-6">
                                <div className="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                    <svg className="w-8 h-8 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p className="text-sm text-slate-500 dark:text-slate-400">No transactions yet</p>
                                <p className="text-xs text-slate-400 dark:text-slate-500 mt-1">Your transactions will appear here</p>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </MobileLayout>
    );
}
