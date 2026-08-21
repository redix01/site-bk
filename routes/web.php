<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Pages3Controller;

// Homepage - using Pages3 template
Route::get('/', [Pages3Controller::class, 'home'])->name('home');
Route::view('/about', 'pages.about')->name('about');

// Pages-3 Routes
Route::prefix('pages-3')->name('pages3.')->group(function () {
    Route::get('/', [Pages3Controller::class, 'home'])->name('home');
    Route::get('/legal', [Pages3Controller::class, 'legal'])->name('legal');
    
    // Home sub-pages with simplified route names for header menu
    Route::get('/asset-management', [Pages3Controller::class, 'homeAssetManagement'])->name('assetmanagement');
    Route::get('/international-banking', [Pages3Controller::class, 'homeInternationalBanking'])->name('internationalbanking');
    Route::get('/private-banking', [Pages3Controller::class, 'homePrivateBanking'])->name('privatebanking');
    Route::get('/contact', [Pages3Controller::class, 'homeContact'])->name('contact');
    Route::get('/our-company', [Pages3Controller::class, 'homeOurCompany'])->name('ourcompany');
    Route::get('/media', [Pages3Controller::class, 'homeMedia'])->name('media');
    Route::get('/investor-relations', [Pages3Controller::class, 'homeInvestorRelations'])->name('investorrelations');
    Route::get('/institutional-clients', [Pages3Controller::class, 'homeInstitutionalClients'])->name('institutionalclients');
    
    // Our Company sub-pages
    Route::get('/our-company/board-of-directors', [Pages3Controller::class, 'homeOurCompanyBoardOfDirectors'])->name('ourcompany.boardofdirectors');
    Route::get('/our-company/corporate-governance', [Pages3Controller::class, 'homeOurCompanyCorporateGovernance'])->name('ourcompany.corporategovernance');
    Route::get('/our-company/corporate-strategy', [Pages3Controller::class, 'homeOurCompanyCorporateStrategy'])->name('ourcompany.corporatestrategy');
    Route::get('/our-company/executive-board', [Pages3Controller::class, 'homeOurCompanyExecutiveBoard'])->name('ourcompany.executiveboard');
    Route::get('/our-company/history', [Pages3Controller::class, 'homeOurCompanyHistory'])->name('ourcompany.history');
    Route::get('/our-company/mission-statement', [Pages3Controller::class, 'homeOurCompanyMissionStatement'])->name('ourcompany.missionstatement');
    Route::get('/our-company/public-service-mandate', [Pages3Controller::class, 'homeOurCompanyPublicServiceMandate'])->name('ourcompany.publicservicemandate');
    
    // Legal sub-pages
    Route::get('/legal/whistleblowing', [Pages3Controller::class, 'legalWhistleblowing'])->name('legal.whistleblowing');
    Route::get('/legal/aeoi', [Pages3Controller::class, 'legalAeoi'])->name('legal.aeoi');
    Route::get('/legal/data-protection', [Pages3Controller::class, 'legalDataProtection'])->name('legal.data-protection');
    Route::get('/legal/terms-conditions', [Pages3Controller::class, 'legalTermsConditions'])->name('legal.terms-conditions');
    Route::get('/legal/trading-and-investment-business', [Pages3Controller::class, 'legalTradingAndInvestmentBusiness'])->name('legal.trading-and-investment-business');
    Route::get('/legal/conflict-of-interest', [Pages3Controller::class, 'legalConflictOfInterest'])->name('legal.conflict-of-interest');
    Route::get('/legal/company-structure', [Pages3Controller::class, 'legalCompanyStructure'])->name('legal.companystructure');
    Route::get('/legal/general-information', [Pages3Controller::class, 'legalGeneralInformation'])->name('legal.general-information');
    Route::get('/legal/gips', [Pages3Controller::class, 'legalGips'])->name('legal.gips');
    Route::get('/legal/kyc-aml-patriot-act', [Pages3Controller::class, 'legalKycAmlPatriotAct'])->name('legal.kyc-aml-patriot-act');
    Route::get('/legal/legal-notices-websites', [Pages3Controller::class, 'legalLegalNoticesWebsites'])->name('legal.legal-notices-websites');
    Route::get('/legal/trust-services', [Pages3Controller::class, 'legalTrustServices'])->name('legal.trust-services');
    
    // LPS pages
    Route::get('/lps/private-banking', [Pages3Controller::class, 'lpsPrivateBanking'])->name('lps.private-banking');
    Route::get('/lps/corporate/berichterstattung', [Pages3Controller::class, 'lpsCorporateBerichterstattung'])->name('lps.corporate.berichterstattung');
});

Route::prefix('personal')->name('personal.')->group(function () {
    Route::view('/banking-services', 'pages.personal.banking-services')->name('banking-services');
    Route::view('/open-account', 'pages.personal.open-account')->name('open-account');
    Route::view('/customer-support', 'pages.personal.customer-support')->name('customer-support');
});

// Unified Authentication Routes — single /login for everyone
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
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
    Route::get('/deposit/crypto', [\App\Http\Controllers\UserDepositController::class, 'crypto'])->name('deposit.crypto');
    Route::post('/deposit', [\App\Http\Controllers\UserDepositController::class, 'store'])->name('deposit.store');
    
    // Withdraw
    Route::get('/withdraw', [\App\Http\Controllers\UserWithdrawalController::class, 'index'])->name('withdraw');
    Route::post('/withdraw', [\App\Http\Controllers\UserWithdrawalController::class, 'store'])->name('withdraw.store');

    // Savings
    Route::get('/savings', [\App\Http\Controllers\UserSavingsController::class, 'index'])->name('savings');
    Route::post('/savings', [\App\Http\Controllers\UserSavingsController::class, 'store'])->name('savings.store');

    // Invest
    Route::get('/invest', [\App\Http\Controllers\UserInvestController::class, 'index'])->name('invest');
    Route::post('/invest', [\App\Http\Controllers\UserInvestController::class, 'store'])->name('invest.store');
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
