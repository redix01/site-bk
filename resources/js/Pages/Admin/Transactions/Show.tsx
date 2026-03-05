import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { ArrowLeft, CheckCircle, XCircle, RotateCcw, Pencil } from 'lucide-react';
import { Transaction, PageProps } from '@/types';
import { FormEventHandler } from 'react';

export default function Show({ transaction }: PageProps & { transaction: Transaction }) {
    const formatDateForInput = (value?: string | null) => {
        if (!value) return '';
        const parsed = new Date(value);
        if (Number.isNaN(parsed.getTime())) return '';
        const year = parsed.getFullYear();
        const month = String(parsed.getMonth() + 1).padStart(2, '0');
        const day = String(parsed.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const createdAtForm = useForm({
        created_at: formatDateForInput(transaction.created_at),
    });

    const handleApprove = () => {
        if (confirm('Approve this transaction?')) {
            router.post(`/admin/transactions/${transaction.id}/approve`);
        }
    };

    const handleReject = () => {
        const reason = prompt('Enter rejection reason:');
        if (reason) {
            router.post(`/admin/transactions/${transaction.id}/reject`, { reason });
        }
    };

    const handleReverse = () => {
        const reason = prompt('Enter reversal reason:');
        if (reason) {
            router.post(`/admin/transactions/${transaction.id}/reverse`, { reason });
        }
    };

    const submitCreatedAt: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();
        createdAtForm.patch(`/admin/transactions/${transaction.id}/created-at`, {
            preserveScroll: true,
            onSuccess: () => createdAtForm.clearErrors(),
        });
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'completed': return 'bg-emerald-900/50 text-emerald-200 border-emerald-700';
            case 'pending': return 'bg-amber-900/50 text-amber-200 border-amber-700';
            case 'failed': return 'bg-red-900/50 text-red-200 border-red-700';
            default: return 'bg-slate-800 text-slate-300 border-slate-700';
        }
    };

    const getTypeColor = (type: string) => {
        switch (type) {
            case 'deposit': return 'bg-green-900/50 text-green-200 border-green-700';
            case 'withdrawal': return 'bg-red-900/50 text-red-200 border-red-700';
            case 'transfer': return 'bg-blue-900/50 text-blue-200 border-blue-700';
            default: return 'bg-slate-800 text-slate-300 border-slate-700';
        }
    };

    return (
        <AdminLayout>
            <Head title={`Transaction: ${transaction.reference}`} />
            
            <div className="space-y-6">
                <div className="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <Link href="/admin/transactions" className="inline-flex items-center text-sm text-slate-400 hover:text-slate-50 mb-4">
                            <ArrowLeft className="h-4 w-4 mr-1" />
                            Back to Transactions
                        </Link>
                        <h1 className="text-3xl font-bold text-slate-50">{transaction.reference}</h1>
                        <p className="text-slate-400 mt-1">Transaction details and actions</p>
                    </div>
                    <Link href={`/admin/transactions/${transaction.id}/edit`}>
                        <Button
                            type="button"
                            variant="outline"
                            className="border-slate-700 bg-slate-800 text-slate-100 hover:bg-slate-700"
                        >
                            <Pencil className="mr-2 h-4 w-4" />
                            Edit Transaction
                        </Button>
                    </Link>
                </div>

                <div className="grid gap-4 md:grid-cols-2">
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Transaction Details</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <p className="text-sm text-slate-400">Reference</p>
                                <p className="text-slate-50 font-mono font-medium">{transaction.reference}</p>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">User</p>
                                <p className="text-slate-50 font-medium">{transaction.user?.name || 'N/A'}</p>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Type</p>
                                <Badge className={getTypeColor(transaction.type)}>
                                    {transaction.type}
                                </Badge>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Amount</p>
                                <p className="text-2xl font-bold text-slate-50">
                                    ${(transaction.amount / 100).toLocaleString('en-US', { minimumFractionDigits: 2 })}
                                </p>
                            </div>
                            <div>
                                <p className="text-sm text-slate-400">Status</p>
                                <Badge className={getStatusColor(transaction.status)}>
                                    {transaction.status}
                                </Badge>
                            </div>
                            {transaction.description && (
                                <div>
                                    <p className="text-sm text-slate-400">Description</p>
                                    <p className="text-slate-50">{transaction.description}</p>
                                </div>
                            )}
                            <div>
                                <p className="text-sm text-slate-400">Date</p>
                                <p className="text-slate-50">{new Date(transaction.created_at).toLocaleString()}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Actions</CardTitle>
                            <CardDescription className="text-slate-400">Manage this transaction</CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-3">
                            {transaction.status === 'pending' && (
                                <>
                                    <Button
                                        onClick={handleApprove}
                                        className="w-full justify-start bg-emerald-900/50 border-emerald-700 hover:bg-emerald-800/50 text-emerald-200"
                                        variant="outline"
                                    >
                                        <CheckCircle className="h-4 w-4 mr-2" />
                                        Approve Transaction
                                    </Button>
                                    <Button
                                        onClick={handleReject}
                                        className="w-full justify-start bg-red-900/50 border-red-700 hover:bg-red-800/50 text-red-200"
                                        variant="outline"
                                    >
                                        <XCircle className="h-4 w-4 mr-2" />
                                        Reject Transaction
                                    </Button>
                                </>
                            )}
                            {transaction.status === 'completed' && (
                                <Button
                                    onClick={handleReverse}
                                    className="w-full justify-start bg-amber-900/50 border-amber-700 hover:bg-amber-800/50 text-amber-200"
                                    variant="outline"
                                >
                                    <RotateCcw className="h-4 w-4 mr-2" />
                                    Reverse Transaction
                                </Button>
                            )}
                            {transaction.status === 'pending' && (
                                <p className="text-xs text-slate-500 mt-4">
                                    This transaction requires admin approval before processing.
                                </p>
                            )}
                        </CardContent>
                    </Card>
                </div>

                <Card id="transaction-date-editor" className="bg-slate-900 border-slate-800 md:max-w-xl">
                    <CardHeader>
                        <CardTitle className="text-slate-50">Edit Transaction Date</CardTitle>
                        <CardDescription className="text-slate-400">
                            Move the transaction date backward or forward
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-4">
                            <div>
                                <p className="text-sm text-slate-400">Current date and time</p>
                                <p className="mt-2 text-slate-50">{new Date(transaction.created_at).toLocaleString()}</p>
                            </div>

                            <form onSubmit={submitCreatedAt} className="space-y-3">
                                <div>
                                    <label htmlFor="created_at" className="mb-2 block text-sm font-medium text-slate-300">
                                        New transaction date
                                    </label>
                                    <input
                                        id="created_at"
                                        type="date"
                                        value={createdAtForm.data.created_at}
                                        onChange={(event) => createdAtForm.setData('created_at', event.target.value)}
                                        className="h-11 w-full rounded-lg border border-slate-700 bg-slate-950/60 px-3 text-sm text-slate-100 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/40"
                                        required
                                    />
                                    {createdAtForm.errors.created_at && (
                                        <p className="mt-2 text-xs text-red-400">{createdAtForm.errors.created_at}</p>
                                    )}
                                </div>

                                <Button
                                    type="submit"
                                    className="w-full justify-center bg-slate-700 hover:bg-slate-600 text-slate-50"
                                    disabled={createdAtForm.processing}
                                >
                                    {createdAtForm.processing ? 'Updating...' : 'Update Transaction Date'}
                                </Button>
                            </form>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}
