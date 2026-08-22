export interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
    profile_photo_url?: string;
    date_of_birth?: string;
    gender?: string;
    nationality?: string;
    address_line1?: string;
    address_line2?: string;
    city?: string;
    state?: string;
    postal_code?: string;
    country?: string;
    passport_number?: string;
    passport_country?: string;
    passport_expiry?: string;
    tax_identification_number?: string;
    occupation?: string;
    employment_status?: string;
    source_of_funds?: string;
    branch_code?: string;
    preferred_currency?: string;
    account_type?: 'savings' | 'current' | 'business';
    status?: 'active' | 'suspended' | 'pending' | 'locked';
    is_admin: boolean;
    balance?: number;
    email_verified_at?: string;
    created_at: string;
    updated_at: string;
    account_number?: string; // Accessor from wallet relationship
    has_transaction_pin?: boolean;
    wallet?: Wallet | null;
}

export interface Transaction {
    id: number;
    user_id: number;
    recipient_id?: number;
    type: 'deposit' | 'withdrawal' | 'transfer';
    amount: number;
    fee?: number;
    reference: string;
    status: 'pending' | 'completed' | 'failed' | 'cancelled' | 'reversed';
    description?: string;
    metadata?: Record<string, any>;
    created_at: string;
    updated_at: string;
    user?: User;
    recipient?: User;
}

export interface TransactionCode {
    id: number;
    code: string;
    type: 'deposit' | 'withdrawal' | 'transfer';
    amount?: number | string | null;
    created_by: number;
    used_by?: number;
    transaction_id?: number;
    expires_at: string;
    is_used: boolean;
    used_at?: string;
    notes?: string;
    created_at: string;
    updated_at: string;
    creator?: User;
    usedBy?: User;
}

export interface AuditLog {
    id: number;
    actor_id?: number;
    event: string;
    auditable_type?: string;
    auditable_id?: number;
    details?: Record<string, any>;
    ip_address?: string;
    user_agent?: string;
    created_at: string;
    actor?: User;
}

export interface Wallet {
    id: number;
    user_id: number;
    account_number: string;
    balance: number;
    ledger_balance: number;
    currency: string;
    status: 'active' | 'inactive' | 'suspended' | 'frozen' | 'closed';
    created_at: string;
    updated_at: string;
}

export interface DashboardStats {
    total_deposits: number;
    total_withdrawals: number;
    total_transfers_sent: number;
    total_transfers_received: number;
}

export interface AppSettings {
    siteName: string;
    siteEmail: string;
    supportEmail?: string | null;
    logoUrl?: string | null;
}

export interface PageProps {
    auth: {
        user: User;
    };
    flash?: {
        success?: string;
        error?: string;
    };
    supportEmail?: string;
    appSettings?: AppSettings;
    wallet?: Wallet;
    recentTransactions?: Transaction[];
    stats?: DashboardStats;
    users?: User[];
    loginHistory?: LoginHistory[];
    notices?: AdminNotice[];
    unreadNoticeCount?: number;
}

export interface AdminNotice {
    id: number;
    user_id: number;
    created_by?: number | null;
    type: 'info' | 'warning';
    title: string;
    message: string;
    read_at?: string | null;
    created_at: string;
    updated_at: string;
}

export interface LoginHistory {
    id: number;
    ip_address?: string | null;
    device?: string | null;
    platform?: string | null;
    browser?: string | null;
    location?: string | null;
    login_successful: boolean;
    formatted_created_at: string;
    created_at: string;
}

