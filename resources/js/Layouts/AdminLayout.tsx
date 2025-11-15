import { Link, usePage } from '@inertiajs/react';
import { PropsWithChildren, useState } from 'react';
import { PageProps } from '@/types';
import {
    LayoutDashboard,
    Users,
    ArrowLeftRight,
    FileText,
    Activity,
    Settings,
    LogOut,
    Menu,
    X,
    Ticket,
    Home,
    ExternalLink,
    CreditCard,
} from 'lucide-react';
import { Button } from '@/Components/ui/button';
import { cn } from '@/lib/utils';

interface NavItem {
    name: string;
    href: string;
    icon: React.ElementType;
    current?: boolean;
}

interface NavGroup {
    title: string;
    items: NavItem[];
}

export default function AdminLayout({ children }: PropsWithChildren) {
    const { auth, flash, appSettings } = usePage<PageProps>().props;
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const currentPath = typeof window !== 'undefined' ? window.location.pathname : '';

    const brandName = appSettings?.siteName ?? 'Banko';
    const adminLabel = `${brandName} Admin`;
    const logoUrl = appSettings?.logoUrl ?? null;

    const navigationGroups: NavGroup[] = [
        {
            title: 'Overview',
            items: [
                { name: 'Dashboard', href: '/admin/dashboard', icon: LayoutDashboard, current: currentPath === '/admin/dashboard' || currentPath === '/admin' },
                { name: 'Client Dashboard', href: '/dashboard?view=client', icon: Home, current: currentPath === '/dashboard' },
            ]
        },
        {
            title: 'User Management',
            items: [
                { name: 'Users', href: '/admin/users', icon: Users, current: currentPath.startsWith('/admin/users') },
            ]
        },
        {
            title: 'Financial',
            items: [
                { name: 'Transactions', href: '/admin/transactions', icon: ArrowLeftRight, current: currentPath.startsWith('/admin/transactions') },
                { name: 'Transaction Codes', href: '/admin/codes', icon: Ticket, current: currentPath.startsWith('/admin/codes') },
                { name: 'Payment Methods', href: '/admin/payment-methods', icon: CreditCard, current: currentPath.startsWith('/admin/payment-methods') },
            ]
        },
        {
            title: 'Reports & Analytics',
            items: [
                { name: 'Reports', href: '/admin/reports', icon: FileText, current: currentPath.startsWith('/admin/reports') },
                { name: 'Activity Logs', href: '/admin/activity-logs', icon: Activity, current: currentPath.startsWith('/admin/activity-logs') },
            ]
        },
        {
            title: 'System',
            items: [
                { name: 'Settings', href: '/admin/settings', icon: Settings, current: currentPath.startsWith('/admin/settings') },
            ]
        }
    ];

    return (
        <div className="min-h-screen bg-slate-950 overflow-x-hidden overscroll-none">
            {/* Mobile sidebar */}
            <div
                className={cn(
                    "fixed inset-0 z-50 lg:hidden",
                    sidebarOpen ? "block" : "hidden"
                )}
            >
                <div className="fixed inset-0 bg-black/80" onClick={() => setSidebarOpen(false)} />
                <div className="fixed inset-y-0 left-0 w-64 bg-slate-900 shadow-xl">
                    <div className="flex h-16 items-center justify-between px-4 border-b border-slate-800">
                    {logoUrl ? (
                        <img src={logoUrl} alt={`${brandName} logo`} className="h-10 w-auto" />
                    ) : (
                        <span className="text-xl font-semibold text-slate-50">{adminLabel}</span>
                    )}
                    <Button
                        variant="ghost"
                        size="icon"
                        onClick={() => setSidebarOpen(false)}
                    >
                        <X className="h-5 w-5" />
                    </Button>
                </div>
                <nav className="flex-1 px-3 py-4 space-y-6 overflow-y-auto">
                        {navigationGroups.map((group) => (
                            <div key={group.title}>
                                <h3 className="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                                    {group.title}
                                </h3>
                                <div className="space-y-1">
                                    {group.items.map((item) => (
                                        <Link
                                            key={item.name}
                                            href={item.href}
                                            className={cn(
                                                "group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors",
                                                item.current
                                                    ? "bg-slate-800 text-slate-50"
                                                    : "text-slate-400 hover:bg-slate-800 hover:text-slate-50"
                                            )}
                                        >
                                            <item.icon
                                                className={cn(
                                                    "mr-3 h-5 w-5 flex-shrink-0",
                                                    item.current ? "text-slate-300" : "text-slate-500 group-hover:text-slate-300"
                                                )}
                                            />
                                            {item.name}
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </nav>
                </div>
            </div>

            {/* Desktop sidebar */}
            <div className="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
                <div className="flex flex-col flex-grow bg-slate-900 border-r border-slate-800">
                    <div className="flex h-16 items-center justify-between px-6 border-b border-slate-800">
                        {logoUrl ? (
                            <img src={logoUrl} alt={`${brandName} logo`} className="h-10 w-auto" />
                        ) : (
                            <span className="text-xl font-semibold text-slate-50">{adminLabel}</span>
                        )}
                        <Link
                            href="/dashboard?view=client"
                            className="text-slate-400 hover:text-slate-50 transition-colors"
                            title="User Dashboard"
                        >
                            <Home className="h-5 w-5" />
                        </Link>
                    </div>
                    <nav className="flex-1 px-3 py-4 space-y-6 overflow-y-auto">
                        {navigationGroups.map((group) => (
                            <div key={group.title}>
                                <h3 className="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                                    {group.title}
                                </h3>
                                <div className="space-y-1">
                                    {group.items.map((item) => (
                                        <Link
                                            key={item.name}
                                            href={item.href}
                                            className={cn(
                                                "group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors",
                                                item.current
                                                    ? "bg-slate-800 text-slate-50"
                                                    : "text-slate-400 hover:bg-slate-800 hover:text-slate-50"
                                            )}
                                        >
                                            <item.icon
                                                className={cn(
                                                    "mr-3 h-5 w-5 flex-shrink-0",
                                                    item.current ? "text-slate-300" : "text-slate-500 group-hover:text-slate-300"
                                                )}
                                            />
                                            {item.name}
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </nav>
                    <div className="flex-shrink-0 border-t border-slate-800 p-4">
                        <div className="flex items-center">
                            <div className="flex-shrink-0">
                                <div className="h-9 w-9 rounded-full bg-slate-800 flex items-center justify-center">
                                    <span className="text-sm font-medium text-slate-300">
                                        {auth.user.name.charAt(0)}
                                    </span>
                                </div>
                            </div>
                            <div className="ml-3 flex-1">
                                <p className="text-sm font-medium text-slate-50">{auth.user.name}</p>
                                <p className="text-xs text-slate-400">{auth.user.email}</p>
                            </div>
                            <Link
                                href="/admin/logout"
                                method="post"
                                as="button"
                                className="ml-3 text-slate-400 hover:text-slate-300"
                            >
                                <LogOut className="h-5 w-5" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            {/* Main content */}
            <div className="lg:pl-64 min-h-screen bg-slate-950 overflow-x-hidden overscroll-none">
                {/* Top bar */}
                <div className="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-slate-900 border-b border-slate-800 lg:hidden">
                    <Button
                        variant="ghost"
                        size="icon"
                        onClick={() => setSidebarOpen(true)}
                        className="ml-2 text-slate-400 hover:text-slate-50"
                    >
                        <Menu className="h-6 w-6" />
                    </Button>
                    <div className="flex flex-1 justify-between px-4 items-center">
                        {logoUrl ? (
                            <img src={logoUrl} alt={`${brandName} logo`} className="h-10 w-auto" />
                        ) : (
                            <span className="text-lg font-semibold text-slate-50">{adminLabel}</span>
                        )}
                        <Link
                            href="/dashboard?view=client"
                            className="text-slate-400 hover:text-slate-50 transition-colors"
                            title="User Dashboard"
                        >
                            <Home className="h-5 w-5" />
                        </Link>
                    </div>
                </div>

                {/* Flash messages */}
                {flash?.success && (
                    <div className="mx-4 mt-4">
                        <div className="rounded-md bg-emerald-900/50 border border-emerald-700 p-4">
                            <p className="text-sm font-medium text-emerald-200">{flash.success}</p>
                        </div>
                    </div>
                )}
                {flash?.error && (
                    <div className="mx-4 mt-4">
                        <div className="rounded-md bg-red-900/50 border border-red-700 p-4">
                            <p className="text-sm font-medium text-red-200">{flash.error}</p>
                        </div>
                    </div>
                )}

                {/* Page content */}
                <main className="py-6 min-h-screen">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}

