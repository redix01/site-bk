import { ReactNode } from 'react';
import { Link } from '@inertiajs/react';

interface ComingSoonProps {
    icon: ReactNode;
    title: string;
    description: string;
    accent: string;
    viewParam: string;
}

export default function ComingSoon({ icon, title, description, accent, viewParam }: ComingSoonProps) {
    return (
        <div className="px-4 py-6">
            <div className="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 text-center">
                <div className={`w-16 h-16 mx-auto mb-5 rounded-full flex items-center justify-center ${accent}`}>
                    {icon}
                </div>
                <h1 className="text-lg font-semibold text-slate-900 dark:text-slate-50">{title}</h1>
                <p className="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-xs mx-auto">
                    {description}
                </p>
                <Link
                    href={"/dashboard" + viewParam}
                    className="inline-flex items-center justify-center mt-6 text-sm font-medium text-blue-600 dark:text-blue-500 hover:text-blue-700 dark:hover:text-blue-400"
                >
                    Back to Dashboard
                </Link>
            </div>
        </div>
    );
}
