import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Head, Link, useForm } from '@inertiajs/react';
import { PageProps } from '@/types';
import { ArrowLeft } from 'lucide-react';
import { FormEvent, useState } from 'react';

export default function Create({ types }: PageProps & { types: Record<string, string> }) {
    const { data, setData, post, processing, errors } = useForm({
        type: 'bank',
        name: '',
        key: '',
        enabled: true,
        min_amount: 1000,
        max_amount: '',
        processing_time: '',
        fee_percentage: '',
        fee_fixed: '',
        requires_reference: true,
        sort_order: 0,
        configuration: {} as Record<string, any>,
        instructions: {} as Record<string, string>,
        notes: [] as string[],
    });

    const [configFields, setConfigFields] = useState<Array<{ key: string; value: string }>>([{ key: '', value: '' }]);
    const [instructionFields, setInstructionFields] = useState<Array<{ key: string; value: string }>>([{ key: '', value: '' }]);
    const [noteFields, setNoteFields] = useState<string[]>(['']);
    
    // Crypto wallet fields
    const [cryptoWallets, setCryptoWallets] = useState<Array<{ 
        currency: string; 
        name: string; 
        address: string; 
        network: string;
    }>>([
        { currency: 'BTC', name: 'Bitcoin (BTC)', address: '', network: 'Bitcoin' },
        { currency: 'ETH', name: 'Ethereum (ETH)', address: '', network: 'Ethereum (ERC-20)' },
        { currency: 'USDT', name: 'Tether (USDT)', address: '', network: 'Ethereum (ERC-20)' },
    ]);

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();

        // Build configuration object from fields
        const configuration: Record<string, any> = {};
        
        // For crypto type, add currencies from crypto wallets
        if (data.type === 'crypto') {
            const currencies: Record<string, any> = {};
            cryptoWallets.forEach(wallet => {
                if (wallet.currency && wallet.address) {
                    currencies[wallet.currency] = {
                        name: wallet.name,
                        address: wallet.address,
                        network: wallet.network,
                    };
                }
            });
            if (Object.keys(currencies).length > 0) {
                configuration.currencies = currencies;
            }
        }
        
        // Add other config fields
        configFields.forEach(field => {
            if (field.key && field.value) {
                configuration[field.key] = field.value;
            }
        });

        // Build instructions object from fields
        const instructions: Record<string, string> = {};
        instructionFields.forEach(field => {
            if (field.key && field.value) {
                instructions[field.key] = field.value;
            }
        });

        // Filter out empty notes
        const notes = noteFields.filter(note => note.trim() !== '');

        post('/admin/payment-methods', {
            ...data,
            configuration,
            instructions,
            notes,
        });
    };

    const addConfigField = () => {
        setConfigFields([...configFields, { key: '', value: '' }]);
    };

    const removeConfigField = (index: number) => {
        setConfigFields(configFields.filter((_, i) => i !== index));
    };

    const addInstructionField = () => {
        setInstructionFields([...instructionFields, { key: '', value: '' }]);
    };

    const removeInstructionField = (index: number) => {
        setInstructionFields(instructionFields.filter((_, i) => i !== index));
    };

    const addNoteField = () => {
        setNoteFields([...noteFields, '']);
    };

    const removeNoteField = (index: number) => {
        setNoteFields(noteFields.filter((_, i) => i !== index));
    };

    // Crypto wallet handlers
    const addCryptoWallet = () => {
        setCryptoWallets([...cryptoWallets, { currency: '', name: '', address: '', network: '' }]);
    };

    const removeCryptoWallet = (index: number) => {
        setCryptoWallets(cryptoWallets.filter((_, i) => i !== index));
    };

    const updateCryptoWallet = (index: number, field: string, value: string) => {
        const newWallets = [...cryptoWallets];
        (newWallets[index] as any)[field] = value;
        setCryptoWallets(newWallets);
    };

    return (
        <AdminLayout>
            <Head title="Add Payment Method" />
            
            <div className="space-y-6">
                <div className="flex items-center gap-4">
                    <Link href="/admin/payment-methods">
                        <Button variant="ghost" className="text-slate-400 hover:text-slate-50">
                            <ArrowLeft className="h-4 w-4 mr-2" />
                            Back
                        </Button>
                    </Link>
                    <div>
                        <h1 className="text-3xl font-bold text-slate-50">Add Payment Method</h1>
                        <p className="text-slate-400 mt-1">Configure a new deposit method</p>
                    </div>
                </div>

                <form onSubmit={handleSubmit}>
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Basic Information</CardTitle>
                            <CardDescription className="text-slate-400">
                                Set up the basic details for this payment method
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            {/* Type */}
                            <div>
                                <label className="block text-sm font-medium text-slate-300 mb-2">
                                    Type <span className="text-red-400">*</span>
                                </label>
                                <select
                                    value={data.type}
                                    onChange={(e) => {
                                        const value = e.target.value;
                                        setData((prev) => ({
                                            ...prev,
                                            type: value,
                                            ...(value === 'crypto' ? { name: 'Cryptocurrency', key: 'crypto' } : {}),
                                        }));
                                    }}
                                    className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                >
                                    {Object.entries(types).map(([value, label]) => (
                                        <option key={value} value={value}>{label}</option>
                                    ))}
                                </select>
                                {errors.type && <p className="text-red-400 text-sm mt-1">{errors.type}</p>}
                            </div>

                            {/* Name & Key — fixed for crypto since deposit lookups hardcode key === 'crypto' */}
                            {data.type === 'crypto' ? (
                                <div className="rounded-md border border-slate-700 bg-slate-800/50 px-3 py-2.5 text-xs text-slate-400">
                                    Name and key are fixed to <span className="font-mono text-slate-300">Cryptocurrency</span> / <span className="font-mono text-slate-300">crypto</span> so deposits can always find this method.
                                </div>
                            ) : (
                                <>
                                    <div>
                                        <label className="block text-sm font-medium text-slate-300 mb-2">
                                            Name <span className="text-red-400">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                            placeholder="e.g., Bank Transfer"
                                        />
                                        {errors.name && <p className="text-red-400 text-sm mt-1">{errors.name}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-medium text-slate-300 mb-2">
                                            Key (Unique Identifier) <span className="text-red-400">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.key}
                                            onChange={(e) => setData('key', e.target.value)}
                                            className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                            placeholder="e.g., bank_transfer"
                                        />
                                        <p className="text-slate-500 text-xs mt-1">Use lowercase with underscores, e.g., bank_transfer</p>
                                        {errors.key && <p className="text-red-400 text-sm mt-1">{errors.key}</p>}
                                    </div>
                                </>
                            )}

                            {/* Min Amount */}
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-300 mb-2">
                                        Min Amount (cents) <span className="text-red-400">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        value={data.min_amount}
                                        onChange={(e) => setData('min_amount', parseInt(e.target.value))}
                                        className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                        placeholder="1000"
                                    />
                                    <p className="text-slate-500 text-xs mt-1">1000 = $10.00</p>
                                    {errors.min_amount && <p className="text-red-400 text-sm mt-1">{errors.min_amount}</p>}
                                </div>

                                {/* Max Amount */}
                                <div>
                                    <label className="block text-sm font-medium text-slate-300 mb-2">
                                        Max Amount (cents)
                                    </label>
                                    <input
                                        type="number"
                                        value={data.max_amount}
                                        onChange={(e) => setData('max_amount', e.target.value)}
                                        className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                        placeholder="Leave empty for no limit"
                                    />
                                    {errors.max_amount && <p className="text-red-400 text-sm mt-1">{errors.max_amount}</p>}
                                </div>
                            </div>

                            {/* Processing Time */}
                            <div>
                                <label className="block text-sm font-medium text-slate-300 mb-2">
                                    Processing Time
                                </label>
                                <input
                                    type="text"
                                    value={data.processing_time}
                                    onChange={(e) => setData('processing_time', e.target.value)}
                                    className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    placeholder="e.g., 1-3 business days"
                                />
                                {errors.processing_time && <p className="text-red-400 text-sm mt-1">{errors.processing_time}</p>}
                            </div>

                            {/* Fees */}
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-300 mb-2">
                                        Fee Percentage (%)
                                    </label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        value={data.fee_percentage}
                                        onChange={(e) => setData('fee_percentage', e.target.value)}
                                        className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                        placeholder="e.g., 2.9"
                                    />
                                    {errors.fee_percentage && <p className="text-red-400 text-sm mt-1">{errors.fee_percentage}</p>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-slate-300 mb-2">
                                        Fixed Fee (cents)
                                    </label>
                                    <input
                                        type="number"
                                        value={data.fee_fixed}
                                        onChange={(e) => setData('fee_fixed', e.target.value)}
                                        className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                        placeholder="e.g., 2500 for $25"
                                    />
                                    {errors.fee_fixed && <p className="text-red-400 text-sm mt-1">{errors.fee_fixed}</p>}
                                </div>
                            </div>

                            {/* Toggles */}
                            <div className="space-y-3">
                                <label className="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        checked={data.enabled}
                                        onChange={(e) => setData('enabled', e.target.checked)}
                                        className="rounded bg-slate-800 border-slate-700 text-amber-600 focus:ring-amber-500"
                                    />
                                    <span className="text-sm text-slate-300">Enabled</span>
                                </label>

                                <label className="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        checked={data.requires_reference}
                                        onChange={(e) => setData('requires_reference', e.target.checked)}
                                        className="rounded bg-slate-800 border-slate-700 text-amber-600 focus:ring-amber-500"
                                    />
                                    <span className="text-sm text-slate-300">Requires Payment Reference</span>
                                </label>
                            </div>

                            {/* Sort Order */}
                            <div>
                                <label className="block text-sm font-medium text-slate-300 mb-2">
                                    Sort Order
                                </label>
                                <input
                                    type="number"
                                    value={data.sort_order}
                                    onChange={(e) => setData('sort_order', parseInt(e.target.value))}
                                    className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    placeholder="0"
                                />
                                <p className="text-slate-500 text-xs mt-1">Lower numbers appear first</p>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Crypto Wallets (only for crypto type) */}
                    {data.type === 'crypto' && (
                        <Card className="bg-slate-900 border-slate-800 mt-6">
                            <CardHeader>
                                <CardTitle className="text-slate-50">Cryptocurrency Wallets</CardTitle>
                                <CardDescription className="text-slate-400">
                                    Add wallet addresses for different cryptocurrencies
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                {cryptoWallets.map((wallet, index) => (
                                    <div key={index} className="p-4 bg-slate-800/50 rounded-lg space-y-3 border border-slate-700">
                                        <div className="grid grid-cols-2 gap-3">
                                            <div>
                                                <label className="block text-xs font-medium text-slate-400 mb-1">
                                                    Currency Code
                                                </label>
                                                <input
                                                    type="text"
                                                    value={wallet.currency}
                                                    onChange={(e) => updateCryptoWallet(index, 'currency', e.target.value)}
                                                    className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                                    placeholder="e.g., BTC, ETH, USDT"
                                                />
                                            </div>
                                            <div>
                                                <label className="block text-xs font-medium text-slate-400 mb-1">
                                                    Display Name
                                                </label>
                                                <input
                                                    type="text"
                                                    value={wallet.name}
                                                    onChange={(e) => updateCryptoWallet(index, 'name', e.target.value)}
                                                    className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                                    placeholder="e.g., Bitcoin (BTC)"
                                                />
                                            </div>
                                        </div>
                                        <div>
                                            <label className="block text-xs font-medium text-slate-400 mb-1">
                                                Wallet Address
                                            </label>
                                            <input
                                                type="text"
                                                value={wallet.address}
                                                onChange={(e) => updateCryptoWallet(index, 'address', e.target.value)}
                                                className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                                                placeholder="e.g., bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-xs font-medium text-slate-400 mb-1">
                                                Network
                                            </label>
                                            <input
                                                type="text"
                                                value={wallet.network}
                                                onChange={(e) => updateCryptoWallet(index, 'network', e.target.value)}
                                                className="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                                placeholder="e.g., Bitcoin, Ethereum (ERC-20)"
                                            />
                                        </div>
                                        <div className="flex items-center gap-2 pt-2 border-t border-slate-700">
                                            <div className="flex-1 text-xs text-slate-500">
                                                {wallet.currency && wallet.address ? 
                                                    '✓ Ready to save' : 
                                                    'Fill in Currency Code and Wallet Address'
                                                }
                                            </div>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                onClick={() => removeCryptoWallet(index)}
                                                className="text-red-400 hover:text-red-300"
                                            >
                                                Remove
                                            </Button>
                                        </div>
                                    </div>
                                ))}
                                <Button type="button" variant="outline" onClick={addCryptoWallet}>
                                    Add Currency
                                </Button>
                            </CardContent>
                        </Card>
                    )}

                    {/* Configuration (for non-crypto fields) */}
                    <Card className="bg-slate-900 border-slate-800 mt-6">
                        <CardHeader>
                            <CardTitle className="text-slate-50">
                                {data.type === 'crypto' ? 'Additional Configuration' : 'Configuration'}
                            </CardTitle>
                            <CardDescription className="text-slate-400">
                                {data.type === 'crypto' 
                                    ? 'Add any additional configuration (optional)' 
                                    : 'Add configuration details (e.g., account numbers, API keys)'}
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            {configFields.map((field, index) => (
                                <div key={index} className="flex gap-2">
                                    <input
                                        type="text"
                                        value={field.key}
                                        onChange={(e) => {
                                            const newFields = [...configFields];
                                            newFields[index].key = e.target.value;
                                            setConfigFields(newFields);
                                        }}
                                        className="flex-1 px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                        placeholder="Key (e.g., api_key)"
                                    />
                                    <input
                                        type="text"
                                        value={field.value}
                                        onChange={(e) => {
                                            const newFields = [...configFields];
                                            newFields[index].value = e.target.value;
                                            setConfigFields(newFields);
                                        }}
                                        className="flex-1 px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                        placeholder="Value"
                                    />
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        onClick={() => removeConfigField(index)}
                                        className="text-red-400 hover:text-red-300"
                                    >
                                        Remove
                                    </Button>
                                </div>
                            ))}
                            <Button type="button" variant="outline" onClick={addConfigField}>
                                Add Field
                            </Button>
                        </CardContent>
                    </Card>

                    {/* Instructions — not applicable to crypto, which uses the Cryptocurrency Wallets card above */}
                    {data.type !== 'crypto' && (
                        <Card className="bg-slate-900 border-slate-800 mt-6">
                            <CardHeader>
                                <CardTitle className="text-slate-50">Instructions</CardTitle>
                                <CardDescription className="text-slate-400">
                                    Payment instructions shown to users
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                {instructionFields.map((field, index) => (
                                    <div key={index} className="flex gap-2">
                                        <input
                                            type="text"
                                            value={field.key}
                                            onChange={(e) => {
                                                const newFields = [...instructionFields];
                                                newFields[index].key = e.target.value;
                                                setInstructionFields(newFields);
                                            }}
                                            className="flex-1 px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                            placeholder="Label (e.g., Account Number)"
                                        />
                                        <input
                                            type="text"
                                            value={field.value}
                                            onChange={(e) => {
                                                const newFields = [...instructionFields];
                                                newFields[index].value = e.target.value;
                                                setInstructionFields(newFields);
                                            }}
                                            className="flex-1 px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                            placeholder="Value"
                                        />
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            onClick={() => removeInstructionField(index)}
                                            className="text-red-400 hover:text-red-300"
                                        >
                                            Remove
                                        </Button>
                                    </div>
                                ))}
                                <Button type="button" variant="outline" onClick={addInstructionField}>
                                    Add Instruction
                                </Button>
                            </CardContent>
                        </Card>
                    )}

                    {/* Notes */}
                    <Card className="bg-slate-900 border-slate-800 mt-6">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Notes</CardTitle>
                            <CardDescription className="text-slate-400">
                                Important notes or disclaimers for users
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            {noteFields.map((note, index) => (
                                <div key={index} className="flex gap-2">
                                    <input
                                        type="text"
                                        value={note}
                                        onChange={(e) => {
                                            const newNotes = [...noteFields];
                                            newNotes[index] = e.target.value;
                                            setNoteFields(newNotes);
                                        }}
                                        className="flex-1 px-3 py-2 bg-slate-800 border border-slate-700 rounded-md text-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                        placeholder="Note text"
                                    />
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        onClick={() => removeNoteField(index)}
                                        className="text-red-400 hover:text-red-300"
                                    >
                                        Remove
                                    </Button>
                                </div>
                            ))}
                            <Button type="button" variant="outline" onClick={addNoteField}>
                                Add Note
                            </Button>
                        </CardContent>
                    </Card>

                    <div className="flex gap-4 mt-6">
                        <Button
                            type="submit"
                            disabled={processing}
                            className="bg-amber-600 hover:bg-amber-700 text-white"
                        >
                            {processing ? 'Creating...' : 'Create Payment Method'}
                        </Button>
                        <Link href="/admin/payment-methods">
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                        </Link>
                    </div>
                </form>
            </div>
        </AdminLayout>
    );
}


