<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The geofence result is stored on the row at the moment it's recorded
     * rather than derived later, so moving a student's pin never rewrites
     * history. Null means the student had no pin set at the time.
     */
    public function up(): void
    {
        Schema::table('gps_pings', function (Blueprint $table) {
            $table->unsignedInteger('distance_from_company_m')->nullable()->after('longitude');
            $table->boolean('outside_geofence')->nullable()->after('distance_from_company_m');
        });

        Schema::table('dtr_entries', function (Blueprint $table) {
            $table->unsignedInteger('time_in_distance_from_company_m')->nullable()->after('time_in_longitude');
            $table->boolean('time_in_outside_geofence')->nullable()->after('time_in_distance_from_company_m');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gps_pings', function (Blueprint $table) {
            $table->dropColumn(['distance_from_company_m', 'outside_geofence']);
        });

        Schema::table('dtr_entries', function (Blueprint $table) {
            $table->dropColumn(['time_in_distance_from_company_m', 'time_in_outside_geofence']);
        });
    }
};
