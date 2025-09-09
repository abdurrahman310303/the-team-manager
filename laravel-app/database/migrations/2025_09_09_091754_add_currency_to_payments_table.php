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
            $table->string('currency', 3)->default('USD')->after('amount');
            $table->decimal('exchange_rate', 10, 4)->nullable()->after('currency');
            $table->decimal('amount_usd', 15, 2)->nullable()->after('exchange_rate');
            $table->enum('fund_purpose', ['salaries', 'upwork_connects', 'project_expenses', 'office_rent', 'equipment', 'marketing', 'other'])->after('payment_type');
            $table->boolean('is_project_related')->default(false)->after('fund_purpose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['currency', 'exchange_rate', 'amount_usd', 'fund_purpose', 'is_project_related']);
        });
    }
};