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

    const { data, setData, post, processing, errors } = useForm({
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

    const handleLoginSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        if (isSubmissionSuspicious()) {
            return;
        }
        post('/login', {
            data: {
                email: data.email,
                password: data.password,
                remember: data.remember,
                internal_code: data.internal_code,
                page_loaded_at: data.page_loaded_at,
                interaction_token: data.interaction_token,
            }
        });
    };

    return (
        <>
            <Head title="Log in" />

            <div className="fixed inset-0 flex items-center justify-center overflow-auto bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
                <div className="w-full py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center min-h-full">
                    <Card className="w-full max-w-md bg-slate-900/95 border-slate-700/50 shadow-2xl backdrop-blur-sm">
                        <CardHeader className="space-y-4 text-center pb-6">
                            <div className="flex flex-col items-center space-y-3">
                                <div className="flex h-14 w-14 items-center justify-center rounded-full border-2 border-blue-500/50 bg-blue-500/10 shadow-lg">
                                    <Banknote className="h-7 w-7 text-blue-400" />
                                </div>
                                <span className="text-xl font-bold tracking-[0.4em] text-slate-200 uppercase">
                                    Banko
                                </span>
                            </div>
                            <CardTitle className="text-3xl font-bold text-slate-50">
                                Welcome back
                            </CardTitle>
                            <CardDescription className="text-base text-slate-400">
                                Sign in to your account to continue
                            </CardDescription>
                        </CardHeader>
                        
                        <CardContent className="space-y-6">
                            {status && (
                                <div className="rounded-lg bg-emerald-900/30 border border-emerald-700/50 p-4">
                                    <p className="text-sm font-medium text-emerald-200">{status}</p>
                                </div>
                            )}

                            {(botWarning || securityError) && (
                                <div className="rounded-lg border border-red-500/50 bg-red-500/10 p-4">
                                    <p className="text-sm font-medium text-red-200">{botWarning ?? securityError}</p>
                                </div>
                            )}

                            <form onSubmit={handleLoginSubmit} className="space-y-5">
                                {/* Hidden honeypot field for bot protection */}
                                <div
                                    aria-hidden="true"
                                    className="absolute left-[-10000px] top-auto w-[1px] h-[1px] overflow-hidden"
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

                                {/* Email Field */}
                                <div className="space-y-2">
                                    <label htmlFor="email" className="block text-sm font-semibold text-slate-300">
                                        Email Address
                                    </label>
                                    <div className="relative">
                                        <Mail className="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 h-5 w-5" />
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                            className="w-full pl-12 pr-4 py-3 rounded-lg bg-slate-800/50 border border-slate-700/50 text-slate-50 text-sm placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all"
                                            placeholder="you@example.com"
                                            autoComplete="email"
                                            autoFocus
                                            required
                                        />
                                    </div>
                                    {errors.email && (
                                        <p className="mt-1.5 text-sm font-medium text-red-400">{errors.email}</p>
                                    )}
                                </div>

                                {/* Password Field */}
                                <div className="space-y-2">
                                    <label htmlFor="password" className="block text-sm font-semibold text-slate-300">
                                        Password
                                    </label>
                                    <div className="relative">
                                        <Lock className="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 h-5 w-5" />
                                        <input
                                            id="password"
                                            name="password"
                                            type="password"
                                            value={data.password}
                                            onChange={(e) => setData('password', e.target.value)}
                                            className="w-full pl-12 pr-4 py-3 rounded-lg bg-slate-800/50 border border-slate-700/50 text-slate-50 text-sm placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all"
                                            placeholder="Enter your password"
                                            autoComplete="current-password"
                                            required
                                        />
                                    </div>
                                    {errors.password && (
                                        <p className="mt-1.5 text-sm font-medium text-red-400">{errors.password}</p>
                                    )}
                                </div>

                                {/* Remember Me */}
                                <div className="flex items-center">
                                    <input
                                        id="remember"
                                        name="remember"
                                        type="checkbox"
                                        checked={data.remember}
                                        onChange={(e) => setData('remember', e.target.checked)}
                                        className="h-4 w-4 rounded border-slate-700 bg-slate-800/50 text-blue-600 focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-0 focus:ring-offset-slate-900 cursor-pointer"
                                    />
                                    <label htmlFor="remember" className="ml-3 text-sm font-medium text-slate-400 cursor-pointer hover:text-slate-300 transition-colors">
                                        Remember me
                                    </label>
                                </div>

                                {/* Login Button */}
                                <Button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {processing ? (
                                        <span className="flex items-center justify-center">
                                            <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Signing in...
                                        </span>
                                    ) : (
                                        'Sign in'
                                    )}
                                </Button>
                            </form>

                            {/* Sign Up Link */}
                            <div className="pt-4 text-center border-t border-slate-700/50">
                                <p className="text-sm text-slate-400">
                                    Don't have an account?{' '}
                                    <Link
                                        href="/register"
                                        className="font-semibold text-blue-400 hover:text-blue-300 transition-colors"
                                    >
                                        Sign up
                                    </Link>
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </>
    );
}
