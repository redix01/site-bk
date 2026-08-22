import { ReactNode, useState } from 'react';
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
    const { notices = [], unreadNoticeCount = 0 } = usePage<PageProps>().props;
    const [isNoticePanelOpen, setIsNoticePanelOpen] = useState(false);

    // Add view parameter for admins to keep them in client view
    const viewParam = user.is_admin ? '?view=client' : '';
    const isSuspended = user.status === 'suspended';
    const isLocked = user.status === 'locked';
    const noticeBadge = unreadNoticeCount > 99 ? '99+' : unreadNoticeCount;

    const markNoticeRead = (noticeId: number) => {
        router.post(`/notifications/${noticeId}/read`, {}, {
            preserveScroll: true,
            onSuccess: () => setIsNoticePanelOpen(false),
        });
    };

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
                                {!user.is_admin && (
                                    <div className="relative">
                                        <button
                                            type="button"
                                            onClick={() => setIsNoticePanelOpen((open) => !open)}
                                            className="relative rounded-full p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-50"
                                            title="Notifications"
                                            aria-label={`Notifications${unreadNoticeCount ? `, ${unreadNoticeCount} unread` : ''}`}
                                            aria-expanded={isNoticePanelOpen}
                                        >
                                            <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                            {unreadNoticeCount > 0 && (
                                                <span className="absolute -right-1 -top-1 min-w-4 h-4 rounded-full bg-rose-500 px-1 text-[10px] font-bold leading-4 text-white ring-2 ring-white dark:ring-slate-900">
                                                    {noticeBadge}
                                                </span>
                                            )}
                                        </button>

                                        {isNoticePanelOpen && (
                                            <div className="absolute right-0 top-full mt-2 w-[min(22rem,calc(100vw-2rem))] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl shadow-slate-900/15 dark:border-slate-700 dark:bg-slate-900">
                                                <div className="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                                                    <div>
                                                        <p className="text-sm font-semibold text-slate-900 dark:text-slate-50">Notifications</p>
                                                        <p className="text-xs text-slate-500 dark:text-slate-400">
                                                            {unreadNoticeCount ? `${unreadNoticeCount} unread` : 'You’re all caught up'}
                                                        </p>
                                                    </div>
                                                    <button type="button" onClick={() => setIsNoticePanelOpen(false)} className="rounded-full p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200" aria-label="Close notifications">
                                                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                {notices.length > 0 ? (
                                                    <div className="max-h-96 divide-y divide-slate-100 overflow-y-auto dark:divide-slate-800">
                                                        {notices.map((notice) => {
                                                            const isWarning = notice.type === 'warning';
                                                            return (
                                                                <div key={notice.id} className="px-4 py-3">
                                                                    <div className="flex items-start gap-3">
                                                                        <span className={`mt-1 h-2 w-2 shrink-0 rounded-full ${isWarning ? 'bg-amber-500' : 'bg-blue-500'}`} />
                                                                        <div className="min-w-0 flex-1">
                                                                            <p className="text-sm font-semibold text-slate-900 dark:text-slate-100">{notice.title}</p>
                                                                            <p className="mt-1 whitespace-pre-wrap text-sm text-slate-600 dark:text-slate-300">{notice.message}</p>
                                                                            <button type="button" onClick={() => markNoticeRead(notice.id)} className="mt-2 text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                                                                Mark as read
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            );
                                                        })}
                                                    </div>
                                                ) : (
                                                    <p className="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">No active notifications.</p>
                                                )}
                                                {unreadNoticeCount > notices.length && (
                                                    <p className="border-t border-slate-100 px-4 py-2 text-center text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400">Showing the 5 most recent notifications.</p>
                                                )}
                                            </div>
                                        )}
                                    </div>
                                )}
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
