import { FormEventHandler, useEffect, useMemo, useRef, useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';

type RegisterForm = {
    name: string;
    email: string;
    phone: string;
    password: string;
    password_confirmation: string;
    date_of_birth: string;
    gender: string;
    nationality: string;
    address_line1: string;
    address_line2: string;
    city: string;
    state: string;
    postal_code: string;
    country: string;
    passport_number: string;
    passport_country: string;
    passport_expiry: string;
    tax_identification_number: string;
    occupation: string;
    employment_status: string;
    source_of_funds: string;
    branch_code: string;
    preferred_currency: string;
    account_type: 'savings' | 'current' | 'business';
    internal_code: string;
    page_loaded_at: string;
    interaction_token: string;
};

type StepDefinition = {
    key: string;
    title: string;
    description: string;
    fields: (keyof RegisterForm)[];
    required: (keyof RegisterForm)[];
};

const passportDateFormats = [
    {
        regex: /^\d{4}-\d{2}-\d{2}$/,
        parse: (value: string) => new Date(value),
    },
    {
        regex: /^\d{2}\/\d{2}\/\d{4}$/,
        parse: (value: string) => {
            const [day, month, year] = value.split('/').map((segment) => parseInt(segment, 10));
            if (!day || !month || !year) {
                return null;
            }
            return new Date(year, month - 1, day);
        },
    },
    {
        regex: /^\d{2}-\d{2}-\d{4}$/,
        parse: (value: string) => {
            const [month, day, year] = value.split('-').map((segment) => parseInt(segment, 10));
            if (!day || !month || !year) {
                return null;
            }
            return new Date(year, month - 1, day);
        },
    },
];

const parsePassportExpiry = (value: string): Date | null => {
    const trimmed = value.trim();
    if (!trimmed) {
        return null;
    }

    for (const format of passportDateFormats) {
        if (format.regex.test(trimmed)) {
            const parsed = format.parse(trimmed);
            if (parsed instanceof Date && !Number.isNaN(parsed.valueOf())) {
                return parsed;
            }
        }
    }

    const fallback = new Date(trimmed);
    return Number.isNaN(fallback.valueOf()) ? null : fallback;
};

const countryOptions = [
    'Afghanistan',
    'Åland Islands',
    'Albania',
    'Algeria',
    'American Samoa',
    'Andorra',
    'Angola',
    'Anguilla',
    'Antarctica',
    'Antigua and Barbuda',
    'Argentina',
    'Armenia',
    'Aruba',
    'Australia',
    'Austria',
    'Azerbaijan',
    'Bahamas',
    'Bahrain',
    'Bangladesh',
    'Barbados',
    'Belarus',
    'Belgium',
    'Belize',
    'Benin',
    'Bermuda',
    'Bhutan',
    'Bolivia',
    'Bosnia and Herzegovina',
    'Botswana',
    'Bouvet Island',
    'Brazil',
    'British Indian Ocean Territory',
    'Brunei Darussalam',
    'Bulgaria',
    'Burkina Faso',
    'Burundi',
    'Cambodia',
    'Cameroon',
    'Canada',
    'Cape Verde',
    'Cayman Islands',
    'Central African Republic',
    'Chad',
    'Chile',
    'China',
    'Christmas Island',
    'Cocos (Keeling) Islands',
    'Colombia',
    'Comoros',
    'Congo',
    'Congo, The Democratic Republic of The',
    'Cook Islands',
    'Costa Rica',
    "Cote D'ivoire",
    'Croatia',
    'Cuba',
    'Cyprus',
    'Czech Republic',
    'Denmark',
    'Djibouti',
    'Dominica',
    'Dominican Republic',
    'Ecuador',
    'Egypt',
    'El Salvador',
    'Equatorial Guinea',
    'Eritrea',
    'Estonia',
    'Ethiopia',
    'Falkland Islands (Malvinas)',
    'Faroe Islands',
    'Fiji',
    'Finland',
    'France',
    'French Guiana',
    'French Polynesia',
    'French Southern Territories',
    'Gabon',
    'Gambia',
    'Georgia',
    'Germany',
    'Ghana',
    'Gibraltar',
    'Greece',
    'Greenland',
    'Grenada',
    'Guadeloupe',
    'Guam',
    'Guatemala',
    'Guernsey',
    'Guinea',
    'Guinea-bissau',
    'Guyana',
    'Haiti',
    'Heard Island and Mcdonald Islands',
    'Holy See (Vatican City State)',
    'Honduras',
    'Hong Kong',
    'Hungary',
    'Iceland',
    'India',
    'Indonesia',
    'Iran, Islamic Republic of',
    'Iraq',
    'Ireland',
    'Isle of Man',
    'Israel',
    'Italy',
    'Jamaica',
    'Japan',
    'Jersey',
    'Jordan',
    'Kazakhstan',
    'Kenya',
    'Kiribati',
    "Korea, Democratic People's Republic of",
    'Korea, Republic of',
    'Kuwait',
    'Kyrgyzstan',
    "Lao People's Democratic Republic",
    'Latvia',
    'Lebanon',
    'Lesotho',
    'Liberia',
    'Libyan Arab Jamahiriya',
    'Liechtenstein',
    'Lithuania',
    'Luxembourg',
    'Macao',
    'Macedonia, The Former Yugoslav Republic of',
    'Madagascar',
    'Malawi',
    'Malaysia',
    'Maldives',
    'Mali',
    'Malta',
    'Marshall Islands',
    'Martinique',
    'Mauritania',
    'Mauritius',
    'Mayotte',
    'Mexico',
    'Micronesia, Federated States of',
    'Moldova, Republic of',
    'Monaco',
    'Mongolia',
    'Montenegro',
    'Montserrat',
    'Morocco',
    'Mozambique',
    'Myanmar',
    'Namibia',
    'Nauru',
    'Nepal',
    'Netherlands',
    'Netherlands Antilles',
    'New Caledonia',
    'New Zealand',
    'Nicaragua',
    'Niger',
    'Nigeria',
    'Niue',
    'Norfolk Island',
    'Northern Mariana Islands',
    'Norway',
    'Oman',
    'Pakistan',
    'Palau',
    'Palestinian Territory, Occupied',
    'Panama',
    'Papua New Guinea',
    'Paraguay',
    'Peru',
    'Philippines',
    'Pitcairn',
    'Poland',
    'Portugal',
    'Puerto Rico',
    'Qatar',
    'Reunion',
    'Romania',
    'Russian Federation',
    'Rwanda',
    'Saint Helena',
    'Saint Kitts and Nevis',
    'Saint Lucia',
    'Saint Pierre and Miquelon',
    'Saint Vincent and The Grenadines',
    'Samoa',
    'San Marino',
    'Sao Tome and Principe',
    'Saudi Arabia',
    'Senegal',
    'Serbia',
    'Seychelles',
    'Sierra Leone',
    'Singapore',
    'Slovakia',
    'Slovenia',
    'Solomon Islands',
    'Somalia',
    'South Africa',
    'South Georgia and The South Sandwich Islands',
    'Spain',
    'Sri Lanka',
    'Sudan',
    'Suriname',
    'Svalbard and Jan Mayen',
    'Swaziland',
    'Sweden',
    'Switzerland',
    'Syrian Arab Republic',
    'Taiwan',
    'Tajikistan',
    'Tanzania, United Republic of',
    'Thailand',
    'Timor-leste',
    'Togo',
    'Tokelau',
    'Tonga',
    'Trinidad and Tobago',
    'Tunisia',
    'Turkey',
    'Turkmenistan',
    'Turks and Caicos Islands',
    'Tuvalu',
    'Uganda',
    'Ukraine',
    'United Arab Emirates',
    'United Kingdom',
    'United States',
    'United States Minor Outlying Islands',
    'Uruguay',
    'Uzbekistan',
    'Vanuatu',
    'Venezuela',
    'Viet Nam',
    'Virgin Islands, British',
    'Virgin Islands, U.S.',
    'Wallis and Futuna',
    'Western Sahara',
    'Yemen',
    'Zambia',
    'Zimbabwe',
];

export default function Register() {
    const initialData: RegisterForm = {
        name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
        date_of_birth: '',
        gender: 'prefer_not_to_say',
        nationality: '',
        address_line1: '',
        address_line2: '',
        city: '',
        state: '',
        postal_code: '',
        country: '',
        passport_number: '',
        passport_country: '',
        passport_expiry: '',
        tax_identification_number: '',
        occupation: '',
        employment_status: '',
        source_of_funds: '',
        branch_code: '',
        preferred_currency: 'USD',
        account_type: 'savings',
        internal_code: '',
        page_loaded_at: '',
        interaction_token: 'pending',
    };

    const { data, setData, post, processing, errors, reset } = useForm<RegisterForm>(initialData);
    const [currentStep, setCurrentStep] = useState<number>(0);
    const [clientErrors, setClientErrors] = useState<Record<string, string>>({});
    const [botWarning, setBotWarning] = useState<string | null>(null);
    const [humanDetected, setHumanDetected] = useState<boolean>(false);
    const mountTimeRef = useRef<number>(Date.now());
    const securityError = (errors as Record<string, string | undefined>).form_security;

    const steps = useMemo<StepDefinition[]>(
        () => [
            {
                key: 'personal',
                title: 'Personal Details',
                description: 'Identity, contact, and residential information.',
                fields: [
                    'name',
                    'phone',
                    'date_of_birth',
                    'gender',
                    'nationality',
                    'address_line1',
                    'address_line2',
                    'city',
                    'state',
                    'postal_code',
                    'country',
                    'passport_number',
                    'passport_country',
                    'passport_expiry',
                ],
                required: [
                    'name',
                    'phone',
                    'date_of_birth',
                    'nationality',
                    'address_line1',
                    'city',
                    'country',
                    'passport_number',
                    'passport_country',
                ],
            },
            {
                key: 'banking',
                title: 'Bank & Compliance',
                description: 'Financial preferences and regulatory disclosures.',
                fields: [
                    'occupation',
                    'employment_status',
                    'source_of_funds',
                    'tax_identification_number',
                    'branch_code',
                    'preferred_currency',
                    'account_type',
                ],
                required: ['preferred_currency', 'account_type'],
            },
            {
                key: 'account',
                title: 'Account Setup',
                description: 'Credentials and security details.',
                fields: ['email', 'password', 'password_confirmation'],
                required: ['email', 'password', 'password_confirmation'],
            },
        ],
        []
    );

    const updateField = <K extends keyof RegisterForm>(field: K, value: RegisterForm[K]) => {
        setData(field, value as any);
        setClientErrors((previous) => {
            if (!previous[field as string]) {
                return previous;
            }
            const next = { ...previous };
            delete next[field as string];
            return next;
        });
    };

    const getFieldError = (field: keyof RegisterForm) =>
        clientErrors[field as string] ?? (errors[field] as string | undefined);

    const focusStepWithError = () => {
        const serverErrorFields = Object.keys(errors);
        if (serverErrorFields.length === 0) {
            return;
        }

        const stepIndex = steps.findIndex((step) => step.fields.some((field) => serverErrorFields.includes(field)));
        if (stepIndex !== -1) {
            setCurrentStep(stepIndex);
        }
    };

    useEffect(() => {
        focusStepWithError();
    }, [errors, steps]);

    useEffect(() => {
        const startedAt = Date.now();
        mountTimeRef.current = startedAt;
        setData('page_loaded_at', Math.floor(startedAt / 1000).toString());
        setData('internal_code', '');
        setData('interaction_token', 'pending');

        const markHuman = () => {
            setHumanDetected(true);
            setBotWarning(null);
            setData('interaction_token', 'human');
        };

        const events: Array<keyof WindowEventMap> = [
            'mousemove',
            'mousedown',
            'keydown',
            'touchstart',
            'pointerdown',
            'scroll',
        ];

        events.forEach((event) => {
            window.addEventListener(event, markHuman, { once: true });
        });

        return () => {
            events.forEach((event) => {
                window.removeEventListener(event, markHuman);
            });
        };
    }, [setData]);

    useEffect(() => {
        setBotWarning(null);
    }, [currentStep]);

    useEffect(
        () => () => {
            reset();
        },
        [reset]
    );

    const validateStep = (stepIndex: number) => {
        const step = steps[stepIndex];
        const missing: Record<string, string> = {};

        step.required.forEach((field) => {
            const value = data[field];
            if (value === null || value === undefined || value === '' || (Array.isArray(value) && value.length === 0)) {
                missing[field as string] = 'This field is required.';
            }
        });

        if (step.key === 'account' && data.password !== data.password_confirmation) {
            missing.password_confirmation = 'Passwords do not match.';
        }

        if (step.key === 'personal') {
            const expiryRaw = (data.passport_expiry || '').trim();
            if (expiryRaw.length > 0) {
                const parsedDate = parsePassportExpiry(expiryRaw);
                if (!parsedDate) {
                    missing.passport_expiry = 'Enter a valid passport expiry date.';
                } else {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    parsedDate.setHours(0, 0, 0, 0);
                    if (parsedDate < today) {
                        missing.passport_expiry = 'Passport expiry must be today or a future date.';
                    }
                }
            }
        }

        if (Object.keys(missing).length > 0) {
            setClientErrors((previous) => ({ ...previous, ...missing }));
            return false;
        }

        return true;
    };

    const goToNextStep = () => {
        if (!validateStep(currentStep)) {
            return;
        }

        if (currentStep < steps.length - 1) {
            setCurrentStep((previous) => previous + 1);
        }
    };

    const goToPreviousStep = () => {
        setCurrentStep((previous) => Math.max(previous - 1, 0));
    };

    const submit: FormEventHandler = (event) => {
        event.preventDefault();

        const minimumFormTimeMs = 3000;
        const elapsedTime = Date.now() - mountTimeRef.current;

        if (data.internal_code.trim() !== '') {
            setBotWarning('Security challenge failed. Please refresh the page and try again.');
            return;
        }

        if (!humanDetected || data.interaction_token !== 'human') {
            setBotWarning('Please interact with the form (move your mouse or tap) before submitting.');
            return;
        }

        if (elapsedTime < minimumFormTimeMs) {
            setBotWarning('Please take a moment to review your details before submitting.');
            return;
        }

        setBotWarning(null);

        if (currentStep < steps.length - 1) {
            goToNextStep();
            return;
        }

        if (!validateStep(currentStep)) {
            return;
        }

        post('/register');
    };

    const currencyOptions = ['USD', 'EUR', 'GBP', 'NGN', 'CAD'];
    const employmentOptions = [
        { value: '', label: 'Select employment status' },
        { value: 'employed', label: 'Employed' },
        { value: 'self_employed', label: 'Self-employed' },
        { value: 'student', label: 'Student' },
        { value: 'retired', label: 'Retired' },
        { value: 'unemployed', label: 'Unemployed' },
    ];
    const fundsOptions = [
        { value: '', label: 'Select primary source' },
        { value: 'salary', label: 'Salary' },
        { value: 'savings', label: 'Savings' },
        { value: 'investments', label: 'Investments' },
        { value: 'inheritance', label: 'Inheritance' },
        { value: 'business_income', label: 'Business income' },
        { value: 'other', label: 'Other' },
    ];

    return (
        <>
            <Head title="Register" />

            <div className="fixed inset-0 overflow-auto bg-slate-950/95 py-12">
                <div className="mx-auto flex min-h-full w-full max-w-5xl flex-col justify-center px-4 sm:px-6 lg:px-8">
                    <div className="space-y-6">
                        <div className="text-center">
                            <CardTitle className="text-3xl font-bold text-slate-50">Open your Banko account</CardTitle>
                            <CardDescription className="mt-2 text-base text-slate-400">
                                Complete the three-step onboarding to activate secure digital banking.
                            </CardDescription>
                        </div>

                        <div className="grid gap-4 md:grid-cols-3">
                            {steps.map((step, index) => {
                                const isActive = index === currentStep;
                                const isCompleted = index < currentStep;

                                return (
                                    <div
                                        key={step.key}
                                        className={`rounded-lg border p-4 transition ${
                                            isActive
                                                ? 'border-slate-400 bg-slate-900/80'
                                                : isCompleted
                                                ? 'border-emerald-500/40 bg-slate-900/60'
                                                : 'border-slate-800 bg-slate-900/40'
                                        }`}
                                    >
                                        <div
                                            className={`flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold ${
                                                isCompleted
                                                    ? 'bg-emerald-500/20 text-emerald-300'
                                                    : isActive
                                                    ? 'bg-slate-800 text-slate-100'
                                                    : 'bg-slate-800 text-slate-500'
                                            }`}
                                        >
                                            {index + 1}
                                        </div>
                                        <h3 className="mt-3 text-base font-semibold text-slate-50">{step.title}</h3>
                                        <p className="mt-1 text-sm text-slate-400">{step.description}</p>
                                    </div>
                                );
                            })}
                        </div>

                        <Card className="border-slate-800 bg-slate-900">
                            <CardHeader>
                                <CardTitle className="text-slate-50">
                                    {steps[currentStep]?.title ?? 'Account Setup'}
                        </CardTitle>
                                <CardDescription className="text-slate-400">
                                    {steps[currentStep]?.description ?? 'Add your account credentials.'}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                                <form onSubmit={submit} className="space-y-6">
                                    <div
                                        aria-hidden="true"
                                        style={{
                                            position: 'absolute',
                                            left: '-10000px',
                                            top: 'auto',
                                            width: '1px',
                                            height: '1px',
                                            overflow: 'hidden',
                                        }}
                                    >
                                        <label htmlFor="internal_code">Internal code</label>
                                        <input
                                            id="internal_code"
                                            name="internal_code"
                                            type="text"
                                            tabIndex={-1}
                                            autoComplete="off"
                                            value={data.internal_code}
                                            onChange={(event) => setData('internal_code', event.target.value)}
                                        />
                                    </div>

                                    {(botWarning || securityError) && (
                                        <div className="rounded-md border border-red-500/60 bg-red-500/10 p-3 text-sm text-red-200">
                                            {botWarning ?? securityError}
                                        </div>
                                    )}

                                    {currentStep === 0 && (
                                        <div className="grid gap-4 md:grid-cols-2">
                            <div>
                                                <label
                                                    htmlFor="name"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Full Name <span className="text-red-400">*</span>
                                </label>
                                <input
                                    id="name"
                                    type="text"
                                    value={data.name}
                                                    onChange={(event) => updateField('name', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    autoComplete="name"
                                    autoFocus
                                                />
                                                {getFieldError('name') && (
                                                    <p className="mt-1 text-sm text-red-400">{getFieldError('name')}</p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="phone"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Phone Number <span className="text-red-400">*</span>
                                                </label>
                                                <input
                                                    id="phone"
                                                    type="tel"
                                                    value={data.phone}
                                                    onChange={(event) => updateField('phone', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                    autoComplete="tel"
                                                />
                                                {getFieldError('phone') && (
                                                    <p className="mt-1 text-sm text-red-400">{getFieldError('phone')}</p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="date_of_birth"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Date of Birth <span className="text-red-400">*</span>
                                                </label>
                                                <input
                                                    id="date_of_birth"
                                                    type="date"
                                                    value={data.date_of_birth}
                                                    onChange={(event) => updateField('date_of_birth', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                />
                                                {getFieldError('date_of_birth') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('date_of_birth')}
                                                    </p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="gender"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Gender (optional)
                                                </label>
                                                <select
                                                    id="gender"
                                                    value={data.gender}
                                                    onChange={(event) => updateField('gender', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                >
                                                    <option value="prefer_not_to_say">Prefer not to say</option>
                                                    <option value="female">Female</option>
                                                    <option value="male">Male</option>
                                                    <option value="other">Other</option>
                                                </select>
                                                {getFieldError('gender') && (
                                                    <p className="mt-1 text-sm text-red-400">{getFieldError('gender')}</p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="nationality"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Nationality <span className="text-red-400">*</span>
                                                </label>
                                            <select
                                                id="nationality"
                                                value={data.nationality}
                                                onChange={(event) => updateField('nationality', event.target.value)}
                                                className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                            >
                                                <option value="">Select nationality</option>
                                                {countryOptions.map((countryName) => (
                                                    <option key={countryName} value={countryName}>
                                                        {countryName}
                                                    </option>
                                                ))}
                                            </select>
                                                {getFieldError('nationality') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('nationality')}
                                                    </p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="country"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Country of Residence <span className="text-red-400">*</span>
                                                </label>
                                            <select
                                                id="country"
                                                value={data.country}
                                                onChange={(event) => updateField('country', event.target.value)}
                                                className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                            >
                                                <option value="">Select country</option>
                                                {countryOptions.map((countryName) => (
                                                    <option key={countryName} value={countryName}>
                                                        {countryName}
                                                    </option>
                                                ))}
                                            </select>
                                                {getFieldError('country') && (
                                                    <p className="mt-1 text-sm text-red-400">{getFieldError('country')}</p>
                                                )}
                                            </div>

                                            <div className="md:col-span-2">
                                                <label
                                                    htmlFor="address_line1"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Street Address <span className="text-red-400">*</span>
                                                </label>
                                                <input
                                                    id="address_line1"
                                                    type="text"
                                                    value={data.address_line1}
                                                    onChange={(event) => updateField('address_line1', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                    placeholder="Building and street information"
                                                />
                                                {getFieldError('address_line1') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('address_line1')}
                                                    </p>
                                                )}
                                            </div>

                                            <div className="md:col-span-2">
                                                <label
                                                    htmlFor="address_line2"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Address Line 2 (optional)
                                                </label>
                                                <input
                                                    id="address_line2"
                                                    type="text"
                                                    value={data.address_line2}
                                                    onChange={(event) => updateField('address_line2', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                    placeholder="Apartment, suite, unit, etc."
                                                />
                                                {getFieldError('address_line2') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('address_line2')}
                                                    </p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="city"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    City <span className="text-red-400">*</span>
                                                </label>
                                                <input
                                                    id="city"
                                                    type="text"
                                                    value={data.city}
                                                    onChange={(event) => updateField('city', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                />
                                                {getFieldError('city') && (
                                                    <p className="mt-1 text-sm text-red-400">{getFieldError('city')}</p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="state"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    State / Province (optional)
                                                </label>
                                                <input
                                                    id="state"
                                                    type="text"
                                                    value={data.state}
                                                    onChange={(event) => updateField('state', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                />
                                                {getFieldError('state') && (
                                                    <p className="mt-1 text-sm text-red-400">{getFieldError('state')}</p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="postal_code"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Postal Code (optional)
                                                </label>
                                                <input
                                                    id="postal_code"
                                                    type="text"
                                                    value={data.postal_code}
                                                    onChange={(event) => updateField('postal_code', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                />
                                                {getFieldError('postal_code') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('postal_code')}
                                                    </p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="passport_number"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Passport Number <span className="text-red-400">*</span>
                                                </label>
                                                <input
                                                    id="passport_number"
                                                    type="text"
                                                    value={data.passport_number}
                                                    onChange={(event) => updateField('passport_number', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                />
                                                {getFieldError('passport_number') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('passport_number')}
                                                    </p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="passport_country"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Passport Issuing Country <span className="text-red-400">*</span>
                                                </label>
                                                <input
                                                    id="passport_country"
                                                    type="text"
                                                    value={data.passport_country}
                                                    onChange={(event) => updateField('passport_country', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                />
                                                {getFieldError('passport_country') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('passport_country')}
                                                    </p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="passport_expiry"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Passport Expiry Date (optional)
                                                </label>
                                                <input
                                                    id="passport_expiry"
                                                    type="date"
                                                    value={data.passport_expiry}
                                                    onChange={(event) => updateField('passport_expiry', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                />
                                                {getFieldError('passport_expiry') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('passport_expiry')}
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                    )}

                                    {currentStep === 1 && (
                                        <div className="grid gap-4 md:grid-cols-2">
                                            <div>
                                                <label
                                                    htmlFor="occupation"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Occupation (optional)
                                                </label>
                                                <input
                                                    id="occupation"
                                                    type="text"
                                                    value={data.occupation}
                                                    onChange={(event) => updateField('occupation', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                />
                                                {getFieldError('occupation') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('occupation')}
                                                    </p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="employment_status"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Employment Status (optional)
                                                </label>
                                                <select
                                                    id="employment_status"
                                                    value={data.employment_status}
                                                    onChange={(event) =>
                                                        updateField('employment_status', event.target.value)
                                                    }
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                >
                                                    {employmentOptions.map((option) => (
                                                        <option key={option.value} value={option.value}>
                                                            {option.label}
                                                        </option>
                                                    ))}
                                                </select>
                                                {getFieldError('employment_status') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('employment_status')}
                                                    </p>
                                                )}
                                            </div>

                                            <div>
                                                <label
                                                    htmlFor="source_of_funds"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Primary Source of Funds (optional)
                                                </label>
                                                <select
                                                    id="source_of_funds"
                                                    value={data.source_of_funds}
                                                    onChange={(event) => updateField('source_of_funds', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                >
                                                    {fundsOptions.map((option) => (
                                                        <option key={option.value} value={option.value}>
                                                            {option.label}
                                                        </option>
                                                    ))}
                                                </select>
                                                {getFieldError('source_of_funds') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('source_of_funds')}
                                                    </p>
                                )}
                            </div>

                            <div>
                                                <label
                                                    htmlFor="tax_identification_number"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Tax Identification Number (optional)
                                </label>
                                <input
                                                    id="tax_identification_number"
                                                    type="text"
                                                    value={data.tax_identification_number}
                                                    onChange={(event) =>
                                                        updateField('tax_identification_number', event.target.value)
                                                    }
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                    placeholder="TIN, SSN, etc."
                                                />
                                                {getFieldError('tax_identification_number') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('tax_identification_number')}
                                                    </p>
                                                )}
                                            </div>

                                                <div>
                                                    <label
                                                        htmlFor="preferred_currency"
                                                        className="mb-1 block text-sm font-medium text-slate-300"
                                                    >
                                                        Preferred Currency <span className="text-red-400">*</span>
                                                    </label>
                                                    <select
                                                        id="preferred_currency"
                                                        value={data.preferred_currency}
                                                        onChange={(event) => updateField('preferred_currency', event.target.value)}
                                                        className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                    >
                                                        {currencyOptions.map((currency) => (
                                                            <option key={currency} value={currency}>
                                                                {currency}
                                                            </option>
                                                        ))}
                                                    </select>
                                                    {getFieldError('preferred_currency') && (
                                                        <p className="mt-1 text-sm text-red-400">
                                                            {getFieldError('preferred_currency')}
                                                        </p>
                                )}
                            </div>

                            <div>
                                                    <label
                                                        htmlFor="account_type"
                                                        className="mb-1 block text-sm font-medium text-slate-300"
                                                    >
                                                        Account Type <span className="text-red-400">*</span>
                                                    </label>
                                                    <select
                                                        id="account_type"
                                                        value={data.account_type}
                                                        onChange={(event) =>
                                                            updateField(
                                                                'account_type',
                                                                event.target.value as RegisterForm['account_type']
                                                            )
                                                        }
                                                        className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                    >
                                                        <option value="savings">Savings</option>
                                                        <option value="current">Current</option>
                                                        <option value="business">Business</option>
                                                    </select>
                                                    {getFieldError('account_type') && (
                                                        <p className="mt-1 text-sm text-red-400">
                                                            {getFieldError('account_type')}
                                                        </p>
                                                    )}
                                                </div>

                                                <div className="md:col-span-2">
                                                    <label
                                                        htmlFor="branch_code"
                                                        className="mb-1 block text-sm font-medium text-slate-300"
                                                    >
                                                        Branch Code (optional)
                                                    </label>
                                                    <input
                                                        id="branch_code"
                                                        type="text"
                                                        value={data.branch_code}
                                                        onChange={(event) => updateField('branch_code', event.target.value)}
                                                        className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                        placeholder="Internal reference or branch identifier"
                                                    />
                                                    {getFieldError('branch_code') && (
                                                        <p className="mt-1 text-sm text-red-400">
                                                            {getFieldError('branch_code')}
                                                        </p>
                                                    )}
                                                </div>
                                        </div>
                                    )}

                                    {currentStep === 2 && (
                                        <div className="grid gap-4 md:grid-cols-2">
                                            <div className="md:col-span-2">
                                                <label
                                                    htmlFor="email"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Email Address <span className="text-red-400">*</span>
                                </label>
                                <input
                                                    id="email"
                                                    type="email"
                                                    value={data.email}
                                                    onChange={(event) => updateField('email', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                                    autoComplete="email"
                                                />
                                                {getFieldError('email') && (
                                                    <p className="mt-1 text-sm text-red-400">{getFieldError('email')}</p>
                                )}
                            </div>

                            <div>
                                                <label
                                                    htmlFor="password"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Password <span className="text-red-400">*</span>
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    value={data.password}
                                                    onChange={(event) => updateField('password', event.target.value)}
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    autoComplete="new-password"
                                />
                                                {getFieldError('password') && (
                                                    <p className="mt-1 text-sm text-red-400">{getFieldError('password')}</p>
                                )}
                            </div>

                            <div>
                                                <label
                                                    htmlFor="password_confirmation"
                                                    className="mb-1 block text-sm font-medium text-slate-300"
                                                >
                                                    Confirm Password <span className="text-red-400">*</span>
                                </label>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    value={data.password_confirmation}
                                                    onChange={(event) =>
                                                        updateField('password_confirmation', event.target.value)
                                                    }
                                                    className="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-600"
                                    autoComplete="new-password"
                                                />
                                                {getFieldError('password_confirmation') && (
                                                    <p className="mt-1 text-sm text-red-400">
                                                        {getFieldError('password_confirmation')}
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                    )}

                                    <div className="flex flex-col gap-3 pt-4 md:flex-row md:items-center md:justify-between">
                                        <div className="text-sm text-slate-400">
                                            Step {currentStep + 1} of {steps.length}
                            </div>

                                        <div className="flex flex-1 items-center justify-end gap-3">
                                            {currentStep > 0 && (
                                                <Button
                                                    type="button"
                                                    variant="outline"
                                                    onClick={goToPreviousStep}
                                                    className="border-slate-700 bg-slate-800 text-slate-50 hover:bg-slate-700"
                                                >
                                                    Back
                                                </Button>
                                            )}

                            <Button
                                type="submit"
                                disabled={processing}
                                                className="bg-slate-700 text-slate-50 hover:bg-slate-600"
                                            >
                                                {processing
                                                    ? 'Submitting...'
                                                    : currentStep === steps.length - 1
                                                    ? 'Create Account'
                                                    : 'Next Step'}
                            </Button>
                                        </div>
                                    </div>
                        </form>

                        <div className="mt-6 text-center text-sm">
                            <span className="text-slate-400">Already have an account? </span>
                                    <Link href="/login" className="font-medium text-slate-300 hover:text-slate-50">
                                Sign in
                            </Link>
                        </div>
                    </CardContent>
                </Card>
                    </div>
                </div>
            </div>
        </>
    );
}

