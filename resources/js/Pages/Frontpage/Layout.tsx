import type { PropsWithChildren } from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '../../../../shirecity-ui/src/components/Navbar';
import Footer from '../../../../shirecity-ui/src/components/Footer';

type FrontpageLayoutProps = PropsWithChildren<{
    title: string;
}>;

export default function Layout({ title, children }: FrontpageLayoutProps) {
    return (
        <>
            <Head title={title} />

            <div className="min-h-screen flex flex-col">
                <Navbar />
                <main className="flex-grow">{children}</main>
                <Footer />
            </div>
        </>
    );
}
