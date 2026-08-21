import { ReactNode } from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import BottomNavigation from '@/Components/BottomNavigation';
import ThemeToggle from '@/Components/ThemeToggle';
import { useTheme } from '@/hooks/useTheme';
import { PageProps, User } from '@/types';

interface MobileLayoutProps {
    children: ReactNode;
    title?: string;
    user: User;
    currentRoute?: string;
}

export default function MobileLayout({ children, title, user, currentRoute = 'dashboard' }: MobileLayoutProps) {
    const { theme, toggleTheme } = useTheme();
    const { notices = [] } = usePage<PageProps>().props;

    // Add view parameter for admins to keep them in client view
    const viewParam = user.is_admin ? '?view=client' : '';
    const isSuspended = user.status === 'suspended';
    const isLocked = user.status === 'locked';

    const navItems = [
        {
            name: 'Home',
            href: '/dashboard' + viewParam,
            active: currentRoute === 'dashboard',
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            ),
        },
        {
            name: 'Transactions',
            href: '/transactions' + viewParam,
            active: currentRoute === 'transactions',
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            ),
        },
        {
            name: 'Transfer',
            href: '/transfer' + viewParam,
            active: currentRoute === 'transfer',
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            ),
        },
        {
            name: 'Profile',
            href: '/profile' + viewParam,
            active: currentRoute === 'profile',
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            ),
        },
    ];

    return (
        <>
            {title && <Head title={title} />}
            
            <div className="min-h-screen bg-slate-50 dark:bg-slate-950 pb-20 transition-colors">
                {/* Header */}
                <header className="sticky top-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-sm border-b border-slate-200 dark:border-slate-800 z-40">
                    <div className="max-w-3xl mx-auto px-4 py-4">
                        <div className="flex items-center justify-between">
                            <div className="flex items-center space-x-3">
                                <div className="relative w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-base overflow-hidden">
                                    {user.profile_photo_url ? (
                                        <img
                                            src={user.profile_photo_url}
                                            alt={user.name}
                                            className="h-full w-full object-cover"
                                        />
                                    ) : (
                                        user.name.charAt(0).toUpperCase()
                                    )}
                                </div>
                                <div>
                                    <p className="text-sm text-slate-500 dark:text-slate-400">Welcome back</p>
                                    <p className="text-base font-semibold text-slate-900 dark:text-slate-50">{user.name}</p>
                                </div>
                            </div>
                            <div className="flex items-center space-x-1">
                                <ThemeToggle theme={theme} onToggle={toggleTheme} />
                                <button
                                    onClick={() => router.post('/logout')}
                                    className="p-2 rounded-full text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-50 dark:hover:bg-slate-800 transition-colors"
                                    title="Logout"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                {/* Account status & admin notices */}
                <div className="max-w-3xl mx-auto px-4 pt-4 space-y-3">
                    {(isSuspended || isLocked) && (
                        <div className="flex items-start gap-3 rounded-xl border border-rose-300 bg-rose-50 p-4 dark:border-rose-500/40 dark:bg-rose-950/40">
                            <svg className="mt-0.5 h-5 w-5 shrink-0 text-rose-500 dark:text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01M5.07 19h13.86A2 2 0 0020.9 17L13.84 4.66a2 2 0 00-3.68 0L3.1 17a2 2 0 001.97 2z" />
                            </svg>
                            <div>
                                <p className="text-sm font-semibold uppercase tracking-wide text-rose-700 dark:text-rose-200">
                                    Account {isSuspended ? 'Suspended' : 'Locked'}
                                </p>
                                <p className="mt-0.5 text-sm text-rose-700/90 dark:text-rose-100/90">
                                    Transfers, deposits, withdrawals, and other account changes are disabled until an administrator restores your account. Please contact support if you believe this is a mistake.
                                </p>
                            </div>
                        </div>
                    )}

                    {notices.map((notice) => {
                        const isWarning = notice.type === 'warning';
                        return (
                            <div
                                key={notice.id}
                                className={`flex items-start gap-3 rounded-xl border p-4 ${
                                    isWarning
                                        ? 'border-amber-300 bg-amber-50 dark:border-amber-500/40 dark:bg-amber-950/40'
                                        : 'border-blue-300 bg-blue-50 dark:border-blue-500/40 dark:bg-blue-950/40'
                                }`}
                            >
                                <svg
                                    className={`mt-0.5 h-5 w-5 shrink-0 ${isWarning ? 'text-amber-500 dark:text-amber-300' : 'text-blue-500 dark:text-blue-300'}`}
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                >
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01M5.07 19h13.86A2 2 0 0020.9 17L13.84 4.66a2 2 0 00-3.68 0L3.1 17a2 2 0 001.97 2z" />
                                </svg>
                                <div className="flex-1">
                                    <p className={`text-sm font-semibold ${isWarning ? 'text-amber-800 dark:text-amber-200' : 'text-blue-800 dark:text-blue-200'}`}>
                                        {notice.title}
                                    </p>
                                    <p className={`mt-0.5 text-sm ${isWarning ? 'text-amber-700/90 dark:text-amber-100/90' : 'text-blue-700/90 dark:text-blue-100/90'}`}>
                                        {notice.message}
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    onClick={() => router.post(`/notifications/${notice.id}/read`, {}, { preserveScroll: true })}
                                    className={`shrink-0 rounded-full p-1 transition-colors ${
                                        isWarning
                                            ? 'text-amber-500 hover:bg-amber-100 dark:text-amber-300 dark:hover:bg-amber-900/40'
                                            : 'text-blue-500 hover:bg-blue-100 dark:text-blue-300 dark:hover:bg-blue-900/40'
                                    }`}
                                    title="Dismiss"
                                >
                                    <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        );
                    })}
                </div>

                {/* Main Content */}
                <main className="max-w-3xl mx-auto">
                    {children}
                </main>

                {/* Bottom Navigation */}
                <BottomNavigation items={navItems} />
            </div>
        </>
    );
}

