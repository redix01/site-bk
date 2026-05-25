import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Link, Head } from '@inertiajs/react';
import { Transaction, PageProps } from '@/types';
import { ArrowLeftRight, Plus, DollarSign } from 'lucide-react';

interface TransactionsIndexProps extends PageProps {
    transactions: {
        data: Transaction[];
        links: any[];
        meta: any;
    };
}

export default function Index({ transactions }: TransactionsIndexProps) {
    const formatTransactionType = (type: string) => (
        type.split('_').map((part) => part.charAt(0).toUpperCase() + part.slice(1)).join(' ')
    );

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'completed':
                return 'bg-emerald-900/50 text-emerald-200 border-emerald-700';
            case 'pending':
                return 'bg-amber-900/50 text-amber-200 border-amber-700';
            case 'failed':
                return 'bg-red-900/50 text-red-200 border-red-700';
            case 'cancelled':
                return 'bg-slate-800 text-slate-300 border-slate-700';
            default:
                return 'bg-slate-800 text-slate-300 border-slate-700';
        }
    };

    const getTypeColor = (type: string) => {
        switch (type) {
            case 'deposit':
                return 'bg-green-900/50 text-green-200 border-green-700';
            case 'withdrawal':
            case 'fee':
            case 'stamp_duty':
            case 'monthly_fee':
                return 'bg-red-900/50 text-red-200 border-red-700';
            case 'transfer':
                return 'bg-blue-900/50 text-blue-200 border-blue-700';
            default:
                return 'bg-slate-800 text-slate-300 border-slate-700';
        }
    };

    return (
        <AdminLayout>
            <Head title="Transactions" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold text-slate-50">Transactions</h1>
                        <p className="text-slate-400 mt-1">Monitor and manage all transactions</p>
                    </div>
                    <Link href="/admin/transactions/create">
                        <Button className="bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-900/50 hover:shadow-xl hover:shadow-emerald-900/60 transition-all">
                            <Plus className="h-4 w-4 mr-2" />
                            New Transaction
                        </Button>
                    </Link>
                </div>

                {/* Stats */}
                <div className="grid gap-4 md:grid-cols-4">
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="pb-2">
                            <CardDescription className="text-slate-400">Total</CardDescription>
                            <CardTitle className="text-2xl text-slate-50">
                                {transactions.meta?.total || transactions.data.length}
                            </CardTitle>
                        </CardHeader>
                    </Card>
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="pb-2">
                            <CardDescription className="text-slate-400">Completed</CardDescription>
                            <CardTitle className="text-2xl text-slate-50">
                                {transactions.data.filter(t => t.status === 'completed').length}
                            </CardTitle>
                        </CardHeader>
                    </Card>
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="pb-2">
                            <CardDescription className="text-slate-400">Pending</CardDescription>
                            <CardTitle className="text-2xl text-slate-50">
                                {transactions.data.filter(t => t.status === 'pending').length}
                            </CardTitle>
                        </CardHeader>
                    </Card>
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader className="pb-2">
                            <CardDescription className="text-slate-400">Failed</CardDescription>
                            <CardTitle className="text-2xl text-slate-50">
                                {transactions.data.filter(t => t.status === 'failed').length}
                            </CardTitle>
                        </CardHeader>
                    </Card>
                </div>

                {/* Transactions Table */}
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50">All Transactions</CardTitle>
                        <CardDescription className="text-slate-400">
                            Complete transaction history
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b border-slate-800">
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Reference</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">User</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Type</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Amount</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Status</th>
                                        <th className="text-left py-3 px-4 text-sm font-medium text-slate-400">Date</th>
                                        <th className="text-right py-3 px-4 text-sm font-medium text-slate-400">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {transactions.data.map((transaction) => (
                                        <tr key={transaction.id} className="border-b border-slate-800 hover:bg-slate-800/50">
                                            <td className="py-3 px-4">
                                                <span className="text-sm font-mono text-slate-50">{transaction.reference}</span>
                                            </td>
                                            <td className="py-3 px-4">
                                                <div className="flex items-center space-x-2">
                                                    <div className="h-8 w-8 rounded-full bg-slate-800 flex items-center justify-center">
                                                        <DollarSign className="h-4 w-4 text-slate-400" />
                                                    </div>
                                                    <span className="text-sm text-slate-50">
                                                        {transaction.user?.name || 'N/A'}
                                                    </span>
                                                </div>
                                            </td>
                                            <td className="py-3 px-4">
                                                <Badge className={getTypeColor(transaction.type)}>
                                                    {formatTransactionType(transaction.type)}
                                                </Badge>
                                            </td>
                                            <td className="py-3 px-4">
                                                <span className="text-sm font-semibold text-slate-50">
                                                    ${(transaction.amount / 100).toLocaleString('en-US', { minimumFractionDigits: 2 })}
                                                </span>
                                            </td>
                                            <td className="py-3 px-4">
                                                <Badge className={getStatusColor(transaction.status)}>
                                                    {transaction.status}
                                                </Badge>
                                            </td>
                                            <td className="py-3 px-4 text-sm text-slate-400">
                                                {new Date(transaction.created_at).toLocaleDateString()}
                                            </td>
                                            <td className="py-3 px-4 text-right">
                                                <Link
                                                    href={`/admin/transactions/${transaction.id}`}
                                                    className="text-sm text-slate-400 hover:text-slate-50"
                                                >
                                                    View
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>

                            {transactions.data.length === 0 && (
                                <div className="text-center py-12">
                                    <ArrowLeftRight className="h-12 w-12 text-slate-700 mx-auto mb-3" />
                                    <p className="text-slate-500">No transactions found</p>
                                </div>
                            )}
                        </div>

                        {/* Pagination */}
                        {transactions.links && transactions.links.length > 3 && (
                            <div className="flex justify-center mt-6 space-x-2">
                                {transactions.links.map((link: any, index: number) => (
                                    link.url ? (
                                        <Link
                                            key={index}
                                            href={link.url}
                                            className={`px-3 py-1 rounded text-sm ${
                                                link.active
                                                    ? 'bg-slate-700 text-slate-50'
                                                    : 'bg-slate-800 text-slate-400 hover:bg-slate-700'
                                            }`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ) : (
                                        <span
                                            key={index}
                                            className="px-3 py-1 rounded text-sm bg-slate-900 text-slate-600"
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    )
                                ))}
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}
