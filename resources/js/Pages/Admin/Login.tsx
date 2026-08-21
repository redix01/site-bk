import { FormEventHandler, useEffect } from 'react';
import { Head, router, useForm } from '@inertiajs/react';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Mail, Lock, Shield } from 'lucide-react';

interface AdminLoginProps {
    status?: string;
}

export default function AdminLogin({ status }: AdminLoginProps) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        router.visit('/login', { replace: true });
    }, []);

    const handleSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <>
            <Head title="Admin Login" />

            <div className="fixed inset-0 flex items-center justify-center overflow-auto" style={{ backgroundColor: '#020617' }}>
                <div className="w-full py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center min-h-full">
                    <Card className="w-full max-w-md bg-slate-900 border-slate-800">
                        <CardHeader className="space-y-1">
                            <div className="flex items-center justify-center mb-4">
                                <div className="p-3 bg-blue-600/10 rounded-full">
                                    <Shield className="h-8 w-8 text-blue-500" />
                                </div>
                            </div>
                            <CardTitle className="text-2xl font-bold text-center text-slate-50">
                                Admin Login
                            </CardTitle>
                            <CardDescription className="text-center text-slate-400">
                                Sign in to access the admin dashboard
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            {status && (
                                <div className="mb-4 rounded-md bg-emerald-900/50 border border-emerald-700 p-3">
                                    <p className="text-sm text-emerald-200">{status}</p>
                                </div>
                            )}

                            <form onSubmit={handleSubmit} className="space-y-4">
                                <div>
                                    <label htmlFor="email" className="block text-sm font-medium text-slate-300 mb-1">
                                        Email Address
                                    </label>
                                    <div className="relative">
                                        <Mail className="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 h-4 w-4" />
                                        <input
                                            id="email"
                                            type="email"
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                            className="w-full pl-10 rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent placeholder:text-slate-500"
                                            placeholder="admin@example.com"
                                            autoComplete="email"
                                            autoFocus
                                            required
                                        />
                                    </div>
                                    {errors.email && (
                                        <p className="mt-1 text-sm text-red-400">{errors.email}</p>
                                    )}
                                </div>

                                <div>
                                    <label htmlFor="password" className="block text-sm font-medium text-slate-300 mb-1">
                                        Password
                                    </label>
                                    <div className="relative">
                                        <Lock className="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 h-4 w-4" />
                                        <input
                                            id="password"
                                            type="password"
                                            value={data.password}
                                            onChange={(e) => setData('password', e.target.value)}
                                            className="w-full pl-10 rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent placeholder:text-slate-500"
                                            placeholder="Enter your password"
                                            autoComplete="current-password"
                                            required
                                        />
                                    </div>
                                    {errors.password && (
                                        <p className="mt-1 text-sm text-red-400">{errors.password}</p>
                                    )}
                                </div>

                                <div className="flex items-center justify-between">
                                    <label className="flex items-center">
                                        <input
                                            type="checkbox"
                                            checked={data.remember}
                                            onChange={(e) => setData('remember', e.target.checked)}
                                            className="h-4 w-4 rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-blue-600"
                                        />
                                        <span className="ml-2 text-sm text-slate-400">Remember me</span>
                                    </label>
                                </div>

                                <Button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg"
                                >
                                    {processing ? 'Signing in...' : 'Sign In'}
                                </Button>
                            </form>

                            <div className="mt-6 text-center">
                                <p className="text-xs text-slate-500">
                                    🔒 Restricted access for authorized administrators only
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </>
    );
}


