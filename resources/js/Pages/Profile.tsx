import { ChangeEvent, FormEvent, useEffect, useRef, useState } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import MobileLayout from '@/Layouts/MobileLayout';
import { LoginHistory, PageProps } from '@/types';
import { useForm, router } from '@inertiajs/react';
import { Camera } from 'lucide-react';

type ProfileAction = 'view' | 'profile' | 'password' | 'security';
type ProfilePageProps = PageProps & { loginHistory?: LoginHistory[] };

type ProfileFormData = {
    name: string;
    email: string;
    phone: string | null;
    profile_photo: File | null;
};

export default function Profile({ auth, flash, loginHistory = [] }: ProfilePageProps) {
    const hasTransactionPin = auth.user.has_transaction_pin ?? false;
    const [activeAction, setActiveAction] = useState<ProfileAction>('view');
    const loginEntries = loginHistory ?? [];
    const [profilePhotoPreview, setProfilePhotoPreview] = useState<string | null>(null);
    const fileInputRef = useRef<HTMLInputElement | null>(null);
    const [pendingPhotoSelect, setPendingPhotoSelect] = useState(false);

    const {
        data: profileData,
        setData: setProfileData,
        post: submitProfile,
        processing: profileProcessing,
        errors: profileErrors,
        reset: resetProfileForm,
        clearErrors: clearProfileErrors,
    } = useForm<ProfileFormData>({
        name: auth.user.name ?? '',
        email: auth.user.email ?? '',
        phone: auth.user.phone ?? '',
        profile_photo: null,
    });

    const {
        data: passwordData,
        setData: setPasswordData,
        post: submitPassword,
        processing: passwordProcessing,
        errors: passwordErrors,
        reset: resetPasswordForm,
        clearErrors: clearPasswordErrors,
    } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const {
        data: securityData,
        setData: setSecurityData,
        post: submitSecurity,
        processing: securityProcessing,
        errors: securityErrors,
        reset: resetSecurityForm,
        clearErrors: clearSecurityErrors,
    } = useForm({
        current_password: '',
    });

    const {
        data: pinData,
        setData: setPinData,
        post: submitPin,
        processing: pinProcessing,
        errors: pinErrors,
        reset: resetPinForm,
        clearErrors: clearPinErrors,
    } = useForm({
        current_password: '',
        current_transaction_pin: '',
        transaction_pin: '',
        transaction_pin_confirmation: '',
    });

    useEffect(() => {
        setProfileData('name', auth.user.name ?? '');
        setProfileData('email', auth.user.email ?? '');
        setProfileData('phone', auth.user.phone ?? '');
        setProfileData('profile_photo', null);
        setProfilePhotoPreview((prev) => {
            if (prev) {
                URL.revokeObjectURL(prev);
            }
            return null;
        });
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
    }, [auth.user.name, auth.user.email, auth.user.phone, setProfileData]);

    useEffect(() => {
        return () => {
            if (profilePhotoPreview) {
                URL.revokeObjectURL(profilePhotoPreview);
            }
        };
    }, [profilePhotoPreview]);

    useEffect(() => {
        if (pendingPhotoSelect && activeAction === 'profile') {
            fileInputRef.current?.click();
            setPendingPhotoSelect(false);
        }
    }, [activeAction, pendingPhotoSelect]);

    // Log when the server-provided avatar URL changes (helps verify fresh auth data)
    useEffect(() => {
        if (auth?.user?.profile_photo_url) {
            console.debug('[Profile] profile_photo_url changed', auth.user.profile_photo_url);
        } else {
            console.debug('[Profile] profile_photo_url is empty');
        }
    }, [auth?.user?.profile_photo_url]);

    const actionButtonClasses = (action: ProfileAction) =>
        action === activeAction
            ? 'w-full bg-blue-600 hover:bg-blue-700 text-white'
            : 'w-full border-slate-700 text-slate-300 hover:bg-slate-800';

    const handleActionChange = (action: ProfileAction) => {
        setActiveAction((current) => (current === action ? 'view' : action));
    };

    const handleProfileInput = (field: 'name' | 'email' | 'phone') => (event: ChangeEvent<HTMLInputElement>) => {
        setProfileData(field, event.target.value);
        if (profileErrors[field]) {
            clearProfileErrors(field);
        }
    };

    const handleProfileSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        submitProfile(route('profile.update'), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                console.info('[Profile] Avatar upload success');
                resetProfileForm();
                setActiveAction('view');
                setProfilePhotoPreview((prev) => {
                    if (prev) {
                        URL.revokeObjectURL(prev);
                    }
                    return null;
                });
                if (fileInputRef.current) {
                    fileInputRef.current.value = '';
                }
                // Reload the page to get fresh user data with updated profile photo
                router.reload({ only: ['auth'] });
            },
            onError: (errors) => {
                console.error('[Profile] Avatar upload failed', errors);
            },
            onFinish: () => {
                console.debug('[Profile] Avatar upload request finished');
            },
        });
    };

    const handleProfileCancel = () => {
        setProfileData('name', auth.user.name ?? '');
        setProfileData('email', auth.user.email ?? '');
        setProfileData('phone', auth.user.phone ?? '');
        setProfileData('profile_photo', null);
        clearProfileErrors();
        if (profilePhotoPreview) {
            URL.revokeObjectURL(profilePhotoPreview);
            setProfilePhotoPreview(null);
        }
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
        setPendingPhotoSelect(false);
        setActiveAction('view');
    };

    const handleProfilePhotoChange = (event: ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0] ?? null;
        if (file) {
            console.debug('[Profile] Selected avatar file', {
                name: file.name,
                type: file.type,
                size: file.size,
            });
        } else {
            console.debug('[Profile] No avatar file selected');
        }
        setProfileData('profile_photo', file);
        if (profileErrors.profile_photo) {
            clearProfileErrors('profile_photo');
        }

        setProfilePhotoPreview((prev) => {
            if (prev) {
                URL.revokeObjectURL(prev);
            }
            return file ? URL.createObjectURL(file) : null;
        });
    };

    const handleAvatarUploadClick = () => {
        if (activeAction !== 'profile') {
            setActiveAction('profile');
            setPendingPhotoSelect(true);
            return;
        }

        fileInputRef.current?.click();
    };

    const handlePasswordInput = (field: 'current_password' | 'password' | 'password_confirmation') => (event: ChangeEvent<HTMLInputElement>) => {
        setPasswordData(field, event.target.value);
        if (passwordErrors[field]) {
            clearPasswordErrors(field);
        }
    };

    const handlePasswordSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        submitPassword(route('profile.password'), {
            preserveScroll: true,
            onSuccess: () => {
                resetPasswordForm();
                setActiveAction('view');
            },
            onError: () => {
                setPasswordData('password', '');
                setPasswordData('password_confirmation', '');
            },
        });
    };

    const handlePasswordCancel = () => {
        resetPasswordForm();
        clearPasswordErrors();
        setActiveAction('view');
    };

    const handleSecurityInput = (event: ChangeEvent<HTMLInputElement>) => {
        setSecurityData('current_password', event.target.value);
        if (securityErrors.current_password) {
            clearSecurityErrors('current_password');
        }
    };

    const handleSecuritySubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        submitSecurity(route('profile.logout-sessions'), {
            preserveScroll: true,
            onSuccess: () => {
                resetSecurityForm();
                setActiveAction('view');
            },
            onError: () => {
                setSecurityData('current_password', '');
            },
        });
    };

    const handleTransactionPinSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        submitPin(route('profile.transaction-pin'), {
            preserveScroll: true,
            onSuccess: () => {
                resetPinForm();
            },
            onError: () => {
                setPinData('transaction_pin', '');
                setPinData('transaction_pin_confirmation', '');
            },
        });
    };

    const handleDigitInput = (field: 'current_transaction_pin' | 'transaction_pin' | 'transaction_pin_confirmation') => (event: ChangeEvent<HTMLInputElement>) => {
        const digitsOnly = event.target.value.replace(/\D/g, '').slice(0, 6);
        setPinData(field, digitsOnly);
        if (pinErrors[field]) {
            clearPinErrors(field);
        }
        if (pinErrors.error) {
            clearPinErrors('error');
        }
    };

    const handlePinPasswordChange = (event: ChangeEvent<HTMLInputElement>) => {
        setPinData('current_password', event.target.value);
        if (pinErrors.current_password) {
            clearPinErrors('current_password');
        }
    };

    return (
        <MobileLayout user={auth.user} title="Profile" currentRoute="profile">
            <div className="px-4 py-6 space-y-6">
                {(flash?.success || flash?.error) && (
                    <Card className="border-slate-800 bg-slate-900">
                        <CardContent className="py-4">
                            {flash?.success && (
                                <p className="text-sm text-emerald-300">
                                    {flash.success}
                                </p>
                            )}
                            {flash?.error && (
                                <p className="text-sm text-red-300">
                                    {flash.error}
                                </p>
                            )}
                        </CardContent>
                    </Card>
                )}

                {/* Profile Header */}
                <Card className="bg-slate-900 border-slate-800">
                    <CardContent className="pt-6">
                                <div className="flex flex-col items-center space-y-4">
                            <div className="relative w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-3xl overflow-hidden">
                                {profilePhotoPreview || auth.user.profile_photo_url ? (
                                    <img
                                        src={profilePhotoPreview ?? auth.user.profile_photo_url}
                                        alt={auth.user.name}
                                        className="h-full w-full object-cover"
                                        onLoad={(e) => {
                                            const img = e.currentTarget as HTMLImageElement;
                                            console.info('[Profile] Avatar <img> loaded', { src: img.src });
                                        }}
                                        onError={(e) => {
                                            const img = e.currentTarget as HTMLImageElement;
                                            console.error('[Profile] Avatar <img> failed to load', { src: img.src });
                                        }}
                                    />
                                ) : (
                                    auth.user.name.charAt(0).toUpperCase()
                                )}
                                <button
                                    type="button"
                                    onClick={handleAvatarUploadClick}
                                    className="absolute bottom-0 right-0 flex h-8 w-8 items-center justify-center rounded-full border border-slate-800 bg-slate-900/90 text-slate-200 shadow-lg transition hover:bg-slate-800"
                                    aria-label="Upload profile photo"
                                >
                                    <Camera className="h-4 w-4" />
                                </button>
                            </div>
                            <div className="text-center">
                                <h2 className="text-xl font-bold text-slate-50">{auth.user.name}</h2>
                                <p className="text-sm text-slate-400">{auth.user.email}</p>
                            </div>
                            <div className="flex items-center space-x-2">
                                <div className={`w-2 h-2 rounded-full ${
                                    auth.user.status === 'active' ? 'bg-green-500' : 'bg-yellow-500'
                                }`}></div>
                                <span className="text-sm text-slate-400 capitalize">{auth.user.status}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Account Information */}
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50 text-lg">Account Information</CardTitle>
                        <CardDescription className="text-slate-400">
                            Your personal account details
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        {activeAction === 'profile' ? (
                            <form onSubmit={handleProfileSubmit} className="space-y-4">
                                <div>
                                    <label className="text-xs text-slate-300">Full Name</label>
                                    <input
                                        type="text"
                                        value={profileData.name}
                                        onChange={handleProfileInput('name')}
                                        placeholder="Enter your full name"
                                        className="mt-1 w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    {profileErrors.name && (
                                        <p className="mt-2 text-sm text-red-400">{profileErrors.name}</p>
                                    )}
                                </div>
                                <div>
                                    <label className="text-xs text-slate-300">Email Address</label>
                                    <input
                                        type="email"
                                        value={profileData.email}
                                        onChange={handleProfileInput('email')}
                                        placeholder="Enter your email address"
                                        className="mt-1 w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    {profileErrors.email && (
                                        <p className="mt-2 text-sm text-red-400">{profileErrors.email}</p>
                                    )}
                                </div>
                                <div>
                                    <label className="text-xs text-slate-300">Phone Number</label>
                                    <input
                                        type="tel"
                                        value={profileData.phone ?? ''}
                                        onChange={handleProfileInput('phone')}
                                        placeholder="Enter your phone number"
                                        className="mt-1 w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    {profileErrors.phone && (
                                        <p className="mt-2 text-sm text-red-400">{profileErrors.phone}</p>
                                    )}
                                </div>

                                <div>
                                    <label className="text-xs text-slate-300">Profile Photo</label>
                                    <input
                                        ref={fileInputRef}
                                        type="file"
                                        accept="image/*"
                                        onChange={handleProfilePhotoChange}
                                        className="mt-1 block w-full text-sm text-slate-300 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-800 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-50 hover:file:bg-slate-700"
                                    />
                                    <p className="mt-2 text-xs text-slate-500">JPG, PNG, GIF up to 2MB.</p>
                                    {profileErrors.profile_photo && (
                                        <p className="mt-2 text-sm text-red-400">{profileErrors.profile_photo}</p>
                                    )}
                                </div>

                                <div className="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                                    <Button
                                        type="button"
                                        variant="outline"
                                        className="border-slate-700 text-slate-300 hover:bg-slate-800"
                                        onClick={handleProfileCancel}
                                        disabled={profileProcessing}
                                    >
                                        Cancel
                                    </Button>
                                    <Button
                                        type="submit"
                                        className="bg-blue-600 hover:bg-blue-700 text-white"
                                        disabled={profileProcessing}
                                    >
                                        {profileProcessing ? 'Saving...' : 'Save Changes'}
                                    </Button>
                                </div>
                            </form>
                        ) : (
                            <div className="space-y-4">
                        <div>
                            <label className="text-xs text-slate-400">Full Name</label>
                            <p className="text-sm text-slate-50 mt-1">{auth.user.name}</p>
                        </div>
                        <div>
                            <label className="text-xs text-slate-400">Email Address</label>
                            <p className="text-sm text-slate-50 mt-1">{auth.user.email}</p>
                        </div>
                        {auth.user.phone && (
                            <div>
                                <label className="text-xs text-slate-400">Phone Number</label>
                                <p className="text-sm text-slate-50 mt-1">{auth.user.phone}</p>
                            </div>
                        )}
                        <div>
                            <label className="text-xs text-slate-400">Member Since</label>
                            <p className="text-sm text-slate-50 mt-1">
                                {new Date(auth.user.created_at).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                })}
                            </p>
                        </div>
                            </div>
                        )}
                    </CardContent>
                </Card>

                {/* Actions */}
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                        <CardTitle className="text-slate-50 text-lg">Settings & Actions</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        <Button
                            type="button"
                            onClick={() => handleActionChange('profile')}
                            className={actionButtonClasses('profile')}
                            variant={activeAction === 'profile' ? 'default' : 'outline'}
                        >
                            Edit Profile
                        </Button>
                        <Button
                            type="button"
                            onClick={() => handleActionChange('password')}
                            variant={activeAction === 'password' ? 'default' : 'outline'}
                            className={actionButtonClasses('password')}
                        >
                            Change Password
                        </Button>
                        <Button
                            type="button"
                            onClick={() => handleActionChange('security')}
                            variant={activeAction === 'security' ? 'default' : 'outline'}
                            className={actionButtonClasses('security')}
                        >
                            Security Settings
                        </Button>
                    </CardContent>
                </Card>

                {activeAction === 'password' && (
                <Card className="bg-slate-900 border-slate-800">
                    <CardHeader>
                            <CardTitle className="text-slate-50 text-lg">Change Password</CardTitle>
                        <CardDescription className="text-slate-400">
                                Choose a strong password to protect your account.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                            <form onSubmit={handlePasswordSubmit} className="space-y-4">
                                <div>
                                    <label className="text-sm text-slate-300 mb-2 block">Current Password</label>
                                    <input
                                        type="password"
                                        autoComplete="current-password"
                                        value={passwordData.current_password}
                                        onChange={handlePasswordInput('current_password')}
                                        placeholder="Enter current password"
                                        className="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    {passwordErrors.current_password && (
                                        <p className="mt-2 text-sm text-red-400">{passwordErrors.current_password}</p>
                                    )}
                                </div>
                                <div>
                                    <label className="text-sm text-slate-300 mb-2 block">New Password</label>
                                    <input
                                        type="password"
                                        autoComplete="new-password"
                                        value={passwordData.password}
                                        onChange={handlePasswordInput('password')}
                                        placeholder="Enter new password"
                                        className="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    {passwordErrors.password && (
                                        <p className="mt-2 text-sm text-red-400">{passwordErrors.password}</p>
                                    )}
                                </div>
                                <div>
                                    <label className="text-sm text-slate-300 mb-2 block">Confirm Password</label>
                                    <input
                                        type="password"
                                        autoComplete="new-password"
                                        value={passwordData.password_confirmation}
                                        onChange={handlePasswordInput('password_confirmation')}
                                        placeholder="Re-enter new password"
                                        className="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    {passwordErrors.password_confirmation && (
                                        <p className="mt-2 text-sm text-red-400">{passwordErrors.password_confirmation}</p>
                                    )}
                                </div>

                                <div className="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                                    <Button
                                        type="button"
                                        variant="outline"
                                        className="border-slate-700 text-slate-300 hover:bg-slate-800"
                                        onClick={handlePasswordCancel}
                                        disabled={passwordProcessing}
                                    >
                                        Cancel
                                    </Button>
                                    <Button
                                        type="submit"
                                        className="bg-blue-600 hover:bg-blue-700 text-white"
                                        disabled={passwordProcessing}
                                    >
                                        {passwordProcessing ? 'Updating...' : 'Update Password'}
                                    </Button>
                                </div>
                            </form>
                        </CardContent>
                    </Card>
                )}

                {activeAction === 'security' && (
                    <Card className="bg-slate-900 border-slate-800">
                        <CardHeader>
                            <CardTitle className="text-slate-50 text-lg">Security Settings</CardTitle>
                            <CardDescription className="text-slate-400">
                                Review your recent activity, manage sessions, and update your transaction PIN.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            <div>
                                <h3 className="text-sm font-semibold text-slate-200">Recent Login Activity</h3>
                                {loginEntries.length ? (
                                    <div className="mt-3 space-y-3">
                                        {loginEntries.map((entry) => (
                                            <div
                                                key={entry.id}
                                                className="rounded-lg border border-slate-800 bg-slate-950/60 p-3 text-xs text-slate-400"
                                            >
                                                <div className="flex items-center justify-between text-sm">
                                                    <span className="font-medium text-slate-50">
                                                        {entry.location || 'Unknown location'}
                                                    </span>
                                                    <span className={entry.login_successful ? 'text-emerald-300' : 'text-red-300'}>
                                                        {entry.login_successful ? 'Success' : 'Failed'}
                                                    </span>
                                                </div>
                                                <div className="mt-1">
                                                    {entry.device || 'Device unknown'} • {entry.browser || 'Browser unknown'}
                                                </div>
                                                <div className="mt-1 text-slate-500">
                                                    {entry.ip_address || 'IP unavailable'} • {entry.formatted_created_at}
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <p className="mt-2 text-sm text-slate-400">No recent login activity recorded.</p>
                                )}
                            </div>

                            <div className="rounded-lg border border-slate-800 bg-slate-950/60 p-4">
                                <p className="text-sm font-semibold text-slate-50">Log out of other sessions</p>
                                <p className="mt-1 text-xs text-slate-400">
                                    Enter your password to sign out from every device except this one.
                                </p>
                                <form onSubmit={handleSecuritySubmit} className="mt-4 space-y-3">
                                    <div>
                                        <label className="text-xs text-slate-300">Account Password</label>
                                        <input
                                            type="password"
                                            value={securityData.current_password}
                                            onChange={handleSecurityInput}
                                            placeholder="Confirm with your password"
                                            className="mt-1 w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required
                                        />
                                        {securityErrors.current_password && (
                                            <p className="mt-2 text-sm text-red-400">{securityErrors.current_password}</p>
                                        )}
                                    </div>
                                    <div className="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                                        <Button
                                            type="button"
                                            variant="outline"
                                            className="border-slate-700 text-slate-300 hover:bg-slate-800"
                                            onClick={() => {
                                                resetSecurityForm();
                                                clearSecurityErrors();
                                                setActiveAction('view');
                                            }}
                                            disabled={securityProcessing}
                                        >
                                            Cancel
                                        </Button>
                                        <Button
                                            type="submit"
                                            className="bg-amber-600 hover:bg-amber-700 text-white"
                                            disabled={securityProcessing}
                                        >
                                            {securityProcessing ? 'Signing out...' : 'Log out other sessions'}
                                        </Button>
                                    </div>
                                </form>
                            </div>

                            <div className="rounded-lg border border-slate-800 bg-slate-950/60 p-4">
                                <p className="text-sm font-semibold text-slate-50">Transaction PIN</p>
                                <p className="mt-1 text-xs text-slate-400">
                                    {hasTransactionPin
                                        ? 'Update your 6-digit transfer PIN to keep transfers secure.'
                                        : 'Set a 6-digit PIN to authorize transfers from your account.'}
                                </p>

                                {pinErrors.error && (
                                    <div className="mt-3 rounded-lg border border-red-800 bg-red-900/30 px-3 py-2 text-sm text-red-300">
                                        {pinErrors.error}
                            </div>
                        )}

                                <form onSubmit={handleTransactionPinSubmit} className="mt-4 space-y-4">
                            {hasTransactionPin && (
                                <div>
                                    <label className="text-sm text-slate-300 mb-2 block">
                                        Current Transaction PIN
                                    </label>
                                    <input
                                        type="password"
                                        inputMode="numeric"
                                        autoComplete="one-time-code"
                                        maxLength={6}
                                        value={pinData.current_transaction_pin}
                                        onChange={handleDigitInput('current_transaction_pin')}
                                        placeholder="Enter current PIN"
                                        className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-center tracking-[0.5em]"
                                        required
                                    />
                                    {pinErrors.current_transaction_pin && (
                                        <p className="mt-2 text-sm text-red-400">{pinErrors.current_transaction_pin}</p>
                                    )}
                                </div>
                            )}

                            <div>
                                <label className="text-sm text-slate-300 mb-2 block">
                                    New Transaction PIN
                                </label>
                                <input
                                    type="password"
                                    inputMode="numeric"
                                    autoComplete="new-password"
                                    maxLength={6}
                                    value={pinData.transaction_pin}
                                    onChange={handleDigitInput('transaction_pin')}
                                    placeholder="Enter 6-digit PIN"
                                    className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-center text-2xl tracking-[0.5em]"
                                    required
                                />
                                {pinErrors.transaction_pin && (
                                    <p className="mt-2 text-sm text-red-400">{pinErrors.transaction_pin}</p>
                                )}
                            </div>

                            <div>
                                <label className="text-sm text-slate-300 mb-2 block">
                                    Confirm New PIN
                                </label>
                                <input
                                    type="password"
                                    inputMode="numeric"
                                    autoComplete="new-password"
                                    maxLength={6}
                                    value={pinData.transaction_pin_confirmation}
                                    onChange={handleDigitInput('transaction_pin_confirmation')}
                                    placeholder="Re-enter 6-digit PIN"
                                    className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-center tracking-[0.5em]"
                                    required
                                />
                                {pinErrors.transaction_pin_confirmation && (
                                    <p className="mt-2 text-sm text-red-400">{pinErrors.transaction_pin_confirmation}</p>
                                )}
                            </div>

                            <div>
                                <label className="text-sm text-slate-300 mb-2 block">
                                    Confirm with Account Password
                                </label>
                                <input
                                    type="password"
                                    autoComplete="current-password"
                                    value={pinData.current_password}
                                            onChange={handlePinPasswordChange}
                                    placeholder="Enter your account password"
                                    className="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                                {pinErrors.current_password && (
                                    <p className="mt-2 text-sm text-red-400">{pinErrors.current_password}</p>
                                )}
                            </div>

                            <Button
                                type="submit"
                                className="w-full bg-blue-600 hover:bg-blue-700 text-white py-3"
                                disabled={pinProcessing}
                            >
                                {pinProcessing ? 'Saving PIN...' : hasTransactionPin ? 'Update PIN' : 'Set PIN'}
                            </Button>
                        </form>
                            </div>
                    </CardContent>
                </Card>
                )}

                {/* Danger Zone */}
                <Card className="bg-red-950/20 border-red-900">
                    <CardHeader>
                        <CardTitle className="text-red-400 text-lg">Danger Zone</CardTitle>
                        <CardDescription className="text-red-300/60">
                            Irreversible actions
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button variant="destructive" className="w-full">
                            Close Account
                        </Button>
                    </CardContent>
                </Card>
            </div>
        </MobileLayout>
    );
}

