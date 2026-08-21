import { FormEventHandler, useState, useEffect, useRef } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Mail, Lock, Banknote } from 'lucide-react';
import ThemeToggle from '@/Components/ThemeToggle';
import { useTheme } from '@/hooks/useTheme';

const appName = import.meta.env.VITE_APP_NAME || 'Current Financial Bank';

interface LoginProps {
    status?: string;
    flash?: {
        success?: string;
    };
}

export default function Login({ status, flash }: LoginProps) {
    const { theme, toggleTheme } = useTheme();
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

            <div className="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors">
                <header className="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur-sm dark:border-slate-800 dark:bg-slate-900/95">
                    <div className="mx-auto flex max-w-5xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                        <Link href="/" className="flex items-center gap-2 font-semibold text-slate-900 dark:text-slate-50">
                            <Banknote className="h-5 w-5 text-blue-600 dark:text-blue-400" />
                            <span className="text-sm sm:text-base">{appName}</span>
                        </Link>
                        <ThemeToggle theme={theme} onToggle={toggleTheme} />
                    </div>
                </header>

                <div className="flex w-full items-center justify-center px-4 py-10 sm:px-6 sm:py-16 lg:px-8">
                    <Card className="w-full max-w-md border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <CardHeader className="space-y-4 text-center pb-6">
                            <div className="flex flex-col items-center space-y-3">
                                <div className="flex h-14 w-14 items-center justify-center rounded-full border-2 border-blue-200 bg-blue-50 shadow-sm dark:border-blue-500/50 dark:bg-blue-500/10">
                                    <Banknote className="h-7 w-7 text-blue-600 dark:text-blue-400" />
                                </div>
                                <span className="text-xl font-bold tracking-[0.4em] text-slate-700 dark:text-slate-200 uppercase">
                                    {appName}
                                </span>
                            </div>
                            <CardTitle className="text-3xl font-bold text-slate-900 dark:text-slate-50">
                                Welcome back
                            </CardTitle>
                            <CardDescription className="text-base text-slate-500 dark:text-slate-400">
                                Sign in to your account to continue
                            </CardDescription>
                        </CardHeader>

                        <CardContent className="space-y-6">
                            {(status || flash?.success) && (
                                <div className="rounded-lg bg-emerald-50 border border-emerald-200 p-4 dark:bg-emerald-900/30 dark:border-emerald-700/50">
                                    <p className="text-sm font-medium text-emerald-700 dark:text-emerald-200">{status || flash?.success}</p>
                                </div>
                            )}

                            {(botWarning || securityError) && (
                                <div className="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-500/50 dark:bg-red-500/10">
                                    <p className="text-sm font-medium text-red-700 dark:text-red-200">{botWarning ?? securityError}</p>
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
                                    <label htmlFor="email" className="block text-sm font-semibold text-slate-700 dark:text-slate-300">
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
                                            className="w-full pl-12 pr-4 py-3 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-50 text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            placeholder="you@example.com"
                                            autoComplete="email"
                                            autoFocus
                                            required
                                        />
                                    </div>
                                    {errors.email && (
                                        <p className="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{errors.email}</p>
                                    )}
                                </div>

                                {/* Password Field */}
                                <div className="space-y-2">
                                    <label htmlFor="password" className="block text-sm font-semibold text-slate-700 dark:text-slate-300">
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
                                            className="w-full pl-12 pr-4 py-3 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-50 text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            placeholder="Enter your password"
                                            autoComplete="current-password"
                                            required
                                        />
                                    </div>
                                    {errors.password && (
                                        <p className="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{errors.password}</p>
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
                                        className="h-4 w-4 rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-blue-600 focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-0 cursor-pointer"
                                    />
                                    <label htmlFor="remember" className="ml-3 text-sm font-medium text-slate-600 dark:text-slate-400 cursor-pointer hover:text-slate-900 dark:hover:text-slate-300 transition-colors">
                                        Remember me
                                    </label>
                                </div>

                                {/* Login Button */}
                                <Button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow-sm hover:shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed"
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
                            <div className="pt-4 text-center border-t border-slate-200 dark:border-slate-700">
                                <p className="text-sm text-slate-500 dark:text-slate-400">
                                    Don't have an account?{' '}
                                    <Link
                                        href="/register"
                                        className="font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
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
