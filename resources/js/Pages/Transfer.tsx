import { useState, FormEvent, useEffect, MouseEvent } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import MobileLayout from '@/Layouts/MobileLayout';
import { PageProps, Wallet } from '@/types';
import { router } from '@inertiajs/react';
import axios from 'axios';

interface TransferPageProps extends PageProps {
    wallet?: Wallet;
}

type TransferType = 'internal' | 'wire';
type TransferStep = 'details' | 'verify' | 'processing';

export default function Transfer({ auth, wallet, supportEmail }: TransferPageProps) {
    const [transferType, setTransferType] = useState<TransferType>('internal');
    const [step, setStep] = useState<TransferStep>('details');
    const isLocked = auth.user.status === 'locked';
    
    // Internal Transfer States
    const [recipientAccount, setRecipientAccount] = useState('');
    const [recipientName, setRecipientName] = useState('');
    const [lookingUpAccount, setLookingUpAccount] = useState(false);
    const [accountNotFound, setAccountNotFound] = useState(false);
    
    // Wire Transfer States
    const [wireBankName, setWireBankName] = useState('');
    const [wireAccountNumber, setWireAccountNumber] = useState('');
    const [wireRoutingNumber, setWireRoutingNumber] = useState('');
    const [wireSwiftCode, setWireSwiftCode] = useState('');
    const [wireBeneficiaryName, setWireBeneficiaryName] = useState('');
    const [wireBeneficiaryAddress, setWireBeneficiaryAddress] = useState('');
    
    // Common States
    const [amount, setAmount] = useState('');
    const [description, setDescription] = useState('');
    const [transactionPin, setTransactionPin] = useState('');
    const [transactionCode, setTransactionCode] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});
    const [isRequestingCode, setIsRequestingCode] = useState(false);
    const [codeRequestFeedback, setCodeRequestFeedback] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: wallet?.currency || 'USD',
            minimumFractionDigits: 2,
        }).format(amount / 100);
    };

    // Lookup account when typing internal transfer recipient
    useEffect(() => {
        if (transferType === 'internal' && recipientAccount.length >= 5) {
            const timer = setTimeout(async () => {
                setLookingUpAccount(true);
                setRecipientName('');
                setAccountNotFound(false);
                
                try {
                    const response = await axios.get(`/api/lookup-account/${recipientAccount}`);
                    if (response.data.success) {
                        setRecipientName(response.data.user.name);
                        setAccountNotFound(false);
                    } else {
                        setRecipientName('');
                        setAccountNotFound(true);
                    }
                } catch (error) {
                    setRecipientName('');
                    setAccountNotFound(true);
                } finally {
                    setLookingUpAccount(false);
                }
            }, 1000);
            
            return () => clearTimeout(timer);
        } else {
            setRecipientName('');
            setAccountNotFound(false);
        }
    }, [recipientAccount, transferType]);

    const handleDetailsSubmit = (e: FormEvent) => {
        e.preventDefault();
        setError('');
        setFormErrors({});

        const parsedAmount = parseFloat(amount);
        const availableBalanceCents = wallet?.balance ?? 0;
        const availableBalance = availableBalanceCents / 100;

        if (Number.isNaN(parsedAmount) || parsedAmount <= 0) {
            setFormErrors({ amount: 'Enter a valid transfer amount.' });
            return;
        }

        if (transferType === 'wire' && parsedAmount > availableBalance) {
            setFormErrors({ amount: 'Transfer amount exceeds your available balance.' });
            return;
        }

        setFormErrors({});
        setStep('verify');
    };

    const handleVerifySubmit = async (e: FormEvent) => {
        e.preventDefault();
        setLoading(true);
        setError('');
        setStep('processing');
        
        // Simulate processing delay (1.5-2.5 seconds)
        const delay = 1500 + Math.random() * 1000;
        await new Promise(resolve => setTimeout(resolve, delay));
        
        if (transferType === 'internal') {
            router.post('/transfer/internal', {
                recipient_account: recipientAccount,
                amount: parseFloat(amount),
                description: description,
                transaction_pin: transactionPin,
                transaction_code: transactionCode,
            }, {
                onSuccess: () => {
                    setLoading(false);
                },
                onError: (errors) => {
                    setLoading(false);
                    setStep('verify');
                    setError(Object.values(errors)[0] as string || 'Transfer failed. Please try again.');
                }
            });
        } else {
            router.post('/transfer/wire', {
                bank_name: wireBankName,
                account_number: wireAccountNumber,
                routing_number: wireRoutingNumber,
                swift_code: wireSwiftCode,
                beneficiary_name: wireBeneficiaryName,
                beneficiary_address: wireBeneficiaryAddress,
                amount: parseFloat(amount),
                description: description,
                transaction_pin: transactionPin,
                transaction_code: transactionCode,
            }, {
                onSuccess: () => {
                    setLoading(false);
                },
                onError: (errors) => {
                    setLoading(false);
                    setStep('verify');
                    setError(Object.values(errors)[0] as string || 'Transfer failed. Please try again.');
                }
            });
        }
    };

    const handleBack = () => {
        if (step === 'verify') {
            setStep('details');
            setTransactionPin('');
            setTransactionCode('');
            setError('');
            setFormErrors({});
        }
    };

    const clearFormError = (field: string) => {
        setFormErrors((prev) => {
            if (!prev[field]) {
                return prev;
            }
            const { [field]: _omit, ...rest } = prev;
            return rest;
        });
    };

    const handleRequestTransferCode = async (event: MouseEvent<HTMLAnchorElement>) => {
        event.preventDefault();
        if (isRequestingCode) {
            return;
        }

        const parsedAmount = parseFloat(amount);
        if (!amount || Number.isNaN(parsedAmount) || parsedAmount <= 0) {
            setFormErrors({ amount: 'Enter a valid transfer amount before requesting a code.' });
            setCodeRequestFeedback(null);
            setStep('details');
            return;
        }

        if (transferType === 'internal' && recipientAccount.trim().length === 0) {
            setFormErrors({ recipient_account: 'Add a recipient account number before requesting a transfer code.' });
            setCodeRequestFeedback(null);
            setStep('details');
            return;
        }

        if (transferType === 'wire') {
            const missingWireFields: string[] = [];
            if (!wireBeneficiaryName.trim()) missingWireFields.push('beneficiary name');
            if (!wireBeneficiaryAddress.trim()) missingWireFields.push('beneficiary address');
            if (!wireBankName.trim()) missingWireFields.push('bank name');
            if (!wireAccountNumber.trim()) missingWireFields.push('account number');
            if (!wireRoutingNumber.trim()) missingWireFields.push('routing number');

            if (missingWireFields.length > 0) {
                const errorMap: Record<string, string> = {};
                if (!wireBeneficiaryName.trim()) errorMap.wire_beneficiary_name = 'Beneficiary name is required.';
                if (!wireBeneficiaryAddress.trim()) errorMap.wire_beneficiary_address = 'Beneficiary address is required.';
                if (!wireBankName.trim()) errorMap.wire_bank_name = 'Bank name is required.';
                if (!wireAccountNumber.trim()) errorMap.wire_account_number = 'Account number is required.';
                if (!wireRoutingNumber.trim()) errorMap.wire_routing_number = 'Routing number is required.';
                setFormErrors(errorMap);
                setCodeRequestFeedback(null);
                setStep('details');
                return;
            }
        }

        setIsRequestingCode(true);
        setCodeRequestFeedback(null);

        try {
            await axios.post('/transfer/request-code', {
                transfer_type: transferType,
                amount: parsedAmount,
                description,
                recipient_account: transferType === 'internal' ? recipientAccount : undefined,
                wire_bank_name: transferType === 'wire' ? wireBankName : undefined,
                wire_account_number: transferType === 'wire' ? wireAccountNumber : undefined,
                wire_routing_number: transferType === 'wire' ? wireRoutingNumber : undefined,
                wire_swift_code: transferType === 'wire' ? wireSwiftCode : undefined,
                wire_beneficiary_name: transferType === 'wire' ? wireBeneficiaryName : undefined,
                wire_beneficiary_address: transferType === 'wire' ? wireBeneficiaryAddress : undefined,
            });

            setFormErrors({});
            setCodeRequestFeedback({
                type: 'success',
                message: `Support has been notified at ${supportEmail ?? 'support@banko.test'}. Watch your email for a transfer code.`,
            });
        } catch (requestError: any) {
            const message =
                requestError?.response?.data?.message ||
                'Unable to send the request right now. Please try again shortly.';

            if (requestError?.response?.status === 422 && requestError.response.data?.errors) {
                const apiErrors = requestError.response.data.errors as Record<string, string[] | string>;
                const mappedErrors: Record<string, string> = {};
                Object.entries(apiErrors).forEach(([key, value]) => {
                    mappedErrors[key] = Array.isArray(value) ? value[0] : value;
                });
                setFormErrors(mappedErrors);
                setStep('details');
                setCodeRequestFeedback(null);
            } else {
                setCodeRequestFeedback({
                    type: 'error',
                    message,
                });
            }
        } finally {
            setIsRequestingCode(false);
        }
    };

    const renderDetailsStep = () => (
        <>
            {/* Balance Card */}
            <Card className="bg-gradient-to-br from-blue-600 to-purple-600 border-0 text-white shadow-xl">
                <CardContent className="pt-6 pb-6">
                    <div className="text-center">
                        <p className="text-sm text-blue-100 mb-2">Available Balance</p>
                        <p className="text-3xl font-bold">{formatCurrency(wallet?.balance || 0)}</p>
                    </div>
                </CardContent>
            </Card>

            {/* Transfer Type Tabs */}
            <Card className="bg-slate-900 border-slate-800">
                <CardContent className="p-2">
                    <div className="grid grid-cols-2 gap-2">
                        <Button
                            type="button"
                            variant={transferType === 'internal' ? 'default' : 'ghost'}
                            onClick={() => {
                                setTransferType('internal');
                                setFormErrors({});
                                setCodeRequestFeedback(null);
                            }}
                            className={transferType === 'internal' 
                                ? 'bg-blue-600 hover:bg-blue-700 text-white' 
                                : 'text-slate-400 hover:text-slate-50 hover:bg-slate-800'
                            }
                        >
                            Internal Transfer
                        </Button>
                        <Button
                            type="button"
                            variant={transferType === 'wire' ? 'default' : 'ghost'}
                            onClick={() => {
                                setTransferType('wire');
                                setFormErrors({});
                                setCodeRequestFeedback(null);
                            }}
                            className={transferType === 'wire' 
                                ? 'bg-blue-600 hover:bg-blue-700 text-white' 
                                : 'text-slate-400 hover:text-slate-50 hover:bg-slate-800'
                            }
                        >
                            Wire Transfer
                        </Button>
                    </div>
                </CardContent>
            </Card>

            {/* Transfer Form */}
            <Card className="bg-slate-900 border-slate-800">
                <CardHeader>
                    <CardTitle className="text-slate-50 text-lg">
                        {transferType === 'internal' ? 'Internal Transfer' : 'Wire Transfer'}
                    </CardTitle>
                    <CardDescription className="text-slate-400">
                        {transferType === 'internal' 
                            ? 'Send money to another user in the system'
                            : 'Send money to an external bank account'
                        }
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form onSubmit={handleDetailsSubmit} className="space-y-4">
                        {transferType === 'internal' ? (
                            <>
                                {/* Internal Transfer Fields */}
                                <div>
                                    <label className="text-sm text-slate-300 mb-2 block">
                                        Recipient Account Number
                                    </label>
                                    <input
                                        type="number"
                                        inputMode="numeric"
                                        min="0"
                                        value={recipientAccount}
                                        onChange={(e) => {
                                            setRecipientAccount(e.target.value);
                                            clearFormError('recipient_account');
                                        }}
                                        placeholder="Enter account number"
                                        className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    {formErrors.recipient_account && (
                                        <p className="mt-2 text-sm text-red-400">{formErrors.recipient_account}</p>
                                    )}
                                    {lookingUpAccount && (
                                        <div className="mt-2 p-3 bg-blue-900/20 border border-blue-800 rounded-lg animate-pulse">
                                            <p className="text-sm text-blue-300 flex items-center">
                                                <svg className="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Looking up account...
                                            </p>
                                        </div>
                                    )}
                                    {!lookingUpAccount && recipientName && (
                                        <div className="mt-2 p-3 bg-green-900/20 border border-green-800 rounded-lg transition-all duration-300 ease-in-out">
                                            <p className="text-sm text-green-300 flex items-center">
                                                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                </svg>
                                                <strong>{recipientName}</strong>
                                            </p>
                                        </div>
                                    )}
                                    {!lookingUpAccount && accountNotFound && (
                                        <div className="mt-2 p-3 bg-red-900/20 border border-red-800 rounded-lg">
                                            <p className="text-sm text-red-300 flex items-center">
                                                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Account not found
                                            </p>
                                        </div>
                                    )}
                                </div>
                            </>
                        ) : (
                            <>
                                {/* Wire Transfer Fields */}
                                <div>
                                    <label className="text-sm text-slate-300 mb-2 block">
                                        Beneficiary Name
                                    </label>
                                    <input
                                        type="text"
                                        value={wireBeneficiaryName}
                                        onChange={(e) => {
                                            setWireBeneficiaryName(e.target.value);
                                            clearFormError('wire_beneficiary_name');
                                        }}
                                        placeholder="Full name of recipient"
                                        className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    {formErrors.wire_beneficiary_name && (
                                        <p className="mt-2 text-sm text-red-400">{formErrors.wire_beneficiary_name}</p>
                                    )}
                                </div>

                                <div>
                                    <label className="text-sm text-slate-300 mb-2 block">
                                        Bank Name
                                    </label>
                                    <input
                                        type="text"
                                        value={wireBankName}
                                        onChange={(e) => {
                                            setWireBankName(e.target.value);
                                            clearFormError('wire_bank_name');
                                        }}
                                        placeholder="Enter bank name"
                                        className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    {formErrors.wire_bank_name && (
                                        <p className="mt-2 text-sm text-red-400">{formErrors.wire_bank_name}</p>
                                    )}
                                </div>

                                <div>
                                    <label className="text-sm text-slate-300 mb-2 block">
                                        Account Number
                                    </label>
                                    <input
                                        type="number"
                                        inputMode="numeric"
                                        min="0"
                                        value={wireAccountNumber}
                                        onChange={(e) => {
                                            setWireAccountNumber(e.target.value);
                                            clearFormError('wire_account_number');
                                        }}
                                        placeholder="Enter account number"
                                        className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    {formErrors.wire_account_number && (
                                        <p className="mt-2 text-sm text-red-400">{formErrors.wire_account_number}</p>
                                    )}
                                </div>

                                <div className="grid grid-cols-2 gap-3">
                                    <div>
                                        <label className="text-sm text-slate-300 mb-2 block">
                                            Routing Number
                                        </label>
                                        <input
                                            type="text"
                                            inputMode="numeric"
                                            pattern="[0-9]*"
                                            value={wireRoutingNumber}
                                            onChange={(e) => {
                                                setWireRoutingNumber(e.target.value);
                                                clearFormError('wire_routing_number');
                                            }}
                                            placeholder="Routing #"
                                            className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required
                                        />
                                        {formErrors.wire_routing_number && (
                                            <p className="mt-2 text-sm text-red-400">{formErrors.wire_routing_number}</p>
                                        )}
                                    </div>
                                    <div>
                                        <label className="text-sm text-slate-300 mb-2 block">
                                            SWIFT/BIC Code
                                        </label>
                                        <input
                                            type="text"
                                            value={wireSwiftCode}
                                            onChange={(e) => {
                                                setWireSwiftCode(e.target.value.toUpperCase());
                                                clearFormError('wire_swift_code');
                                            }}
                                            placeholder="Optional"
                                            className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        />
                                        {formErrors.wire_swift_code && (
                                            <p className="mt-2 text-sm text-red-400">{formErrors.wire_swift_code}</p>
                                        )}
                                    </div>
                                </div>

                                <div>
                                    <label className="text-sm text-slate-300 mb-2 block">
                                        Beneficiary Address
                                    </label>
                                    <textarea
                                        value={wireBeneficiaryAddress}
                                        onChange={(e) => {
                                            setWireBeneficiaryAddress(e.target.value);
                                            clearFormError('wire_beneficiary_address');
                                        }}
                                        placeholder="Full address of beneficiary"
                                        rows={2}
                                        className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                        required
                                    />
                                    {formErrors.wire_beneficiary_address && (
                                        <p className="mt-2 text-sm text-red-400">{formErrors.wire_beneficiary_address}</p>
                                    )}
                                </div>
                            </>
                        )}

                        {/* Common Fields */}
                        <div>
                            <label className="text-sm text-slate-300 mb-2 block">
                                Amount
                            </label>
                            <div className="relative">
                                <span className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                    $
                                </span>
                                <input
                                    type="number"
                                    inputMode="decimal"
                                    step="0.01"
                                    min="0.01"
                                    value={amount}
                                    onChange={(e) => {
                                        setAmount(e.target.value);
                                        clearFormError('amount');
                                    }}
                                    placeholder="0.00"
                                    className="w-full pl-8 pr-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                            </div>
                            {formErrors.amount && (
                                <p className="mt-2 text-sm text-red-400">
                                    {formErrors.amount}
                                </p>
                            )}
                        </div>

                        <div>
                            <label className="text-sm text-slate-300 mb-2 block">
                                Description (Optional)
                            </label>
                                <textarea
                                    value={description}
                                    onChange={(e) => {
                                        setDescription(e.target.value);
                                        clearFormError('description');
                                    }}
                                    placeholder="What's this transfer for?"
                                    rows={3}
                                    className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                />
                                {formErrors.description && (
                                    <p className="mt-2 text-sm text-red-400">{formErrors.description}</p>
                                )}
                        </div>

                        <Button 
                            type="submit" 
                            className="w-full bg-blue-600 hover:bg-blue-700 text-white py-6 text-base"
                            disabled={loading || (transferType === 'internal' && !recipientName)}
                        >
                            Continue
                        </Button>
                    </form>
                </CardContent>
            </Card>

            {/* Info Card */}
            <Card className="bg-blue-950/20 border-blue-900">
                <CardContent className="pt-6 space-y-2">
                    <div className="flex items-start space-x-2">
                        <svg className="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div className="text-sm text-blue-200 space-y-1">
                            {transferType === 'internal' ? (
                                <>
                                    <p>• Internal transfers are instant</p>
                                    <p>• No fees for internal transfers</p>
                                    <p>• Make sure the account number is correct</p>
                                </>
                            ) : (
                                <>
                                    <p>• Wire transfers may take 1-3 business days</p>
                                    <p>• A wire transfer fee may apply</p>
                                    <p>• International transfers require SWIFT code</p>
                                    <p>• Verify all bank details before sending</p>
                                </>
                            )}
                        </div>
                    </div>
                </CardContent>
            </Card>
        </>
    );

    const renderVerifyStep = () => (
        <>
            {/* Summary Card */}
            <Card className="bg-slate-900 border-slate-800">
                <CardHeader>
                    <CardTitle className="text-slate-50 text-lg">Verify Transfer</CardTitle>
                    <CardDescription className="text-slate-400">
                        Review your transfer details
                    </CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="space-y-3">
                        <div className="flex justify-between py-2 border-b border-slate-800">
                            <span className="text-slate-400">Recipient</span>
                            <span className="text-slate-50 font-medium">
                                {transferType === 'internal' ? recipientName : wireBeneficiaryName}
                            </span>
                        </div>
                        {transferType === 'internal' ? (
                            <div className="flex justify-between py-2 border-b border-slate-800">
                                <span className="text-slate-400">Account</span>
                                <span className="text-slate-50 font-mono">{recipientAccount}</span>
                            </div>
                        ) : (
                            <>
                                <div className="flex justify-between py-2 border-b border-slate-800">
                                    <span className="text-slate-400">Bank</span>
                                    <span className="text-slate-50">{wireBankName}</span>
                                </div>
                                <div className="flex justify-between py-2 border-b border-slate-800">
                                    <span className="text-slate-400">Account</span>
                                    <span className="text-slate-50 font-mono">{wireAccountNumber}</span>
                                </div>
                            </>
                        )}
                        <div className="flex justify-between py-2 border-b border-slate-800">
                            <span className="text-slate-400">Amount</span>
                            <span className="text-slate-50 font-bold text-lg">
                                ${parseFloat(amount).toFixed(2)}
                            </span>
                        </div>
                        {description && (
                            <div className="flex justify-between py-2 border-b border-slate-800">
                                <span className="text-slate-400">Description</span>
                                <span className="text-slate-50 text-right text-sm">{description}</span>
                            </div>
                        )}
                    </div>
                </CardContent>
            </Card>

            {/* Verification Form */}
            <Card className="bg-slate-900 border-slate-800">
                <CardHeader>
                    <CardTitle className="text-slate-50 text-lg">Security Verification</CardTitle>
                    <CardDescription className="text-slate-400">
                        Enter your PIN and transaction code
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form onSubmit={handleVerifySubmit} className="space-y-4">
                        {error && (
                            <div className="p-3 bg-red-900/20 border border-red-800 rounded-lg">
                                <p className="text-sm text-red-300 flex items-center">
                                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    {error}
                                </p>
                            </div>
                        )}

                        <div>
                            <label className="text-sm text-slate-300 mb-2 block">
                                Transaction PIN
                            </label>
                            <input
                                type="password"
                                inputMode="numeric"
                                maxLength={6}
                                value={transactionPin}
                                onChange={(e) => setTransactionPin(e.target.value.replace(/\D/g, ''))}
                                placeholder="Enter 6-digit PIN"
                                className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-center text-2xl tracking-widest"
                                required
                            />
                        </div>

                        <div>
                            <label className="text-sm text-slate-300 mb-2 block">
                                Transaction Code
                            </label>
                            <input
                                type="text"
                                value={transactionCode}
                                onChange={(e) => setTransactionCode(e.target.value.toUpperCase())}
                                placeholder="XXX-XXX-XXX"
                                className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-center font-mono"
                                required
                            />
                            <p className="mt-2 text-xs text-slate-400">
                                Enter the transfer code provided by our bank. Need one?{' '}
                                <a
                                    href="#"
                                    onClick={handleRequestTransferCode}
                                    className="font-medium text-slate-200 underline underline-offset-4"
                                >
                                    {isRequestingCode ? 'Requesting…' : 'Request Transfer Code'}
                                </a>
                                .
                            </p>
                            {codeRequestFeedback && (
                                <p
                                    className={`mt-2 text-sm ${
                                        codeRequestFeedback.type === 'success' ? 'text-emerald-400' : 'text-red-400'
                                    }`}
                                >
                                    {codeRequestFeedback.message}
                                </p>
                            )}
                        </div>

                        <div className="space-y-2 pt-2">
                            <Button 
                                type="submit" 
                                className="w-full bg-green-600 hover:bg-green-700 text-white py-6 text-base"
                                disabled={loading || !transactionPin || !transactionCode}
                            >
                                Confirm Transfer
                            </Button>
                            <Button 
                                type="button"
                                onClick={handleBack}
                                variant="outline"
                                className="w-full border-slate-700 text-slate-300 hover:bg-slate-800 py-6"
                                disabled={loading}
                            >
                                Back
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </>
    );

    const renderProcessingStep = () => (
        <Card className="bg-slate-900 border-slate-800">
            <CardContent className="py-16">
                <div className="flex flex-col items-center justify-center space-y-6">
                    <div className="relative">
                        <div className="w-20 h-20 rounded-full border-4 border-blue-200 border-t-blue-600 animate-spin"></div>
                        <div className="absolute inset-0 flex items-center justify-center">
                            <svg className="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div className="text-center">
                        <h3 className="text-xl font-semibold text-slate-50 mb-2">Processing Transfer</h3>
                        <p className="text-slate-400">Please wait while we process your transaction...</p>
                    </div>
                    <div className="flex flex-col items-center space-y-2">
                        <div className="flex items-center text-sm text-slate-500">
                            <div className="w-2 h-2 rounded-full bg-blue-600 mr-2 animate-pulse"></div>
                            Verifying transaction details
                        </div>
                        <div className="flex items-center text-sm text-slate-500">
                            <div className="w-2 h-2 rounded-full bg-blue-600 mr-2 animate-pulse" style={{ animationDelay: '0.2s' }}></div>
                            Authenticating transaction code
                        </div>
                        <div className="flex items-center text-sm text-slate-500">
                            <div className="w-2 h-2 rounded-full bg-blue-600 mr-2 animate-pulse" style={{ animationDelay: '0.4s' }}></div>
                            Processing payment
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>
    );

    if (isLocked) {
        return (
            <MobileLayout user={auth.user} title="Transfer" currentRoute="transfer">
                <div className="px-4 py-6">
                    <Card className="border-rose-500/40 bg-rose-950/40 text-rose-100">
                        <CardContent className="pt-6 pb-6">
                            <div className="flex flex-col space-y-4">
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
                                            Transfers are currently disabled for your account. Please reach out to our support team to resolve this issue before attempting another transfer.
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <p className="text-xs text-rose-100/70">
                                        Need help? Contact us at <span className="font-semibold">{supportEmail ?? 'support@banko.test'}</span>.
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </MobileLayout>
        );
    }

    return (
        <MobileLayout user={auth.user} title="Transfer" currentRoute="transfer">
            <div className="px-4 py-6 space-y-6">
                {step === 'details' && renderDetailsStep()}
                {step === 'verify' && renderVerifyStep()}
                {step === 'processing' && renderProcessingStep()}
            </div>
        </MobileLayout>
    );
}
