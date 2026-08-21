import { useState, FormEvent } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import MobileLayout from '@/Layouts/MobileLayout';
import { PageProps, Wallet } from '@/types';
import { useForm } from '@inertiajs/react';

const CopyButton = ({ text }: { text: string }) => {
    const [copied, setCopied] = useState(false);

    const handleCopy = async () => {
        try {
            // Try modern Clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
                setCopied(true);
                setTimeout(() => setCopied(false), 2000);
            } else {
                // Fallback for older browsers or non-secure contexts
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                
                try {
                    const successful = document.execCommand('copy');
                    if (successful) {
                        setCopied(true);
                        setTimeout(() => setCopied(false), 2000);
                    } else {
                        alert('Copy failed. Please copy manually: ' + text);
                    }
                } catch (err) {
                    alert('Copy failed. Please copy manually: ' + text);
                } finally {
                    document.body.removeChild(textArea);
                }
            }
        } catch (err) {
            console.error('Failed to copy:', err);
            // Final fallback - show the text in an alert so user can manually copy
            alert('Copy to clipboard: ' + text);
        }
    };

    return (
        <button
            onClick={handleCopy}
            className="ml-2 p-1 hover:bg-slate-700 rounded transition-colors flex-shrink-0"
            title="Copy to clipboard"
            type="button"
        >
            {copied ? (
                <svg className="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                </svg>
            ) : (
                <svg className="w-4 h-4 text-slate-400 hover:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            )}
        </button>
    );
};

const truncateText = (text: string, maxLength: number = 25) => {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
};

interface DepositMethod {
    name: string;
    enabled: boolean;
    min_amount?: number;
    processing_time: string;
    requires_form?: boolean;
    requires_reference?: boolean;
    fee?: number;
    fee_percentage?: number;
    instructions?: Record<string, string>;
    currencies?: Record<string, any>;
    notes?: string[];
}

interface DepositPageProps extends PageProps {
    wallet?: Wallet;
    depositMethods?: Record<string, DepositMethod>;
}

type DepositMode = 'code' | 'payment';
type DepositStep = 'select' | 'input' | 'confirm' | 'processing' | 'success';

export default function Deposit({ auth, wallet, depositMethods = {}, flash }: DepositPageProps) {
    const [mode, setMode] = useState<DepositMode>('payment');
    const [step, setStep] = useState<DepositStep>('select');
    const [selectedMethod, setSelectedMethod] = useState<string>('');
    
    // Code redemption form
    const codeForm = useForm({
        deposit_type: 'code',
        code: '',
        amount: '',
    });
    
    // Payment method form
    const paymentForm = useForm({
        deposit_type: 'payment',
        method: '',
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

    const quickAmounts = [1000, 5000, 10000, 25000]; // amounts in cents

    const handleQuickAmount = (quickAmount: number) => {
        if (mode === 'code') {
            codeForm.setData('amount', (quickAmount / 100).toString());
        } else {
            paymentForm.setData('amount', (quickAmount / 100).toString());
        }
    };

    const handleMethodSelect = (method: string) => {
        setSelectedMethod(method);
        paymentForm.setData('method', method);
        setStep('input');
    };

    const handleCodeContinue = (e: FormEvent) => {
        e.preventDefault();
        if (!codeForm.data.code.trim() || !codeForm.data.amount) return;
        setStep('confirm');
    };

    const handlePaymentContinue = (e: FormEvent) => {
        e.preventDefault();
        if (!paymentForm.data.amount || !paymentForm.data.payment_reference) return;
        setStep('confirm');
    };

    const handleConfirm = () => {
        setStep('processing');
        
        if (mode === 'code') {
            codeForm.post(route('deposit.store'), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    setStep('success');
                    setTimeout(() => {
                        window.location.href = route('transactions');
                    }, 2000);
                },
                onError: () => {
                    setStep('input');
                },
            });
        } else {
            paymentForm.post(route('deposit.store'), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    setStep('success');
        setTimeout(() => {
                        window.location.href = route('transactions');
                    }, 3000);
                },
                onError: () => {
                    setStep('input');
                },
            });
        }
    };

    const handleBack = () => {
        if (step === 'confirm') {
            setStep('input');
        } else if (step === 'input' && mode === 'payment') {
            setStep('select');
            setSelectedMethod('');
        }
    };

    const getMethodIcon = (methodKey: string) => {
        const icons: Record<string, JSX.Element> = {
            bank_transfer: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
            ),
            crypto: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            ),
            paypal: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            ),
            wire_transfer: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            ),
        };
        return icons[methodKey] || icons.bank_transfer;
    };

    // Step 1: Mode & Method Selection
    const renderSelectStep = () => (
        <>
            {/* Balance Card */}
            <Card className="bg-gradient-to-br from-green-600 to-emerald-600 border-0 text-white">
                <CardContent className="pt-6 pb-6">
                    <div className="text-center">
                        <p className="text-sm text-green-100 mb-2">Current Balance</p>
                        <p className="text-3xl font-bold">{formatCurrency(wallet?.balance || 0)}</p>
                    </div>
                </CardContent>
            </Card>

            {/* Mode Selector */}
            <Card className="bg-slate-900 border-slate-800">
                <CardContent className="p-2">
                    <div className="grid grid-cols-2 gap-2">
                        <Button
                            variant={mode === 'payment' ? 'default' : 'ghost'}
                            onClick={() => setMode('payment')}
                            className={mode === 'payment' ? 'bg-green-600 hover:bg-green-700' : 'text-slate-400 hover:text-slate-50 hover:bg-slate-800'}
                        >
                            Make Deposit
                        </Button>
                        <Button
                            variant={mode === 'code' ? 'default' : 'ghost'}
                            onClick={() => { setMode('code'); setStep('input'); }}
                            className={mode === 'code' ? 'bg-green-600 hover:bg-green-700' : 'text-slate-400 hover:text-slate-50 hover:bg-slate-800'}
                        >
                            Redeem Code
                        </Button>
                    </div>
                </CardContent>
            </Card>

            {/* Method Selection */}
            {mode === 'payment' && (
                <>
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50 text-lg">Select Deposit Method</CardTitle>
                            <CardDescription className="text-slate-400">
                                Choose how you want to deposit funds
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-3">
                            {Object.entries(depositMethods).map(([key, method]) => {
                                if (!method.enabled) return null;
    return (
                                    <button
                                        key={key}
                                        onClick={() => handleMethodSelect(key)}
                                        className="w-full flex items-center justify-between p-4 bg-slate-800 hover:bg-slate-700 rounded-lg border border-slate-700 hover:border-green-500 transition-all group"
                                    >
                                        <div className="flex items-center space-x-3">
                                            <div className="text-green-400 group-hover:text-green-300">
                                                {getMethodIcon(key)}
                                            </div>
                                            <div className="text-left">
                                                <p className="text-slate-50 font-semibold">{method.name}</p>
                                                <p className="text-xs text-slate-400">
                                                    Min: {formatCurrency(method.min_amount)} • {method.processing_time}
                                                </p>
                                            </div>
                                        </div>
                                        <svg className="w-5 h-5 text-slate-500 group-hover:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                );
                            })}
                        </CardContent>
                    </Card>

                    {/* Info */}
                    <Card className="bg-green-950/20 border-green-900">
                        <CardContent className="pt-6 space-y-2">
                            <div className="flex items-start space-x-2">
                                <svg className="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div className="text-sm text-green-200 space-y-1">
                                    <p><strong>How deposits work:</strong></p>
                                    <p>• Select your preferred payment method</p>
                                    <p>• Send payment using the provided details</p>
                                    <p>• Submit your transaction reference</p>
                                    <p>• Admin verifies and credits your account</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </>
            )}
        </>
    );

    // Step 2: Input (Code or Payment Details)
    const renderInputStep = () => {
        if (mode === 'code') {
            return renderCodeInput();
        } else {
            return renderPaymentInput();
        }
    };

    const renderCodeInput = () => (
        <>
                {/* Balance Card */}
                <Card className="bg-gradient-to-br from-green-600 to-emerald-600 border-0 text-white">
                    <CardContent className="pt-6 pb-6">
                        <div className="text-center">
                            <p className="text-sm text-green-100 mb-2">Current Balance</p>
                            <p className="text-3xl font-bold">{formatCurrency(wallet?.balance || 0)}</p>
                        </div>
                    </CardContent>
                </Card>

            {/* Error Messages */}
            {(codeForm.errors.code || codeForm.errors.amount || flash?.error) && (
                <Card className="bg-red-950/30 border-red-800">
                    <CardContent className="pt-4 pb-4">
                        <div className="flex items-start space-x-2">
                            <svg className="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div className="text-sm text-red-200 space-y-1">
                                {codeForm.errors.code && <p>{codeForm.errors.code}</p>}
                                {codeForm.errors.amount && <p>{codeForm.errors.amount}</p>}
                                {flash?.error && <p>{flash.error}</p>}
                            </div>
                        </div>
                    </CardContent>
                </Card>
            )}

            {/* Code Form */}
            <Card className="bg-slate-900 border-slate-800">
                <CardHeader>
                    <CardTitle className="text-slate-50 text-lg">Redeem Transaction Code</CardTitle>
                    <CardDescription className="text-slate-400">
                        Enter the code provided by an admin
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form onSubmit={handleCodeContinue} className="space-y-4">
                        <div>
                            <label className="text-sm text-slate-300 mb-2 block">
                                Transaction Code *
                            </label>
                            <input
                                type="text"
                                value={codeForm.data.code}
                                onChange={(e) => codeForm.setData('code', e.target.value.toUpperCase())}
                                placeholder="XXX-XXX-XXX"
                                className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-green-500 font-mono tracking-wider text-center text-lg"
                                required
                                maxLength={11}
                            />
                        </div>

                        <div>
                            <label className="text-sm text-slate-300 mb-2 block">
                                Amount *
                            </label>
                            <div className="relative">
                                <span className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">
                                    $
                                </span>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    value={codeForm.data.amount}
                                    onChange={(e) => codeForm.setData('amount', e.target.value)}
                                    placeholder="0.00"
                                    className="w-full pl-8 pr-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg"
                                    required
                                />
                            </div>
                        </div>

                        {/* Quick Amount Buttons */}
                        <div>
                            <label className="text-sm text-slate-300 mb-2 block">
                                Quick Amount
                            </label>
                            <div className="grid grid-cols-4 gap-2">
                                {quickAmounts.map((quickAmount) => (
                                    <Button
                                        key={quickAmount}
                                        type="button"
                                        variant="outline"
                                        size="sm"
                                        onClick={() => handleQuickAmount(quickAmount)}
                                        className="border-slate-700 text-slate-300 hover:bg-green-600 hover:text-white hover:border-green-600 transition-all"
                                    >
                                        ${quickAmount / 100}
                                    </Button>
                                ))}
                            </div>
                        </div>

                        <div className="flex space-x-3 pt-2">
                            <Button 
                                type="button"
                                onClick={() => { setMode('payment'); setStep('select'); }}
                                variant="outline"
                                className="flex-1 border-slate-700 text-slate-300 hover:bg-slate-800 py-6 text-base"
                            >
                                Back
                            </Button>
                            <Button 
                                type="submit" 
                                className="flex-1 bg-green-600 hover:bg-green-700 text-white py-6 text-base font-semibold transition-all"
                                disabled={!codeForm.data.code || !codeForm.data.amount}
                            >
                                Continue
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </>
    );

    const renderPaymentInput = () => {
        const method = depositMethods[selectedMethod];
        if (!method) return null;

        // For view-only methods (like bank transfer), show only instructions
        if (method.requires_form === false) {
            return (
                <>
                    {/* Balance Card */}
                    <Card className="bg-gradient-to-br from-green-600 to-emerald-600 border-0 text-white">
                        <CardContent className="pt-6 pb-6">
                            <div className="text-center">
                                <p className="text-sm text-green-100 mb-2">Current Balance</p>
                                <p className="text-3xl font-bold">{formatCurrency(wallet?.balance || 0)}</p>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Account Details */}
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50 text-lg flex items-center space-x-2">
                                <span className="text-green-400">{getMethodIcon(selectedMethod)}</span>
                                <span>Your Account Details</span>
                            </CardTitle>
                            <CardDescription className="text-slate-400">
                                Share these details with anyone sending you money
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            {/* Instructions */}
                            {method.instructions && (
                                <div className="bg-slate-800 rounded-lg p-4 space-y-2">
                                    <p className="text-sm font-semibold text-slate-300 mb-3">Bank Account Details:</p>
                                    {Object.entries(method.instructions).map(([label, value]) => (
                                        <div key={label} className="flex justify-between items-center py-2 border-b border-slate-700 last:border-0">
                                            <span className="text-sm text-slate-400 flex-shrink-0 mr-3">{label}</span>
                                            <div className="flex items-center min-w-0">
                                                <span 
                                                    className="text-sm text-slate-50 font-mono truncate" 
                                                    title={value}
                                                >
                                                    {truncateText(value, 20)}
                                                </span>
                                                <CopyButton text={value} />
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}

                            {/* Notes */}
                            {method.notes && method.notes.length > 0 && (
                                <div className="bg-green-950/20 border border-green-900 rounded-lg p-4">
                                    <div className="flex items-start space-x-2">
                                        <svg className="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div className="text-xs text-green-200 space-y-1">
                                            {method.notes.map((note, i) => (
                                                <p key={i}>• {note}</p>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Back Button */}
                            <div className="pt-2">
                                <Button 
                                    type="button"
                                    onClick={handleBack}
                                    variant="outline"
                                    className="w-full border-slate-700 text-slate-300 hover:bg-slate-800 py-6 text-base"
                                >
                                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Back to Methods
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </>
            );
        }

        // For other methods, show the full form
        return (
            <>
                {/* Payment Instructions */}
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50 text-lg flex items-center space-x-2">
                            <span className="text-green-400">{getMethodIcon(selectedMethod)}</span>
                            <span>{method.name}</span>
                        </CardTitle>
                        <CardDescription className="text-slate-400">
                            Follow the instructions below to complete your deposit
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        {/* Instructions */}
                        {method.instructions && (
                            <div className="bg-slate-800 rounded-lg p-4 space-y-2">
                                <p className="text-sm font-semibold text-slate-300 mb-3">Payment Details:</p>
                                {Object.entries(method.instructions).map(([label, value]) => (
                                    <div key={label} className="flex justify-between items-center py-2 border-b border-slate-700 last:border-0">
                                        <span className="text-sm text-slate-400 flex-shrink-0 mr-3">{label}</span>
                                        <div className="flex items-center min-w-0">
                                            <span 
                                                className="text-sm text-slate-50 font-mono truncate" 
                                                title={value}
                                            >
                                                {truncateText(value, 20)}
                                            </span>
                                            <CopyButton text={value} />
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}

                        {/* Crypto Currencies */}
                        {selectedMethod === 'crypto' && method.currencies && (
                            <div className="space-y-2">
                                <label className="text-sm text-slate-300 mb-2 block">
                                    Select Cryptocurrency *
                                </label>
                                {Object.entries(method.currencies).map(([key, currency]: [string, any]) => (
                                    <button
                                        key={key}
                                        type="button"
                                        onClick={() => paymentForm.setData('crypto_currency', key)}
                                        className={`w-full p-3 rounded-lg border transition-all ${
                                            paymentForm.data.crypto_currency === key
                                                ? 'bg-green-600 border-green-500 text-white'
                                                : 'bg-slate-800 border-slate-700 text-slate-300 hover:border-green-500'
                                        }`}
                                    >
                                        <div className="flex justify-between items-center">
                                            <span className="font-semibold">{currency.name}</span>
                                            <span className="text-xs">{currency.network}</span>
                                        </div>
                                        {paymentForm.data.crypto_currency === key && (
                                            <div className="mt-2 flex items-center justify-between bg-green-700 p-2 rounded">
                                                <span className="text-xs font-mono truncate mr-2" title={currency.address}>
                                                    {truncateText(currency.address, 30)}
                                                </span>
                                                <CopyButton text={currency.address} />
                                            </div>
                                        )}
                                    </button>
                                ))}
                            </div>
                        )}

                        {/* Notes */}
                        {method.notes && method.notes.length > 0 && (
                            <div className="bg-amber-950/20 border border-amber-900 rounded-lg p-4">
                                <div className="flex items-start space-x-2">
                                    <svg className="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div className="text-xs text-amber-200 space-y-1">
                                        {method.notes.map((note, i) => (
                                            <p key={i}>• {note}</p>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        )}
                    </CardContent>
                </Card>

                {/* Error Messages */}
                {(paymentForm.errors.amount || paymentForm.errors.payment_reference || paymentForm.errors.method || flash?.error) && (
                    <Card className="bg-red-950/30 border-red-800">
                        <CardContent className="pt-4 pb-4">
                            <div className="flex items-start space-x-2">
                                <svg className="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div className="text-sm text-red-200 space-y-1">
                                    {paymentForm.errors.amount && <p>{paymentForm.errors.amount}</p>}
                                    {paymentForm.errors.payment_reference && <p>{paymentForm.errors.payment_reference}</p>}
                                    {paymentForm.errors.method && <p>{paymentForm.errors.method}</p>}
                                    {flash?.error && <p>{flash.error}</p>}
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                )}

                {/* Deposit Form */}
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50 text-lg">Deposit Details</CardTitle>
                        <CardDescription className="text-slate-400">
                            Enter your deposit information
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handlePaymentContinue} className="space-y-4">
                            <div>
                                <label className="text-sm text-slate-300 mb-2 block">
                                    Deposit Amount *
                                </label>
                                <div className="relative">
                                    <span className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">
                                        $
                                    </span>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min={(method.min_amount / 100).toString()}
                                        value={paymentForm.data.amount}
                                        onChange={(e) => paymentForm.setData('amount', e.target.value)}
                                        placeholder="0.00"
                                        className="w-full pl-8 pr-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg"
                                        required
                                    />
                                </div>
                                <p className="text-xs text-slate-500 mt-1">
                                    Minimum: {formatCurrency(method.min_amount)}
                                </p>
                            </div>

                            {/* Quick Amount Buttons */}
                            <div>
                                <label className="text-sm text-slate-300 mb-2 block">
                                    Quick Amount
                                </label>
                                <div className="grid grid-cols-4 gap-2">
                                    {quickAmounts.filter(amt => amt >= method.min_amount).map((quickAmount) => (
                                        <Button
                                            key={quickAmount}
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            onClick={() => handleQuickAmount(quickAmount)}
                                            className="border-slate-700 text-slate-300 hover:bg-green-600 hover:text-white hover:border-green-600 transition-all"
                                        >
                                            ${quickAmount / 100}
                                        </Button>
                                    ))}
                                </div>
                            </div>

                            {/* Payment Reference - Only show for methods that require it */}
                            {method.requires_reference !== false && (
                                <div>
                                    <label className="text-sm text-slate-300 mb-2 block">
                                        Payment Reference / Transaction ID *
                                    </label>
                                    <input
                                        type="text"
                                        value={paymentForm.data.payment_reference}
                                        onChange={(e) => paymentForm.setData('payment_reference', e.target.value)}
                                        placeholder={selectedMethod === 'crypto' ? 'Transaction hash' : 'Transaction ID or reference number'}
                                        className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                                        required
                                    />
                                    <p className="text-xs text-slate-500 mt-1">
                                        Enter your payment confirmation number
                                    </p>
                                </div>
                            )}

                            <div>
                                <label className="text-sm text-slate-300 mb-2 block">
                                    Additional Notes (Optional)
                                </label>
                                <textarea
                                    value={paymentForm.data.notes}
                                    onChange={(e) => paymentForm.setData('notes', e.target.value)}
                                    placeholder="Any additional information..."
                                    rows={3}
                                    className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"
                                />
                            </div>

                            <div className="flex space-x-3 pt-2">
                                <Button 
                                    type="button"
                                    onClick={handleBack}
                                    variant="outline"
                                    className="flex-1 border-slate-700 text-slate-300 hover:bg-slate-800 py-6 text-base"
                                >
                                    Back
                                </Button>
                                <Button 
                                    type="submit" 
                                    className="flex-1 bg-green-600 hover:bg-green-700 text-white py-6 text-base font-semibold transition-all"
                                    disabled={
                                        !paymentForm.data.amount || 
                                        (method.requires_reference !== false && !paymentForm.data.payment_reference) || 
                                        (selectedMethod === 'crypto' && !paymentForm.data.crypto_currency)
                                    }
                                >
                                    Continue
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </>
        );
    };

    // Step 3: Confirmation
    const renderConfirmStep = () => {
        if (mode === 'code') {
            return (
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50 text-lg">Confirm Deposit</CardTitle>
                        <CardDescription className="text-slate-400">
                            Please review the details before confirming
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="bg-slate-800 rounded-lg p-4 space-y-3">
                            <div className="flex justify-between items-center pb-3 border-b border-slate-700">
                                <span className="text-slate-400">Transaction Code</span>
                                <span className="text-slate-50 font-mono font-semibold">{codeForm.data.code}</span>
                            </div>
                            <div className="flex justify-between items-center pb-3 border-b border-slate-700">
                                <span className="text-slate-400">Amount</span>
                                <span className="text-green-400 text-2xl font-bold">
                                    ${parseFloat(codeForm.data.amount).toFixed(2)}
                                </span>
                            </div>
                            <div className="flex justify-between items-center pt-2">
                                <span className="text-slate-300 font-semibold">New Balance</span>
                                <span className="text-slate-50 text-xl font-bold">
                                    {formatCurrency((wallet?.balance || 0) + parseFloat(codeForm.data.amount) * 100)}
                                </span>
                            </div>
                        </div>

                        <div className="space-y-3 pt-2">
                            <Button 
                                type="button"
                                onClick={handleConfirm}
                                className="w-full bg-green-600 hover:bg-green-700 text-white py-6 text-base font-semibold transition-all"
                                disabled={codeForm.processing}
                            >
                                {codeForm.processing ? 'Processing...' : 'Confirm Deposit'}
                            </Button>
                            <Button 
                                type="button"
                                onClick={handleBack}
                                variant="outline"
                                className="w-full border-slate-700 text-slate-300 hover:bg-slate-800 py-6 text-base"
                                disabled={codeForm.processing}
                            >
                                Back to Edit
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            );
        } else {
            const method = depositMethods[selectedMethod];
            return (
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50 text-lg">Confirm Deposit Request</CardTitle>
                        <CardDescription className="text-slate-400">
                            Please review your deposit details
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="bg-slate-800 rounded-lg p-4 space-y-3">
                            <div className="flex justify-between items-center pb-3 border-b border-slate-700">
                                <span className="text-slate-400">Method</span>
                                <span className="text-slate-50 font-semibold">{method?.name}</span>
                            </div>
                            <div className="flex justify-between items-center pb-3 border-b border-slate-700">
                                <span className="text-slate-400">Amount</span>
                                <span className="text-green-400 text-2xl font-bold">
                                    ${parseFloat(paymentForm.data.amount).toFixed(2)}
                                </span>
                            </div>
                            {selectedMethod === 'crypto' && paymentForm.data.crypto_currency && (
                                <div className="flex justify-between items-center pb-3 border-b border-slate-700">
                                    <span className="text-slate-400">Currency</span>
                                    <span className="text-slate-50 font-semibold">{paymentForm.data.crypto_currency}</span>
                                </div>
                            )}
                            <div className="pb-3 border-b border-slate-700">
                                <span className="text-slate-400 text-sm block mb-2">Payment Reference</span>
                                <p className="text-slate-50 text-sm bg-slate-900 p-2 rounded break-all font-mono">
                                    {paymentForm.data.payment_reference}
                                </p>
                            </div>
                            <div className="flex justify-between items-center pt-2">
                                <span className="text-slate-300 font-semibold">Processing Time</span>
                                <span className="text-slate-50 text-sm">{method?.processing_time}</span>
                            </div>
                        </div>

                        <Card className="bg-amber-950/20 border-amber-900">
                            <CardContent className="pt-4 pb-4">
                                <div className="flex items-start space-x-2">
                                    <svg className="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <div className="text-sm text-amber-200">
                                        <p>Your deposit will be pending until an admin verifies your payment and approves the deposit.</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <div className="space-y-3 pt-2">
                            <Button 
                                type="button"
                                onClick={handleConfirm}
                                className="w-full bg-green-600 hover:bg-green-700 text-white py-6 text-base font-semibold transition-all"
                                disabled={paymentForm.processing}
                            >
                                {paymentForm.processing ? 'Submitting...' : 'Submit Deposit Request'}
                            </Button>
                            <Button 
                                type="button"
                                onClick={handleBack}
                                variant="outline"
                                className="w-full border-slate-700 text-slate-300 hover:bg-slate-800 py-6 text-base"
                                disabled={paymentForm.processing}
                            >
                                Back to Edit
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            );
        }
    };

    // Step 4: Processing
    const renderProcessingStep = () => (
        <Card className="bg-slate-900 border-slate-800">
            <CardContent className="pt-12 pb-12">
                <div className="text-center space-y-4">
                    <div className="flex justify-center">
                        <div className="animate-spin rounded-full h-16 w-16 border-b-2 border-green-500"></div>
                    </div>
                    <div>
                        <p className="text-slate-50 text-xl font-semibold mb-2">Processing</p>
                        <p className="text-slate-400">Please wait...</p>
                    </div>
                </div>
            </CardContent>
        </Card>
    );

    // Step 5: Success
    const renderSuccessStep = () => {
        const isCodeDeposit = mode === 'code';
        const amount = isCodeDeposit ? codeForm.data.amount : paymentForm.data.amount;

        return (
            <Card className="bg-slate-900 border-slate-800">
                <CardContent className="pt-12 pb-12">
                    <div className="text-center space-y-4">
                        <div className="flex justify-center">
                            <div className={`rounded-full p-4 ${isCodeDeposit ? 'bg-green-600' : 'bg-amber-600'}`}>
                                <svg className="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d={isCodeDeposit ? "M5 13l4 4L19 7" : "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"} />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p className="text-slate-50 text-2xl font-bold mb-2">
                                {isCodeDeposit ? 'Deposit Successful!' : 'Request Submitted!'}
                            </p>
                            <p className="text-slate-400 mb-4">
                                {isCodeDeposit ? 'Your account has been credited' : 'Your deposit is pending verification'}
                            </p>
                            <p className="text-green-400 text-3xl font-bold">${parseFloat(amount).toFixed(2)}</p>
                        </div>
                        {!isCodeDeposit && (
                            <div className="pt-4">
                                <Card className="bg-amber-950/20 border-amber-900">
                                    <CardContent className="pt-4 pb-4">
                                        <p className="text-amber-200 text-sm">
                                            An admin will verify your payment and credit your account once confirmed.
                                        </p>
                                    </CardContent>
                                </Card>
                            </div>
                        )}
                        <div className="pt-2">
                            <p className="text-slate-500 text-sm">Redirecting to transactions...</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        );
    };

    return (
        <MobileLayout user={auth.user} title="Deposit" currentRoute="dashboard">
            <div className="px-4 py-6 space-y-6">
                {/* Progress Indicator (only for payment method deposits) */}
                {mode === 'payment' && step !== 'success' && step !== 'select' && (
                    <div className="flex justify-center items-center space-x-2 mb-2">
                        <div className={`h-2 w-16 rounded-full transition-all ${step === 'input' ? 'bg-green-500' : 'bg-slate-700'}`}></div>
                        <div className={`h-2 w-16 rounded-full transition-all ${step === 'confirm' ? 'bg-green-500' : 'bg-slate-700'}`}></div>
                        <div className={`h-2 w-16 rounded-full transition-all ${step === 'processing' ? 'bg-green-500' : 'bg-slate-700'}`}></div>
                    </div>
                )}

                {step === 'select' && renderSelectStep()}
                {step === 'input' && renderInputStep()}
                {step === 'confirm' && renderConfirmStep()}
                {step === 'processing' && renderProcessingStep()}
                {step === 'success' && renderSuccessStep()}
            </div>
        </MobileLayout>
    );
}
