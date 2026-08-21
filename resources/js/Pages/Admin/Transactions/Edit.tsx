import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import { ArrowLeft } from 'lucide-react';
import { User, Transaction, PageProps } from '@/types';

export default function Edit({ transaction, users }: PageProps & { transaction: Transaction; users: User[] }) {
    const { data, setData, put, processing, errors } = useForm({
        user_id: transaction.user_id || '',
        type: transaction.type || 'deposit',
        amount: (transaction.amount / 100).toString(),
        description: transaction.description || '',
        status: transaction.status || 'pending',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(`/admin/transactions/${transaction.id}`);
    };

    return (
        <AdminLayout>
            <Head title="Edit Transaction" />
            
            <div className="space-y-6">
                <div>
                    <Link href="/admin/transactions" className="inline-flex items-center text-sm text-slate-400 hover:text-slate-50 mb-4">
                        <ArrowLeft className="h-4 w-4 mr-1" />
                        Back to Transactions
                    </Link>
                    <h1 className="text-3xl font-bold text-slate-50">Edit Transaction</h1>
                    <p className="text-slate-400 mt-1">Update transaction details</p>
                </div>

                <Card className="bg-slate-900 border-slate-800 max-w-2xl mx-auto">
                    <CardHeader>
                        <CardTitle className="text-slate-50">Transaction Details</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="space-y-4">
                            <div>
                                <label htmlFor="user_id" className="block text-sm font-medium text-slate-300 mb-1">
                                    User <span className="text-red-400">*</span>
                                </label>
                                <select
                                    id="user_id"
                                    value={data.user_id}
                                    onChange={(e) => setData('user_id', e.target.value)}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    required
                                >
                                    {users.map((user) => (
                                        <option key={user.id} value={user.id}>
                                            {user.name} ({user.email})
                                        </option>
                                    ))}
                                </select>
                                {errors.user_id && <p className="mt-1 text-sm text-red-400">{errors.user_id}</p>}
                            </div>

                            <div>
                                <label htmlFor="type" className="block text-sm font-medium text-slate-300 mb-1">
                                    Type <span className="text-red-400">*</span>
                                </label>
                                <select
                                    id="type"
                                    value={data.type}
                                    onChange={(e) => setData('type', e.target.value)}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    required
                                >
                                    <option value="deposit">Deposit</option>
                                    <option value="withdrawal">Withdrawal</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                                {errors.type && <p className="mt-1 text-sm text-red-400">{errors.type}</p>}
                            </div>

                            <div>
                                <label htmlFor="amount" className="block text-sm font-medium text-slate-300 mb-1">
                                    Amount ($) <span className="text-red-400">*</span>
                                </label>
                                <input
                                    id="amount"
                                    type="number"
                                    step="0.01"
                                    value={data.amount}
                                    onChange={(e) => setData('amount', e.target.value)}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    required
                                />
                                {errors.amount && <p className="mt-1 text-sm text-red-400">{errors.amount}</p>}
                            </div>

                            <div>
                                <label htmlFor="description" className="block text-sm font-medium text-slate-300 mb-1">
                                    Description <span className="text-red-400">*</span>
                                </label>
                                <textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    rows={3}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    required
                                />
                                {errors.description && <p className="mt-1 text-sm text-red-400">{errors.description}</p>}
                            </div>

                            <div>
                                <label htmlFor="status" className="block text-sm font-medium text-slate-300 mb-1">
                                    Status <span className="text-red-400">*</span>
                                </label>
                                <select
                                    id="status"
                                    value={data.status}
                                    onChange={(e) => setData('status', e.target.value)}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    required
                                >
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="failed">Failed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                {errors.status && <p className="mt-1 text-sm text-red-400">{errors.status}</p>}
                            </div>

                            <div className="flex items-center justify-end space-x-3 pt-4">
                                <Link href="/admin/transactions">
                                    <Button type="button" variant="outline" className="bg-slate-800 border-slate-700 hover:bg-slate-700 text-slate-50">
                                        Cancel
                                    </Button>
                                </Link>
                                <Button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-slate-700 hover:bg-slate-600 text-slate-50"
                                >
                                    {processing ? 'Saving...' : 'Save Changes'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}


