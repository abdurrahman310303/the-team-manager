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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('report_type', ['developer', 'bd', 'general']);
            $table->date('report_date');
            $table->text('work_completed');
            $table->text('challenges_faced')->nullable();
            $table->text('next_plans')->nullable();
            $table->integer('hours_worked')->default(0);
            $table->integer('leads_generated')->default(0);
            $table->integer('proposals_submitted')->default(0);
            $table->integer('projects_locked')->default(0);
            $table->decimal('revenue_generated', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'report_date', 'report_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
