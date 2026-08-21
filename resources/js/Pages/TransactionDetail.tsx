import { useState } from 'react';
import { Link } from '@inertiajs/react';
import MobileLayout from '@/Layouts/MobileLayout';
import { PageProps, Transaction } from '@/types';
import { copyToClipboard } from './Deposit';

interface TransactionDetailPageProps extends PageProps {
    transaction: Transaction;
}

export default function TransactionDetail({ auth, transaction }: TransactionDetailPageProps) {
    const viewParam = auth.user.is_admin ? '?view=client' : '';
    const [copied, setCopied] = useState(false);

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
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        }).format(new Date(dateString));
    };

    const isCredit =
        transaction.type === 'deposit' ||
        (transaction.type === 'transfer' && transaction.recipient_id === auth.user.id);

    const isTransfer = transaction.type === 'transfer';
    const isReceived = isTransfer && transaction.recipient_id === auth.user.id;
    const counterparty = isTransfer ? (isReceived ? transaction.user : transaction.recipient) : undefined;

    const getTypeIcon = () => {
        switch (transaction.type) {
            case 'deposit':
                return (
                    <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                    </svg>
                );
            case 'withdrawal':
                return (
                    <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 12H4" />
                    </svg>
                );
            case 'transfer':
                return (
                    <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
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
            reversed: 'bg-purple-100 text-purple-700 border-purple-200 dark:bg-purple-500/20 dark:text-purple-400 dark:border-purple-500/40',
        };

        return (
            <span className={`inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold capitalize ${styles[status] || styles.cancelled}`}>
                {status}
            </span>
        );
    };

    const handleCopyReference = async () => {
        const ok = await copyToClipboard(transaction.reference);
        if (ok) {
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        }
    };

    const metadataLabels: Record<string, string> = {
        method_name: 'Payment Method',
        processing_time: 'Processing Time',
        crypto_currency: 'Cryptocurrency',
        payment_reference: 'Payment Reference',
        user_notes: 'Notes',
        code: 'Transaction Code',
    };

    const metadataEntries = Object.entries(transaction.metadata || {}).filter(
        ([key]) => metadataLabels[key]
    );

    return (
        <MobileLayout user={auth.user} title="Transaction Details" currentRoute="transactions">
            <div className="px-4 pt-4 pb-3 flex items-center justify-between border-b border-slate-200 dark:border-slate-800 mb-2">
                <Link
                    href={"/transactions" + viewParam}
                    className="w-9 h-9 -ml-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-slate-50 transition-colors"
                    aria-label="Back to transactions"
                >
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h1 className="text-base font-semibold text-slate-900 dark:text-slate-50">Transaction Details</h1>
                <div className="w-9" />
            </div>

            <div className="px-4 py-6 space-y-4">
                {/* Summary */}
                <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 text-center">
                    <div
                        className={`w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center ${
                            isCredit ? 'bg-green-500/10 text-green-600 dark:text-green-500' : 'bg-red-500/10 text-red-600 dark:text-red-500'
                        }`}
                    >
                        {getTypeIcon()}
                    </div>
                    <p className="text-sm text-slate-500 dark:text-slate-400 capitalize">
                        {isTransfer ? (isReceived ? 'Transfer Received' : 'Transfer Sent') : transaction.type}
                    </p>
                    <p className={`text-3xl font-bold mt-1 ${isCredit ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500'}`}>
                        {isCredit ? '+' : '-'}{formatCurrency(transaction.amount)}
                    </p>
                    <div className="mt-3 flex justify-center">
                        {getStatusBadge(transaction.status)}
                    </div>
                </div>

                {/* Details */}
                <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl divide-y divide-slate-100 dark:divide-slate-800 overflow-hidden">
                    <div className="flex items-center justify-between px-5 py-3.5">
                        <span className="text-sm text-slate-500 dark:text-slate-400">Reference</span>
                        <button
                            type="button"
                            onClick={handleCopyReference}
                            className="flex items-center gap-1.5 text-sm font-mono font-medium text-slate-900 dark:text-slate-50 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                        >
                            {transaction.reference}
                            <svg className="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {copied ? (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                ) : (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                )}
                            </svg>
                        </button>
                    </div>

                    <div className="flex items-center justify-between px-5 py-3.5">
                        <span className="text-sm text-slate-500 dark:text-slate-400">Date & Time</span>
                        <span className="text-sm text-slate-900 dark:text-slate-50">{formatDate(transaction.created_at)}</span>
                    </div>

                    <div className="flex items-center justify-between px-5 py-3.5">
                        <span className="text-sm text-slate-500 dark:text-slate-400">Type</span>
                        <span className="text-sm text-slate-900 dark:text-slate-50 capitalize">{transaction.type}</span>
                    </div>

                    {isTransfer && counterparty?.name && (
                        <div className="flex items-center justify-between px-5 py-3.5">
                            <span className="text-sm text-slate-500 dark:text-slate-400">{isReceived ? 'From' : 'To'}</span>
                            <span className="text-sm text-slate-900 dark:text-slate-50">{counterparty.name}</span>
                        </div>
                    )}

                    {!!transaction.fee && (
                        <div className="flex items-center justify-between px-5 py-3.5">
                            <span className="text-sm text-slate-500 dark:text-slate-400">Fee</span>
                            <span className="text-sm text-slate-900 dark:text-slate-50">{formatCurrency(transaction.fee)}</span>
                        </div>
                    )}

                    {metadataEntries.map(([key, value]) => (
                        <div key={key} className="flex items-center justify-between px-5 py-3.5 gap-4">
                            <span className="text-sm text-slate-500 dark:text-slate-400 shrink-0">{metadataLabels[key]}</span>
                            <span className="text-sm text-slate-900 dark:text-slate-50 text-right break-all">{String(value)}</span>
                        </div>
                    ))}

                    {transaction.description && (
                        <div className="px-5 py-3.5">
                            <span className="text-sm text-slate-500 dark:text-slate-400 block mb-1">Description</span>
                            <span className="text-sm text-slate-900 dark:text-slate-50">{transaction.description}</span>
                        </div>
                    )}
                </div>
            </div>
        </MobileLayout>
    );
}
