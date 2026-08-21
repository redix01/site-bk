import MobileLayout from '@/Layouts/MobileLayout';
import { PageProps } from '@/types';

interface SavingsProduct {
    key: string;
    name: string;
    shortName: string;
    rate: string;
    description: string;
    accent: string;
}

const products: SavingsProduct[] = [
    {
        key: 'hysa',
        name: 'High-Yield Savings',
        shortName: 'HYSA',
        rate: '4.00%',
        description: 'Earn more on your everyday savings with instant access to your funds, no lock-in required.',
        accent: 'from-amber-500 to-orange-500',
    },
    {
        key: 'mma',
        name: 'Money Market Account',
        shortName: 'MMA',
        rate: '3.57%',
        description: 'Combine a competitive return with the flexibility of a checking-style account.',
        accent: 'from-blue-500 to-indigo-600',
    },
];

export default function Savings({ auth }: PageProps) {
    const viewParam = auth.user.is_admin ? '?view=client' : '';

    return (
        <MobileLayout user={auth.user} title="Savings" currentRoute="savings">
            <div className="px-4 py-6 space-y-5">
                <div>
                    <h1 className="text-lg font-semibold text-slate-900 dark:text-slate-50">Grow Your Savings</h1>
                    <p className="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Choose an account that fits how you save.
                    </p>
                </div>

                <div className="space-y-4">
                    {products.map((product) => (
                        <div
                            key={product.key}
                            className="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden"
                        >
                            <div className={`bg-gradient-to-br ${product.accent} px-5 py-5 text-white`}>
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-xs font-medium uppercase tracking-wide text-white/80">
                                            {product.shortName}
                                        </p>
                                        <p className="text-base font-semibold mt-0.5">{product.name}</p>
                                    </div>
                                    <div className="text-right">
                                        <p className="text-3xl font-bold leading-none">{product.rate}</p>
                                        <p className="text-[11px] text-white/80 mt-1">P.A</p>
                                    </div>
                                </div>
                            </div>
                            <div className="px-5 py-4">
                                <p className="text-sm text-slate-600 dark:text-slate-300">
                                    {product.description}
                                </p>
                                <button
                                    type="button"
                                    disabled
                                    className="w-full mt-4 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-sm font-semibold py-3 cursor-not-allowed"
                                >
                                    Coming Soon
                                </button>
                            </div>
                        </div>
                    ))}
                </div>

                <p className="text-xs text-slate-400 dark:text-slate-500 text-center px-4">
                    Rates shown are annual percentage yields (P.A) and may change. Account opening will be available directly from the app soon.
                </p>

                <a
                    href={"/dashboard" + viewParam}
                    className="block text-center text-sm font-medium text-blue-600 dark:text-blue-500 hover:text-blue-700 dark:hover:text-blue-400 py-2"
                >
                    Back to Dashboard
                </a>
            </div>
        </MobileLayout>
    );
}
