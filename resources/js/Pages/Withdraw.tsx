import { useState, FormEvent } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import MobileLayout from '@/Layouts/MobileLayout';
import { PageProps, Wallet } from '@/types';
import { useForm } from '@inertiajs/react';

interface WithdrawPageProps extends PageProps {
    wallet?: Wallet;
}

type WithdrawStep = 'input' | 'confirm' | 'processing' | 'success';

export default function Withdraw({ auth, wallet, flash }: WithdrawPageProps) {
    const [step, setStep] = useState<WithdrawStep>('input');
    const { data, setData, post, processing, errors, reset } = useForm({
        amount: '',
        method: 'bank_transfer',
        account_details: '',
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
        setData('amount', (quickAmount / 100).toString());
    };

    const handleContinue = (e: FormEvent) => {
        e.preventDefault();
        
        // Basic validation
        if (!data.amount || parseFloat(data.amount) < 10) {
            return;
        }
        if (!data.account_details.trim()) {
            return;
        }
        
        // Check balance
        const amountInCents = parseFloat(data.amount) * 100;
        if (amountInCents > (wallet?.balance || 0)) {
            return;
        }
        
        setStep('confirm');
    };

    const handleConfirm = () => {
        setStep('processing');
        
        post(route('withdraw.store'), {
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
    };

    const handleBack = () => {
        setStep('input');
    };

    const handleNewWithdrawal = () => {
        reset();
        setStep('input');
    };

    const getMethodLabel = (method: string) => {
        const labels: Record<string, string> = {
            bank_transfer: 'Bank Transfer',
            paypal: 'PayPal',
            crypto: 'Cryptocurrency',
            check: 'Check',
        };
        return labels[method] || method;
    };

    // Step 1: Input Form
    const renderInputStep = () => (
        <>
            {/* Balance Card */}
            <Card className="bg-gradient-to-br from-red-600 to-rose-600 border-0 text-white">
                <CardContent className="pt-6 pb-6">
                    <div className="text-center">
                        <p className="text-sm text-red-100 mb-2">Available Balance</p>
                        <p className="text-3xl font-bold">{formatCurrency(wallet?.balance || 0)}</p>
                    </div>
                </CardContent>
            </Card>

            {/* Error Messages */}
            {(errors.amount || errors.method || errors.account_details || flash?.error) && (
                <Card className="bg-red-950/30 border-red-800">
                    <CardContent className="pt-4 pb-4">
                        <div className="flex items-start space-x-2">
                            <svg className="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div className="text-sm text-red-200 space-y-1">
                                {errors.amount && <p>{errors.amount}</p>}
                                {errors.method && <p>{errors.method}</p>}
                                {errors.account_details && <p>{errors.account_details}</p>}
                                {flash?.error && <p>{flash.error}</p>}
                            </div>
                        </div>
                    </CardContent>
                </Card>
            )}

            {/* Withdraw Form */}
            <Card className="bg-slate-900 border-slate-800">
                <CardHeader>
                    <CardTitle className="text-slate-50 text-lg">Withdraw Money</CardTitle>
                    <CardDescription className="text-slate-400">
                        Request a withdrawal from your account
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form onSubmit={handleContinue} className="space-y-4">
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
                                    min="10.00"
                                    max={(wallet?.balance || 0) / 100}
                                    value={data.amount}
                                    onChange={(e) => setData('amount', e.target.value)}
                                    placeholder="0.00"
                                    className="w-full pl-8 pr-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500 text-lg"
                                    required
                                />
                            </div>
                            <p className="text-xs text-slate-500 mt-1">
                                Minimum withdrawal: $10.00
                            </p>
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
                                        className="border-slate-700 text-slate-300 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all"
                                        disabled={(wallet?.balance || 0) < quickAmount}
                                    >
                                        ${quickAmount / 100}
                                    </Button>
                                ))}
                            </div>
                        </div>

                        <div>
                            <label className="text-sm text-slate-300 mb-2 block">
                                Withdrawal Method *
                            </label>
                            <select
                                value={data.method}
                                onChange={(e) => setData('method', e.target.value)}
                                className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                            >
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="paypal">PayPal</option>
                                <option value="crypto">Cryptocurrency</option>
                                <option value="check">Check</option>
                            </select>
                        </div>

                        <div>
                            <label className="text-sm text-slate-300 mb-2 block">
                                Account Details *
                            </label>
                            <textarea
                                value={data.account_details}
                                onChange={(e) => setData('account_details', e.target.value)}
                                placeholder="Enter your bank account number, PayPal email, or wallet address..."
                                rows={3}
                                className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"
                                required
                            />
                            <p className="text-xs text-slate-500 mt-1">
                                Double-check your details to avoid delays
                            </p>
                        </div>

                        <Button 
                            type="submit" 
                            className="w-full bg-red-600 hover:bg-red-700 text-white py-6 text-base font-semibold transition-all"
                            disabled={!data.amount || !data.account_details || !wallet || wallet.balance <= 0}
                        >
                            Continue
                            <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </Button>
                    </form>
                </CardContent>
            </Card>

            {/* Info Card */}
            <Card className="bg-red-950/20 border-red-900">
                <CardContent className="pt-6 space-y-2">
                    <div className="flex items-start space-x-2">
                        <svg className="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div className="text-sm text-red-200 space-y-1">
                            <p><strong>Important:</strong></p>
                            <p>• Withdrawals require admin approval</p>
                            <p>• Processing may take 1-3 business days</p>
                            <p>• Ensure your account details are correct</p>
                            <p>• Minimum withdrawal: $10.00</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </>
    );

    // Step 2: Confirmation
    const renderConfirmStep = () => (
        <>
            <Card className="bg-slate-900 border-slate-800">
                <CardHeader>
                    <CardTitle className="text-slate-50 text-lg">Confirm Withdrawal</CardTitle>
                    <CardDescription className="text-slate-400">
                        Please review the details before confirming
                    </CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    {/* Transaction Details */}
                    <div className="bg-slate-800 rounded-lg p-4 space-y-3">
                        <div className="flex justify-between items-center pb-3 border-b border-slate-700">
                            <span className="text-slate-400">Withdrawal Amount</span>
                            <span className="text-red-400 text-2xl font-bold">
                                ${parseFloat(data.amount).toFixed(2)}
                            </span>
                        </div>
                        <div className="flex justify-between items-center pb-3 border-b border-slate-700">
                            <span className="text-slate-400">Method</span>
                            <span className="text-slate-50 font-semibold">{getMethodLabel(data.method)}</span>
                        </div>
                        <div className="flex justify-between items-center pb-3 border-b border-slate-700">
                            <span className="text-slate-400">Fee</span>
                            <span className="text-slate-50 font-semibold">$0.00</span>
                        </div>
                        <div className="pb-3 border-b border-slate-700">
                            <span className="text-slate-400 text-sm block mb-2">Account Details</span>
                            <p className="text-slate-50 text-sm bg-slate-900 p-2 rounded break-words">
                                {data.account_details}
                            </p>
                        </div>
                        <div className="flex justify-between items-center pt-2">
                            <span className="text-slate-300 font-semibold">New Balance</span>
                            <span className="text-slate-50 text-xl font-bold">
                                {formatCurrency((wallet?.balance || 0) - parseFloat(data.amount) * 100)}
                            </span>
                        </div>
                    </div>

                    {/* Warning */}
                    <Card className="bg-amber-950/20 border-amber-900">
                        <CardContent className="pt-4 pb-4">
                            <div className="flex items-start space-x-2">
                                <svg className="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div className="text-sm text-amber-200">
                                    <p>Your withdrawal will be pending until an admin reviews and processes it.</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Action Buttons */}
                    <div className="space-y-3 pt-2">
                        <Button 
                            type="button"
                            onClick={handleConfirm}
                            className="w-full bg-red-600 hover:bg-red-700 text-white py-6 text-base font-semibold transition-all"
                            disabled={processing}
                        >
                            {processing ? 'Processing...' : 'Confirm Withdrawal'}
                            <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                            </svg>
                        </Button>
                        <Button 
                            type="button"
                            onClick={handleBack}
                            variant="outline"
                            className="w-full border-slate-700 text-slate-300 hover:bg-slate-800 py-6 text-base"
                            disabled={processing}
                        >
                            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Edit
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </>
    );

    // Step 3: Processing
    const renderProcessingStep = () => (
        <Card className="bg-slate-900 border-slate-800">
            <CardContent className="pt-12 pb-12">
                <div className="text-center space-y-4">
                    <div className="flex justify-center">
                        <div className="animate-spin rounded-full h-16 w-16 border-b-2 border-red-500"></div>
                    </div>
                    <div>
                        <p className="text-slate-50 text-xl font-semibold mb-2">Processing Withdrawal</p>
                        <p className="text-slate-400">Please wait while we submit your request...</p>
                    </div>
                </div>
            </CardContent>
        </Card>
    );

    // Step 4: Success
    const renderSuccessStep = () => (
        <Card className="bg-slate-900 border-slate-800">
            <CardContent className="pt-12 pb-12">
                <div className="text-center space-y-4">
                    <div className="flex justify-center">
                        <div className="rounded-full bg-amber-600 p-4">
                            <svg className="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p className="text-slate-50 text-2xl font-bold mb-2">Withdrawal Submitted!</p>
                        <p className="text-slate-400 mb-4">Your request is pending approval</p>
                        <p className="text-red-400 text-3xl font-bold">${parseFloat(data.amount).toFixed(2)}</p>
                    </div>
                    <div className="pt-4">
                        <Card className="bg-amber-950/20 border-amber-900">
                            <CardContent className="pt-4 pb-4">
                                <p className="text-amber-200 text-sm">
                                    An admin will review your withdrawal request. You'll be notified once it's processed.
                                </p>
                            </CardContent>
                        </Card>
                    </div>
                    <div className="pt-2">
                        <p className="text-slate-500 text-sm">Redirecting to transactions...</p>
                    </div>
                </div>
            </CardContent>
        </Card>
    );

    return (
        <MobileLayout user={auth.user} title="Withdraw" currentRoute="dashboard">
            <div className="px-4 py-6 space-y-6">
                {/* Progress Indicator */}
                {step !== 'success' && (
                    <div className="flex justify-center items-center space-x-2 mb-2">
                        <div className={`h-2 w-16 rounded-full transition-all ${step === 'input' ? 'bg-red-500' : 'bg-slate-700'}`}></div>
                        <div className={`h-2 w-16 rounded-full transition-all ${step === 'confirm' ? 'bg-red-500' : 'bg-slate-700'}`}></div>
                        <div className={`h-2 w-16 rounded-full transition-all ${step === 'processing' ? 'bg-red-500' : 'bg-slate-700'}`}></div>
                    </div>
                )}

                {step === 'input' && renderInputStep()}
                {step === 'confirm' && renderConfirmStep()}
                {step === 'processing' && renderProcessingStep()}
                {step === 'success' && renderSuccessStep()}
            </div>
        </MobileLayout>
    );
}

