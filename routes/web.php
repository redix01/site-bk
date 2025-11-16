<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TransactionController;

Route::view('/', 'pages.index')->name('home');
Route::view('/about', 'pages.about')->name('about');

Route::prefix('personal')->name('personal.')->group(function () {
    Route::view('/banking-services', 'pages.personal.banking-services')->name('banking-services');
    Route::view('/open-account', 'pages.personal.open-account')->name('open-account');
    Route::view('/customer-support', 'pages.personal.customer-support')->name('customer-support');
});

// Admin Authentication Routes (separate from regular user auth)
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Regular User Authentication Routes (with OTP)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']); // Legacy login
Route::post('/login/send-otp', [LoginController::class, 'sendOtp'])->name('login.send-otp');
Route::post('/login/resend-otp', [LoginController::class, 'resendOtp'])->name('login.resend-otp');
Route::post('/login/verify-otp', [LoginController::class, 'verifyOtpAndLogin'])->name('login.verify-otp');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// API Routes
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/lookup-account/{accountNumber}', [\App\Http\Controllers\Api\AccountLookupController::class, 'lookup']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/transaction-pin', [\App\Http\Controllers\UserProfileController::class, 'updateTransactionPin'])->name('profile.transaction-pin');
    Route::post('/profile/password', [\App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/logout-sessions', [\App\Http\Controllers\UserProfileController::class, 'logoutOtherSessions'])->name('profile.logout-sessions');
    
    // Transactions
    Route::get('/transactions', [\App\Http\Controllers\UserTransactionController::class, 'index'])->name('transactions');
    
    // Transfer
    Route::get('/transfer', [\App\Http\Controllers\UserTransferController::class, 'index'])->name('transfer');
    Route::post('/transfer/internal', [\App\Http\Controllers\UserTransferController::class, 'storeInternal'])->name('transfer.internal');
    Route::post('/transfer/wire', [\App\Http\Controllers\UserTransferController::class, 'storeWire'])->name('transfer.wire');
    Route::post('/transfer/request-code', [\App\Http\Controllers\UserTransferController::class, 'requestTransferCode'])->name('transfer.request-code');
    Route::get('/transfer/success/{transaction}', [\App\Http\Controllers\UserTransferController::class, 'success'])->name('transfer.success');
    Route::get('/transfer/receipt/{transaction}', [\App\Http\Controllers\UserTransferController::class, 'downloadReceipt'])->name('transfer.receipt');
    
    // Deposit
    Route::get('/deposit', [\App\Http\Controllers\UserDepositController::class, 'index'])->name('deposit');
    Route::post('/deposit', [\App\Http\Controllers\UserDepositController::class, 'store'])->name('deposit.store');
    
    // Withdraw
    Route::get('/withdraw', [\App\Http\Controllers\UserWithdrawalController::class, 'index'])->name('withdraw');
    Route::post('/withdraw', [\App\Http\Controllers\UserWithdrawalController::class, 'store'])->name('withdraw.store');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    
    // User Management
    Route::resource('users', UserController::class);
    Route::get('/users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
    Route::post('/users/{user}/fund', [UserController::class, 'fund'])->name('users.fund');
    Route::post('/users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/lock', [UserController::class, 'lock'])->name('users.lock');
    Route::patch('/users/{user}/currency', [UserController::class, 'updateCurrency'])->name('users.currency');
    Route::patch('/users/{user}/created-at', [UserController::class, 'updateCreatedAt'])->name('users.created-at');
    
    // Transaction Management
    Route::resource('transactions', TransactionController::class);
    Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
    Route::post('/transactions/{transaction}/reverse', [TransactionController::class, 'reverse'])->name('transactions.reverse');
    
    // Transaction Codes
    Route::resource('codes', \App\Http\Controllers\Admin\TransactionCodeController::class)->except(['edit', 'update']);
    Route::post('/codes/{code}/send', [\App\Http\Controllers\Admin\TransactionCodeController::class, 'send'])->name('codes.send');
    Route::post('/codes/bulk-generate', [\App\Http\Controllers\Admin\TransactionCodeController::class, 'bulkGenerate'])->name('codes.bulk-generate');
    
    // Payment Methods
    Route::resource('payment-methods', \App\Http\Controllers\Admin\PaymentMethodController::class);
    Route::post('/payment-methods/{paymentMethod}/toggle-status', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/export-transactions', [\App\Http\Controllers\Admin\ReportsController::class, 'exportTransactions'])->name('reports.export-transactions');
    Route::get('/reports/fraud-detection', [\App\Http\Controllers\Admin\ReportsController::class, 'fraudDetection'])->name('reports.fraud-detection');
    
    // Audit Logs
    Route::get('/activity-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('activity-logs');
    Route::get('/activity-logs/{auditLog}', [\App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('activity-logs.show');
    Route::get('/activity-logs/export', [\App\Http\Controllers\Admin\AuditLogController::class, 'export'])->name('activity-logs.export');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/clear-cache', [\App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/run-migrations', [\App\Http\Controllers\Admin\SettingsController::class, 'runMigrations'])->name('settings.run-migrations');
    Route::get('/settings/backup-database', [\App\Http\Controllers\Admin\SettingsController::class, 'backupDatabase'])->name('settings.backup-database');
    Route::get('/settings/system-info', [\App\Http\Controllers\Admin\SettingsController::class, 'systemInfo'])->name('settings.system-info');
});
