<?php

use App\Enums\Department;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Nullable at the DB level (no backfill value for pre-existing rows) -
     * required at the application layer for every new-account path instead
     * (Dean-creates-Dean, Dean-creates-Student, self-registration).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('department', array_column(Department::cases(), 'value'))->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('department');
        });
    }
};
