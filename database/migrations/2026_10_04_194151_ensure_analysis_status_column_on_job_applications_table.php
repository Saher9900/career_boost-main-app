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
        if (! Schema::hasTable('job_applications')) {
            throw new RuntimeException('The job_applications table must exist before adding analysis_status.');
        }

        if (Schema::hasColumn('job_applications', 'analysis_status')) {
            return;
        }

        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('analysis_status')->default('completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('job_applications') || ! Schema::hasColumn('job_applications', 'analysis_status')) {
            return;
        }

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('analysis_status');
        });
    }
};
