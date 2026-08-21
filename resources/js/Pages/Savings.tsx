import { FormEvent, useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import MobileLayout from '@/Layouts/MobileLayout';
import { Button } from '@/Components/ui/button';
import { PageProps, Wallet } from '@/types';

interface SavingsProduct {
    key: string;
    name: string;
    shortName: string;
    rate: string;
    description: string;
    balance: number;
}

interface SavingsPageProps extends PageProps {
    wallet?: Wallet;
    products: SavingsProduct[];
}

type SavingsStep = 'list' | 'amount' | 'confirm' | 'processing' | 'success';

const accents: Record<string, string> = {
    hysa: 'from-amber-500 to-orange-500',
    mma: 'from-blue-500 to-indigo-600',
};

export default function Savings({ auth, wallet, products, flash }: SavingsPageProps) {
    const viewParam = auth.user.is_admin ? '?view=client' : '';
    const [step, setStep] = useState<SavingsStep>('list');
    const [selectedKey, setSelectedKey] = useState<string>('');

    const form = useForm({
        product: '',
        amount: '',
    });

    const selectedProduct = products.find((p) => p.key === selectedKey);
    const totalSaved = products.reduce((sum, p) => sum + p.balance, 0);

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: wallet?.currency || 'USD',
            minimumFractionDigits: 2,
        }).format(amount / 100);
    };

    const handleSelectProduct = (key: string) => {
        setSelectedKey(key);
        form.setData('product', key);
        setStep('amount');
    };

    const handleAmountSubmit = (e: FormEvent) => {
        e.preventDefault();
        if (!form.data.amount || parseFloat(form.data.amount) < 10) return;
        setStep('confirm');
    };

    const handleConfirm = () => {
        setStep('processing');
        form.post(route('savings.store'), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                setStep('success');
                setTimeout(() => {
                    setStep('list');
                    setSelectedKey('');
                    form.reset();
                }, 2500);
            },
            onError: () => setStep('amount'),
        });
    };

    const handleBack = () => {
        if (step === 'confirm') setStep('amount');
        else if (step === 'amount') {
            setStep('list');
            setSelectedKey('');
        }
    };

    const renderListStep = () => (
        <div className="space-y-5">
            <div>
                <h1 className="text-lg font-semibold text-slate-900 dark:text-slate-50">Grow Your Savings</h1>
                <p className="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Choose an account that fits how you save.
                </p>
            </div>

            {totalSaved > 0 && (
                <div className="rounded-2xl bg-slate-900 dark:bg-slate-950 border border-slate-800 px-5 py-4 text-white">
                    <p className="text-xs text-slate-400">Total in Savings</p>
                    <p className="text-2xl font-bold mt-0.5">{formatCurrency(totalSaved)}</p>
                </div>
            )}

            <div className="space-y-4">
                {products.map((product) => (
                    <div
                        key={product.key}
                        className="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden"
                    >
                        <div className={`bg-gradient-to-br ${accents[product.key] || 'from-slate-600 to-slate-800'} px-5 py-5 text-white`}>
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-xs font-medium uppercase tracking-wide text-white/80">
                                        {product.shortName}
                                    </p>
                                    <p className="text-base font-semibold mt-0.5">{product.name}</p>
                                </div>
                                <div className="text-right">
                                    <p className="text-3xl font-bold leading-none">{product.rate}</p>
                                    <p className="text-[11px] text-white/80 mt-1">P.A</p>
                                </div>
                            </div>
                        </div>
                        <div className="px-5 py-4">
                            <p className="text-sm text-slate-600 dark:text-slate-300">
                                {product.description}
                            </p>
                            {product.balance > 0 && (
                                <div className="flex items-center justify-between mt-3 text-sm">
                                    <span className="text-slate-500 dark:text-slate-400">Your balance</span>
                                    <span className="font-semibold text-slate-900 dark:text-slate-50">
                                        {formatCurrency(product.balance)}
                                    </span>
                                </div>
                            )}
                            <button
                                type="button"
                                onClick={() => handleSelectProduct(product.key)}
                                className="w-full mt-4 rounded-xl bg-slate-900 dark:bg-blue-600 text-white text-sm font-semibold py-3 hover:opacity-90 transition-opacity"
                            >
                                Add Money
                            </button>
                        </div>
                    </div>
                ))}
            </div>

            <p className="text-xs text-slate-400 dark:text-slate-500 text-center px-4">
                Rates shown are annual percentage yields (P.A) and may change.
            </p>
        </div>
    );

    const renderAmountStep = () => {
        if (!selectedProduct) return null;

        return (
            <div className="space-y-4">
                <div className={`bg-gradient-to-br ${accents[selectedProduct.key] || 'from-slate-600 to-slate-800'} rounded-2xl px-5 py-4 text-white`}>
                    <p className="text-xs font-medium uppercase tracking-wide text-white/80">{selectedProduct.shortName}</p>
                    <p className="text-base font-semibold">{selectedProduct.name}</p>
                    <p className="text-2xl font-bold mt-1">{selectedProduct.rate} <span className="text-xs font-normal text-white/80">P.A</span></p>
                </div>

                <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
                    <form onSubmit={handleAmountSubmit} className="space-y-4">
                        <div>
                            <label className="text-sm text-slate-600 dark:text-slate-300 mb-2 block">Amount to Save *</label>
                            <div className="relative">
                                <span className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">$</span>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="10"
                                    value={form.data.amount}
                                    onChange={(e) => form.setData('amount', e.target.value)}
                                    placeholder="0.00"
                                    className="w-full pl-8 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-50 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg"
                                    required
                                    autoFocus
                                />
                            </div>
                            <p className="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                Available Balance: {formatCurrency(wallet?.balance || 0)}
                            </p>
                        </div>

                        {(form.errors.amount || flash?.error) && (
                            <div className="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-lg p-3">
                                <div className="text-sm text-red-600 dark:text-red-200 space-y-1">
                                    {form.errors.amount && <p>{form.errors.amount}</p>}
                                    {flash?.error && <p>{flash.error}</p>}
                                </div>
                            </div>
                        )}

                        <Button
                            type="submit"
                            className="w-full bg-slate-900 dark:bg-blue-600 hover:opacity-90 text-white py-6 text-base font-semibold transition-all"
                            disabled={!form.data.amount || parseFloat(form.data.amount) < 10}
                        >
                            Continue
                        </Button>
                    </form>
                </div>
            </div>
        );
    };

    const renderConfirmStep = () => {
        if (!selectedProduct) return null;
        const amountCents = Math.round(parseFloat(form.data.amount || '0') * 100);

        return (
            <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-4">
                <p className="text-sm font-semibold text-slate-900 dark:text-slate-50">Confirm Your Deposit</p>
                <div className="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 space-y-3">
                    <div className="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700">
                        <span className="text-slate-500 dark:text-slate-400 text-sm">Account</span>
                        <span className="text-slate-900 dark:text-slate-50 font-semibold">{selectedProduct.name}</span>
                    </div>
                    <div className="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700">
                        <span className="text-slate-500 dark:text-slate-400 text-sm">Amount</span>
                        <span className="text-blue-600 dark:text-blue-400 text-2xl font-bold">
                            {formatCurrency(amountCents)}
                        </span>
                    </div>
                    <div className="flex justify-between items-center pt-1">
                        <span className="text-slate-500 dark:text-slate-400 text-sm">New Savings Balance</span>
                        <span className="text-slate-900 dark:text-slate-50 font-semibold">
                            {formatCurrency(selectedProduct.balance + amountCents)}
                        </span>
                    </div>
                </div>

                <div className="space-y-3 pt-1">
                    <Button
                        type="button"
                        onClick={handleConfirm}
                        className="w-full bg-slate-900 dark:bg-blue-600 hover:opacity-90 text-white py-6 text-base font-semibold transition-all"
                        disabled={form.processing}
                    >
                        {form.processing ? 'Processing...' : 'Confirm'}
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
    };

    const renderProcessingStep = () => (
        <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl py-16 text-center">
            <div className="animate-spin rounded-full h-14 w-14 border-b-2 border-blue-500 mx-auto mb-4" />
            <p className="text-slate-900 dark:text-slate-50 font-semibold">Processing</p>
            <p className="text-slate-500 dark:text-slate-400 text-sm mt-1">Please wait...</p>
        </div>
    );

    const renderSuccessStep = () => {
        const amountCents = Math.round(parseFloat(form.data.amount || '0') * 100);
        return (
            <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl py-14 px-6 text-center">
                <div className="w-16 h-16 rounded-full bg-emerald-500 mx-auto mb-5 flex items-center justify-center">
                    <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p className="text-xl font-bold text-slate-900 dark:text-slate-50 mb-1">Added to Savings!</p>
                <p className="text-slate-500 dark:text-slate-400 mb-3">{selectedProduct?.name}</p>
                <p className="text-blue-600 dark:text-blue-400 text-3xl font-bold">
                    {formatCurrency(amountCents)}
                </p>
            </div>
        );
    };

    return (
        <MobileLayout user={auth.user} title="Savings" currentRoute="savings">
            <div className="px-4 pt-4 flex items-center justify-between">
                {step === 'list' ? (
                    <Link
                        href={"/dashboard" + viewParam}
                        className="p-2 -ml-2 rounded-full text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-50 dark:hover:bg-slate-800 transition-colors"
                        aria-label="Back to dashboard"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m0 0h18" />
                        </svg>
                    </Link>
                ) : step !== 'processing' && step !== 'success' ? (
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
                ) : (
                    <div className="w-9" />
                )}
                <h1 className="text-base font-semibold text-slate-900 dark:text-slate-50">Savings</h1>
                <div className="w-9" />
            </div>

            <div className="px-4 py-6">
                {step === 'list' && renderListStep()}
                {step === 'amount' && renderAmountStep()}
                {step === 'confirm' && renderConfirmStep()}
                {step === 'processing' && renderProcessingStep()}
                {step === 'success' && renderSuccessStep()}
            </div>
        </MobileLayout>
    );
}
