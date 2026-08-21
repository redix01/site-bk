import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Head, Link, router } from '@inertiajs/react';
import { ArrowLeft, CheckCircle, XCircle, RotateCcw } from 'lucide-react';
import { Transaction, PageProps } from '@/types';

export default function Show({ transaction }: PageProps & { transaction: Transaction }) {
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
                <div>
                    <Link href="/admin/transactions" className="inline-flex items-center text-sm text-slate-400 hover:text-slate-50 mb-4">
                        <ArrowLeft className="h-4 w-4 mr-1" />
                        Back to Transactions
                    </Link>
                    <h1 className="text-3xl font-bold text-slate-50">{transaction.reference}</h1>
                    <p className="text-slate-400 mt-1">Transaction details and actions</p>
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
            </div>
        </AdminLayout>
    );
}


