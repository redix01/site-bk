import { Head, Link } from '@inertiajs/react';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { ArrowRight, Shield, Zap, Lock } from 'lucide-react';

export default function Welcome() {
    return (
        <>
            <Head title="Welcome" />

            <div className="min-h-screen bg-slate-950">
                {/* Header */}
                <header className="border-b border-slate-800">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        <div className="flex justify-between items-center">
                            <h1 className="text-2xl font-bold text-slate-50">Banko</h1>
                            <div className="flex space-x-4">
                                <Link href="/login">
                                    <Button variant="ghost" className="text-slate-400 hover:text-slate-50">
                                        Sign in
                                    </Button>
                                </Link>
                                <Link href="/register">
                                    <Button className="bg-slate-700 hover:bg-slate-600">
                                        Get Started
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </header>

                {/* Hero Section */}
                <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                    <div className="text-center space-y-8">
                        <div className="space-y-4">
                            <h2 className="text-5xl font-bold text-slate-50">
                                Mini Bank Sandbox System
                            </h2>
                            <p className="text-xl text-slate-400 max-w-2xl mx-auto">
                                A secure banking sandbox with wallet payments, deposits, withdrawals, and transfers
                            </p>
                        </div>

                        <div className="flex justify-center space-x-4">
                            <Link href="/register">
                                <Button size="lg" className="bg-slate-700 hover:bg-slate-600">
                                    Create Account
                                    <ArrowRight className="ml-2 h-5 w-5" />
                                </Button>
                            </Link>
                            <Link href="/login">
                                <Button size="lg" variant="outline" className="bg-slate-900 border-slate-700 hover:bg-slate-800 text-slate-50">
                                    Sign In
                                </Button>
                            </Link>
                        </div>

                        {/* Features */}
                        <div className="grid gap-6 md:grid-cols-3 mt-20">
                            <Card className="bg-slate-900 border-slate-800">
                                <CardHeader>
                                    <div className="h-12 w-12 rounded-lg bg-slate-800 flex items-center justify-center mb-4">
                                        <Shield className="h-6 w-6 text-emerald-400" />
                                    </div>
                                    <CardTitle className="text-slate-50">Secure Transactions</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <p className="text-slate-400 text-sm">
                                        Bank-grade security with authorization codes and audit logging
                                    </p>
                                </CardContent>
                            </Card>

                            <Card className="bg-slate-900 border-slate-800">
                                <CardHeader>
                                    <div className="h-12 w-12 rounded-lg bg-slate-800 flex items-center justify-center mb-4">
                                        <Zap className="h-6 w-6 text-amber-400" />
                                    </div>
                                    <CardTitle className="text-slate-50">Instant Transfers</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <p className="text-slate-400 text-sm">
                                        Send and receive money instantly with real-time balance updates
                                    </p>
                                </CardContent>
                            </Card>

                            <Card className="bg-slate-900 border-slate-800">
                                <CardHeader>
                                    <div className="h-12 w-12 rounded-lg bg-slate-800 flex items-center justify-center mb-4">
                                        <Lock className="h-6 w-6 text-blue-400" />
                                    </div>
                                    <CardTitle className="text-slate-50">Admin Controls</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <p className="text-slate-400 text-sm">
                                        Comprehensive admin panel with full transaction monitoring
                                    </p>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </main>

                {/* Footer */}
                <footer className="border-t border-slate-800 mt-20">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        <p className="text-center text-slate-500 text-sm">
                            © 2025 Banko. Mini Bank Sandbox System.
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}


