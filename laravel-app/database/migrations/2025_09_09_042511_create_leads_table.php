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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'proposal_sent', 'negotiating', 'closed_won', 'closed_lost'])->default('new');
            $table->enum('source', ['website', 'referral', 'cold_call', 'social_media', 'advertisement', 'other'])->default('website');
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->date('last_contact_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
