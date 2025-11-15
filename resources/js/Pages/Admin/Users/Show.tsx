import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Head, Link, router, useForm } from '@inertiajs/react';
import {
    Activity,
    ArrowDownLeft,
    ArrowLeft,
    ArrowUpRight,
    Ban,
    Check,
    CheckCircle,
    Clock,
    Copy,
    Edit,
    KeyRound,
    Lock,
    MailCheck,
    PlusCircle,
    ShieldCheck,
} from 'lucide-react';
import { LoginHistory, PageProps, Transaction, User, Wallet } from '@/types';
import { FormEventHandler, useEffect, useState } from 'react';

type UserStats = {
    totalDeposits: number;
    totalWithdrawals: number;
    totalTransfersSent: number;
    totalTransfersReceived: number;
    pendingTransactions: number;
};

type SecuritySnapshot = {
    hasTransactionPin: boolean;
    emailVerified: boolean;
    failedLogins: number;
    successfulLogins: number;
    lastLoginAt?: string | null;
};

type ShowProps = PageProps & {
    user: User;
    wallet: (Wallet & { balance: number | string; ledger_balance: number | string }) | null;
    recentTransactions: Transaction[];
    loginHistory: LoginHistory[];
    stats: UserStats;
    security: SecuritySnapshot;
    supportedCurrencies?: string[];
};

const normalizeMoney = (value: number | string | null | undefined) => {
    if (value === null || value === undefined) {
        return 0;
    }

    const numeric = typeof value === 'string' ? parseFloat(value) : value;
    return Number.isFinite(numeric) ? Number(numeric) : 0;
};

const formatCurrency = (amount: number | string | null | undefined, currencyCode?: string) => {
    const cents = normalizeMoney(amount);
    const code = currencyCode?.toUpperCase() || 'USD';
    const units = cents / 100;
    const formattedUnits = units.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

    return `${code} ${formattedUnits}`;
};

const formatDateTime = (value?: string | null) => {
    if (!value) {
        return 'Not available';
    }

    const parsed = new Date(value);
    if (Number.isNaN(parsed.getTime())) {
        return 'Not available';
    }

    return parsed.toLocaleString();
};

const getAccountStatusClass = (status?: string | null) => {
    switch (status) {
        case 'active':
            return 'border-emerald-500/30 bg-emerald-500/10 text-emerald-300';
        case 'suspended':
            return 'border-amber-500/30 bg-amber-500/10 text-amber-200';
        case 'locked':
            return 'border-rose-500/30 bg-rose-500/10 text-rose-200';
        case 'pending':
            return 'border-sky-500/30 bg-sky-500/10 text-sky-200';
        default:
            return 'border-slate-700 bg-slate-800 text-slate-300';
    }
};

const getWalletStatusClass = (status?: string | null) => {
    switch (status) {
        case 'active':
            return 'border-emerald-500/40 bg-emerald-500/10 text-emerald-200';
        case 'frozen':
            return 'border-amber-500/40 bg-amber-500/10 text-amber-200';
        case 'closed':
            return 'border-rose-500/40 bg-rose-500/10 text-rose-200';
        default:
            return 'border-slate-700 bg-slate-800 text-slate-300';
    }
};

const getTransactionStatusClass = (status: string) => {
    switch (status) {
        case 'completed':
            return 'border-emerald-500/30 bg-emerald-500/10 text-emerald-200';
        case 'pending':
            return 'border-amber-500/30 bg-amber-500/10 text-amber-200';
        case 'failed':
        case 'cancelled':
            return 'border-rose-500/30 bg-rose-500/10 text-rose-200';
        case 'reversed':
            return 'border-sky-500/30 bg-sky-500/10 text-sky-200';
        default:
            return 'border-slate-700 bg-slate-800 text-slate-300';
    }
};

const getLoginStatusClass = (success: boolean) =>
    success
        ? 'border-emerald-500/30 bg-emerald-500/10 text-emerald-200'
        : 'border-rose-500/30 bg-rose-500/10 text-rose-200';

const getTransactionTypeAccent = (type: string) => {
    switch (type) {
        case 'deposit':
            return 'text-emerald-300';
        case 'withdrawal':
            return 'text-rose-300';
        case 'transfer':
            return 'text-sky-300';
        default:
            return 'text-slate-300';
    }
};

export default function Show({
    user,
    wallet,
    recentTransactions = [],
    loginHistory = [],
    stats,
    security,
    supportedCurrencies = [],
}: ShowProps) {
    const [copied, setCopied] = useState(false);

    const currencyCode = (wallet?.currency || user.preferred_currency || 'USD').toUpperCase();
    const currencyOptions = Array.from(
        new Set([currencyCode, ...supportedCurrencies.map((code) => code.toUpperCase())]),
    );
    const formatMoney = (value: number | string | null | undefined) => formatCurrency(value, currencyCode);

    const fundingForm = useForm({
        amount: '',
        description: '',
        reference: '',
        notify_user: true,
    });

    const currencyForm = useForm({
        preferred_currency: currencyCode,
    });

    // Format date for input field (YYYY-MM-DD)
    const formatDateForInput = (dateString?: string | null) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        if (Number.isNaN(date.getTime())) return '';
        return date.toISOString().split('T')[0];
    };

    const createdAtForm = useForm({
        created_at: formatDateForInput(user.created_at),
    });

    useEffect(() => {
        currencyForm.setData('preferred_currency', currencyCode);
    }, [currencyCode]);

    const submitCurrency: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();
        currencyForm.patch(`/admin/users/${user.id}/currency`, {
            preserveScroll: true,
            onSuccess: () => currencyForm.clearErrors(),
        });
    };

    const submitCreatedAt: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();
        createdAtForm.patch(`/admin/users/${user.id}/created-at`, {
            preserveScroll: true,
            onSuccess: () => {
                createdAtForm.clearErrors();
                router.reload({ only: ['user'] });
            },
        });
    };

    const handleCopy = async (text: string) => {
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
            } else {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
            }
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        } catch (err) {
            console.error('Failed to copy text: ', err);
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        }
    };

    const handleSuspend = () => {
        if (confirm('Are you sure you want to suspend this user?')) {
            router.post(`/admin/users/${user.id}/suspend`, { reason: 'Admin action' }, { preserveScroll: true });
        }
    };

    const handleActivate = () => {
        router.post(`/admin/users/${user.id}/activate`, {}, { preserveScroll: true });
    };

    const handleLock = () => {
        if (confirm('Are you sure you want to lock this user?')) {
            router.post(`/admin/users/${user.id}/lock`, { reason: 'Admin action' }, { preserveScroll: true });
        }
    };

    const submitFunding: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();
        fundingForm.post(`/admin/users/${user.id}/fund`, {
            preserveScroll: true,
            onSuccess: () => fundingForm.reset(),
        });
    };

    const walletBalance = formatMoney(wallet?.balance ?? user.balance ?? 0);
    const ledgerBalance = formatMoney(wallet?.ledger_balance ?? 0);
    const statsData = {
        totalDeposits: stats?.totalDeposits ?? 0,
        totalWithdrawals: stats?.totalWithdrawals ?? 0,
        totalTransfersSent: stats?.totalTransfersSent ?? 0,
        totalTransfersReceived: stats?.totalTransfersReceived ?? 0,
        pendingTransactions: stats?.pendingTransactions ?? 0,
    };

    const securityData: SecuritySnapshot = security ?? {
        hasTransactionPin: Boolean(user.has_transaction_pin),
        emailVerified: Boolean(user.email_verified_at),
        failedLogins: 0,
        successfulLogins: 0,
        lastLoginAt: null,
    };

    const lastLoginDisplay = formatDateTime(securityData.lastLoginAt);

    return (
        <AdminLayout>
            <Head title={`User: ${user.name}`} />

            <div className="space-y-6">
                <div>
                    <Link
                        href="/admin/users"
                        className="mb-4 inline-flex items-center text-sm text-slate-400 transition hover:text-slate-50"
                    >
                        <ArrowLeft className="mr-1 h-4 w-4" />
                        Back to Users
                    </Link>
                    <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 className="text-3xl font-bold text-slate-50">{user.name}</h1>
                            <div className="mt-2 flex flex-wrap items-center gap-2 text-sm text-slate-400">
                                <span>{user.email}</span>
                                {user.phone && (
                                    <>
                                        <span className="text-slate-600">•</span>
                                        <span>{user.phone}</span>
                                    </>
                                )}
                                <span className="text-slate-600">•</span>
                                <span>Joined {formatDateTime(user.created_at)}</span>
                            </div>
                        </div>
                        <div className="flex flex-wrap items-center gap-3">
                            <Badge
                                variant="outline"
                                className={`capitalize ${getAccountStatusClass(user.status)}`}
                            >
                                {user.status || 'active'}
                            </Badge>
                            <Badge
                                variant="outline"
                                className={`capitalize ${
                                    user.is_admin
                                        ? 'border-sky-500/30 bg-sky-500/10 text-sky-200'
                                        : 'border-slate-700 bg-slate-800 text-slate-300'
                                }`}
                            >
                                {user.is_admin ? 'Admin' : 'User'}
                            </Badge>
                            <Link href={`/admin/users/${user.id}/edit`}>
                                <Button className="bg-slate-700 hover:bg-slate-600">
                                    <Edit className="mr-2 h-4 w-4" />
                                    Edit
                                </Button>
                            </Link>
                        </div>
                    </div>
                </div>

                <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <Card className="border-slate-800 bg-slate-900">
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium text-slate-400">Total Deposits</CardTitle>
                            <ArrowDownLeft className="h-4 w-4 text-emerald-300" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-semibold text-slate-50">
                                {formatMoney(statsData.totalDeposits)}
                            </div>
                            <p className="mt-1 text-xs text-slate-500">Completed admin and user deposits</p>
                        </CardContent>
                    </Card>

                    <Card className="border-slate-800 bg-slate-900">
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium text-slate-400">Total Withdrawals</CardTitle>
                            <ArrowUpRight className="h-4 w-4 text-rose-300" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-semibold text-slate-50">
                                {formatMoney(statsData.totalWithdrawals)}
                            </div>
                            <p className="mt-1 text-xs text-slate-500">Completed withdrawals by this user</p>
                        </CardContent>
                    </Card>

                    <Card className="border-slate-800 bg-slate-900">
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium text-slate-400">Transfers Sent</CardTitle>
                            <Activity className="h-4 w-4 text-sky-300" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-semibold text-slate-50">
                                {formatMoney(statsData.totalTransfersSent)}
                            </div>
                            <p className="mt-1 text-xs text-slate-500">Includes internal transfers</p>
                        </CardContent>
                    </Card>

                    <Card className="border-slate-800 bg-slate-900">
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium text-slate-400">Pending Actions</CardTitle>
                            <Clock className="h-4 w-4 text-amber-300" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-semibold text-slate-50">
                                {statsData.pendingTransactions}
                            </div>
                            <p className="mt-1 text-xs text-slate-500">Transactions awaiting admin action</p>
                        </CardContent>
                    </Card>
                </div>

                <div className="grid gap-4 lg:grid-cols-3">
                    <Card className="border-slate-800 bg-slate-900 lg:col-span-2">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Profile Overview</CardTitle>
                            <CardDescription className="text-slate-400">
                                Quick glance at key customer information
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="grid gap-6 md:grid-cols-2">
                                <div className="space-y-4">
                                    <div>
                                        <p className="text-sm text-slate-400">Full Name</p>
                                        <p className="text-slate-50 font-medium">{user.name}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-slate-400">Email Address</p>
                                        <p className="text-slate-50 font-medium">{user.email}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-slate-400">Account Type</p>
                                        <Badge
                                            variant="outline"
                                            className="border-slate-700 bg-slate-800 text-slate-300 capitalize"
                                        >
                                            {user.account_type || 'savings'}
                                        </Badge>
                                    </div>
                                    <div>
                                        <p className="text-sm text-slate-400">Preferred Currency</p>
                                        <Badge
                                            variant="outline"
                                            className="border-slate-700 bg-slate-800 text-slate-300 uppercase"
                                        >
                                            {currencyCode}
                                        </Badge>
                                    </div>
                                    <div>
                                        <p className="text-sm text-slate-400">Transaction PIN</p>
                                        <Badge
                                            variant="outline"
                                            className={
                                                securityData.hasTransactionPin
                                                    ? 'border-emerald-500/40 bg-emerald-500/10 text-emerald-200'
                                                    : 'border-amber-500/40 bg-amber-500/10 text-amber-200'
                                            }
                                        >
                                            {securityData.hasTransactionPin ? 'Set' : 'Not set'}
                                        </Badge>
                                    </div>
                                </div>

                                <div className="space-y-4">
                                    <div>
                                        <p className="text-sm text-slate-400">Account Number</p>
                                        <div className="flex items-center gap-2">
                                            <p className="font-mono text-lg font-semibold text-slate-50">
                                                {wallet?.account_number || user.account_number || 'Not assigned'}
                                            </p>
                                            {(wallet?.account_number || user.account_number) && (
                                                <Button
                                                    size="sm"
                                                    variant="ghost"
                                                    onClick={() =>
                                                        handleCopy(wallet?.account_number || user.account_number || '')
                                                    }
                                                    className="h-7 w-7 p-0 hover:bg-slate-800"
                                                >
                                                    {copied ? (
                                                        <Check className="h-4 w-4 text-emerald-400" />
                                                    ) : (
                                                        <Copy className="h-4 w-4 text-slate-400" />
                                                    )}
                                                </Button>
                                            )}
                                        </div>
                                    </div>
                                    <div>
                                        <p className="text-sm text-slate-400">Wallet Balance</p>
                                        <p className="text-lg font-semibold text-slate-50">{walletBalance}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-slate-400">Ledger Balance</p>
                                        <p className="text-lg font-semibold text-slate-50">{ledgerBalance}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-slate-400">Last Login</p>
                                        <p className="text-slate-50">{lastLoginDisplay}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="border-slate-800 bg-slate-900">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Account Actions</CardTitle>
                            <CardDescription className="text-slate-400">
                                Administrative controls for this user
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-3">
                            {user.status !== 'active' && (
                                <Button
                                    onClick={handleActivate}
                                    className="w-full justify-start border border-emerald-500/40 bg-emerald-500/10 text-emerald-200 hover:bg-emerald-500/20"
                                    variant="outline"
                                >
                                    <CheckCircle className="mr-2 h-4 w-4" />
                                    Activate Account
                                </Button>
                            )}
                            {user.status !== 'suspended' && (
                                <Button
                                    onClick={handleSuspend}
                                    className="w-full justify-start border border-amber-500/40 bg-amber-500/10 text-amber-200 hover:bg-amber-500/20"
                                    variant="outline"
                                >
                                    <Ban className="mr-2 h-4 w-4" />
                                    Suspend Account
                                </Button>
                            )}
                            {user.status !== 'locked' && (
                                <Button
                                    onClick={handleLock}
                                    className="w-full justify-start border border-rose-500/40 bg-rose-500/10 text-rose-200 hover:bg-rose-500/20"
                                    variant="outline"
                                >
                                    <Lock className="mr-2 h-4 w-4" />
                                    Lock Account
                                </Button>
                            )}
                            <div className="rounded-lg border border-slate-800 bg-slate-950/60 p-4">
                                <p className="text-sm font-semibold text-slate-200">Security Snapshot</p>
                                <div className="mt-3 space-y-2 text-xs text-slate-400">
                                    <div className="flex items-center justify-between">
                                        <span className="flex items-center gap-2">
                                            <ShieldCheck className="h-4 w-4 text-emerald-300" />
                                            Successful logins
                                        </span>
                                        <span className="font-medium text-slate-200">{securityData.successfulLogins}</span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="flex items-center gap-2">
                                            <KeyRound className="h-4 w-4 text-amber-300" />
                                            Failed attempts
                                        </span>
                                        <span className="font-medium text-slate-200">{securityData.failedLogins}</span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="flex items-center gap-2">
                                            <MailCheck className="h-4 w-4 text-sky-300" />
                                            Email verified
                                        </span>
                                        <span className="font-medium text-slate-200">
                                            {securityData.emailVerified ? 'Yes' : 'No'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                </div>

                <div className="grid gap-4 lg:grid-cols-3">
                    <Card className="border-slate-800 bg-slate-900 lg:col-span-2">
                        <CardHeader className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div>
                                <CardTitle className="text-slate-50">Wallet Overview</CardTitle>
                                <CardDescription className="text-slate-400">
                                    Real-time balances and account health
                                </CardDescription>
                            </div>
                            <Badge
                                variant="outline"
                                className={`capitalize ${getWalletStatusClass(wallet?.status)}`}
                            >
                                {wallet?.status ?? 'active'}
                            </Badge>
                        </CardHeader>
                        <CardContent>
                            <div className="grid gap-4 md:grid-cols-2">
                                <div className="rounded-lg border border-slate-800 bg-slate-950/60 p-4">
                                    <p className="text-xs uppercase tracking-wide text-slate-500">Available balance</p>
                                    <p className="mt-2 text-2xl font-semibold text-slate-50">{walletBalance}</p>
                                    <p className="mt-1 text-xs text-slate-500">
                                        Updated {formatDateTime(wallet?.updated_at)}
                                    </p>
                                </div>
                                <div className="rounded-lg border border-slate-800 bg-slate-950/60 p-4">
                                    <p className="text-xs uppercase tracking-wide text-slate-500">Ledger balance</p>
                                    <p className="mt-2 text-2xl font-semibold text-slate-50">{ledgerBalance}</p>
                                    <p className="mt-1 text-xs text-slate-500">Includes pending settlements</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <div className="flex flex-col gap-4">
                        <Card className="border-slate-800 bg-slate-900">
                            <CardHeader>
                                <CardTitle className="text-slate-50">Fund User Balance</CardTitle>
                                <CardDescription className="text-slate-400">
                                    Credit this wallet instantly with an admin top-up
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <form onSubmit={submitFunding} className="space-y-5">
                                    <div>
                                        <label
                                            htmlFor="amount"
                                            className="mb-2 block text-sm font-medium text-slate-300"
                                        >
                                            Amount ({currencyCode})
                                        </label>
                                        <div className="relative">
                                            <span className="pointer-events-none absolute inset-y-0 left-3 flex items-center text-xs font-semibold uppercase text-slate-500">
                                                {currencyCode}
                                            </span>
                                            <input
                                                id="amount"
                                                type="number"
                                                step="0.01"
                                                min="1"
                                                value={fundingForm.data.amount}
                                                onChange={(e) => fundingForm.setData('amount', e.target.value)}
                                                className="h-11 w-full rounded-lg border border-slate-700 bg-slate-950/60 pl-16 pr-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/40"
                                                placeholder="250.00"
                                            />
                                        </div>
                                        {fundingForm.errors.amount && (
                                            <p className="mt-2 text-xs text-rose-300">{fundingForm.errors.amount}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label
                                            htmlFor="reference"
                                            className="mb-2 block text-sm font-medium text-slate-300"
                                        >
                                            Reference (optional)
                                        </label>
                                        <input
                                            id="reference"
                                            type="text"
                                            value={fundingForm.data.reference}
                                            onChange={(e) => fundingForm.setData('reference', e.target.value)}
                                            className="h-11 w-full rounded-lg border border-slate-700 bg-slate-950/60 px-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/40"
                                            placeholder="FND-2025-001"
                                        />
                                        {fundingForm.errors.reference && (
                                            <p className="mt-2 text-xs text-rose-300">{fundingForm.errors.reference}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label
                                            htmlFor="description"
                                            className="mb-2 block text-sm font-medium text-slate-300"
                                        >
                                            Admin note (optional)
                                        </label>
                                        <textarea
                                            id="description"
                                            value={fundingForm.data.description}
                                            onChange={(e) => fundingForm.setData('description', e.target.value)}
                                            rows={3}
                                            className="w-full rounded-lg border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/40"
                                            placeholder="Reason for credit or internal note"
                                        />
                                        {fundingForm.errors.description && (
                                            <p className="mt-2 text-xs text-rose-300">{fundingForm.errors.description}</p>
                                        )}
                                    </div>

                                    <label className="flex items-center gap-2 text-sm text-slate-300">
                                        <input
                                            type="checkbox"
                                            checked={fundingForm.data.notify_user}
                                            onChange={(e) => fundingForm.setData('notify_user', e.target.checked)}
                                            className="h-4 w-4 rounded border-slate-600 bg-slate-950 text-primary-500 focus:ring-primary-500/60"
                                        />
                                        Email user about this credit
                                    </label>

                                    <Button
                                        type="submit"
                                        className="w-full justify-center bg-primary-600 hover:bg-primary-700"
                                        disabled={fundingForm.processing}
                                    >
                                        <PlusCircle className="mr-2 h-4 w-4" />
                                        {fundingForm.processing ? 'Funding...' : 'Fund Balance'}
                                    </Button>
                                </form>
                            </CardContent>
                        </Card>

                        <Card className="border-slate-800 bg-slate-900">
                            <CardHeader>
                                <CardTitle className="text-slate-50">Base Currency</CardTitle>
                                <CardDescription className="text-slate-400">
                                    Control how balances and statements display
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-4">
                                    <div>
                                        <p className="text-sm text-slate-400">Current currency</p>
                                        <Badge
                                            variant="outline"
                                            className="mt-2 border-slate-700 bg-slate-800 text-slate-200 uppercase"
                                        >
                                            {currencyCode}
                                        </Badge>
                                    </div>

                                    <form onSubmit={submitCurrency} className="space-y-3">
                                        <div>
                                            <label
                                                htmlFor="preferred_currency"
                                                className="mb-2 block text-sm font-medium text-slate-300"
                                            >
                                                Set base currency
                                            </label>
                                            <select
                                                id="preferred_currency"
                                                value={currencyForm.data.preferred_currency}
                                                onChange={(event) =>
                                                    currencyForm.setData('preferred_currency', event.target.value.toUpperCase())
                                                }
                                                className="h-11 w-full rounded-lg border border-slate-700 bg-slate-950/60 px-3 text-sm text-slate-100 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/40"
                                            >
                                                {currencyOptions.map((code) => (
                                                    <option key={code} value={code}>
                                                        {code}
                                                    </option>
                                                ))}
                                            </select>
                                            {currencyForm.errors.preferred_currency && (
                                                <p className="mt-2 text-xs text-rose-300">
                                                    {currencyForm.errors.preferred_currency}
                                                </p>
                                            )}
                                        </div>

                                        <Button
                                            type="submit"
                                            className="w-full justify-center bg-primary-600 hover:bg-primary-700"
                                            disabled={currencyForm.processing}
                                        >
                                            {currencyForm.processing ? 'Updating...' : 'Update Currency'}
                                        </Button>
                                    </form>
                                </div>
                            </CardContent>
                        </Card>

                        <Card className="border-slate-800 bg-slate-900">
                            <CardHeader>
                                <CardTitle className="text-slate-50">Account Created Date</CardTitle>
                                <CardDescription className="text-slate-400">
                                    Update the account creation date
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-4">
                                    <div>
                                        <p className="text-sm text-slate-400">Current created date</p>
                                        <p className="mt-2 text-slate-200">{formatDateTime(user.created_at)}</p>
                                    </div>

                                    <form onSubmit={submitCreatedAt} className="space-y-3">
                                        <div>
                                            <label
                                                htmlFor="created_at"
                                                className="mb-2 block text-sm font-medium text-slate-300"
                                            >
                                                New created date
                                            </label>
                                            <input
                                                id="created_at"
                                                type="date"
                                                value={createdAtForm.data.created_at}
                                                onChange={(e) =>
                                                    createdAtForm.setData('created_at', e.target.value)
                                                }
                                                className="h-11 w-full rounded-lg border border-slate-700 bg-slate-950/60 px-3 text-sm text-slate-100 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/40"
                                                required
                                            />
                                            {createdAtForm.errors.created_at && (
                                                <p className="mt-2 text-xs text-rose-300">
                                                    {createdAtForm.errors.created_at}
                                                </p>
                                            )}
                                        </div>

                                        <Button
                                            type="submit"
                                            className="w-full justify-center bg-primary-600 hover:bg-primary-700"
                                            disabled={createdAtForm.processing}
                                        >
                                            {createdAtForm.processing ? 'Updating...' : 'Update Created Date'}
                                        </Button>
                                    </form>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>

                <div className="grid gap-4 lg:grid-cols-2">
                    <Card className="border-slate-800 bg-slate-900">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Recent Transactions</CardTitle>
                            <CardDescription className="text-slate-400">
                                Last 10 activities involving this user
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            {recentTransactions.length ? (
                                <div className="space-y-3">
                                    {recentTransactions.map((transaction) => (
                                        <div
                                            key={transaction.id}
                                            className="flex flex-col gap-2 rounded-lg border border-slate-800 bg-slate-950/60 p-4 md:flex-row md:items-center md:justify-between"
                                        >
                                            <div>
                                                <div className="flex items-center gap-3">
                                                    <p
                                                        className={`text-sm font-semibold capitalize ${getTransactionTypeAccent(
                                                            transaction.type,
                                                        )}`}
                                                    >
                                                        {transaction.type}
                                                    </p>
                                                    <Badge
                                                        variant="outline"
                                                        className={`text-xs capitalize ${getTransactionStatusClass(
                                                            transaction.status,
                                                        )}`}
                                                    >
                                                        {transaction.status}
                                                    </Badge>
                                                </div>
                                                <p className="mt-1 text-sm text-slate-200">
                                                    {transaction.description || 'No description provided'}
                                                </p>
                                                <p className="text-xs text-slate-500">
                                                    {formatDateTime(transaction.created_at)}
                                                </p>
                                            </div>
                                            <div className="text-right">
                                                <p className="text-base font-semibold text-slate-50">
                                                    {formatMoney(transaction.amount)}
                                                </p>
                                                <p className="mt-1 text-xs font-mono text-slate-500">
                                                    {transaction.reference}
                                                </p>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="rounded-lg border border-dashed border-slate-800 bg-slate-950/40 p-6 text-center text-sm text-slate-400">
                                    No recent transactions to display.
                                </div>
                            )}
                        </CardContent>
                    </Card>

                    <Card className="border-slate-800 bg-slate-900">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Login Activity</CardTitle>
                            <CardDescription className="text-slate-400">
                                Latest access attempts for this account
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            {loginHistory.length ? (
                                <div className="space-y-3">
                                    {loginHistory.map((entry) => (
                                        <div
                                            key={entry.id}
                                            className="flex flex-col gap-2 rounded-lg border border-slate-800 bg-slate-950/60 p-4 md:flex-row md:items-center md:justify-between"
                                        >
                                            <div>
                                                <p className="text-sm font-semibold text-slate-200">
                                                    {entry.location || 'Unknown location'}
                                                </p>
                                                <div className="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                                    <span>{entry.device || 'Device unknown'}</span>
                                                    <span className="text-slate-600">•</span>
                                                    <span>{entry.platform || 'Platform N/A'}</span>
                                                    <span className="text-slate-600">•</span>
                                                    <span>{entry.ip_address || 'IP N/A'}</span>
                                                </div>
                                                <p className="text-xs text-slate-500">
                                                    {entry.formatted_created_at || formatDateTime(entry.created_at)}
                                                </p>
                                            </div>
                                            <Badge
                                                variant="outline"
                                                className={`text-xs ${getLoginStatusClass(entry.login_successful)}`}
                                            >
                                                {entry.login_successful ? 'Success' : 'Failed'}
                                            </Badge>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="rounded-lg border border-dashed border-slate-800 bg-slate-950/40 p-6 text-center text-sm text-slate-400">
                                    No login activity recorded yet.
                                </div>
                            )}
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AdminLayout>
    );
}
