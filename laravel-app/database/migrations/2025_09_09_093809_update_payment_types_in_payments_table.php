<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // First, modify the payment_type enum to include new values
            $table->enum('payment_type', ['investment', 'expense_reimbursement', 'profit_share', 'reimbursement', 'other'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_type', ['investment', 'expense_reimbursement', 'profit_share'])->change();
        });
    }
};