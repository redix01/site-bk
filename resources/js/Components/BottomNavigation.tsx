import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

interface NavItem {
    name: string;
    href: string;
    icon: React.ReactNode;
    active?: boolean;
}

interface BottomNavigationProps {
    items: NavItem[];
}

export default function BottomNavigation({ items }: BottomNavigationProps) {
    return (
        <nav className="fixed bottom-0 left-0 right-0 bg-slate-900 border-t border-slate-800 safe-area-inset-bottom z-50">
            <div className="max-w-3xl mx-auto px-2">
                <div className="flex items-center justify-around h-16">
                    {items.map((item) => (
                        <Link
                            key={item.name}
                            href={item.href}
                            className={cn(
                                "flex flex-col items-center justify-center flex-1 h-full space-y-1 transition-colors",
                                item.active
                                    ? "text-blue-500"
                                    : "text-slate-400 hover:text-slate-300"
                            )}
                        >
                            <div className="w-6 h-6">
                                {item.icon}
                            </div>
                            <span className="text-xs font-medium">{item.name}</span>
                        </Link>
                    ))}
                </div>
            </div>
        </nav>
    );
}

