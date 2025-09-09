<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfitShareController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Projects
    Route::resource('projects', ProjectController::class);
    
    // Proposals
    Route::resource('proposals', ProposalController::class);
    
    // Leads
    Route::resource('leads', LeadController::class);
    
    // Daily Reports
    Route::resource('daily-reports', DailyReportController::class);
    
    // Expenses
    Route::resource('expenses', ExpenseController::class);
    Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    
    // Payments (Investor only)
    Route::middleware('investor')->group(function () {
        Route::resource('payments', PaymentController::class);
    });
    
    // Profit Shares
    Route::resource('profit-shares', ProfitShareController::class);
    Route::post('profit-shares/calculate', [ProfitShareController::class, 'calculate'])->name('profit-shares.calculate');
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class);
        Route::get('overview', [DashboardController::class, 'adminOverview'])->name('overview');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
