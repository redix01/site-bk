import { FormEventHandler, useState, useEffect, useRef } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Mail, Lock, Banknote } from 'lucide-react';

interface LoginProps {
    status?: string;
    flash?: {
        success?: string;
    };
}

export default function Login({ status, flash }: LoginProps) {
    const [botWarning, setBotWarning] = useState<string | null>(null);
    const [humanDetected, setHumanDetected] = useState<boolean>(false);
    const mountTimeRef = useRef<number>(Date.now());

    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
        internal_code: '',
        page_loaded_at: '',
        interaction_token: 'pending',
    });
    const securityError = (errors as Record<string, string | undefined>).form_security;

    useEffect(() => {
        const startedAt = Date.now();
        mountTimeRef.current = startedAt;
        setData('page_loaded_at', Math.floor(startedAt / 1000).toString());
        setData('internal_code', '');
        setData('interaction_token', 'pending');

        const markHuman = () => {
            setHumanDetected(true);
            setBotWarning(null);
            setData('interaction_token', 'human');
        };

        const events: Array<keyof WindowEventMap> = [
            'mousemove',
            'mousedown',
            'keydown',
            'touchstart',
            'pointerdown',
            'scroll',
        ];

        events.forEach((event) => {
            window.addEventListener(event, markHuman, { once: true });
        });

        return () => {
            events.forEach((event) => {
                window.removeEventListener(event, markHuman);
            });
        };
    }, [setData]);

    const isSubmissionSuspicious = () => {
        const minimumFormTimeMs = 3000;
        const elapsedTime = Date.now() - mountTimeRef.current;

        if (data.internal_code.trim() !== '') {
            setBotWarning('Security challenge failed. Please refresh the page and try again.');
            return true;
        }

        if (!humanDetected || data.interaction_token !== 'human') {
            setBotWarning('Please interact with the page (move your mouse or tap) before continuing.');
            return true;
        }

        if (elapsedTime < minimumFormTimeMs) {
            setBotWarning('Please take a moment before continuing.');
            return true;
        }

        setBotWarning(null);
        return false;
    };

    const securityPayload = {
        internal_code: data.internal_code,
        page_loaded_at: data.page_loaded_at,
        interaction_token: data.interaction_token,
    };

    const handleLoginSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        if (isSubmissionSuspicious()) {
            return;
        }
        post('/login', {
            data: {
                ...data,
                ...securityPayload,
            }
        });
    };

    return (
        <>
            <Head title="Log in" />

            <div className="fixed inset-0 flex items-center justify-center overflow-auto" style={{ backgroundColor: '#020617' }}>
                <div className="w-full py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center min-h-full">
                <Card className="w-full max-w-md bg-slate-900 border-slate-800">
                    <CardHeader className="space-y-3 text-center">
                        <div className="flex flex-col items-center space-y-2">
                            <div className="flex h-12 w-12 items-center justify-center rounded-full border border-blue-500/40 bg-blue-500/10">
                                <Banknote className="h-6 w-6 text-blue-400" />
                            </div>
                            <span className="text-lg font-semibold tracking-[0.35em] text-slate-300 uppercase">
                                Banko
                            </span>
                        </div>
                        <CardTitle className="text-2xl font-bold text-center text-slate-50">
                            Welcome back
                        </CardTitle>
                        <CardDescription className="text-center text-slate-400">
                            Enter your email and password to continue
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        {status && (
                            <div className="mb-4 rounded-md bg-emerald-900/50 border border-emerald-700 p-3">
                                <p className="text-sm text-emerald-200">{status}</p>
                            </div>
                        )}

                        {(botWarning || securityError) && (
                            <div className="mb-4 rounded-md border border-red-500/60 bg-red-500/10 p-3 text-sm text-red-200">
                                {botWarning ?? securityError}
                            </div>
                        )}

                        <form onSubmit={handleLoginSubmit} className="space-y-4">
                            <div
                                aria-hidden="true"
                                style={{
                                    position: 'absolute',
                                    left: '-10000px',
                                    top: 'auto',
                                    width: '1px',
                                    height: '1px',
                                    overflow: 'hidden',
                                }}
                            >
                                <label htmlFor="internal_code">Internal code</label>
                                <input
                                    id="internal_code"
                                    name="internal_code"
                                    type="text"
                                    tabIndex={-1}
                                    autoComplete="off"
                                    value={data.internal_code}
                                    onChange={(event) => setData('internal_code', event.target.value)}
                                />
                            </div>

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
                                        className="w-full pl-10 rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-transparent placeholder:text-slate-500"
                                        placeholder="Enter your email address"
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
                                        className="w-full pl-10 rounded-md bg-slate-800 border border-slate-700 text-slate-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-transparent"
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
                                        className="h-4 w-4 rounded border-slate-700 bg-slate-800 text-slate-400 focus:ring-slate-600"
                                    />
                                    <span className="ml-2 text-sm text-slate-400">Remember me</span>
                                </label>
                            </div>

                            <Button
                                type="submit"
                                disabled={processing}
                                className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg"
                            >
                                {processing ? 'Accessing...' : 'Access Login'}
                            </Button>
                        </form>

                        <div className="mt-6 text-center text-sm">
                            <span className="text-slate-400">Don't have an account? </span>
                            <Link
                                href="/register"
                                className="font-medium text-slate-300 hover:text-slate-50"
                            >
                                Sign up
                            </Link>
                        </div>
                    </CardContent>
                </Card>
                </div>
            </div>
        </>
    );
}
