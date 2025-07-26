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
        Schema::table('security_logs', function (Blueprint $table) {
            // Index for IP address and created_at (most common query pattern)
            $table->index(['ip_address', 'created_at'], 'idx_security_logs_ip_created');
            
            // Index for event_type and severity (filtering queries)
            $table->index(['event_type', 'severity'], 'idx_security_logs_event_severity');
            
            // Index for is_resolved and created_at (pending issues queries)
            $table->index(['is_resolved', 'created_at'], 'idx_security_logs_resolved_created');
            
            // Index for user_id and created_at (user-specific queries)
            $table->index(['user_id', 'created_at'], 'idx_security_logs_user_created');
            
            // Index for severity and created_at (critical events queries)
            $table->index(['severity', 'created_at'], 'idx_security_logs_severity_created');
            
            // Index for event_type and created_at (event type filtering)
            $table->index(['event_type', 'created_at'], 'idx_security_logs_event_created');
            
            // Index for risk_score (high-risk queries)
            $table->index('risk_score', 'idx_security_logs_risk_score');
            
            // Index for route (attacked routes analysis)
            $table->index('route', 'idx_security_logs_route');
            
            // Index for country_code (geographic analysis)
            $table->index('country_code', 'idx_security_logs_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_logs', function (Blueprint $table) {
            $table->dropIndex('idx_security_logs_ip_created');
            $table->dropIndex('idx_security_logs_event_severity');
            $table->dropIndex('idx_security_logs_resolved_created');
            $table->dropIndex('idx_security_logs_user_created');
            $table->dropIndex('idx_security_logs_severity_created');
            $table->dropIndex('idx_security_logs_event_created');
            $table->dropIndex('idx_security_logs_risk_score');
            $table->dropIndex('idx_security_logs_route');
            $table->dropIndex('idx_security_logs_country');
        });
    }
};
