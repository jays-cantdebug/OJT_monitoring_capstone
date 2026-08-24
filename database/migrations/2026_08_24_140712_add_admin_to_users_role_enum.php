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
     * Laravel's schema builder can't alter a MySQL ENUM's value list
     * without doctrine/dbal (not installed here) - raw SQL is the
     * established pattern this project already uses for schema changes
     * that don't fit the fluent builder, see
     * 2026_08_20_150000_restructure_accomplishment_report_fields.php.
     * SQLite (used for the in-memory test DB, see phpunit.xml) has no
     * MODIFY syntax and represents enum() as a CHECK constraint instead -
     * it needs the column dropped and re-added rather than altered. Safe
     * on both drivers: in a normal migration run this column never has
     * data at the point this migration executes.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['student_intern', 'dean', 'admin'])->after('password');
            });

            return;
        }

        DB::statement("ALTER TABLE users MODIFY role ENUM('student_intern', 'dean', 'admin') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['student_intern', 'dean'])->after('password');
            });

            return;
        }

        DB::statement("ALTER TABLE users MODIFY role ENUM('student_intern', 'dean') NOT NULL");
    }
};
