import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import { ArrowLeft } from 'lucide-react';
import { User, PageProps } from '@/types';

export default function Edit({ user }: PageProps & { user: User }) {
    // Get balance from wallet if available, otherwise from user (convert cents to dollars)
    const getBalance = () => {
        if (user.wallet?.balance !== undefined && user.wallet.balance !== null) {
            // Wallet balance is stored as decimal dollars
            const walletBalance = typeof user.wallet.balance === 'number' 
                ? user.wallet.balance 
                : parseFloat(String(user.wallet.balance));
            return isNaN(walletBalance) ? 0 : walletBalance;
        }
        // User balance is stored in cents, convert to dollars
        if (user.balance !== undefined && user.balance !== null) {
            const userBalance = typeof user.balance === 'number' 
                ? user.balance 
                : parseFloat(String(user.balance));
            return isNaN(userBalance) ? 0 : userBalance / 100;
        }
        return 0;
    };

    const { data, setData, put, processing, errors } = useForm({
        name: user.name || '',
        email: user.email || '',
        phone: user.phone || '',
        balance: getBalance(),
        is_admin: user.is_admin || false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(`/admin/users/${user.id}`);
    };

    return (
        <AdminLayout>
            <Head title="Edit User" />
            
            <div className="space-y-6">
                <div>
                    <Link href="/admin/users" className="inline-flex items-center text-sm text-slate-400 hover:text-slate-50 mb-4">
                        <ArrowLeft className="h-4 w-4 mr-1" />
                        Back to Users
                    </Link>
                    <h1 className="text-3xl font-bold text-slate-50">Edit User</h1>
                    <p className="text-slate-400 mt-1">Update user information</p>
                </div>

                <Card className="bg-slate-900 border-slate-800 max-w-2xl mx-auto">
                    <CardHeader>
                        <CardTitle className="text-slate-50">User Information</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="space-y-4">
                            <div>
                                <label htmlFor="name" className="block text-sm font-medium text-slate-300 mb-1">
                                    Full Name <span className="text-red-400">*</span>
                                </label>
                                <input
                                    id="name"
                                    type="text"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    required
                                />
                                {errors.name && <p className="mt-1 text-sm text-red-400">{errors.name}</p>}
                            </div>

                            <div>
                                <label htmlFor="email" className="block text-sm font-medium text-slate-300 mb-1">
                                    Email <span className="text-red-400">*</span>
                                </label>
                                <input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    required
                                />
                                {errors.email && <p className="mt-1 text-sm text-red-400">{errors.email}</p>}
                            </div>

                            <div>
                                <label htmlFor="phone" className="block text-sm font-medium text-slate-300 mb-1">
                                    Phone Number <span className="text-red-400">*</span>
                                </label>
                                <input
                                    id="phone"
                                    type="tel"
                                    value={data.phone}
                                    onChange={(e) => setData('phone', e.target.value)}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    required
                                />
                                {errors.phone && <p className="mt-1 text-sm text-red-400">{errors.phone}</p>}
                            </div>

                            <div>
                                <label htmlFor="balance" className="block text-sm font-medium text-slate-300 mb-1">
                                    Balance ($)
                                </label>
                                <input
                                    id="balance"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    value={data.balance}
                                    onChange={(e) => {
                                        const value = parseFloat(e.target.value);
                                        setData('balance', isNaN(value) ? 0 : value);
                                    }}
                                    className="w-full rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600"
                                />
                                {errors.balance && <p className="mt-1 text-sm text-red-400">{errors.balance}</p>}
                            </div>

                            <div className="flex items-center space-x-2">
                                <input
                                    id="is_admin"
                                    type="checkbox"
                                    checked={data.is_admin}
                                    onChange={(e) => setData('is_admin', e.target.checked)}
                                    className="h-4 w-4 rounded border-slate-700 bg-slate-800 text-slate-400 focus:ring-slate-600"
                                />
                                <label htmlFor="is_admin" className="text-sm text-slate-300">
                                    Admin Account
                                </label>
                            </div>

                            <div className="flex items-center justify-end space-x-3 pt-4">
                                <Link href="/admin/users">
                                    <Button type="button" variant="outline" className="bg-slate-800 border-slate-700 hover:bg-slate-700 text-slate-50">
                                        Cancel
                                    </Button>
                                </Link>
                                <Button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-slate-700 hover:bg-slate-600 text-slate-50"
                                >
                                    {processing ? 'Updating...' : 'Update User'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}


