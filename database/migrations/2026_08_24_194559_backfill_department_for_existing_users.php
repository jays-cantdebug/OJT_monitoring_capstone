<?php

use App\Enums\Department;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 2026_08_24_131002_add_department_to_users_table.php deliberately left
     * pre-existing rows NULL (documented in its own docblock) rather than
     * backfilling - that backfill was actually done as a one-off `tinker`
     * command on one dev machine only, never captured in a migration, so it
     * never traveled with the codebase to any other environment (e.g. a
     * fresh client machine after `git pull` + `migrate`). This migration
     * makes that backfill a real, repeatable, idempotent part of the
     * codebase: safe to run anywhere, including machines where it was
     * already done manually (whereNull matches nothing there).
     *
     * IT was chosen as the backfill target for every pre-existing Dean/
     * Student account - these are dev/test accounts, not real students with
     * a real department assignment, so a single safe default is fine. There
     * is currently no UI to reassign a user's department after creation, so
     * this is a one-time correction, not an ongoing tool.
     *
     * Admin accounts are deliberately excluded: department = null is the
     * correct, permanent state for role = 'admin' (Admin oversees every
     * department, it doesn't belong to one) - backfilling them to IT would
     * be a real regression, not a fix.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereNull('department')
            ->where('role', '!=', 'admin')
            ->update(['department' => Department::IT->value]);
    }

    /**
     * Reverse the migrations.
     *
     * Deliberately a no-op - the original NULL state carried no information
     * worth restoring, and un-backfilling would silently break any account
     * that has since relied on having a department (e.g. a Dean who logged
     * in and used department-scoped pages after this ran).
     */
    public function down(): void
    {
        //
    }
};
