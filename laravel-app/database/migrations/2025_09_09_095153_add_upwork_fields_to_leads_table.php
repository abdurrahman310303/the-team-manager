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
        Schema::table('leads', function (Blueprint $table) {
            // Upwork specific fields
            $table->string('upwork_job_id')->nullable()->after('source');
            $table->string('upwork_job_url')->nullable()->after('upwork_job_id');
            $table->enum('upwork_job_type', ['hourly', 'fixed_price', 'milestone'])->nullable()->after('upwork_job_url');
            $table->decimal('upwork_budget_min', 10, 2)->nullable()->after('upwork_job_type');
            $table->decimal('upwork_budget_max', 10, 2)->nullable()->after('upwork_budget_min');
            $table->integer('upwork_proposals_count')->nullable()->after('upwork_budget_max');
            $table->integer('upwork_connects_required')->nullable()->after('upwork_proposals_count');
            $table->enum('upwork_client_rating', ['excellent', 'good', 'average', 'poor', 'new'])->nullable()->after('upwork_connects_required');
            $table->integer('upwork_client_jobs_posted')->nullable()->after('upwork_client_rating');
            $table->decimal('upwork_client_hourly_rate', 8, 2)->nullable()->after('upwork_client_jobs_posted');
            $table->text('upwork_job_description')->nullable()->after('upwork_client_hourly_rate');
            $table->text('upwork_skills_required')->nullable()->after('upwork_job_description');
            $table->enum('upwork_experience_level', ['entry', 'intermediate', 'expert'])->nullable()->after('upwork_skills_required');
            $table->integer('upwork_job_duration')->nullable()->after('upwork_experience_level'); // in days
            $table->enum('upwork_job_status', ['open', 'interviewing', 'awarded', 'closed'])->nullable()->after('upwork_job_duration');
            $table->timestamp('upwork_job_posted_at')->nullable()->after('upwork_job_status');
            $table->timestamp('upwork_proposal_sent_at')->nullable()->after('upwork_job_posted_at');
            $table->text('upwork_proposal_text')->nullable()->after('upwork_proposal_sent_at');
            $table->decimal('upwork_proposal_amount', 10, 2)->nullable()->after('upwork_proposal_text');
            $table->integer('upwork_proposal_delivery_days')->nullable()->after('upwork_proposal_amount');
            
            // LinkedIn specific fields
            $table->string('linkedin_company_url')->nullable()->after('upwork_proposal_delivery_days');
            $table->string('linkedin_contact_url')->nullable()->after('linkedin_company_url');
            $table->text('linkedin_connection_message')->nullable()->after('linkedin_contact_url');
            $table->timestamp('linkedin_connection_sent_at')->nullable()->after('linkedin_connection_message');
            $table->enum('linkedin_connection_status', ['pending', 'accepted', 'declined', 'ignored'])->nullable()->after('linkedin_connection_sent_at');
            
            // General bidding fields
            $table->enum('bidding_platform', ['upwork', 'linkedin', 'direct', 'referral', 'other'])->default('upwork')->after('linkedin_connection_status');
            $table->enum('bidding_status', ['researching', 'ready_to_bid', 'bid_sent', 'interviewing', 'negotiating', 'won', 'lost', 'withdrawn'])->default('researching')->after('bidding_platform');
            $table->text('bidding_notes')->nullable()->after('bidding_status');
            $table->integer('bidding_priority')->default(1)->after('bidding_notes'); // 1-5 scale
            $table->boolean('requires_connects')->default(false)->after('bidding_priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'upwork_job_id',
                'upwork_job_url',
                'upwork_job_type',
                'upwork_budget_min',
                'upwork_budget_max',
                'upwork_proposals_count',
                'upwork_connects_required',
                'upwork_client_rating',
                'upwork_client_jobs_posted',
                'upwork_client_hourly_rate',
                'upwork_job_description',
                'upwork_skills_required',
                'upwork_experience_level',
                'upwork_job_duration',
                'upwork_job_status',
                'upwork_job_posted_at',
                'upwork_proposal_sent_at',
                'upwork_proposal_text',
                'upwork_proposal_amount',
                'upwork_proposal_delivery_days',
                'linkedin_company_url',
                'linkedin_contact_url',
                'linkedin_connection_message',
                'linkedin_connection_sent_at',
                'linkedin_connection_status',
                'bidding_platform',
                'bidding_status',
                'bidding_notes',
                'bidding_priority',
                'requires_connects'
            ]);
        });
    }
};