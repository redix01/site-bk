import { FormEvent, ReactNode, useState } from 'react';
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

const productMeta: Record<string, { iconBg: string; iconColor: string; icon: ReactNode }> = {
    hysa: {
        iconBg: 'bg-amber-500/10',
        iconColor: 'text-amber-600 dark:text-amber-500',
        icon: (
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.25 18L9 11.25l4.306 4.306a11.95 11.95 0 015.814-5.518l2.74-1.22m0 0l-5.94-2.281m5.94 2.28l-2.28 5.941" />
            </svg>
        ),
    },
    mma: {
        iconBg: 'bg-blue-500/10',
        iconColor: 'text-blue-600 dark:text-blue-500',
        icon: (
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
            </svg>
        ),
    },
};

const fallbackMeta = {
    iconBg: 'bg-slate-500/10',
    iconColor: 'text-slate-600 dark:text-slate-400',
    icon: (
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 9l9-6 9 6M4.5 9.75V21M19.5 9.75V21M9 21v-6a3 3 0 013-3v0a3 3 0 013 3v6M3 21h18" />
        </svg>
    ),
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
                {products.map((product) => {
                    const meta = productMeta[product.key] || fallbackMeta;
                    return (
                        <div
                            key={product.key}
                            className="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5"
                        >
                            <div className="flex items-start justify-between gap-3">
                                <div className="flex items-center gap-3">
                                    <div className={`w-11 h-11 rounded-full flex items-center justify-center ${meta.iconBg} ${meta.iconColor}`}>
                                        {meta.icon}
                                    </div>
                                    <div>
                                        <p className="text-sm font-semibold text-slate-900 dark:text-slate-50">{product.name}</p>
                                        <p className="text-xs text-slate-500 dark:text-slate-400">{product.shortName}</p>
                                    </div>
                                </div>
                                <div className="text-right shrink-0">
                                    <p className="text-lg font-bold text-slate-900 dark:text-slate-50">{product.rate}</p>
                                    <p className="text-[10px] uppercase tracking-wide text-slate-400 dark:text-slate-500">P.A</p>
                                </div>
                            </div>

                            <p className="text-sm text-slate-600 dark:text-slate-300 mt-3">
                                {product.description}
                            </p>

                            {product.balance > 0 && (
                                <div className="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 text-sm">
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
                    );
                })}
            </div>

            <p className="text-xs text-slate-400 dark:text-slate-500 text-center px-4">
                Rates shown are annual percentage yields (P.A) and may change.
            </p>
        </div>
    );

    const renderAmountStep = () => {
        if (!selectedProduct) return null;

        const meta = productMeta[selectedProduct.key] || fallbackMeta;

        return (
            <div className="space-y-4">
                <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-center justify-between gap-3">
                    <div className="flex items-center gap-3">
                        <div className={`w-11 h-11 rounded-full flex items-center justify-center ${meta.iconBg} ${meta.iconColor}`}>
                            {meta.icon}
                        </div>
                        <div>
                            <p className="text-sm font-semibold text-slate-900 dark:text-slate-50">{selectedProduct.name}</p>
                            <p className="text-xs text-slate-500 dark:text-slate-400">{selectedProduct.shortName}</p>
                        </div>
                    </div>
                    <div className="text-right shrink-0">
                        <p className="text-base font-bold text-slate-900 dark:text-slate-50">{selectedProduct.rate}</p>
                        <p className="text-[10px] uppercase tracking-wide text-slate-400 dark:text-slate-500">P.A</p>
                    </div>
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
            <div className="px-4 pt-4 pb-3 flex items-center justify-between border-b border-slate-200 dark:border-slate-800 mb-2">
                {step === 'list' ? (
                    <Link
                        href={"/dashboard" + viewParam}
                        className="w-9 h-9 -ml-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-slate-50 transition-colors"
                        aria-label="Back to dashboard"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                ) : step !== 'processing' && step !== 'success' ? (
                    <button
                        type="button"
                        onClick={handleBack}
                        className="w-9 h-9 -ml-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-slate-50 transition-colors"
                        aria-label="Back"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
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
