<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Splits the single 'description' field into three named fields. New
     * columns are nullable at the DB level (validation enforces 'required'
     * for new submissions in AccomplishmentReportController) so existing
     * rows, which only ever had 'description', don't need fabricated data
     * for the two fields they never collected.
     */
    public function up(): void
    {
        Schema::table('accomplishment_reports', function (Blueprint $table) {
            $table->text('activities_performed')->nullable()->after('report_date');
            $table->text('problems_encountered')->nullable()->after('activities_performed');
            $table->text('learnings_acquired')->nullable()->after('problems_encountered');
        });

        DB::statement('update accomplishment_reports set activities_performed = description');

        Schema::table('accomplishment_reports', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accomplishment_reports', function (Blueprint $table) {
            $table->text('description')->nullable();
        });

        DB::statement('update accomplishment_reports set description = activities_performed');

        Schema::table('accomplishment_reports', function (Blueprint $table) {
            $table->dropColumn(['activities_performed', 'problems_encountered', 'learnings_acquired']);
        });
    }
};
