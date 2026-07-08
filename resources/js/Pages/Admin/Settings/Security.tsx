import { FormEvent } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Head, useForm } from '@inertiajs/react';

export default function Security() {
    const form = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });
    const { data, setData, errors, processing } = form;

    const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        form.post('/admin/settings/security/password', {
            preserveScroll: true,
            onSuccess: () => {
                form.reset('current_password', 'password', 'password_confirmation');
            },
        });
    };

    return (
        <AdminLayout>
            <Head title="Security Settings" />

            <div className="space-y-6">
                <div>
                    <h1 className="text-3xl font-bold text-slate-50">Security Settings</h1>
                    <p className="text-slate-400 mt-1">Manage your admin account security and password.</p>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50">Change Password</CardTitle>
                            <CardDescription className="text-slate-400">
                                Update your admin account password. You'll be logged out of other active sessions.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            <div>
                                <label htmlFor="current_password" className="block text-sm font-medium text-slate-300">
                                    Current Password
                                </label>
                                <div className="mt-2">
                                    <Input
                                        id="current_password"
                                        type="password"
                                        value={data.current_password}
                                        onChange={(e) => setData('current_password', e.target.value)}
                                        placeholder="Enter your current password"
                                    />
                                    {errors.current_password && (
                                        <p className="text-sm text-red-400 mt-1">{errors.current_password}</p>
                                    )}
                                </div>
                            </div>

                            <div>
                                <label htmlFor="password" className="block text-sm font-medium text-slate-300">
                                    New Password
                                </label>
                                <div className="mt-2">
                                    <Input
                                        id="password"
                                        type="password"
                                        value={data.password}
                                        onChange={(e) => setData('password', e.target.value)}
                                        placeholder="Enter your new password"
                                    />
                                    {errors.password && (
                                        <p className="text-sm text-red-400 mt-1">{errors.password}</p>
                                    )}
                                </div>
                            </div>

                            <div>
                                <label htmlFor="password_confirmation" className="block text-sm font-medium text-slate-300">
                                    Confirm New Password
                                </label>
                                <div className="mt-2">
                                    <Input
                                        id="password_confirmation"
                                        type="password"
                                        value={data.password_confirmation}
                                        onChange={(e) => setData('password_confirmation', e.target.value)}
                                        placeholder="Confirm your new password"
                                    />
                                    {errors.password_confirmation && (
                                        <p className="text-sm text-red-400 mt-1">{errors.password_confirmation}</p>
                                    )}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <Button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-semibold"
                                >
                                    {processing ? 'Updating...' : 'Update Password'}
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </form>
            </div>
        </AdminLayout>
    );
}
