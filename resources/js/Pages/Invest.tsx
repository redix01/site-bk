import MobileLayout from '@/Layouts/MobileLayout';
import ComingSoon from '@/Components/ComingSoon';
import { PageProps } from '@/types';

export default function Invest({ auth }: PageProps) {
    const viewParam = auth.user.is_admin ? '?view=client' : '';

    return (
        <MobileLayout user={auth.user} title="Invest" currentRoute="invest">
            <ComingSoon
                viewParam={viewParam}
                accent="bg-purple-500/10"
                title="Investing is on the way"
                description="Grow your balance with curated investment options, right from your account. We're putting the finishing touches on it."
                icon={
                    <svg className="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.25 18L9 11.25l4.306 4.306a11.95 11.95 0 015.814-5.518l2.74-1.22m0 0l-5.94-2.281m5.94 2.28l-2.28 5.941" />
                    </svg>
                }
            />
        </MobileLayout>
    );
}
