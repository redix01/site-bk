import { ReactNode } from 'react';
import { Head, router } from '@inertiajs/react';
import BottomNavigation from '@/Components/BottomNavigation';
import { User } from '@/types';

interface MobileLayoutProps {
    children: ReactNode;
    title?: string;
    user: User;
    currentRoute?: string;
}

export default function MobileLayout({ children, title, user, currentRoute = 'dashboard' }: MobileLayoutProps) {
    // Add view parameter for admins to keep them in client view
    const viewParam = user.is_admin ? '?view=client' : '';
    
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
            
            <div className="min-h-screen bg-slate-950 pb-20">
                {/* Header */}
                <header className="sticky top-0 bg-slate-900/95 backdrop-blur-sm border-b border-slate-800 z-40">
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
                                    <p className="text-sm text-slate-400">Welcome back</p>
                                    <p className="text-base font-semibold text-slate-50">{user.name}</p>
                                </div>
                            </div>
                            <button
                                onClick={() => router.post('/logout')}
                                className="p-2 text-slate-400 hover:text-slate-50 transition-colors"
                                title="Logout"
                            >
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>

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

