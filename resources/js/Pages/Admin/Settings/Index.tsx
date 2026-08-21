import { ChangeEvent, FormEvent, useEffect, useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Head, router, useForm } from '@inertiajs/react';
import { Database, Download, Info, Trash2, X } from 'lucide-react';
import { PageProps } from '@/types';

type SettingsPayload = {
    site: {
        name: string;
        email: string;
        support_email?: string | null;
        url: string;
        timezone: string;
        currency: string;
    };
    branding: {
        logo_path?: string | null;
        logo_url?: string | null;
    };
    security: {
        max_login_attempts: number | null;
        lockout_time: number | null;
        session_timeout: number | null;
        two_factor_threshold: number | null;
        admin_approval_threshold: number | null;
    };
};

type Props = PageProps & { settings: SettingsPayload };

type SettingsFormData = {
    site_name: string;
    site_email: string;
    support_email: string;
    app_url: string;
    timezone: string;
    currency: string;
    site_logo: File | null;
    remove_logo: boolean;
};

export default function Index({ settings }: Props) {
    const form = useForm<SettingsFormData>({
        site_name: settings.site.name ?? '',
        site_email: settings.site.email ?? '',
        support_email: settings.site.support_email ?? '',
        app_url: settings.site.url ?? '',
        timezone: settings.site.timezone ?? '',
        currency: settings.site.currency ?? '',
        site_logo: null,
        remove_logo: false,
    });
    const { data, setData, errors, processing } = form;

    useEffect(() => {
        setData((current) => ({
            ...current,
            site_name: settings.site.name ?? '',
            site_email: settings.site.email ?? '',
            support_email: settings.site.support_email ?? '',
            app_url: settings.site.url ?? '',
            timezone: settings.site.timezone ?? '',
            currency: settings.site.currency ?? '',
            site_logo: null,
            remove_logo: false,
        }));
    }, [
        setData,
        settings.site.currency,
        settings.site.email,
        settings.site.name,
        settings.site.support_email,
        settings.site.timezone,
        settings.site.url,
    ]);

    const [logoPreview, setLogoPreview] = useState<string | null>(settings.branding.logo_url ?? null);
    const [logoObjectUrl, setLogoObjectUrl] = useState<string | null>(null);

    useEffect(() => {
        if (!data.site_logo) {
            setLogoPreview(settings.branding.logo_url ?? null);
        }
    }, [settings.branding.logo_url, data.site_logo]);

    useEffect(() => {
        return () => {
            if (logoObjectUrl) {
                URL.revokeObjectURL(logoObjectUrl);
            }
        };
    }, [logoObjectUrl]);

    const handleClearCache = () => {
        if (confirm('Clear application cache?')) {
            router.post('/admin/settings/clear-cache');
        }
    };

    const handleRunMigrations = () => {
        if (confirm('Run database migrations? This may affect the database.')) {
            router.post('/admin/settings/run-migrations');
        }
    };

    const handleLogoChange = (event: ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0] ?? null;

        if (logoObjectUrl) {
            URL.revokeObjectURL(logoObjectUrl);
            setLogoObjectUrl(null);
        }

        setData('site_logo', file);
        setData('remove_logo', false);

        if (file) {
            const url = URL.createObjectURL(file);
            setLogoPreview(url);
            setLogoObjectUrl(url);
        } else {
            setLogoPreview(settings.branding.logo_url ?? null);
        }
    };

    const handleRemoveLogo = () => {
        if (logoObjectUrl) {
            URL.revokeObjectURL(logoObjectUrl);
            setLogoObjectUrl(null);
        }

        setData('site_logo', null);
        setData('remove_logo', true);
        setLogoPreview(null);
    };

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        form.transform((formData) => ({
            ...formData,
            site_name: formData.site_name.trim() === '' ? null : formData.site_name.trim(),
            site_email: formData.site_email.trim() === '' ? null : formData.site_email.trim(),
            support_email: formData.support_email.trim() === '' ? null : formData.support_email.trim(),
            app_url: formData.app_url.trim() === '' ? null : formData.app_url.trim(),
            timezone: formData.timezone.trim() === '' ? null : formData.timezone.trim(),
            currency: formData.currency.trim() === '' ? null : formData.currency.trim(),
        }));

        form.put('/admin/settings', {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                setData('site_logo', null);
                setData('remove_logo', false);
                if (!data.site_logo) {
                    setLogoPreview(settings.branding.logo_url ?? null);
                }
            },
            onFinish: () => {
                form.transform((payload) => payload);
            },
        });
    };

    return (
        <AdminLayout>
            <Head title="Settings" />

            <div className="space-y-6">
                <div>
                    <h1 className="text-3xl font-bold text-slate-50">Settings</h1>
                    <p className="text-slate-400 mt-1">Manage application configuration, branding, and security.</p>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Site Information</CardTitle>
                            <CardDescription className="text-slate-400">
                                Update the public-facing details for your banking portal.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label htmlFor="site_name" className="block text-sm font-medium text-slate-300">
                                        Site Name
                                    </label>
                                    <div className="mt-2 space-y-2">
                                        <Input
                                        id="site_name"
                                        value={data.site_name}
                                        onChange={(e) => setData('site_name', e.target.value)}
                                        placeholder="Banko"
                                    />
                                        {errors.site_name && <p className="text-sm text-red-400">{errors.site_name}</p>}
                                    </div>
                                </div>
                                <div>
                                    <label htmlFor="site_email" className="block text-sm font-medium text-slate-300">
                                        Primary Email
                                    </label>
                                    <div className="mt-2 space-y-2">
                                        <Input
                                        id="site_email"
                                        type="email"
                                        value={data.site_email}
                                        onChange={(e) => setData('site_email', e.target.value)}
                                        placeholder="support@banko.test"
                                    />
                                        {errors.site_email && <p className="text-sm text-red-400">{errors.site_email}</p>}
                                    </div>
                                </div>
                                <div>
                                    <label htmlFor="support_email" className="block text-sm font-medium text-slate-300">
                                        Support Email
                                    </label>
                                    <div className="mt-2 space-y-2">
                                        <Input
                                        id="support_email"
                                        type="email"
                                            value={data.support_email}
                                        onChange={(e) => setData('support_email', e.target.value)}
                                        placeholder="help@banko.test"
                                    />
                                    {errors.support_email && (
                                            <p className="text-sm text-red-400">{errors.support_email}</p>
                                    )}
                                    </div>
                                </div>
                                <div>
                                    <label htmlFor="app_url" className="block text-sm font-medium text-slate-300">
                                        Application URL
                                    </label>
                                    <div className="mt-2 space-y-2">
                                        <Input
                                        id="app_url"
                                        type="url"
                                        value={data.app_url}
                                        onChange={(e) => setData('app_url', e.target.value)}
                                        placeholder="https://banko.test"
                                    />
                                        {errors.app_url && <p className="text-sm text-red-400">{errors.app_url}</p>}
                                    </div>
                                </div>
                                <div>
                                    <label htmlFor="timezone" className="block text-sm font-medium text-slate-300">
                                        Timezone
                                    </label>
                                    <div className="mt-2 space-y-2">
                                        <Input
                                        id="timezone"
                                        value={data.timezone}
                                        onChange={(e) => setData('timezone', e.target.value)}
                                        placeholder="UTC"
                                    />
                                        {errors.timezone && <p className="text-sm text-red-400">{errors.timezone}</p>}
                                    </div>
                                </div>
                                <div>
                                    <label htmlFor="currency" className="block text-sm font-medium text-slate-300">
                                        Currency
                                    </label>
                                    <div className="mt-2 space-y-2">
                                        <Input
                                        id="currency"
                                        value={data.currency}
                                        onChange={(e) => setData('currency', e.target.value.toUpperCase())}
                                        placeholder="NGN"
                                            className="uppercase"
                                    />
                                        {errors.currency && <p className="text-sm text-red-400">{errors.currency}</p>}
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Branding</CardTitle>
                            <CardDescription className="text-slate-400">
                                Upload or replace the logo displayed across the platform.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            <div className="flex flex-col md:flex-row md:items-center md:space-x-6 space-y-4 md:space-y-0">
                                <div className="h-24 w-24 rounded-lg border border-slate-700 bg-slate-950 flex items-center justify-center overflow-hidden">
                                    {logoPreview ? (
                                        <img src={logoPreview} alt="Site logo preview" className="h-full w-full object-cover" />
                                    ) : (
                                        <span className="text-xs text-slate-500 text-center px-2">No logo uploaded</span>
                                    )}
                                </div>
                                <div className="flex-1 space-y-3">
                                    <div>
                                        <label htmlFor="site_logo" className="block text-sm font-medium text-slate-300">
                                            Upload Logo
                                        </label>
                                        <input
                                            id="site_logo"
                                            type="file"
                                            accept="image/*"
                                            onChange={handleLogoChange}
                                            className="mt-2 block w-full text-sm text-slate-300 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-500 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-900 hover:file:bg-emerald-400"
                                        />
                                        {errors.site_logo && <p className="mt-2 text-sm text-red-400">{errors.site_logo}</p>}
                                        <p className="mt-2 text-xs text-slate-500">Accepted formats: PNG, JPG, SVG. Max size 2MB.</p>
                                    </div>
                                    {(logoPreview || settings.branding.logo_url) && (
                                        <Button
                                            type="button"
                                            variant="outline"
                                            onClick={handleRemoveLogo}
                                            className="bg-slate-950 border-slate-700 text-slate-200 hover:bg-slate-800 hover:text-slate-50"
                                        >
                                            <X className="h-4 w-4 mr-2" />
                                            Remove Logo
                                        </Button>
                                    )}
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <div className="flex justify-end">
                            <Button
                                type="submit"
                                disabled={processing}
                                className="bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-semibold"
                            >
                                {processing ? 'Saving...' : 'Save Changes'}
                            </Button>
                    </div>
                </form>

                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50">System Maintenance</CardTitle>
                        <CardDescription className="text-slate-400">
                            Database and cache management tools for administrators.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        <Button
                            onClick={handleClearCache}
                            variant="outline"
                            className="w-full justify-start bg-slate-800 border-slate-700 hover:bg-slate-700 text-slate-50"
                        >
                            <Trash2 className="h-4 w-4 mr-2" />
                            Clear Application Cache
                        </Button>
                        <Button
                            onClick={handleRunMigrations}
                            variant="outline"
                            className="w-full justify-start bg-slate-800 border-slate-700 hover:bg-slate-700 text-slate-50"
                        >
                            <Database className="h-4 w-4 mr-2" />
                            Run Database Migrations
                        </Button>
                        <a href="/admin/settings/backup-database">
                            <Button
                                variant="outline"
                                className="w-full justify-start bg-slate-800 border-slate-700 hover:bg-slate-700 text-slate-50"
                            >
                                <Download className="h-4 w-4 mr-2" />
                                Download Database Backup
                            </Button>
                        </a>
                        <a href="/admin/settings/system-info">
                            <Button
                                variant="outline"
                                className="w-full justify-start bg-slate-800 border-slate-700 hover:bg-slate-700 text-slate-50"
                            >
                                <Info className="h-4 w-4 mr-2" />
                                View System Information
                            </Button>
                        </a>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}
