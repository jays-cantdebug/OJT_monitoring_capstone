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
        Schema::table('student_profiles', function (Blueprint $table) {
            // Pinned by the Dean, never the student - a student-set pin could
            // simply be moved to wherever they happen to be.
            $table->decimal('company_latitude', 10, 7)->nullable()->after('company_address');
            $table->decimal('company_longitude', 10, 7)->nullable()->after('company_latitude');
            $table->unsignedSmallInteger('geofence_radius_m')->default(100)->after('company_longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['company_latitude', 'company_longitude', 'geofence_radius_m']);
        });
    }
};
