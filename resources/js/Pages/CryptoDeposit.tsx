import { FormEvent, useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import { QRCodeSVG } from 'qrcode.react';
import MobileLayout from '@/Layouts/MobileLayout';
import { Button } from '@/Components/ui/button';
import { PageProps, Wallet } from '@/types';
import { copyToClipboard } from './Deposit';

interface CryptoCurrency {
    name: string;
    address: string;
    network: string;
}

interface CryptoMethod {
    name: string;
    min_amount?: number;
    processing_time?: string;
    requires_reference?: boolean;
    currencies?: Record<string, CryptoCurrency>;
    notes?: string[];
}

interface CryptoDepositPageProps extends PageProps {
    wallet?: Wallet;
    cryptoMethod?: CryptoMethod | null;
}

type CryptoStep = 'select' | 'address' | 'form' | 'confirm' | 'processing' | 'success';

export default function CryptoDeposit({ auth, wallet, cryptoMethod, flash, supportEmail }: CryptoDepositPageProps) {
    const viewParam = auth.user.is_admin ? '?view=client' : '';
    const [step, setStep] = useState<CryptoStep>('select');
    const [selectedCurrency, setSelectedCurrency] = useState<string>('');
    const [addressCopied, setAddressCopied] = useState(false);

    const form = useForm({
        deposit_type: 'payment',
        method: 'crypto',
        amount: '',
        payment_reference: '',
        crypto_currency: '',
        notes: '',
    });

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: wallet?.currency || 'USD',
            minimumFractionDigits: 2,
        }).format(amount / 100);
    };

    const currencies = cryptoMethod?.currencies || {};
    const currency = selectedCurrency ? currencies[selectedCurrency] : undefined;

    const handleSelectCurrency = (key: string) => {
        setSelectedCurrency(key);
        form.setData('crypto_currency', key);
        setStep('address');
    };

    const handleCopyAddress = async () => {
        if (!currency?.address) return;
        const ok = await copyToClipboard(currency.address);
        if (ok) {
            setAddressCopied(true);
            setTimeout(() => setAddressCopied(false), 2000);
        }
    };

    const handleContinueToForm = () => setStep('form');

    const handleFormSubmit = (e: FormEvent) => {
        e.preventDefault();
        if (!form.data.amount) return;
        if (cryptoMethod?.requires_reference !== false && !form.data.payment_reference) return;
        setStep('confirm');
    };

    const handleConfirm = () => {
        setStep('processing');
        form.post(route('deposit.store'), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                setStep('success');
                setTimeout(() => {
                    window.location.href = route('transactions');
                }, 3000);
            },
            onError: () => setStep('form'),
        });
    };

    const handleBack = () => {
        if (step === 'confirm') setStep('form');
        else if (step === 'form') setStep('address');
        else if (step === 'address') {
            setStep('select');
            setSelectedCurrency('');
        }
    };

    const coinBadgeColor = (key: string) => {
        const palette: Record<string, string> = {
            BTC: 'bg-amber-500/10 text-amber-600 dark:text-amber-500',
            ETH: 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-500',
            USDT: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-500',
        };
        return palette[key] || 'bg-purple-500/10 text-purple-600 dark:text-purple-500';
    };

    // Step 1: Select currency
    const renderSelectStep = () => (
        <div className="space-y-3">
            <p className="text-sm text-slate-500 dark:text-slate-400 px-1">
                Choose the cryptocurrency you want to deposit
            </p>
            {Object.entries(currencies).map(([key, curr]) => (
                <button
                    key={key}
                    type="button"
                    onClick={() => handleSelectCurrency(key)}
                    className="w-full flex items-center justify-between p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl hover:border-blue-400 dark:hover:border-blue-500 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors"
                >
                    <div className="flex items-center space-x-3">
                        <div className={`w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold ${coinBadgeColor(key)}`}>
                            {key.slice(0, 3)}
                        </div>
                        <div className="text-left">
                            <p className="text-sm font-semibold text-slate-900 dark:text-slate-50">{curr.name}</p>
                            <p className="text-xs text-slate-500 dark:text-slate-400">{curr.network}</p>
                        </div>
                    </div>
                    <svg className="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            ))}

            {Object.keys(currencies).length === 0 && (
                <div className="text-center py-12 px-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl">
                    <p className="text-sm text-slate-500 dark:text-slate-400">No crypto wallets are configured yet.</p>
                </div>
            )}
        </div>
    );

    // Step 2: Wallet address + QR
    const renderAddressStep = () => {
        if (!currency) return null;

        return (
            <div className="space-y-4">
                <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 text-center">
                    <span className={`inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full mb-4 ${coinBadgeColor(selectedCurrency)}`}>
                        {currency.network}
                    </span>
                    <p className="text-base font-semibold text-slate-900 dark:text-slate-50 mb-4">
                        Deposit {currency.name}
                    </p>

                    <div className="mx-auto w-fit rounded-2xl bg-white p-4 shadow-sm border border-slate-200">
                        <QRCodeSVG value={currency.address} size={176} bgColor="#ffffff" fgColor="#0f172a" level="M" />
                    </div>

                    <div className="mt-5 text-left">
                        <p className="text-xs text-slate-500 dark:text-slate-400 mb-1.5">Wallet Address</p>
                        <div className="flex items-center justify-between gap-2 bg-slate-100 dark:bg-slate-800 rounded-xl px-4 py-3">
                            <span className="text-sm font-mono text-slate-900 dark:text-slate-50 break-all">
                                {currency.address}
                            </span>
                        </div>
                        <button
                            type="button"
                            onClick={handleCopyAddress}
                            className="w-full mt-3 rounded-xl bg-slate-900 dark:bg-slate-50 text-white dark:text-slate-900 text-sm font-semibold py-3 hover:opacity-90 transition-opacity"
                        >
                            {addressCopied ? 'Address Copied!' : 'Copy Wallet Address'}
                        </button>
                    </div>
                </div>

                <div className="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900 rounded-2xl p-4">
                    <div className="flex items-start space-x-2">
                        <svg className="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p className="text-xs text-amber-700 dark:text-amber-200">
                            Only send {currency.name} via the {currency.network} network to this address. Sending any other asset or using a different network may result in permanent loss of funds.
                        </p>
                    </div>
                </div>

                <Button
                    type="button"
                    onClick={handleContinueToForm}
                    className="w-full bg-slate-900 dark:bg-blue-600 hover:opacity-90 text-white py-6 text-base font-semibold transition-all"
                >
                    I've Sent the Funds — Continue
                </Button>
            </div>
        );
    };

    // Step 3: Amount + reference form
    const renderFormStep = () => (
        <div className="space-y-4">
            <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
                <p className="text-sm font-semibold text-slate-900 dark:text-slate-50 mb-4">Confirm Your Deposit</p>
                <form onSubmit={handleFormSubmit} className="space-y-4">
                    <div>
                        <label className="text-sm text-slate-600 dark:text-slate-300 mb-2 block">Amount *</label>
                        <div className="relative">
                            <span className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">$</span>
                            <input
                                type="number"
                                step="0.01"
                                min={cryptoMethod?.min_amount ? (cryptoMethod.min_amount / 100).toString() : '10'}
                                value={form.data.amount}
                                onChange={(e) => form.setData('amount', e.target.value)}
                                placeholder="0.00"
                                className="w-full pl-8 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-50 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg"
                                required
                            />
                        </div>
                        {cryptoMethod?.min_amount && (
                            <p className="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                Minimum: {formatCurrency(cryptoMethod.min_amount)}
                            </p>
                        )}
                    </div>

                    {cryptoMethod?.requires_reference !== false && (
                        <div>
                            <label className="text-sm text-slate-600 dark:text-slate-300 mb-2 block">
                                Transaction Hash *
                            </label>
                            <input
                                type="text"
                                value={form.data.payment_reference}
                                onChange={(e) => form.setData('payment_reference', e.target.value)}
                                placeholder="Transaction hash"
                                className="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-50 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                required
                            />
                        </div>
                    )}

                    <div>
                        <label className="text-sm text-slate-600 dark:text-slate-300 mb-2 block">Notes (Optional)</label>
                        <textarea
                            value={form.data.notes}
                            onChange={(e) => form.setData('notes', e.target.value)}
                            placeholder="Any additional information..."
                            rows={3}
                            className="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-50 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                        />
                    </div>

                    {(form.errors.amount || form.errors.payment_reference || flash?.error) && (
                        <div className="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-lg p-3">
                            <div className="text-sm text-red-600 dark:text-red-200 space-y-1">
                                {form.errors.amount && <p>{form.errors.amount}</p>}
                                {form.errors.payment_reference && <p>{form.errors.payment_reference}</p>}
                                {flash?.error && <p>{flash.error}</p>}
                            </div>
                        </div>
                    )}

                    <Button
                        type="submit"
                        className="w-full bg-slate-900 dark:bg-blue-600 hover:opacity-90 text-white py-6 text-base font-semibold transition-all"
                        disabled={!form.data.amount || (cryptoMethod?.requires_reference !== false && !form.data.payment_reference)}
                    >
                        Review Deposit
                    </Button>
                </form>
            </div>
        </div>
    );

    // Step 4: Confirm
    const renderConfirmStep = () => (
        <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4">
            <p className="text-sm font-semibold text-slate-900 dark:text-slate-50">Review & Confirm</p>
            <div className="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 space-y-3">
                <div className="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700">
                    <span className="text-slate-500 dark:text-slate-400 text-sm">Currency</span>
                    <span className="text-slate-900 dark:text-slate-50 font-semibold">{currency?.name}</span>
                </div>
                <div className="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700">
                    <span className="text-slate-500 dark:text-slate-400 text-sm">Amount</span>
                    <span className="text-blue-600 dark:text-blue-400 text-2xl font-bold">
                        ${parseFloat(form.data.amount || '0').toFixed(2)}
                    </span>
                </div>
                {form.data.payment_reference && (
                    <div className="pb-1">
                        <span className="text-slate-500 dark:text-slate-400 text-xs block mb-2">Transaction Hash</span>
                        <p className="text-slate-900 dark:text-slate-50 text-sm bg-white dark:bg-slate-900 p-2 rounded break-all font-mono">
                            {form.data.payment_reference}
                        </p>
                    </div>
                )}
            </div>

            <div className="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900 rounded-xl p-4">
                <p className="text-xs text-amber-700 dark:text-amber-200">
                    Your deposit will be pending until an admin verifies the transaction and credits your account.
                </p>
            </div>

            <div className="space-y-3 pt-1">
                <Button
                    type="button"
                    onClick={handleConfirm}
                    className="w-full bg-slate-900 dark:bg-blue-600 hover:opacity-90 text-white py-6 text-base font-semibold transition-all"
                    disabled={form.processing}
                >
                    {form.processing ? 'Submitting...' : 'Confirm Deposit'}
                </Button>
                <button
                    type="button"
                    onClick={handleBack}
                    className="w-full text-center text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 py-2"
                    disabled={form.processing}
                >
                    Back to Edit
                </button>
            </div>
        </div>
    );

    const renderProcessingStep = () => (
        <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl py-16 text-center">
            <div className="animate-spin rounded-full h-14 w-14 border-b-2 border-blue-500 mx-auto mb-4" />
            <p className="text-slate-900 dark:text-slate-50 font-semibold">Processing</p>
            <p className="text-slate-500 dark:text-slate-400 text-sm mt-1">Please wait...</p>
        </div>
    );

    const renderSuccessStep = () => (
        <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl py-14 px-6 text-center">
            <div className="w-16 h-16 rounded-full bg-amber-500 mx-auto mb-5 flex items-center justify-center">
                <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p className="text-xl font-bold text-slate-900 dark:text-slate-50 mb-1">Request Submitted!</p>
            <p className="text-slate-500 dark:text-slate-400 mb-3">Your deposit is pending verification</p>
            <p className="text-blue-600 dark:text-blue-400 text-3xl font-bold">
                ${parseFloat(form.data.amount || '0').toFixed(2)}
            </p>
            <p className="text-slate-400 dark:text-slate-500 text-xs mt-6">Redirecting to transactions...</p>
        </div>
    );

    return (
        <MobileLayout user={auth.user} title="Crypto Deposit" currentRoute="dashboard">
            <div className="px-4 pt-4 flex items-center justify-between">
                {step === 'select' ? (
                    <Link
                        href={"/deposit" + viewParam}
                        className="p-2 -ml-2 rounded-full text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-50 dark:hover:bg-slate-800 transition-colors"
                        aria-label="Back to Add Money"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m0 0h18" />
                        </svg>
                    </Link>
                ) : (
                    <button
                        type="button"
                        onClick={handleBack}
                        className="p-2 -ml-2 rounded-full text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-50 dark:hover:bg-slate-800 transition-colors"
                        aria-label="Back"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m0 0h18" />
                        </svg>
                    </button>
                )}
                <h1 className="text-base font-semibold text-slate-900 dark:text-slate-50">Crypto Deposit</h1>
                {supportEmail ? (
                    <a
                        href={`mailto:${supportEmail}`}
                        className="p-2 -mr-2 rounded-full text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-50 dark:hover:bg-slate-800 transition-colors"
                        aria-label="Get help"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </a>
                ) : (
                    <div className="w-9" />
                )}
            </div>

            <div className="px-4 py-6 space-y-6">
                {step !== 'select' && step !== 'success' && (
                    <div className="flex justify-center items-center space-x-2 mb-2">
                        <div className={`h-2 w-14 rounded-full transition-all ${step === 'address' ? 'bg-blue-500' : 'bg-slate-200 dark:bg-slate-700'}`} />
                        <div className={`h-2 w-14 rounded-full transition-all ${step === 'form' ? 'bg-blue-500' : 'bg-slate-200 dark:bg-slate-700'}`} />
                        <div className={`h-2 w-14 rounded-full transition-all ${step === 'confirm' || step === 'processing' ? 'bg-blue-500' : 'bg-slate-200 dark:bg-slate-700'}`} />
                    </div>
                )}

                {step === 'select' && renderSelectStep()}
                {step === 'address' && renderAddressStep()}
                {step === 'form' && renderFormStep()}
                {step === 'confirm' && renderConfirmStep()}
                {step === 'processing' && renderProcessingStep()}
                {step === 'success' && renderSuccessStep()}
            </div>
        </MobileLayout>
    );
}
